<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasRoles;

    protected $fillable = [
        'nip',
        'name',
        'email',
        'password',
        'status_akun',
        'activation_token_hash',
        'activation_expires_at',
        'last_login_at',
        'last_login_ip',
        'active_session_id',
        'force_logout_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'activation_token_hash',
        'active_session_id',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activation_expires_at' => 'datetime',
            'last_login_at' => 'datetime',
            'force_logout_at' => 'datetime',
        ];
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id');
    }

    public function simpanan()
    {
        return $this->hasMany(Simpanan::class, 'user_id');
    }

    public function pinjaman()
    {
        return $this->hasMany(Pinjaman::class, 'user_id');
    }

    public function isActive(): bool
    {
        return $this->status_akun === 'active';
    }

    public function isRetired(): bool
    {
        return $this->status_akun === 'retired';
    }

    public function isBlocked(): bool
    {
        return $this->status_akun === 'blocked';
    }

    public function isSensitiveRole(): bool
    {
        return $this->hasRole('admin') || $this->hasRole('verifikator');
    }
}
