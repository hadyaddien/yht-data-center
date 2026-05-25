<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'provinsi_id',
        'sekolah_id',
        'avatar',
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
        ];
    }

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(Dokumen::class, 'uploaded_by');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdminWilayah(): bool
    {
        return $this->role === 'admin_wilayah';
    }

    public function isKepalaSekolah(): bool
    {
        return $this->role === 'kepala_sekolah';
    }

    public function canManageSekolahData(): bool
    {
        return $this->isSuperAdmin();
    }

    public function applySekolahScope(Builder $query): Builder
    {
        if ($this->isSuperAdmin()) {
            return $query;
        }

        if ($this->isAdminWilayah()) {
            return $this->provinsi_id
                ? $query->where('provinsi_id', $this->provinsi_id)
                : $query->whereRaw('1 = 0');
        }

        if ($this->isKepalaSekolah()) {
            return $this->sekolah_id
                ? $query->whereKey($this->sekolah_id)
                : $query->whereRaw('1 = 0');
        }

        return $query->whereRaw('1 = 0');
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'superadmin' => 'Super Admin',
            'admin_wilayah' => 'Admin Wilayah',
            'kepala_sekolah' => 'Kepala Sekolah',
            default => $this->role,
        };
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials;
    }

    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return null;
    }
}
