<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'allowed_golongan_ids',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'allowed_golongan_ids' => 'array',
        ];
    }

    public function bonCreated(): HasMany
    {
        return $this->hasMany(Bon::class, 'created_by');
    }

    public function bonusCreated(): HasMany
    {
        return $this->hasMany(Bonus::class, 'created_by');
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function canAccessGolongan($golonganId): bool
    {
        if ($this->isManager()) {
            return true;
        }

        if ($this->isAdmin() && $this->allowed_golongan_ids) {
            return in_array($golonganId, $this->allowed_golongan_ids);
        }

        return false;
    }

    public function getAllowedGolonganIds(): array
    {
        if ($this->isManager()) {
            return Golongan::pluck('id')->toArray();
        }

        return $this->allowed_golongan_ids ?? [];
    }
}
