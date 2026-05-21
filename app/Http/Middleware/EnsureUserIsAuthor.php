<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAuthor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login.show', 'author');
        }

        if (! ($user->is_active ?? true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login.show', 'author')
                ->withErrors(['email' => 'Your account has been suspended.']);
        }

        if (($user->type ?? '') !== 'author') {
            abort(403);
        }

        return $next($request);
    }
}
