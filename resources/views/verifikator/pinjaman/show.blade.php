<x-app-layout title="Review Pengajuan">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Review Pengajuan</h1>
            <p class="text-sm text-gray-500">Analisis profil anggota dan detail pengajuan.</p>
        </div>
        <a href="{{ route('verifikator.pinjaman.index') }}" class="text-sm font-bold text-gray-500 hover:text-red-700 flex items-center gap-2 transition-colors">
            <i class="ph-bold ph-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- KOLOM KIRI: PROFIL ANGGOTA & RISK --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 font-bold text-gray-800 flex items-center gap-2">
                    <i class="ph-fill ph-user-circle text-lg text-gray-400"></i> Profil Pemohon
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Nama Lengkap</span>
                        <p class="font-bold text-gray-900 text-lg">{{ $pinjaman->user->profile->nama_lengkap }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">NIK / NIP</span>
                        <p class="font-medium text-gray-700">{{ $pinjaman->user->profile->nik }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Unit Kerja</span>
                        <p class="font-medium text-gray-700">{{ $pinjaman->user->profile->unit_kerja }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">No. HP</span>
                        <p class="font-medium text-gray-700">{{ $pinjaman->user->profile->no_hp }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 font-bold text-blue-800 flex items-center gap-2">
                    <i class="ph-fill ph-chart-pie-slice text-lg"></i> Data Keuangan
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Simpanan</span>
                        <span class="font-bold text-green-600">Rp {{ number_format($totalSimpananUser, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Pinjaman Aktif Lain</span>
                        <span class="font-bold {{ $pinjamanAktifLain > 0 ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $pinjamanAktifLain }} Ajuan
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: DETAIL AJUAN & ACTION --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-red-700 px-6 py-4 border-b border-red-800 font-bold text-white flex items-center gap-2">
                    <i class="ph-fill ph-file-text text-lg"></i> Detail Pengajuan
                </div>
                
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <span class="text-sm text-gray-500 block mb-1">Nominal Diajukan</span>
                            <span class="text-3xl font-extrabold text-gray-900">
                                Rp {{ number_format($pinjaman->jumlah_pengajuan, 0, ',', '.') }}
                            </span>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500 block mb-1">Jangka Waktu</span>
                            <span class="text-3xl font-extrabold text-gray-900">
                                {{ $pinjaman->durasi_bulan }} <span class="text-lg font-medium text-gray-500">Bulan</span>
                            </span>
                        </div>
                    </div>

                    <div class="mb-8">
                        <span class="text-sm text-gray-500 block mb-2">Keperluan Pinjaman</span>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-gray-800 italic">
                            "{{ $pinjaman->alasan }}"
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                         {{-- Tombol Tolak --}}
                         <form action="{{ route('verifikator.pinjaman.reject', $pinjaman->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK pengajuan ini?');" class="w-full">
                            @csrf
                            <button type="submit" class="w-full py-4 rounded-xl border-2 border-red-100 text-red-700 font-bold hover:bg-red-50 hover:border-red-200 transition-all flex flex-col items-center justify-center gap-1">
                                <i class="ph-bold ph-x-circle text-2xl"></i>
                                <span>TOLAK PENGAJUAN</span>
                            </button>
                        </form>

                        {{-- Tombol Setuju --}}
                        <form action="{{ route('verifikator.pinjaman.approve', $pinjaman->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI pencairan dana ini?');" class="w-full">
                            @csrf
                            <button type="submit" class="w-full py-4 rounded-xl bg-green-600 text-white font-bold hover:bg-green-700 shadow-lg shadow-green-600/30 transition-all flex flex-col items-center justify-center gap-1 transform active:scale-[0.98]">
                                <i class="ph-bold ph-check-circle text-2xl"></i>
                                <span>SETUJUI & CAIRKAN DANA</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>