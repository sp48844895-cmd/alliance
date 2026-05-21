<?php

namespace App\Services;

use App\Support\MediaUrl;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SbcResourcePoolService
{
    private ?array $cachedPoolImages = null;

    public function listingFromDatabase(): array
    {
        return DB::table('sbc_pool_members')
            ->select([
                'name',
                'email',
                'photo',
                'facebook',
                'twitter',
                'linkedin',
                'instagram',
            ])
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function ($member): array {
                $name = trim((string) ($member->name ?? ''));
                $photo = trim((string) ($member->photo ?? ''));
                $resolvedPhoto = $this->resolvePoolFile($photo, $name);

                return [
                    'name' => $name,
                    'email' => trim((string) ($member->email ?? '')),
                    'image_url' => $resolvedPhoto ? MediaUrl::resolve('sbc-pool', $resolvedPhoto) : null,
                    'social' => $this->socialLinks((array) $member),
                ];
            })
            ->values()
            ->all();
    }

    private function resolvePoolFile(string $image, string $name): ?string
    {
        if ($image !== '') {
            $match = $this->matchPoolFile($image);

            if ($match !== null) {
                return $match;
            }
        }

        $normalizedName = $this->normalizeName($name);

        foreach ($this->poolImages() as $file) {
            if ($this->normalizeName($this->nameFromFilename($file)) === $normalizedName) {
                return $file;
            }
        }

        return null;
    }

    private function matchPoolFile(string $candidate): ?string
    {
        $candidate = trim($candidate);

        if ($candidate === '') {
            return null;
        }

        $dir = public_path('storage/sbc-pool');

        if (is_file($dir.'/'.$candidate)) {
            return $candidate;
        }

        foreach ($this->poolImages() as $file) {
            if (strcasecmp($file, $candidate) === 0) {
                return $file;
            }
        }

        return null;
    }

    private function poolImages(): array
    {
        if ($this->cachedPoolImages !== null) {
            return $this->cachedPoolImages;
        }

        $this->cachedPoolImages = [];
        $dir = public_path('storage/sbc-pool');

        if (! is_dir($dir)) {
            return $this->cachedPoolImages;
        }

        foreach (scandir($dir) ?: [] as $file) {
            if ($file === '.' || $file === '..' || ! is_file($dir.'/'.$file)) {
                continue;
            }

            if (! preg_match('/\.(png|jpe?g|gif|webp|svg)$/i', $file)) {
                continue;
            }

            $this->cachedPoolImages[] = $file;
        }

        sort($this->cachedPoolImages);

        return $this->cachedPoolImages;
    }

    private function normalizeName(string $name): string
    {
        return Str::slug(strtolower(trim($name)));
    }

    private function nameFromFilename(string $file): string
    {
        $name = pathinfo($file, PATHINFO_FILENAME);
        $name = str_replace(['-', '_'], ' ', $name);
        $name = preg_replace('/\s+/', ' ', trim($name)) ?? '';

        return Str::title($name);
    }

    private function socialLinks(array $person): array
    {
        $links = [];
        $platforms = [
            'facebook' => (string) ($person['facebook'] ?? ''),
            'twitter' => (string) ($person['twitter'] ?? ''),
            'linkedin' => (string) ($person['linkedin'] ?? ''),
            'instagram' => (string) ($person['instagram'] ?? ''),
        ];

        foreach ($platforms as $platform => $value) {
            $url = $this->socialUrl($value, $platform);

            if ($url !== null) {
                $links[] = [
                    'platform' => $platform,
                    'url' => $url,
                ];
            }
        }

        return $links;
    }

    private function socialUrl(string $value, string $platform): ?string
    {
        $value = trim($value);

        if ($value === '') {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        $handle = ltrim($value, '@/');

        return match ($platform) {
            'facebook' => 'https://www.facebook.com/'.$handle,
            'instagram' => 'https://www.instagram.com/'.$handle,
            'twitter' => 'https://twitter.com/'.$handle,
            'linkedin' => 'https://www.linkedin.com/in/'.$handle,
            default => null,
        };
    }
}
