<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TurnstileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public const PORTAL_ORDER = [
        'admin',
        'author',
        'volunteer',
        'intern',
        'professional',
        'ngo',
    ];

    private const TYPES = [
        'volunteer' => [
            'label'      => 'Volunteer',
            'headline'   => 'Welcome back, volunteer.',
            'lede'       => 'Sign in to log field activities, mark attendance for talk shows, and stay updated with district programmes.',
            'identifier' => 'email',
            'idLabel'    => 'Email',
            'idType'     => 'email',
            'chapter'    => 'Volunteer access',
            'redirect'   => 'home',
        ],
        'intern' => [
            'label'      => 'Intern',
            'headline'   => 'Welcome, intern.',
            'lede'       => 'Sign in to access your placement timesheet, training modules and submission portal.',
            'identifier' => 'email',
            'idLabel'    => 'Institutional email',
            'idType'     => 'email',
            'chapter'    => 'Intern access',
            'redirect'   => 'home',
        ],
        'professional' => [
            'label'      => 'Professional',
            'headline'   => 'Welcome, professional.',
            'lede'       => 'Sign in to share resources, mentor cohorts and contribute to the SBC resource pool.',
            'identifier' => 'email',
            'idLabel'    => 'Work email',
            'idType'     => 'email',
            'chapter'    => 'Professional access',
            'redirect'   => 'home',
        ],
        'ngo' => [
            'label'      => 'NGO / CSO',
            'headline'   => 'Welcome, partner organisation.',
            'lede'       => 'Sign in to manage your organisation profile, programmes, and member roster.',
            'identifier' => 'email',
            'idLabel'    => 'Organisation email',
            'idType'     => 'email',
            'chapter'    => 'NGO access',
            'redirect'   => 'home',
        ],
        'admin' => [
            'label'      => 'Admin',
            'headline'   => 'Admin sign-in.',
            'lede'       => 'Restricted area for alliance staff. Manage every module of the ABC Chhattisgarh platform.',
            'identifier' => 'email',
            'idLabel'    => 'Admin email',
            'idType'     => 'email',
            'chapter'    => 'Admin access',
            'redirect'   => 'admin.dashboard',
        ],
        'author' => [
            'label'      => 'Author',
            'headline'   => 'Welcome, story author.',
            'lede'       => 'Sign in to submit stories for review. Approved stories appear on the public Stories page.',
            'identifier' => 'email',
            'idLabel'    => 'Author email',
            'idType'     => 'email',
            'chapter'    => 'Author access',
            'redirect'   => 'author.dashboard',
        ],
    ];

    public function __construct(private TurnstileService $turnstile)
    {
    }

    public function index()
    {
        if (Auth::check()) {
            return $this->redirectAuthenticated(Auth::user()->type ?? null);
        }

        return view('auth.login-hub', [
            'portals' => $this->portalsForHub(),
        ]);
    }

    public function showForm(string $type)
    {
        $type = $this->resolveType($type);
        abort_unless(isset(self::TYPES[$type]), 404);

        if (Auth::check() && (Auth::user()->type ?? null) === $type) {
            return $this->redirectAuthenticated($type);
        }

        return view('auth.login', [
            'type'              => $type,
            'typeSlug'          => $this->loginSlug($type),
            'config'            => self::TYPES[$type],
            'portals'           => $this->portalsForSwitcher(),
            'turnstileSiteKey'       => $this->turnstile->siteKey(),
            'turnstileEnabled'       => $this->turnstile->isActive(),
            'turnstileMisconfigured' => $this->turnstile->isMisconfigured(),
        ]);
    }

    public function attempt(Request $request, string $type)
    {
        $type = $this->resolveType($type);
        abort_unless(isset(self::TYPES[$type]), 404);

        $config = self::TYPES[$type];

        $rateLimiterKey = 'login:' . $type . ':' . str($request->input('email', ''))->lower()->value() . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($rateLimiterKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimiterKey);
            return back()
                ->withErrors(['email' => "Too many login attempts. Please try again in {$seconds} seconds."])
                ->onlyInput('email', 'remember');
        }

        $rules = [
            'email'    => 'required|email|max:191',
            'password' => 'required|string|min:8|max:191',
        ];

        if ($this->turnstile->isEnabled()) {
            if ($this->turnstile->isMisconfigured()) {
                return back()
                    ->withErrors(['cf-turnstile-response' => 'Turnstile is enabled but not configured. Contact the site administrator.'])
                    ->onlyInput('email', 'remember');
            }

            $rules['cf-turnstile-response'] = 'required|string';
        }

        $request->validate($rules);

        if ($this->turnstile->isEnabled() && ! $this->turnstile->verify($request->input('cf-turnstile-response'), $request->ip())) {
            RateLimiter::hit($rateLimiterKey, 900);
            return back()
                ->withErrors(['cf-turnstile-response' => 'Please complete the security check.'])
                ->onlyInput('email', 'remember');
        }

        $user = DB::table('users')
            ->select(['id', 'password', 'type'])
            ->where('email', $request->input('email'))
            ->where('type', $type)
            ->where('is_active', true)
            ->whereNull('deleted_at')
            ->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            RateLimiter::hit($rateLimiterKey, 900);
            return back()
                ->withErrors(['email' => 'These credentials do not match our records.'])
                ->onlyInput('email', 'remember');
        }

        RateLimiter::clear($rateLimiterKey);

        $remember = $request->boolean('remember');

        Auth::loginUsingId((int) $user->id, $remember);
        $request->session()->regenerate();

        if ($remember) {
            $this->capRememberCookieLifetime();
        }

        DB::table('users')->where('id', $user->id)->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        return redirect()->intended(route($config['redirect']));
    }

    private function capRememberCookieLifetime(): void
    {
        /** @var \Illuminate\Auth\SessionGuard $guard */
        $guard   = Auth::guard();
        $name    = $guard->getRecallerName();
        $queued  = Cookie::queued($name);
        $minutes = (int) env('AUTH_REMEMBER_DAYS', 30) * 24 * 60;

        if (! $queued) {
            return;
        }

        Cookie::queue(
            $name,
            $queued->getValue(),
            $minutes,
            $queued->getPath(),
            $queued->getDomain(),
            $queued->isSecure(),
            $queued->isHttpOnly(),
            $queued->isRaw(),
            $queued->getSameSite()
        );
    }

    private function redirectAuthenticated(?string $type)
    {
        $route = self::TYPES[$type]['redirect'] ?? 'home';
        return redirect()->route($route);
    }

    private function resolveType(string $type): string
    {
        return $type === 'pro' ? 'professional' : $type;
    }

    private function loginSlug(string $type): string
    {
        return $type === 'professional' ? 'pro' : $type;
    }

    private function portalsForHub(): array
    {
        $icons = [
            'admin'        => 'bi-shield-lock',
            'author'       => 'bi-pencil-square',
            'volunteer'    => 'bi-person-heart',
            'intern'       => 'bi-mortarboard',
            'professional' => 'bi-briefcase',
            'ngo'          => 'bi-buildings',
        ];

        $portals = [];
        foreach (self::PORTAL_ORDER as $type) {
            $config = self::TYPES[$type];
            $portals[] = [
                'type'     => $type,
                'slug'     => $this->loginSlug($type),
                'label'    => $config['label'],
                'short'    => $type === 'professional' ? 'Pro' : $config['label'],
                'lede'     => $config['lede'],
                'chapter'  => $config['chapter'],
                'icon'     => $icons[$type] ?? 'bi-box-arrow-in-right',
            ];
        }

        return $portals;
    }

    private function portalsForSwitcher(): array
    {
        $portals = [];
        foreach (self::PORTAL_ORDER as $type) {
            $config = self::TYPES[$type];
            $portals[] = [
                'slug'  => $this->loginSlug($type),
                'type'  => $type,
                'label' => $type === 'professional' ? 'Pro' : $config['label'],
            ];
        }

        return $portals;
    }
}
