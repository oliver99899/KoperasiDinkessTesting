<!DOCTYPE html>
<html>
<head>
    <title>Bukti Persetujuan Pinjaman</title>
    <style>
        body { 
            font-family: "Times New Roman", Times, serif; 
            font-size: 12pt; 
            color: #000; 
            line-height: 1.5;
        }
        
        .header-table { width: 100%; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 10px; }
        .logo-cell { width: 10%; text-align: center; vertical-align: middle; }
        .text-cell { text-align: center; vertical-align: middle; }
        .kop-1 { font-size: 14pt; font-weight: bold; margin: 0; }
        .kop-2 { font-size: 16pt; font-weight: bold; margin: 0; }
        .kop-alamat { font-size: 11pt; margin: 0; }

        .date-section { text-align: right; margin-bottom: 5px; }

        .info-table { width: 100%; margin-bottom: 20px; }
        .info-label { width: 80px; vertical-align: top; }
        .info-sep { width: 10px; vertical-align: top; }
        
        .content { text-align: justify; margin-bottom: 15px; }
        
        .data-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .data-table th, .data-table td { 
            border: 1px solid #000; 
            padding: 8px; 
            text-align: center; /* Mengubah semua isi tabel menjadi rata tengah */
            font-size: 11pt;
            vertical-align: middle;
        }
        .data-table th { background-color: #f0f0f0; }

        .footer-section { margin-top: 40px; width: 100%; }
        .qr-box { text-align: center; float: right; width: 40%; }
        .note { font-size: 9pt; color: #555; font-style: italic; margin-top: 10px; }
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

    <div class="date-section">
        Semarang, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Nomor</td>
            <td class="info-sep">:</td>
            <td></td>
        </tr>
        <tr>
            <td class="info-label">Sifat</td>
            <td class="info-sep">:</td>
            <td></td>
        </tr>
        <tr>
            <td class="info-label">Lampiran</td>
            <td class="info-sep">:</td>
            <td></td>
        </tr>
        <tr>
            <td class="info-label">Hal</td>
            <td class="info-sep">:</td>
            <td>Persetujuan Pencairan Pinjaman</td>
        </tr>
    </table>

    <div class="content">
        Dengan hormat,<br>
        Menindaklanjuti permohonan pinjaman yang Saudara ajukan, setelah dilakukan verifikasi administrasi dan keuangan, maka dengan ini Pengurus Koperasi menyatakan <strong>MENYETUJUI</strong> pengajuan pinjaman atas nama:
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIK</th>
                <th>Unit Kerja</th>
                <th>Nominal Disetujui</th>
                <th>Tenor</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $pinjaman->user->profile->nama_lengkap ?? 'Anggota Koperasi' }}</td>
                <td>{{ $pinjaman->user->profile->nik ?? '-' }}</td>
                <td>{{ $pinjaman->user->profile->unit_kerja ?? 'Dinas Kesehatan' }}</td>
                <td style="font-weight: bold;">Rp {{ number_format($pinjaman->jumlah_pengajuan, 0, ',', '.') }}</td>
                <td>{{ $pinjaman->durasi_bulan }} Bulan</td>
            </tr>
        </tbody>
    </table>

    <div class="content">
        Surat ini diterbitkan sebagai bukti persetujuan pencairan dana yang sah. Kepada nama yang tercantum di atas, dimohon untuk membawa dan menyerahkan surat ini (atau menunjukkan QR Code dibawah) kepada Bendahara Koperasi guna memproses pengambilan uang tunai.
    </div>

    <div class="footer-section">
        <div class="qr-box">
            <p style="margin-bottom: 10px;">Validasi Digital:</p>
            
            <img src="data:image/svg+xml;base64,{{ $qrcode }}" width="110" style="border: 1px solid #ccc; padding: 5px;">
            
            <p class="note">
                Dokumen ini telah ditandatangani secara elektronik. <br>
                Scan untuk memverifikasi keaslian.
            </p>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>