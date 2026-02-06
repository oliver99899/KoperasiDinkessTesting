<x-app-layout title="Riwayat Angsuran">

    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Riwayat Angsuran</h1>
            <p class="text-sm text-gray-500 font-medium">Monitoring pelunasan dan verifikasi pembayaran pinjaman.</p>
        </div>
        
        @php
            $pinjamanAktif = \App\Models\Pinjaman::where('user_id', auth()->id())
                            ->where('status', 'disetujui')
                            ->where('sisa_pinjaman', '>', 0)
                            ->first();
        @endphp
        
        @if($pinjamanAktif)
            <a href="{{ route('angsuran.create', $pinjamanAktif->id) }}" 
               class="bg-red-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-lg shadow-red-700/20 hover:bg-red-800 transition-all active:scale-95 flex items-center gap-2">
                <i class="ph-bold ph-hand-coins text-lg"></i>
                Bayar Angsuran
            </a>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-900 font-semibold uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Referensi Pinjaman</th>
                        <th class="px-6 py-4">Tenor</th>
                        <th class="px-6 py-4">Tanggal Potong/Bayar</th>
                        <th class="px-6 py-4">Metode Pelunasan</th>
                        <th class="px-6 py-4 text-center">Status Verifikasi</th>
                        <th class="px-6 py-4 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($angsuran as $a)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-gray-900 font-semibold">
                                    {{ $a->pinjaman->nomor_pinjaman ?? 'REF-'.$a->pinjaman_id }}
                                </span>
                                <span class="text-[10px] text-gray-400 font-medium uppercase tracking-tighter">ID Transaksi: #{{ str_pad($a->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">
                            Angsuran Ke-{{ $a->angsuran_ke }}
                        </td>
                        <td class="px-6 py-4 font-medium">
                            {{ \Carbon\Carbon::parse($a->tanggal_potong ?? $a->tanggal_bayar)->translatedFormat('d F Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if($a->metode_bayar === 'potong_gaji')
                                    <i class="ph-fill ph-bank text-blue-500"></i>
                                @else
                                    <i class="ph-fill ph-wallet text-orange-500"></i>
                                @endif
                                <span class="capitalize font-medium">{{ str_replace('_', ' ', $a->metode_bayar) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($a->verified_at)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold border border-green-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                                    Terverifikasi
                                </span>
                            @elseif($a->status === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-yellow-50 text-yellow-700 text-xs font-semibold border border-yellow-100 animate-pulse">
                                    <span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span>
                                    Menunggu
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs font-semibold border border-red-100">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>
                                    Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold text-gray-900 text-base">
                                Rp {{ number_format($a->jumlah_bayar, 0, ',', '.') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="h-16 w-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="ph-duotone ph-receipt-x text-4xl text-gray-300"></i>
                                </div>
                                <h3 class="text-gray-900 font-semibold">Tidak Ada Data</h3>
                                <p class="text-gray-500 text-xs font-medium max-w-[200px] mx-auto mt-1">Sistem belum mencatat riwayat pembayaran angsuran untuk akun Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($angsuran->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $angsuran->links() }}
        </div>
        @endif
    </div>

</x-app-layout>