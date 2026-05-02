<!DOCTYPE html>
<html>
<head>
    <title>Surat Pengajuan Pinjaman</title>
    <style>
        body { font-family: "Times New Roman", Times, serif; font-size: 12pt; margin: 1cm; line-height: 1.5; }
        
        /* CSS Khusus Kop Surat dengan Logo */
        .tabel-kop { width: 100%; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .tabel-kop td { vertical-align: middle; }
        .logo-cell { width: 15%; text-align: center; }
        .teks-cell { width: 85%; text-align: center; }
        .teks-cell h2 { margin: 0; font-size: 16pt; font-weight: bold; }
        .teks-cell h3 { margin: 0; font-size: 14pt; font-weight: bold; }
        .teks-cell p { margin: 0; font-size: 10pt; font-style: italic; }

        .isi-surat { margin-top: 20px; text-align: justify; }
        .tabel-data { width: 100%; margin-top: 10px; margin-bottom: 10px; }
        .tabel-data td { vertical-align: top; padding: 3px 0; }
        .ttd-area { width: 100%; margin-top: 50px; }
        .ttd-kiri { float: left; width: 40%; text-align: center; }
        .ttd-kanan { float: right; width: 40%; text-align: center; }
        .clear { clear: both; }
    </style>
</head>
<body>

    {{-- KOP SURAT BARU DENGAN LOGO --}}
    <table class="tabel-kop">
        <tr>
            <td class="logo-cell">
                {{-- Gunakan public_path agar DomPDF bisa membaca gambarnya --}}
                <img src="{{ public_path('images/logo-semarang.png') }}" width="75" alt="Logo Semarang">
            </td>
            <td class="teks-cell">
                <h2>PEMERINTAH KOTA SEMARANG</h2>
                <h3>KOPERASI PEGAWAI DINAS KESEHATAN</h3>
                <p>Jl. Pandanaran 79, Kota Semarang, Jawa Tengah 50241</p>
            </td>
        </tr>
    </table>

    <div class="isi-surat">
        <p>Perihal: <strong>Permohonan Pinjaman Koperasi</strong></p>
        <br>
        <p>Yang bertanda tangan di bawah ini:</p>
        
        <table class="tabel-data">
            <tr><td width="30%">Nama</td><td width="5%">:</td><td>{{ Auth::user()->profile->nama_lengkap ?? Auth::user()->name }}</td></tr>
            <tr><td>NIP / NIK</td><td>:</td><td>{{ Auth::user()->nip ?? '-' }}</td></tr>
            <tr><td>No. HP / WhatsApp</td><td>:</td><td>{{ Auth::user()->profile->no_hp ?? '-' }}</td></tr>
        </table>

        <p>Dengan ini mengajukan permohonan pinjaman dana kepada Koperasi Dinas Kesehatan Kota Semarang dengan rincian sebagai berikut:</p>

        <table class="tabel-data">
            <tr><td width="30%">Besar Pinjaman</td><td width="5%">:</td><td><strong>Rp {{ number_format($pinjaman->jumlah_pengajuan, 0, ',', '.') }}</strong></td></tr>
            <tr><td>Jangka Waktu (Tenor)</td><td>:</td><td>{{ $pinjaman->durasi_bulan }} Bulan</td></tr>
            <tr><td>Keperluan</td><td>:</td><td>{{ $pinjaman->alasan_pengajuan }}</td></tr>
        </table>

        <p>Saya bersedia mematuhi segala peraturan dan ketentuan yang berlaku di Koperasi Dinas Kesehatan Kota Semarang, termasuk pemotongan gaji setiap bulannya untuk pembayaran angsuran hingga lunas.</p>
        <p>Demikian surat permohonan ini saya buat dengan sebenar-benarnya untuk dapat diproses lebih lanjut.</p>
    </div>

    <div class="ttd-area">
        <div class="ttd-kiri">
            <br>
            Mengetahui,<br>
            Ketua Koperasi<br>
            <br><br><br><br>
            ( ...................................... )
        </div>
        <div class="ttd-kanan">
            Semarang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
            Pemohon,<br>
            <br><br><br><br>
            <strong>( {{ Auth::user()->profile->nama_lengkap ?? Auth::user()->name }} )</strong>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>