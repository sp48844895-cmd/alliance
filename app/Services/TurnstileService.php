<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TurnstileService
{
    public function isEnabled(): bool
    {
        return filter_var(config('services.turnstile.enabled', false), FILTER_VALIDATE_BOOLEAN);
    }

    public function siteKey(): string
    {
        return (string) config('services.turnstile.site_key');
    }

    public function isConfigured(): bool
    {
        return $this->siteKey() !== '' && (string) config('services.turnstile.secret_key') !== '';
    }

    public function isActive(): bool
    {
        return $this->isEnabled() && $this->isConfigured();
    }

    public function isMisconfigured(): bool
    {
        return $this->isEnabled() && ! $this->isConfigured();
    }

    public function verify(?string $token, ?string $ip = null): bool
    {
        if (! $this->isEnabled()) {
            return true;
        }

        if (! $this->isConfigured() || $token === null || $token === '') {
            return false;
        }

        $payload = [
            'secret'   => config('services.turnstile.secret_key'),
            'response' => $token,
        ];

        if ($ip !== null && $ip !== '') {
            $payload['remoteip'] = $ip;
        }

        $result = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', $payload);

        if (! $result->successful()) {
            return false;
        }

        return $result->json('success') === true;
    }
}
