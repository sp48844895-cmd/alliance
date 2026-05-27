<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsFellow
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $this->redirectGuest($request);
        }

        if (! ($user->is_active ?? true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login.show', 'fellow')
                ->withErrors(['email' => 'Your application is under review.']);
        }

        if (($user->type ?? '') !== 'fellow') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return $this->redirectGuest($request)
                ->withErrors(['email' => 'Please sign in with a fellowship account to access that page.']);
        }

        return $next($request);
    }

    private function redirectGuest(Request $request)
    {
        $request->session()->put('url.intended', $request->fullUrl());

        return redirect()->guest(route('login.show', 'fellow'));
    }
}
