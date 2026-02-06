<x-app-layout title="Detail Pinjaman">

    <div class="mb-6">
        <a href="{{ route('verifikator.pinjaman.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-red-700 transition-colors mb-4">
            <i class="ph-bold ph-arrow-left"></i> Kembali ke Daftar
        </a>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Pinjaman #{{ $pinjaman->nomor_pinjaman ?? $pinjaman->id }}</h1>
                <p class="text-sm text-gray-500">
                    {{ $pinjaman->user->profile->nama_lengkap ?? $pinjaman->user->name }} - 
                    <span class="font-mono">{{ $pinjaman->user->nip }}</span>
                </p>
            </div>
            
            <div>
                @if($pinjaman->status == 'dicairkan')
                    <span class="px-4 py-2 rounded-full bg-green-100 text-green-800 font-bold text-sm border border-green-200 flex items-center gap-2">
                        <i class="ph-fill ph-check-circle"></i> Aktif / Dicairkan
                    </span>
                @elseif($pinjaman->status == 'diajukan' || $pinjaman->status == 'verifikasi')
                    <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-800 font-bold text-sm border border-yellow-200 flex items-center gap-2 animate-pulse">
                        <i class="ph-fill ph-clock"></i> Menunggu Verifikasi
                    </span>
                @elseif($pinjaman->status == 'lunas')
                    <span class="px-4 py-2 rounded-full bg-blue-100 text-blue-800 font-bold text-sm border border-blue-200 flex items-center gap-2">
                        <i class="ph-fill ph-medal"></i> Lunas
                    </span>
                @elseif($pinjaman->status == 'ditolak')
                    <span class="px-4 py-2 rounded-full bg-red-100 text-red-800 font-bold text-sm border border-red-200 flex items-center gap-2">
                        <i class="ph-fill ph-x-circle"></i> Ditolak
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-red-700 rounded-3xl shadow-xl overflow-hidden text-white mb-8 relative">
        <div class="absolute top-0 right-0 p-32 bg-white opacity-5 rounded-full -mr-16 -mt-16 blur-2xl"></div>
        <div class="absolute bottom-0 left-0 p-20 bg-black opacity-10 rounded-full -ml-10 -mb-10 blur-xl"></div>

        <div class="relative p-6 md:p-10">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <p class="text-red-100 font-medium mb-1 text-sm uppercase tracking-wider">Total Pengajuan Pinjaman</p>
                    <h2 class="text-4xl md:text-5xl font-extrabold mb-4">
                        Rp {{ number_format($pinjaman->jumlah_pengajuan, 0, ',', '.') }}
                    </h2>
                    <div class="flex items-center gap-4 text-sm font-medium">
                        <div class="bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-sm border border-white/10">
                            <i class="ph-fill ph-calendar-blank mr-1"></i> {{ $pinjaman->durasi_bulan }} Bulan
                        </div>
                        <div class="bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-sm border border-white/10">
                            <i class="ph-fill ph-percent mr-1"></i> Bunga {{ $pinjaman->bunga_persen }}%
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 rounded-2xl p-6 backdrop-blur-sm border border-white/10">
                    @if(in_array($pinjaman->status, ['diajukan', 'verifikasi']))
                        <div class="text-center">
                            <h3 class="font-bold text-lg mb-2">Tindakan Diperlukan</h3>
                            <p class="text-red-100 text-sm mb-6">Pinjaman ini menunggu persetujuan Anda untuk dicairkan.</p>
                            
                            <div class="flex gap-3 justify-center">
                                <form action="{{ route('verifikator.pinjaman.approve', $pinjaman->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin menyetujui pinjaman ini?');">
                                    @csrf
                                    <button type="submit" class="bg-white text-green-700 hover:bg-green-50 px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg transition-all active:scale-95 flex items-center gap-2">
                                        <i class="ph-bold ph-check"></i> Setujui & Cairkan
                                    </button>
                                </form>

                                <form action="{{ route('verifikator.pinjaman.reject', $pinjaman->id) }}" method="POST" onsubmit="return confirm('Tolak pengajuan pinjaman ini?');">
                                    @csrf
                                    <button type="submit" class="bg-red-900/50 hover:bg-red-900 text-white border border-red-500/30 px-6 py-2.5 rounded-xl font-bold text-sm transition-all active:scale-95 flex items-center gap-2">
                                        <i class="ph-bold ph-x"></i> Tolak
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($pinjaman->status == 'dicairkan')
                        @php
                            $persen = $pinjaman->total_pinjaman > 0 ? round((($pinjaman->total_pinjaman - $pinjaman->sisa_pinjaman) / $pinjaman->total_pinjaman) * 100) : 0;
                        @endphp
                        <div class="flex justify-between items-end mb-2">
                            <div>
                                <p class="text-xs text-red-100 uppercase font-bold tracking-wider">Sisa Tagihan</p>
                                <p class="text-2xl font-bold">Rp {{ number_format($pinjaman->sisa_pinjaman, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-3xl font-bold">{{ $persen }}%</p>
                            </div>
                        </div>
                        <div class="w-full bg-black/20 rounded-full h-3 mb-2 overflow-hidden">
                            <div class="bg-yellow-400 h-3 rounded-full transition-all duration-1000" style="width: {{ $persen }}%"></div>
                        </div>
                        <p class="text-xs text-right text-red-100 italic">Pokok: Rp {{ number_format($pinjaman->cicilan_pokok_bulanan, 0, ',', '.') }}/bln</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div class="lg:col-span-2 space-y-6">
            {{-- INFORMASI PENGAJUAN --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                <h3 class="font-bold text-gray-900 text-lg mb-4 flex items-center gap-2">
                    <i class="ph-duotone ph-file-text text-red-600"></i> Informasi Pengajuan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Keperluan</p>
                        <p class="text-gray-900 font-medium">{{ $pinjaman->alasan_pengajuan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Tanggal Pengajuan</p>
                        <p class="text-gray-900 font-medium">{{ $pinjaman->tanggal_pengajuan->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Cicilan Pokok/Bulan</p>
                        <p class="text-gray-900 font-bold text-lg">
                            Rp {{ number_format($pinjaman->simulasi_pokok, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold">Cicilan Bunga/Bulan ({{ $pinjaman->bunga_persen }}%)</p>
                        <p class="text-gray-900 font-bold text-lg">
                            Rp {{ number_format($pinjaman->simulasi_bunga, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="mt-2 p-3 bg-gray-50 rounded-xl border border-gray-200 col-span-2">
                        <p class="text-xs text-gray-500 uppercase font-bold">Total Angsuran/Bulan</p>
                        <p class="text-red-700 font-black text-xl">
                            Rp {{ number_format($pinjaman->simulasi_pokok + $pinjaman->simulasi_bunga, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- DOKUMEN --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                <h3 class="font-bold text-gray-900 text-lg mb-4 flex items-center gap-2">
                    <i class="ph-duotone ph-paperclip text-red-600"></i> Dokumen Persyaratan
                </h3>
                @if($pinjaman->dokumen_syarat)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($pinjaman->dokumen_syarat as $doc)
                            <a href="{{ asset('storage/'.$doc) }}" target="_blank" class="flex flex-col items-center p-3 bg-gray-50 rounded-xl border border-gray-100 hover:bg-red-50 transition-colors">
                                <i class="ph-duotone ph-file-pdf text-3xl text-red-600 mb-1"></i>
                                <span class="text-[10px] font-bold text-gray-500 uppercase">Lihat Dokumen</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 italic">Tidak ada dokumen dilampirkan.</p>
                @endif
            </div>

            {{-- RIWAYAT ANGSURAN --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-900">Riwayat Angsuran</h3>
                </div>
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold">
                        <tr>
                            <th class="px-6 py-3">Tgl Bayar</th>
                            <th class="px-6 py-3">Ke</th>
                            <th class="px-6 py-3 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pinjaman->angsuran as $a)
                            <tr>
                                <td class="px-6 py-3">{{ $a->tanggal_potong->format('d/m/Y') }}</td>
                                <td class="px-6 py-3">{{ $a->angsuran_ke }}</td>
                                <td class="px-6 py-3 text-right font-bold text-gray-900">Rp {{ number_format($a->jumlah_bayar, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-6 py-4 text-center text-gray-400 italic">Belum ada pembayaran.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
                <h3 class="font-bold text-gray-900 text-sm uppercase tracking-wider mb-4">Profil Peminjam</h3>
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-14 w-14 rounded-full bg-red-50 flex items-center justify-center text-xl font-bold text-red-600 border border-red-100">
                        {{ substr($pinjaman->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">{{ $pinjaman->user->profile->nama_lengkap ?? $pinjaman->user->name }}</p>
                        <p class="text-xs text-gray-500 font-mono">{{ $pinjaman->user->nip }}</p>
                    </div>
                </div>
                <div class="space-y-3 pt-4 border-t border-gray-50">
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Total Simpanan</span><span class="font-bold text-gray-900">Rp {{ number_format($totalSimpananUser, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Pinjaman Aktif</span><span class="font-bold text-red-600">{{ $pinjamanAktifLain }}</span></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>