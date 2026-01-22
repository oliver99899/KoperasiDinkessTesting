<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pinjaman - Koperasi</title>
    @vite(['resources/css/app.css'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white max-w-sm w-full rounded-3xl shadow-xl overflow-hidden border border-gray-200">
        
        {{-- Header Status --}}
        <div class="bg-green-600 p-8 text-center text-white">
            <div class="h-20 w-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                <i class="ph-bold ph-check text-4xl"></i>
            </div>
            <h1 class="text-2xl font-bold">Valid & Disetujui!</h1>
            <p class="text-green-100 text-sm mt-1">Data ditemukan dalam sistem.</p>
        </div>

        {{-- Detail --}}
        <div class="p-6 space-y-6">
            <div class="text-center border-b border-gray-100 pb-6">
                <p class="text-xs text-gray-400 uppercase tracking-widest font-bold mb-1">Nominal Pencairan</p>
                <p class="text-3xl font-extrabold text-gray-900">
                    Rp {{ number_format($pinjaman->jumlah_pengajuan, 0, ',', '.') }}
                </p>
            </div>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">ID Pengajuan</span>
                    <span class="font-bold text-gray-900">#PJ-{{ str_pad($pinjaman->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Nama Anggota</span>
                    <span class="font-bold text-gray-900 text-right">{{ $pinjaman->user->profile->nama_lengkap }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">NIK</span>
                    <span class="font-bold text-gray-900">{{ $pinjaman->user->profile->nik }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Tanggal Disetujui</span>
                    <span class="font-bold text-green-600">
                        {{ $pinjaman->tanggal_disetujui ? $pinjaman->tanggal_disetujui->format('d M Y') : '-' }}
                    </span>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 text-center">
                <p class="text-xs text-yellow-800 leading-relaxed">
                    <strong class="block mb-1">Instruksi Petugas:</strong>
                    Silakan proses pencairan dana tunai atau transfer ke rekening anggota sesuai nominal di atas.
                </p>
            </div>
        </div>
    </div>

</body>
</html>