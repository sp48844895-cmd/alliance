<?php

namespace App\Services;

use App\Support\MediaUrl;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MembershipPageService
{
    private ?array $cachedLogoFiles = null;

    public function filters(): array
    {
        return Cache::remember('membership.filters', 3600, function () {
            $districts = DB::table('membership')
                ->join('district', 'district.id', '=', 'membership.district')
                ->select('district.id', 'district.district_name')
                ->distinct()
                ->orderBy('district.district_name')
                ->get();

            $districtOptions = [['value' => 'all', 'label' => 'All districts']];

            foreach ($districts as $district) {
                $districtOptions[] = [
                    'value' => (string) $district->id,
                    'label' => strtoupper($district->district_name),
                ];
            }

            $types = DB::table('membership')
                ->select('type')
                ->whereNotNull('type')
                ->where('type', '!=', '')
                ->distinct()
                ->orderBy('type')
                ->pluck('type');

            $typeOptions = [['value' => 'all', 'label' => 'All member types']];

            foreach ($types as $type) {
                $typeOptions[] = [
                    'value' => $this->typeValue($type),
                    'label' => $type,
                ];
            }

            return [
                'districts' => $districtOptions,
                'member_types' => $typeOptions,
                'count' => $this->memberCount(),
            ];
        });
    }

    public function paginatedListing(int $perPage = 20, array $filters = []): LengthAwarePaginator
    {
        $paginator = $this->membersQuery($filters)->paginate($perPage)->withQueryString();

        return $paginator->setCollection(
            $paginator->getCollection()->map(fn ($row) => $this->formatMember($row))
        );
    }

    private function membersQuery(array $filters = [])
    {
        $query = DB::table('membership')
            ->leftJoin('district', 'district.id', '=', 'membership.district')
            ->orderBy('membership.name')
            ->select([
                'membership.id',
                'membership.name',
                'membership.mobile',
                'membership.email',
                'membership.type',
                'membership.district',
                'membership.fb',
                'membership.insta',
                'membership.twitter',
                'membership.youtube',
                'membership.website',
                'membership.img',
                'district.district_name',
            ]);

        $district = (string) ($filters['district'] ?? 'all');
        $type = (string) ($filters['type'] ?? 'all');
        $search = trim((string) ($filters['search'] ?? ''));

        if ($district !== 'all' && $district !== '') {
            $query->where('membership.district', $district);
        }

        if ($type !== 'all' && $type !== '') {
            $matchingTypes = DB::table('membership')
                ->select('type')
                ->distinct()
                ->pluck('type')
                ->filter(fn ($rowType) => $this->typeValue((string) $rowType) === $type)
                ->values()
                ->all();

            if ($matchingTypes === []) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('membership.type', $matchingTypes);
            }
        }

        if ($search !== '') {
            $term = '%'.$search.'%';
            $query->where(function ($inner) use ($term) {
                $inner->where('membership.name', 'like', $term)
                    ->orWhere('district.district_name', 'like', $term);
            });
        }

        return $query;
    }

    public function memberCount(): int
    {
        return Cache::remember('membership.count', 3600, fn () => (int) DB::table('membership')->count());
    }

    private function formatMember(object $row): array
    {
        $name = trim((string) $row->name);
        $mobile = preg_replace('/\D+/', '', (string) $row->mobile);
        $typeLabel = trim((string) $row->type);

        if ($typeLabel === '') {
            $typeLabel = 'Member';
        }

        return [
            'name' => $name,
            'initial' => Str::upper(Str::substr($name, 0, 1)),
            'logo_url' => $this->memberLogoUrl($name, (string) ($row->img ?? '')),
            'district' => strtoupper((string) ($row->district_name ?? 'Chhattisgarh')),
            'district_value' => (string) ($row->district ?? 'all'),
            'type' => $this->typeValue((string) $row->type),
            'type_label' => $typeLabel,
            'phone' => $mobile,
            'phone_link' => $mobile,
            'email' => trim((string) $row->email),
            'social' => $this->socialLinks($row),
        ];
    }

    private function socialLinks(object $row): array
    {
        $links = [];
        $platforms = [
            'facebook' => (string) ($row->fb ?? ''),
            'instagram' => (string) ($row->insta ?? ''),
            'twitter' => (string) ($row->twitter ?? ''),
            'youtube' => (string) ($row->youtube ?? ''),
            'website' => (string) ($row->website ?? ''),
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
            'youtube' => str_contains($handle, 'youtube.com') ? 'https://'.$handle : 'https://www.youtube.com/'.$handle,
            'website' => 'https://'.$handle,
            default => null,
        };
    }

    private function typeValue(string $type): string
    {
        $type = strtolower(trim($type));

        if ($type === '') {
            return 'member';
        }

        return match ($type) {
            'individual' => 'individual',
            'volunteer' => 'volunteer',
            'cso/ngo', 'ngo/cso' => 'ngo-cso',
            'firm/organization', 'firm/organisation' => 'firm-organization',
            default => Str::slug($type),
        };
    }

    public function resolveImageUrl(string $name, string $img): ?string
    {
        $img = trim($img);

        if ($img === '') {
            return null;
        }

        $direct = MediaUrl::tryResolve('membership', $img);

        if ($direct !== null) {
            return $direct;
        }

        return $this->memberLogoUrl($name, $img);
    }

    private function memberLogoUrl(string $name, string $img): ?string
    {
        $dir = public_path('storage/logos');

        if (! is_dir($dir)) {
            return null;
        }

        foreach ($this->imgCandidates($img) as $candidate) {
            $url = $this->resolveLogoFile($candidate, $dir);

            if ($url !== null) {
                return $url;
            }
        }

        $prefix = strtoupper(trim($name)).'_';
        $matches = array_values(array_filter(
            $this->logoFiles(),
            fn (string $file) => str_starts_with(strtoupper($file), $prefix)
        ));

        if ($matches !== []) {
            usort($matches, fn (string $a, string $b) => $this->logoTimestamp($b) <=> $this->logoTimestamp($a));

            return asset('storage/logos/'.$matches[0]);
        }

        return $this->resolveLogoFile('No_Image_Available.jpg', $dir);
    }

    private function imgCandidates(string $img): array
    {
        $img = trim($img);

        if ($img === '') {
            return [];
        }

        if (preg_match_all('/\S+\.(?:png|jpe?g|gif|webp|svg)\b/i', $img, $matches) && count($matches[0]) > 1) {
            return array_values(array_unique($matches[0]));
        }

        return [$img];
    }

    private function resolveLogoFile(string $candidate, string $dir): ?string
    {
        $candidate = trim($candidate);

        if ($candidate === '') {
            return null;
        }

        if (is_file($dir.'/'.$candidate)) {
            return asset('storage/logos/'.$candidate);
        }

        foreach ($this->logoFiles() as $file) {
            if (strcasecmp($file, $candidate) === 0) {
                return asset('storage/logos/'.$file);
            }

            if (! str_contains($candidate, '.') && str_starts_with(strtoupper($file), strtoupper($candidate))) {
                return asset('storage/logos/'.$file);
            }
        }

        return null;
    }

    private function logoFiles(): array
    {
        if ($this->cachedLogoFiles !== null) {
            return $this->cachedLogoFiles;
        }

        $dir = public_path('storage/logos');
        $this->cachedLogoFiles = [];

        if (! is_dir($dir)) {
            return $this->cachedLogoFiles;
        }

        foreach (scandir($dir) ?: [] as $file) {
            if ($file === '.' || $file === '..' || ! is_file($dir.'/'.$file)) {
                continue;
            }

            $this->cachedLogoFiles[] = $file;
        }

        return $this->cachedLogoFiles;
    }

    private function logoTimestamp(string $filename): int
    {
        if (preg_match('/_(\d{10})/', $filename, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }
}
