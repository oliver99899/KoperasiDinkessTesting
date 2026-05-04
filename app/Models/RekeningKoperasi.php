<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekeningKoperasi extends Model
{
    protected $table = 'rekening_koperasi';

    protected $fillable = [
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'is_active',
        'keterangan',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }
}