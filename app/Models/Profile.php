<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'unit_kerja_id',
        'nama_lengkap',
        'nik',
        'jenis_kelamin',
        'no_hp',
        'alamat',
        'tanggal_lahir',
        'tanggal_bergabung',
        'nama_bank',
        'nomor_rekening',
        'foto_profil_path',
        'foto_profil_updated_at',
        'activated_at',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_bergabung' => 'date',
        'activated_at' => 'datetime',
        'foto_profil_updated_at' => 'datetime',
        'nomor_rekening' => 'string',
    ];

    public function getFotoUrlAttribute()
    {
        if ($this->foto_profil_path && Storage::disk('public')->exists($this->foto_profil_path)) {
            return asset('storage/' . $this->foto_profil_path);
        }

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128"><rect width="100%" height="100%" fill="#e5e7eb"/><text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" font-size="48" fill="#6b7280">👤</text></svg>';
        return 'data:image/svg+xml;utf8,' . rawurlencode($svg);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function unitKerja()
    {
        return $this->belongsTo(UnitKerja::class, 'unit_kerja_id');
    }
}
