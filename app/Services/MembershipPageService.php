<?php

namespace App\Services;

use App\Models\ProgramRegistration;
use App\Support\MediaUrl;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MembershipPageService
{
    public function filters(): array
    {
        return Cache::remember('membership.filters', 3600, function () {
            $districts = collect();

            if (Schema::hasColumn('program_registrations', 'district_id')) {
                $districts = DB::table('program_registrations')
                    ->join('district', 'district.id', '=', 'program_registrations.district_id')
                    ->whereIn('program_registrations.status', ProgramRegistration::approvedStatuses())
                    ->select('district.id', 'district.district_name')
                    ->distinct()
                    ->orderBy('district.district_name')
                    ->get();
            }

            $districtOptions = [['value' => 'all', 'label' => 'All districts']];

            foreach ($districts as $district) {
                $districtOptions[] = [
                    'value' => (string) $district->id,
                    'label' => strtoupper($district->district_name),
                ];
            }

            $types = DB::table('program_registrations')
                ->whereIn('status', ProgramRegistration::approvedStatuses())
                ->select('type')
                ->distinct()
                ->orderBy('type')
                ->pluck('type');

            $typeOptions = [['value' => 'all', 'label' => 'All member types']];
            $memberTypes = ['all' => 'All member types'];

            foreach ($types as $type) {
                $label = ProgramRegistration::publicTypeLabel((string) $type);
                $value = $this->typeValue((string) $type);
                $typeOptions[] = ['value' => $value, 'label' => $label];
                $memberTypes[$value] = $label;
            }

            return [
                'districts' => $districtOptions,
                'member_types' => $typeOptions,
                'member_type_map' => $memberTypes,
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

    public function memberCount(): int
    {
        return Cache::remember('membership.count', 3600, fn () => (int) DB::table('program_registrations')
            ->whereIn('status', ProgramRegistration::approvedStatuses())
            ->count());
    }

    public function clearCache(): void
    {
        Cache::forget('membership.filters');
        Cache::forget('membership.count');
    }

    private function membersQuery(array $filters = [])
    {
        $query = DB::table('program_registrations')
            ->whereIn('program_registrations.status', ProgramRegistration::approvedStatuses())
            ->orderBy('program_registrations.full_name');

        $select = [
            'program_registrations.id',
            'program_registrations.full_name',
            'program_registrations.phone',
            'program_registrations.email',
            'program_registrations.type',
        ];

        if (Schema::hasColumn('program_registrations', 'district_id')) {
            $query->leftJoin('district', 'district.id', '=', 'program_registrations.district_id');
            $select = array_merge($select, [
                'program_registrations.district_id',
                'program_registrations.profile_image',
                'program_registrations.profile',
                'district.district_name',
            ]);
        }

        $query->select($select);

        $district = (string) ($filters['district'] ?? 'all');
        $type = (string) ($filters['type'] ?? 'all');
        $search = trim((string) ($filters['search'] ?? ''));

        if ($district !== 'all' && $district !== '' && Schema::hasColumn('program_registrations', 'district_id')) {
            $query->where('program_registrations.district_id', (int) $district);
        }

        if ($type !== 'all' && $type !== '') {
            $matchingTypes = DB::table('program_registrations')
                ->whereIn('status', ProgramRegistration::approvedStatuses())
                ->select('type')
                ->distinct()
                ->pluck('type')
                ->filter(fn ($rowType) => $this->typeValue((string) $rowType) === $type)
                ->values()
                ->all();

            if ($matchingTypes === []) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('program_registrations.type', $matchingTypes);
            }
        }

        if ($search !== '') {
            $term = '%'.$search.'%';
            $query->where(function ($inner) use ($term) {
                $inner->where('program_registrations.full_name', 'like', $term);
                if (Schema::hasColumn('program_registrations', 'district_id')) {
                    $inner->orWhere('district.district_name', 'like', $term);
                }
            });
        }

        return $query;
    }

    private function formatMember(object $row): array
    {
        $name = trim((string) $row->full_name);
        $mobile = preg_replace('/\D+/', '', (string) $row->phone);
        $typeLabel = ProgramRegistration::publicTypeLabel((string) $row->type);
        $profile = $this->decodeProfile($row->profile ?? null);
        $profileImage = property_exists($row, 'profile_image') ? (string) ($row->profile_image ?? '') : '';

        return [
            'name' => $name,
            'initial' => Str::upper(Str::substr($name, 0, 1)),
            'logo_url' => $this->resolveImageUrl($name, $profileImage),
            'district' => strtoupper((string) ($row->district_name ?? 'CHHATTISGARH')),
            'district_value' => property_exists($row, 'district_id') ? (string) ($row->district_id ?? 'all') : 'all',
            'type' => $this->typeValue((string) $row->type),
            'type_label' => $typeLabel,
            'phone' => $mobile,
            'phone_link' => $mobile,
            'email' => trim((string) $row->email),
            'social' => $this->socialLinks($profile),
        ];
    }

    private function decodeProfile(mixed $profile): array
    {
        if (is_array($profile)) {
            return $profile;
        }

        if (is_string($profile) && $profile !== '') {
            $decoded = json_decode($profile, true);

            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    private function socialLinks(array $profile): array
    {
        $links = [];
        $platforms = [
            'facebook' => (string) ($profile['fb'] ?? ''),
            'instagram' => (string) ($profile['insta'] ?? ''),
            'twitter' => (string) ($profile['twitter'] ?? ''),
            'youtube' => (string) ($profile['youtube'] ?? ''),
            'website' => (string) ($profile['website'] ?? ''),
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

        return Str::slug($type);
    }

    public function resolveImageUrl(string $name, string $img): ?string
    {
        $img = trim($img);

        if ($img === '') {
            return null;
        }

        return MediaUrl::tryResolve('membership', $img) ?? MediaUrl::tryResolve('membership', basename($img));
    }
}
