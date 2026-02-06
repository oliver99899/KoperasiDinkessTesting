<?php

namespace App\Models;

use App\Models\Concerns\HasSignature;
use App\Support\HmacSigner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Angsuran extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasSignature;

    protected $table = 'angsuran';

    protected $fillable = [
        'pinjaman_id',
        'angsuran_ke',
        'pokok_bayar',
        'bunga_bayar',
        'jumlah_bayar',
        'metode_bayar',
        'tanggal_potong',
        'keterangan',
        'created_by',
        'verified_at',
        'signature',
    ];

    protected $casts = [
        'pokok_bayar' => 'decimal:2',
        'bunga_bayar' => 'decimal:2',
        'jumlah_bayar' => 'decimal:2',
        'tanggal_potong' => 'date',
        'verified_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            $m->refreshSignature();
        });

        static::updating(function (self $m) {
            if ($m->isDirty([
                'pinjaman_id',
                'angsuran_ke',
                'pokok_bayar',
                'bunga_bayar',
                'jumlah_bayar',
                'metode_bayar',
                'tanggal_potong',
            ])) {
                $m->refreshSignature();
            }
        });
    }

    protected function signaturePayload(): string
    {
        return HmacSigner::normalize([
            $this->pinjaman_id,
            (string) $this->angsuran_ke,
            number_format((float) $this->pokok_bayar, 2, '.', ''),
            number_format((float) $this->bunga_bayar, 2, '.', ''),
            number_format((float) $this->jumlah_bayar, 2, '.', ''),
            (string) $this->metode_bayar,
            optional($this->tanggal_potong)->format('Y-m-d'),
        ]);
    }

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class, 'pinjaman_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
