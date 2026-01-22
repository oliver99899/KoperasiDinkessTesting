<!DOCTYPE html>
<html>
<head>
    <title>Aktivasi Akun</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333;">

    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
        <h2 style="color: #b91c1c;">Selamat Datang di Koperasi DKK Semarang</h2>

        <p>Halo,</p>
        <p>Admin kami telah mendaftarkan email Anda untuk akses ke Portal Koperasi Digital.</p>

        <p>Untuk mulai menggunakan layanan (Simpan/Pinjam), silakan aktifkan akun Anda dan lengkapi data diri melalui tombol di bawah ini:</p>

        <div style="text-align: center; margin: 30px 0;">
            {{-- Link ini nanti mengarah ke halaman aktivasi user --}}
            <a href="{{ route('aktivasi.form', ['token' => $user->activation_token]) }}" 
               style="background-color: #b91c1c; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">
               Aktifkan Akun Saya
            </a>
        </div>

        <p style="font-size: 12px; color: #666;">
            <i>Jika tombol di atas tidak berfungsi, salin dan tempel link berikut ke browser Anda:</i><br>
            {{ route('aktivasi.form', ['token' => $user->activation_token]) }}
        </p>

        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
        <p style="font-size: 11px; color: #999;">Email ini dikirim otomatis. Jangan balas email ini.</p>
    </div>

</body>
</html>