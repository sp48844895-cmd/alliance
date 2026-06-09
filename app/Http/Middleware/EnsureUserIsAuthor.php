<?php

namespace App\Http\Middleware;

use App\Support\StoryContributor;
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
            return $this->redirectGuest($request);
        }

        if (! ($user->is_active ?? true)) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login.show', 'guest')
                ->withErrors(['email' => 'Your account has been suspended.']);
        }

        $type = $user->type ?? '';

        if (! StoryContributor::canAccess($type)) {
            if ($type === 'admin') {
                return redirect()
                    ->route('admin.dashboard')
                    ->with('error', 'Use the admin panel to manage stories.');
            }

            return redirect()
                ->route('home')
                ->with('error', 'Your account does not have access to the story portal.');
        }

        return $next($request);
    }

    private function redirectGuest(Request $request)
    {
        $request->session()->put('url.intended', $request->fullUrl());

        return redirect()->guest(route('login.show', 'guest'));
    }
}
