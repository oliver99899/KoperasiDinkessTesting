<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan Tahunan - {{ $tahun }}</title>
    <style>
        body { 
            font-family: "Times New Roman", Times, serif; 
            font-size: 11pt; 
            color: #000; 
            line-height: 1.4;
            margin: 0.5cm;
        }
        
        .header-table { width: 100%; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 10px; }
        .logo-cell { width: 10%; text-align: center; vertical-align: middle; }
        .text-cell { text-align: center; vertical-align: middle; }
        .kop-1 { font-size: 14pt; font-weight: bold; margin: 0; }
        .kop-2 { font-size: 16pt; font-weight: bold; margin: 0; }
        .kop-alamat { font-size: 10pt; margin: 0; }

        .report-title { text-align: center; text-decoration: underline; font-weight: bold; font-size: 13pt; margin-top: 20px; text-transform: uppercase; }
        .report-subtitle { text-align: center; font-weight: bold; margin-bottom: 20px; }

        .date-info { text-align: right; font-size: 10pt; margin-bottom: 10px; font-style: italic; }

        .data-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .data-table th, .data-table td { 
            border: 1px solid #000; 
            padding: 6px; 
            font-size: 9pt;
            vertical-align: middle;
        }
        .data-table th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        /* Warna untuk memudahkan audit */
        .row-masuk { color: #000; }
        .row-keluar { color: #d00; } /* Merah untuk uang keluar (Pencairan) */

        .summary-box { margin-top: 20px; width: 100%; border: 2px solid #000; padding: 10px; }
        .summary-title { font-weight: bold; text-decoration: underline; margin-bottom: 5px; display: block; }
        
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
    <div class="report-subtitle">Periode Tahun: {{ $tahun }}</div>

    <div class="date-info">
        Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y [jam] HH:mm') }}
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="15%">Tanggal</th>
                <th width="20%">Nama Anggota</th>
                <th width="25%">Keterangan Transaksi</th>
                <th width="20%">Debit (Masuk)</th>
                <th width="20%">Kredit (Keluar)</th>
            </tr>
        </thead>
        <tbody>
            {{-- 
                Logic: $transaksi adalah gabungan (Union) dari tabel Simpanan, Pencairan Pinjaman, dan Angsuran 
                yang sudah diurutkan 'desc' berdasarkan created_at / tanggal 
            --}}
            @forelse($transaksi as $t)
                <tr class="{{ $t->tipe == 'keluar' ? 'row-keluar' : 'row-masuk' }}">
                    <td class="text-center">{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $t->user->profile->nama_lengkap ?? 'Unknown' }}</td>
                    <td>{{ $t->deskripsi }}</td>
                    
                    {{-- Kolom Debit --}}
                    <td class="text-right">
                        @if($t->tipe == 'masuk')
                            Rp {{ number_format($t->nominal, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>

                    {{-- Kolom Kredit --}}
                    <td class="text-right">
                        @if($t->tipe == 'keluar')
                            Rp {{ number_format($t->nominal, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data transaksi pada tahun ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="font-bold" style="background-color: #f9f9f9;">
                <td colspan="3" class="text-center">TOTAL KAS TAHUN {{ $tahun }}</td>
                <td class="text-right">Rp {{ number_format($total_masuk, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($total_keluar, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="summary-box">
        <span class="summary-title">RINGKASAN ASET KOPERASI (Hingga Saat Ini)</span>
        <table style="width: 100%; font-weight: bold;">
            <tr>
                <td>1. Total Simpanan Anggota (Iuran)</td>
                <td class="text-right">: Rp {{ number_format($akumulasi_simpanan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>2. Total Pendapatan Bunga Pinjaman</td>
                <td class="text-right">: Rp {{ number_format($akumulasi_bunga, 0, ',', '.') }}</td>
            </tr>
            <tr style="font-size: 13pt; border-top: 1px solid #000;">
                <td>TOTAL ASET KAS (1 + 2)</td>
                <td class="text-right" style="color: blue;">: Rp {{ number_format($total_aset, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 30px; font-size: 9pt; text-align: justify; border: 1px dashed #ccc; padding: 10px;">
        <strong>Catatan Audit:</strong> Laporan ini mencakup seluruh dana iuran masuk, dana pinjaman yang dikeluarkan, serta pengembalian pokok dan bunga oleh anggota. Jika terdapat selisih antara Total Aset Kas dengan saldo bank, harap periksa kembali status verifikasi pada masing-masing transaksi di sistem.
    </div>

    <footer>
        Halaman 1 dari 1 - Sistem Informasi Koperasi Dinas Kesehatan Kota Semarang
    </footer>

</body>
</html>