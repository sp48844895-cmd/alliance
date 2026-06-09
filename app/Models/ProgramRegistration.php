<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramRegistration extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_REVIEWED = 'reviewed';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    protected $table = 'program_registrations';

    protected $guarded = [];

    protected $casts = [
        'domain_areas' => 'array',
        'profile' => 'array',
    ];

    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_REVIEWED,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
        ];
    }

    public static function approvedStatuses(): array
    {
        return [self::STATUS_APPROVED, 'accepted'];
    }

    public static function pendingStatuses(): array
    {
        return [self::STATUS_PENDING, 'new'];
    }

    public static function typeLabels(): array
    {
        return [
            'intern' => 'Intern',
            'fellow' => 'Fellowship',
            'partner' => 'Organisation partnership',
            'guest' => 'Guest registration',
        ];
    }

    public static function publicTypeLabel(string $type): string
    {
        return self::typeLabels()[$type] ?? ucfirst($type);
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }
}
