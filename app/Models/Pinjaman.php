<?php

namespace App\Models;

use App\Models\Concerns\HasSignature;
use App\Support\HmacSigner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pinjaman extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasSignature;

    protected $table = 'pinjaman';

    protected $fillable = [
        'user_id',
        'nomor_pinjaman',
        'jumlah_pengajuan',
        'jumlah_disetujui',
        'durasi_bulan',
        'bunga_persen',
        'cicilan_pokok_bulanan',
        'cicilan_bunga_bulanan',
        'total_bunga',
        'total_pinjaman',
        'sisa_pinjaman',
        'alasan_pengajuan',
        'dokumen_syarat',
        'status',
        'alasan_penolakan',
        'tanggal_pengajuan',
        'tanggal_cair',
        'jatuh_tempo_berikutnya',
        'decided_by',
        'decided_at',
        'signature',
        'bunga_id',
    ];

    protected $casts = [
        'jumlah_pengajuan' => 'decimal:2',
        'jumlah_disetujui' => 'decimal:2',
        'durasi_bulan' => 'integer',
        'bunga_persen' => 'decimal:2',
        'cicilan_pokok_bulanan' => 'decimal:2',
        'cicilan_bunga_bulanan' => 'decimal:2',
        'total_bunga' => 'decimal:2',
        'total_pinjaman' => 'decimal:2',
        'sisa_pinjaman' => 'decimal:2',
        'tanggal_pengajuan' => 'date',
        'tanggal_cair' => 'date',
        'jatuh_tempo_berikutnya' => 'date',
        'decided_at' => 'datetime',
        'dokumen_syarat' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            $m->refreshSignature();
        });

        static::updating(function (self $m) {
            if ($m->isDirty([
                'user_id',
                'nomor_pinjaman',
                'jumlah_pengajuan',
                'jumlah_disetujui',
                'durasi_bulan',
                'bunga_persen',
                'cicilan_pokok_bulanan',
                'cicilan_bunga_bulanan',
                'total_bunga',
                'total_pinjaman',
                'tanggal_cair',
            ])) {
                $m->refreshSignature();
            }
        });
    }

    protected function signaturePayload(): string
    {
        return HmacSigner::normalize([
            $this->user_id,
            $this->nomor_pinjaman,
            number_format((float) $this->jumlah_pengajuan, 2, '.', ''),
            is_null($this->jumlah_disetujui) ? '' : number_format((float) $this->jumlah_disetujui, 2, '.', ''),
            (string) $this->durasi_bulan,
            number_format((float) $this->bunga_persen, 2, '.', ''),
            number_format((float) $this->cicilan_pokok_bulanan, 2, '.', ''),
            number_format((float) $this->cicilan_bunga_bulanan, 2, '.', ''),
            number_format((float) $this->total_bunga, 2, '.', ''),
            number_format((float) $this->total_pinjaman, 2, '.', ''),
            optional($this->tanggal_cair)->format('Y-m-d'),
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function decider()
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    public function angsuran()
    {
        return $this->hasMany(Angsuran::class, 'pinjaman_id')->orderBy('angsuran_ke', 'asc');
    }

    public function bunga()
    {
    return $this->belongsTo(BungaPinjaman::class, 'bunga_id');
    }
}
