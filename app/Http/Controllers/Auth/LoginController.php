<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TurnstileService;
use App\Support\LoginPortals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class LoginController extends Controller
{
    public function __construct(private TurnstileService $turnstile)
    {
    }

    public function index()
    {
        if (Auth::check()) {
            return $this->redirectAuthenticated(Auth::user()->type ?? null);
        }

        return view('auth.login-hub', [
            'portals' => LoginPortals::forHub(),
        ]);
    }

    public function showForm(string $type)
    {
        $type = $this->resolveType($type);
        abort_unless(LoginPortals::isValid($type), 404);

        if (Auth::check() && (Auth::user()->type ?? null) === $type) {
            return $this->redirectAuthenticated($type);
        }

        return view('auth.login', [
            'type'                   => $type,
            'typeSlug'               => $type,
            'config'                 => LoginPortals::config($type),
            'portals'                => LoginPortals::forSwitcher(),
            'turnstileSiteKey'       => $this->turnstile->siteKey(),
            'turnstileEnabled'       => $this->turnstile->isActive(),
            'turnstileMisconfigured' => $this->turnstile->isMisconfigured(),
        ]);
    }

    public function attempt(Request $request, string $type)
    {
        $type = $this->resolveType($type);
        abort_unless(LoginPortals::isValid($type), 404);

        $config = LoginPortals::config($type);
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

        $email = strtolower(trim((string) $request->input('email')));

        $user = DB::table('users')
            ->select(['id', 'password', 'type', 'is_active'])
            ->where('email', $email)
            ->where('type', $type)
            ->whereNull('deleted_at')
            ->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            RateLimiter::hit($rateLimiterKey, 900);

            return back()
                ->withErrors(['email' => 'These credentials do not match our records.'])
                ->onlyInput('email', 'remember');
        }

        if (! ($user->is_active ?? false)) {
            RateLimiter::hit($rateLimiterKey, 900);

            $message = $this->inactiveLoginMessage((int) $user->id, $type);

            return back()
                ->withErrors(['email' => $message])
                ->onlyInput('email', 'remember');
        }

        return $this->completeLogin($request, $user, $rateLimiterKey, $config['redirect']);
    }

    private function completeLogin(Request $request, object $user, string $rateLimiterKey, string $redirectRoute)
    {
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

        return redirect()->intended(route($redirectRoute));
    }

    private function capRememberCookieLifetime(): void
    {
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

    private function inactiveLoginMessage(int $userId, string $type): string
    {
        if (! in_array($type, ['intern', 'fellow'], true)) {
            return 'These credentials do not match our records.';
        }

        $registration = DB::table('program_registrations')
            ->where('user_id', $userId)
            ->orderByDesc('id')
            ->first();

        if (! $registration) {
            return 'These credentials do not match our records.';
        }

        if (in_array($registration->status, ['new', 'reviewed'], true)) {
            return 'Your application is under review.';
        }

        if ($registration->status === 'rejected') {
            return 'Your application was not approved.';
        }

        return 'These credentials do not match our records.';
    }

    private function redirectAuthenticated(?string $type)
    {
        $redirect = LoginPortals::redirectRoute($type);

        if ($redirect) {
            return redirect()->route($redirect);
        }

        return redirect()->route('home');
    }

    private function resolveType(string $type): string
    {
        return $type;
    }
}
