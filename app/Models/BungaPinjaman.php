<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BungaPinjaman extends Model
{
    protected $table = 'bunga_pinjaman';

    protected $fillable = [
        'tenor_bulan',
        'persen',
        'keterangan',
        'updated_by',
    ];

    protected $casts = [
        'persen' => 'decimal:2',
        'tenor_bulan' => 'integer',
    ];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function pinjaman()
    {
        return $this->hasMany(Pinjaman::class, 'bunga_id');
    }
}