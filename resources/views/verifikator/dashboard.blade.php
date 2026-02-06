<x-app-layout title="Dashboard Verifikator">

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Ringkasan Eksekutif</h1>
            <p class="text-sm text-gray-500 font-medium">Data real-time aset dan aktivitas operasional koperasi.</p>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('verifikator.laporan.tahunan') }}" method="GET" class="flex items-center gap-2 bg-white p-2 rounded-2xl border border-gray-200 shadow-sm">
                <select name="year" class="text-xs font-bold border-none focus:ring-0 rounded-xl bg-gray-50 pr-8">
                    @for ($i = date('Y'); $i >= 2024; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
                <button type="submit" class="flex items-center gap-2 bg-red-700 hover:bg-red-800 text-white px-4 py-2 rounded-xl text-xs font-black transition-all active:scale-95">
                    <i class="ph-bold ph-file-pdf text-base"></i>
                    UNDUH LAPORAN
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 text-green-500/10 group-hover:scale-110 transition-transform">
                <i class="ph-fill ph-bank text-8xl"></i>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-green-50 flex items-center justify-center text-green-600 mb-4">
                <i class="ph-fill ph-money text-2xl"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Aset Kas</p>
            <h3 class="text-2xl font-black text-gray-900 mt-1">Rp {{ number_format($totalAset, 0, ',', '.') }}</h3>
            <div class="mt-4 flex flex-col gap-1">
                <div class="flex justify-between text-[9px] font-bold">
                    <span class="text-gray-400 uppercase tracking-tighter">Simpanan:</span>
                    <span class="text-green-600">Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-[9px] font-bold">
                    <span class="text-gray-400 uppercase tracking-tighter">Laba Bunga:</span>
                    <span class="text-blue-600">Rp {{ number_format($totalPendapatanBunga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 text-red-500/10 group-hover:scale-110 transition-transform">
                <i class="ph-fill ph-file-text text-8xl"></i>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-red-50 flex items-center justify-center text-red-600 mb-4">
                <i class="ph-fill ph-clock-countdown text-2xl"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Antrean Pinjaman</p>
            <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $pendingLoan }} <span class="text-sm font-bold text-gray-400">Berkas</span></h3>
            <a href="{{ route('verifikator.pinjaman.index') }}" class="mt-4 inline-flex items-center text-[10px] font-black text-red-700 hover:gap-2 transition-all uppercase tracking-widest">
                Periksa Berkas <i class="ph-bold ph-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 text-blue-500/10 group-hover:scale-110 transition-transform">
                <i class="ph-fill ph-users-three text-8xl"></i>
            </div>
            <div class="h-12 w-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 mb-4">
                <i class="ph-fill ph-user-list text-2xl"></i>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Anggota Aktif</p>
            <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $totalAnggota }} <span class="text-sm font-bold text-gray-400">Orang</span></h3>
            <div class="mt-4 flex items-center gap-1">
                <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Status Server: Aman</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-700 to-red-900 rounded-3xl p-6 border border-red-800 shadow-lg relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 text-white/5 group-hover:rotate-12 transition-transform">
                <i class="ph-fill ph-shield-check text-9xl"></i>
            </div>
            <div class="h-10 w-10 rounded-xl bg-white/20 flex items-center justify-center text-white mb-4 backdrop-blur-sm">
                <i class="ph-bold ph-lightning text-xl"></i>
            </div>
            <p class="text-[10px] font-black text-white/60 uppercase tracking-widest">Aksi Cepat</p>
            <div class="mt-4 flex flex-col gap-2 relative z-10">
                <a href="{{ route('verifikator.simpanan.members') }}" class="bg-white text-red-800 px-4 py-2 rounded-xl text-[10px] font-black text-center hover:bg-gray-100 transition-colors shadow-sm uppercase tracking-wider">
                    Input Simpanan
                </a>
                <a href="{{ route('verifikator.angsuran.index') }}" class="bg-red-600/30 text-white border border-white/20 px-4 py-2 rounded-xl text-[10px] font-black text-center hover:bg-red-600/50 transition-colors uppercase tracking-wider">
                    Verifikasi Transfer
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-8 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <h3 class="font-black text-gray-900 uppercase text-xs tracking-widest flex items-center gap-2">
                <i class="ph-bold ph-clock-counter-clockwise text-red-700 text-lg"></i>
                Jejak Audit Transaksi Terakhir
            </h3>
            <span class="text-[10px] font-bold text-gray-400 bg-white border border-gray-200 px-3 py-1 rounded-full uppercase">
                Menampilkan 5 Data Terbaru
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="bg-gray-50/80 text-gray-400 border-b border-gray-100">
                    <tr>
                        <th class="px-8 py-4 font-black uppercase text-[10px] tracking-widest">Detail Waktu</th>
                        <th class="px-8 py-4 font-black uppercase text-[10px] tracking-widest">Identitas Anggota</th>
                        <th class="px-8 py-4 font-black uppercase text-[10px] tracking-widest text-right">Nominal</th>
                        <th class="px-8 py-4 font-black uppercase text-[10px] tracking-widest text-center">Otoritas Input</th>
                        <th class="px-8 py-4 font-black uppercase text-[10px] tracking-widest text-center">Metode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($recentSimpanan as $simpanan)
                    <tr class="hover:bg-blue-50/30 transition-all group">
                        <td class="px-8 py-5 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-900">{{ $simpanan->tanggal_potong ? $simpanan->tanggal_potong->translatedFormat('d M Y') : '-' }}</span>
                                <span class="text-[9px] text-gray-400  uppercase">Ref ID: #SIM-{{ $simpanan->id }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-500 font-semibold group-hover:bg-red-700 group-hover:text-white transition-colors uppercase">
                                    {{ substr($simpanan->user->profile->nama_lengkap ?? $simpanan->user->name, 0, 2) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-black text-gray-900 text-sm tracking-tight">{{ $simpanan->user->profile->nama_lengkap ?? $simpanan->user->name }}</span>
                                    <span class="text-[10px] font-mono text-gray-400 uppercase">NIP: {{ $simpanan->user->nip ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right text-gray-950">
                            <span class="bg-gray-50 px-3 py-1 rounded-lg border border-gray-100">
                                Rp {{ number_format($simpanan->jumlah, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex flex-col items-center">
                                <span class="text-[10px] font-black text-red-700 uppercase tracking-tighter">
                                    {{ $simpanan->creator->name ?? 'System' }}
                                </span>
                                <span class="text-[9px] text-gray-400 font-semibold uppercase italic">Verified</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="px-3 py-1 bg-white border border-gray-200 rounded-xl text-[9px] font-black text-gray-600 uppercase shadow-sm group-hover:border-red-200">
                                {{ str_replace('_', ' ', $simpanan->metode_bayar ?? 'Potong Gaji') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-300 gap-3">
                                <i class="ph-duotone ph-file-search text-6xl"></i>
                                <p class="text-xs uppercase font-black tracking-widest">Arsip transaksi belum tersedia</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>