<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan Koperasi</title>
    <style>
        body { font-family: "Times New Roman", Times, serif; font-size: 11pt; color: #000; line-height: 1.4; margin: 0.5cm; }
        .header-table { width: 100%; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 10px; }
        .logo-cell { width: 10%; text-align: center; vertical-align: middle; }
        .text-cell { text-align: center; vertical-align: middle; }
        .kop-1 { font-size: 14pt; font-weight: bold; margin: 0; }
        .kop-2 { font-size: 16pt; font-weight: bold; margin: 0; }
        .kop-alamat { font-size: 10pt; margin: 0; }
        .report-title { text-align: center; text-decoration: underline; font-weight: bold; font-size: 13pt; margin-top: 20px; text-transform: uppercase; }
        .report-subtitle { text-align: center; font-weight: bold; margin-bottom: 5px; }
        .date-info { text-align: right; font-size: 10pt; margin-bottom: 10px; font-style: italic; }
        .section-title { font-weight: bold; font-size: 11pt; margin: 12px 0 4px 0; text-decoration: underline; }
        .data-table { width: 100%; border-collapse: collapse; margin: 6px 0; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 5px 6px; font-size: 9pt; vertical-align: middle; }
        .data-table th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .row-masuk { color: #000; }
        .row-keluar { color: #c00; }
        .summary-section { margin-top: 14px; width: 100%; }
        .summary-box { border: 1px solid #000; padding: 8px 12px; margin-bottom: 10px; }
        .summary-box-title { font-weight: bold; font-size: 11pt; border-bottom: 1px solid #000; padding-bottom: 4px; margin-bottom: 6px; }
        .summary-row { display: table; width: 100%; margin-bottom: 3px; font-size: 10pt; }
        .summary-label { display: table-cell; width: 70%; }
        .summary-value { display: table-cell; width: 30%; text-align: right; font-weight: bold; }
        .summary-divider { border-top: 1px solid #000; margin: 4px 0; }
        .total-row { font-weight: bold; font-size: 11pt; }
        .total-aset-row { font-weight: bold; font-size: 12pt; color: #00008B; }
        .piutang-note { font-size: 9pt; font-style: italic; color: #555; }
        footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8pt; color: #777; }
    </style>
</head>
<body>

<table class="header-table">
    <tr>
        <td class="logo-cell">
            <img src="{{ public_path('images/logo-dinkes.png') }}" width="60" alt="Logo">
        </td>
        <td class="text-cell">
            <h3 class="kop-1">PEMERINTAH KOTA SEMARANG</h3>
            <h2 class="kop-2">DINAS KESEHATAN</h2>
            <p class="kop-alamat">Jl. Pandanaran 79 Telp. (024) 8415269-8318771 Kode Pos: 50241 Semarang</p>
        </td>
    </tr>
</table>

<div class="report-title">Laporan Rekapitulasi Arus Kas Koperasi</div>
<div class="report-subtitle">
    Periode: {{ \Carbon\Carbon::parse($start_date)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($end_date)->translatedFormat('d F Y') }}
</div>
<div class="date-info">
    Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y [jam] HH:mm') }}
</div>

{{-- TABEL TRANSAKSI --}}
<div class="section-title">I. Rincian Transaksi Periode Ini</div>
<table class="data-table">
    <thead>
        <tr>
            <th width="12%">Tanggal</th>
            <th width="18%">Nama Anggota</th>
            <th width="30%">Keterangan Transaksi</th>
            <th width="20%">Debit (Masuk)</th>
            <th width="20%">Kredit (Keluar)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($transaksi as $t)
        <tr class="{{ $t->tipe == 'keluar' ? 'row-keluar' : 'row-masuk' }}">
            <td class="text-center">{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
            <td>{{ $t->user->profile->nama_lengkap ?? $t->user->name ?? '-' }}</td>
            <td>{{ $t->deskripsi }}</td>
            <td class="text-right">
                @if($t->tipe == 'masuk') Rp {{ number_format($t->nominal, 0, ',', '.') }}
                @else -
                @endif
            </td>
            <td class="text-right">
                @if($t->tipe == 'keluar') Rp {{ number_format($t->nominal, 0, ',', '.') }}
                @else -
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">Tidak ada data transaksi pada periode ini.</td>
        </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr style="background-color: #f9f9f9;">
            <td colspan="2" class="text-center font-bold">TOTAL PERIODE INI</td>
            <td class="text-right font-bold">Total Masuk</td>
            <td class="text-right font-bold">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</td>
            <td class="text-right font-bold"></td>
        </tr>
        <tr style="background-color: #f9f9f9;">
            <td colspan="2"></td>
            <td class="text-right font-bold">Total Keluar</td>
            <td></td>
            <td class="text-right font-bold">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</td>
        </tr>
        <tr style="background-color: #e8e8e8;">
            <td colspan="3" class="text-right font-bold">SELISIH KAS PERIODE INI</td>
            <td colspan="2" class="text-right font-bold" style="{{ $selisihPeriode >= 0 ? 'color:#006400;' : 'color:#c00;' }}">
                Rp {{ number_format(abs($selisihPeriode), 0, ',', '.') }}
                {{ $selisihPeriode >= 0 ? '(Surplus)' : '(Defisit)' }}
            </td>
        </tr>
    </tfoot>
</table>

{{-- RINGKASAN ARUS KAS PERIODE --}}
<div class="summary-section">
    <div class="section-title">II. Ringkasan Arus Kas Periode Ini</div>
    <div class="summary-box">
        <div class="summary-box-title">Arus Kas Masuk</div>
        <div class="summary-row">
            <span class="summary-label">Simpanan anggota</span>
            <span class="summary-value">Rp {{ number_format($totalSimpananPeriode, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Angsuran pokok diterima</span>
            <span class="summary-value">Rp {{ number_format($totalPokokPeriode, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Pendapatan bunga diterima</span>
            <span class="summary-value">Rp {{ number_format($totalBungaPeriode, 0, ',', '.') }}</span>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-row total-row">
            <span class="summary-label">Total Kas Masuk</span>
            <span class="summary-value">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</span>
        </div>

        <div class="summary-box-title" style="margin-top:10px;">Arus Kas Keluar</div>
        <div class="summary-row">
            <span class="summary-label">Pencairan pinjaman</span>
            <span class="summary-value">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</span>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-row total-row">
            <span class="summary-label">Total Kas Keluar</span>
            <span class="summary-value">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</span>
        </div>

        <div class="summary-divider"></div>
        <div class="summary-row total-row" style="{{ $selisihPeriode >= 0 ? 'color:#006400;' : 'color:#c00;' }}">
            <span class="summary-label">Selisih Kas Periode Ini</span>
            <span class="summary-value">
                Rp {{ number_format(abs($selisihPeriode), 0, ',', '.') }}
                {{ $selisihPeriode >= 0 ? '(Surplus)' : '(Defisit)' }}
            </span>
        </div>
    </div>

    {{-- POSISI KEUANGAN KUMULATIF --}}
    <div class="section-title">III. Posisi Keuangan Koperasi (Kumulatif hingga Saat Ini)</div>
    <div class="summary-box">
        <div class="summary-box-title">A. Aset Lancar (Kas & Setara Kas)</div>
        <div class="summary-row">
            <span class="summary-label">Akumulasi simpanan anggota</span>
            <span class="summary-value">Rp {{ number_format($akumulasiSimpanan, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Akumulasi angsuran pokok diterima</span>
            <span class="summary-value">Rp {{ number_format($akumulasiPokok, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Akumulasi pendapatan bunga</span>
            <span class="summary-value">Rp {{ number_format($akumulasiBunga, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row" style="color:#c00;">
            <span class="summary-label">Dikurangi: Total pencairan pinjaman</span>
            <span class="summary-value">(Rp {{ number_format($akumulasiPencairan, 0, ',', '.') }})</span>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-row total-row">
            <span class="summary-label">Kas Bersih</span>
            <span class="summary-value">Rp {{ number_format($kasBersih, 0, ',', '.') }}</span>
        </div>

        <div class="summary-box-title" style="margin-top:10px;">B. Aset Tidak Lancar (Piutang)</div>
        <div class="summary-row">
            <span class="summary-label">Total sisa pinjaman aktif anggota</span>
            <span class="summary-value">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</span>
        </div>
        <p class="piutang-note">* Piutang adalah hak tagih koperasi atas pinjaman yang belum dilunasi anggota.</p>

        <div class="summary-divider"></div>
        <div class="summary-row total-aset-row">
            <span class="summary-label">TOTAL ASET KOPERASI (A + B)</span>
            <span class="summary-value">Rp {{ number_format($totalAset, 0, ',', '.') }}</span>
        </div>
    </div>
</div>

<div style="margin-top: 12px; font-size: 9pt; text-align: justify; border: 1px dashed #ccc; padding: 8px;">
    <strong>Catatan Audit:</strong> Laporan ini disusun berdasarkan data transaksi yang telah terverifikasi dalam sistem. Kas Bersih merupakan selisih antara seluruh dana masuk (simpanan + angsuran) dengan dana yang telah dicairkan sebagai pinjaman. Total Aset Koperasi mencakup kas bersih ditambah piutang pinjaman aktif yang merupakan hak tagih koperasi. Laporan ini bukan merupakan laporan keuangan audited.
</div>

<footer>
    Halaman 1 dari 1 - Sistem Informasi Koperasi Dinas Kesehatan Kota Semarang
</footer>

</body>
</html>