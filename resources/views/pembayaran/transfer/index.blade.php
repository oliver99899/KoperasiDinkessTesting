<x-app-layout title="Bayar Angsuran">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        {{-- HEADER SECTION - TETAP RATA KIRI (LEFT ALIGNED) --}}
        <div class="mb-10 flex items-center gap-5">
            <div class="h-14 w-14 bg-red-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-red-200">
                <i class="ph-bold ph-bank text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Pembayaran Angsuran</h1>
                <p class="text-sm text-gray-500 font-medium">Pilih pinjaman aktif Anda untuk melakukan konfirmasi transfer bank.</p>
            </div>
        </div>

        {{-- PINJAMAN AKTIF SECTION - KARTU RATA TENGAH (CENTERED) --}}
        <div class="mb-12">
            <div class="flex items-center gap-4 mb-8">
                <div class="h-px flex-1 bg-gray-100"></div>
                <h2 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] whitespace-nowrap">Pinjaman Aktif Anda</h2>
                <div class="h-px flex-1 bg-gray-100"></div>
            </div>

            {{-- Container Flex Center agar kartu selalu di tengah --}}
            <div class="flex flex-wrap justify-center gap-6">
                @forelse($pinjamanAktif as $lp)
                    {{-- Kartu Lebar (Landscape) dan Kompak --}}
                    <div class="w-full max-w-xl bg-white rounded-[1.5rem] border border-gray-200 shadow-sm overflow-hidden flex flex-col md:flex-row group hover:border-red-500 transition-all duration-300">
                        {{-- Sisi Kiri: Info Saldo --}}
                        <div class="bg-gray-50 md:w-5/12 p-6 flex flex-col justify-center border-b md:border-b-0 md:border-r border-gray-100">
                            <span class="text-[9px] font-black text-red-600 uppercase tracking-widest mb-1">#{{ $lp->nomor_pinjaman }}</span>
                            <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Sisa Pinjaman</p>
                            <p class="text-2xl font-black text-gray-900 leading-none">
                                <span class="text-xs font-bold text-red-600 mr-0.5">Rp</span>{{ number_format($lp->sisa_pinjaman, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Sisi Kanan: Detail & Tombol --}}
                        <div class="flex-1 p-6 flex flex-col justify-between gap-4">
                            <div class="grid grid-cols-3 gap-2">
                                <div class="text-center md:text-left">
                                    <p class="text-[8px] font-bold text-gray-400 uppercase">Tenor</p>
                                    <p class="text-xs font-black text-gray-700">{{ $lp->durasi_bulan }} Bln</p>
                                </div>
                                <div class="text-center md:text-left">
                                    <p class="text-[8px] font-bold text-gray-400 uppercase">Tagihan</p>
                                    <p class="text-xs font-black text-red-700">Ke-{{ $lp->angsuran_count + 1 }}</p>
                                </div>
                                <div class="text-center md:text-left">
                                    <p class="text-[8px] font-bold text-gray-400 uppercase">Tempo</p>
                                    <p class="text-[10px] font-black text-gray-700">
                                        {{ $lp->jatuh_tempo_berikutnya ? \Carbon\Carbon::parse($lp->jatuh_tempo_berikutnya)->format('d/m/y') : '-' }}
                                    </p>
                                </div>
                            </div>

                            <a href="{{ route('pembayaran.transfer.create', ['pinjaman_id' => $lp->id, 'angsuran_ke' => ($lp->angsuran_count + 1)]) }}" 
                               class="inline-flex items-center justify-center gap-2 bg-gray-900 text-white py-3 px-6 rounded-xl text-[10px] font-black hover:bg-red-700 transition-all active:scale-95 uppercase tracking-widest shadow-md">
                                <i class="ph-bold ph-paper-plane-tilt text-sm"></i>
                                Konfirmasi Bayar
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="w-full max-w-2xl py-10 bg-gray-50 border-2 border-dashed border-gray-200 rounded-[1.5rem] flex flex-col items-center justify-center text-gray-400">
                        <i class="ph-duotone ph-checks text-4xl mb-2"></i>
                        <p class="text-sm font-bold">Tidak ada tagihan aktif yang perlu dibayar.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIWAYAT SECTION - TETAP RATA KIRI --}}
        <div class="bg-white rounded-[1.5rem] border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3 bg-gray-50/30">
                <i class="ph-bold ph-clock-counter-clockwise text-red-600 text-lg"></i>
                <h2 class="font-black text-gray-900 text-xs uppercase tracking-widest">Riwayat Konfirmasi Transfer</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-white text-[9px] font-black text-gray-400 uppercase tracking-[0.15em] border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">ID Pinjaman</th>
                            <th class="px-6 py-4 text-center">Bulan</th>
                            <th class="px-6 py-4 text-center">Tgl Transfer</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Berkas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($items as $item)
                            <tr class="hover:bg-red-50/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <span class="font-bold text-gray-900">#{{ $item->pinjaman->nomor_pinjaman }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-0.5 bg-gray-100 rounded text-[9px] font-black text-gray-600 uppercase">Ke-{{ $item->angsuran_ke }}</span>
                                </td>
                                <td class="px-6 py-4 text-center text-xs font-medium text-gray-500">{{ $item->tanggal_transfer->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->status == 'pending')
                                        <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-[9px] font-black border border-amber-100 uppercase animate-pulse">Menunggu</span>
                                    @elseif($item->status == 'verified' || $item->status == 'approved')
                                        <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-[9px] font-black border border-green-100 uppercase">Valid</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-[9px] font-black border border-red-100 uppercase">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ asset('storage/' . $item->bukti_path) }}" target="_blank" 
                                       class="inline-flex items-center gap-1.5 text-red-600 hover:text-red-800 font-black text-[10px] transition-all">
                                        <i class="ph-bold ph-file-search text-sm"></i> BERKAS
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">
                                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-50">Belum ada data unggahan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>