<?php

namespace App\Models;

use App\Models\Concerns\HasSignature;
use App\Support\HmacSigner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Simpanan extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasSignature;

    protected $table = 'simpanan';

    protected $fillable = [
        'user_id',
        'periode',
        'jumlah',
        'tanggal_potong',
        'keterangan',
        'created_by',
        'verified_at',
        'signature',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_potong' => 'date',
        'verified_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            $m->refreshSignature();
        });

        static::updating(function (self $m) {
            if ($m->isDirty(['user_id', 'periode', 'jumlah', 'tanggal_potong'])) {
                $m->refreshSignature();
            }
        });
    }

    protected function signaturePayload(): string
    {
        return HmacSigner::normalize([
            $this->user_id,
            $this->periode,
            number_format((float) $this->jumlah, 2, '.', ''),
            optional($this->tanggal_potong)->format('Y-m-d'),
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
