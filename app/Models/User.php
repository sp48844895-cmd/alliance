<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'fname', 'lname', 'username', 'email',
        'image', 'bio', 'type', 'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => 'integer',
            'is_active'         => 'boolean',
        ];
    }

    public function getNameAttribute(): string
    {
        return trim(($this->fname ?? '') . ' ' . ($this->lname ?? ''));
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function canPostStories(): bool
    {
        return \App\Support\StoryContributor::canAccess($this->type ?? null);
    }
}
