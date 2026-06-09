<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:191',
        ]);

        $email = strtolower(trim((string) $request->input('email')));
        $exists = NewsletterSubscriber::where('email', $email)->exists();

        if (! $exists) {
            NewsletterSubscriber::insert([
                'email' => $email,
                'ip_address' => $request->ip(),
                'subscribed_at' => now(),
            ]);
        }

        $refererPath = parse_url((string) $request->headers->get('referer', ''), PHP_URL_PATH) ?: '';
        $homePath = parse_url(route('home'), PHP_URL_PATH) ?: '/';
        $redirect = $refererPath === $homePath
            ? redirect()->route('home')->withFragment('newsletter')
            : back();

        $message = $exists
            ? 'You are already subscribed to our newsletter.'
            : 'Thanks for subscribing! We will be in touch.';

        return $redirect->with('status', $message);
    }
}
