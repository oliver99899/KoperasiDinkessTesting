<x-app-layout title="Daftar Pengajuan Pinjaman">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pengajuan Pinjaman</h1>
            <p class="text-sm text-gray-500">Kelola dan verifikasi pengajuan pinjaman anggota.</p>
        </div>
        
        <form method="GET" class="relative">
            <input type="text" name="search" placeholder="Cari Nama / NIP..." value="{{ request('search') }}"
                   class="pl-10 pr-4 py-2 rounded-xl border-gray-300 text-sm focus:border-red-600 focus:ring-red-600 w-full sm:w-64 shadow-sm">
            <i class="ph-bold ph-magnifying-glass absolute left-3 top-2.5 text-gray-400"></i>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-900 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Pemohon</th>
                        <th class="px-6 py-4">Nominal</th>
                        <th class="px-6 py-4">Tenor</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pinjaman as $p)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center font-bold text-lg border border-red-100">
                                    {{ substr($p->user->profile->nama_lengkap ?? $p->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">{{ $p->user->profile->nama_lengkap ?? $p->user->name }}</div>
                                    <div class="text-xs text-gray-500 font-mono">NIP: {{ $p->user->nip ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900">
                            Rp {{ number_format($p->jumlah_pengajuan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $p->durasi_bulan }} Bulan
                        </td>
                        <td class="px-6 py-4">
                            {{ $p->tanggal_pengajuan->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($p->status == 'diajukan' || $p->status == 'verifikasi')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200 animate-pulse">
                                    Menunggu
                                </span>
                            @elseif($p->status == 'dicairkan')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                    Aktif
                                </span>
                            @elseif($p->status == 'lunas')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                    Lunas
                                </span>
                            @elseif($p->status == 'ditolak')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                    Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('verifikator.pinjaman.show', $p->id) }}" 
                               class="inline-flex items-center gap-1 bg-white border border-gray-300 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-red-50 hover:text-red-700 hover:border-red-200 transition-all shadow-sm">
                                <i class="ph-bold ph-eye"></i> Review
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="ph-duotone ph-files text-4xl mb-2 text-gray-300"></i>
                            <p>Belum ada pengajuan pinjaman.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pinjaman->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $pinjaman->links() }}
        </div>
        @endif
    </div>
</x-app-layout>