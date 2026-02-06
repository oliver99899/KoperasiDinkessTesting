<?php

namespace App\Models;

use App\Models\Concerns\HasSignature;
use App\Support\HmacSigner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembayaranAngsuran extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasSignature;

    protected $table = 'pembayaran_angsuran';

    protected $fillable = [
        'pinjaman_id',
        'angsuran_ke',
        'tanggal_transfer',
        'bukti_path',
        'status',
        'submitted_by',
        'reviewed_by',
        'reviewed_at',
        'alasan_penolakan',
        'signature',
    ];

    protected $casts = [
        'tanggal_transfer' => 'date',
        'reviewed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            $m->refreshSignature();
        });

        static::updating(function (self $m) {
            if ($m->isDirty(['pinjaman_id', 'angsuran_ke', 'tanggal_transfer', 'bukti_path', 'status'])) {
                $m->refreshSignature();
            }
        });
    }

    protected function signaturePayload(): string
    {
        return HmacSigner::normalize([
            $this->pinjaman_id,
            (string) $this->angsuran_ke,
            optional($this->tanggal_transfer)->format('Y-m-d'),
            (string) $this->bukti_path,
            (string) $this->status,
        ]);
    }

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class, 'pinjaman_id');
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
