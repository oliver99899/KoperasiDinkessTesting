<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nik',
        'jenis_kelamin',
        'unit_kerja',
        'no_hp',
        'alamat',
        'nama_bank',
        'nomor_rekening',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}