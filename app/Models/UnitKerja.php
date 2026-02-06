<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitKerja extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'unit_kerja';

    protected $fillable = [
        'nama_unit',
        'jenis',
        'alamat',
        'telepon',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function profiles()
    {
        return $this->hasMany(Profile::class, 'unit_kerja_id');
    }

    public function isDinas(): bool
    {
        return $this->jenis === 'dinas';
    }

    public function isPuskesmas(): bool
    {
        return $this->jenis === 'puskesmas';
    }
}
