<x-app-layout title="Riwayat Pinjaman">

    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pinjaman Saya</h1>
            <p class="text-sm text-gray-500 font-medium">Lihat status pengajuan dan detail kewajiban angsuran Anda.</p>
        </div>
        
        <a href="{{ route('pinjaman.create') }}" 
           class="bg-red-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-red-700/20 hover:bg-red-800 transition-all active:scale-95 flex items-center gap-2">
            <i class="ph-bold ph-plus-circle text-lg"></i>
            Ajukan Pinjaman
        </a>
    </div>

    

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-900 font-bold uppercase text-[10px] tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Informasi Pinjaman</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Total Pinjaman</th>
                        <th class="px-6 py-4 text-right border-l border-gray-100">Sisa Tagihan</th>
                        <th class="px-6 py-4 text-center">Opsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($pinjaman as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-900">{{ $item->nomor_pinjaman ?? 'PENGAJUAN BARU' }}</span>
                                    <span class="text-[10px] text-gray-400 font-medium tracking-tight uppercase">
                                        {{ $item->tanggal_pengajuan->translatedFormat('d F Y') }} • {{ $item->durasi_bulan }} Bulan
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->status == 'diajukan')
                                    <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-[10px] font-black border border-blue-100 uppercase">
                                        Menunggu Verifikasi
                                    </span>
                                @elseif($item->status == 'verifikasi')
                                    <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-[10px] font-black border border-amber-100 uppercase animate-pulse">
                                        Sedang Direview
                                    </span>
                                @elseif($item->status == 'dicairkan')
                                    <span class="px-3 py-1 rounded-full bg-green-50 text-green-700 text-[10px] font-black border border-green-100 uppercase">
                                        Aktif (Berjalan)
                                    </span>
                                @elseif($item->status == 'ditolak')
                                    <span class="px-3 py-1 rounded-full bg-red-50 text-red-700 text-[10px] font-black border border-red-100 uppercase">
                                        Pengajuan Ditolak
                                    </span>
                                @elseif($item->status == 'lunas')
                                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-[10px] font-black border border-gray-200 uppercase">
                                        Selesai / Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900 whitespace-nowrap">
                                Rp {{ number_format($item->jumlah_pengajuan, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right font-black whitespace-nowrap border-l border-gray-50 {{ $item->sisa_pinjaman > 0 ? 'text-red-700' : 'text-gray-300' }}">
                                Rp {{ number_format($item->sisa_pinjaman ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Muncul hanya jika sudah disetujui/cair --}}
                                    @if($item->status == 'dicairkan' && $item->sisa_pinjaman > 0)
                                        <a href="{{ route('pembayaran.transfer.index') }}" 
                                           class="h-8 px-3 flex items-center justify-center bg-red-700 text-white rounded-lg hover:bg-red-800 transition-all font-bold text-[10px] gap-1.5 shadow-sm">
                                            <i class="ph-bold ph-wallet"></i> BAYAR
                                        </a>
                                        <a href="{{ route('pinjaman.download', $item->id) }}" 
                                           class="h-8 w-8 flex items-center justify-center bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-all"
                                           title="Bukti Pencairan">
                                            <i class="ph-bold ph-printer"></i>
                                        </a>
                                    @endif

                                    {{-- Tombol Batal jika status masih diajukan --}}
                                    @if($item->status == 'diajukan')
                                        <form action="{{ route('pinjaman.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan dan menghapus pengajuan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-[10px] font-black text-gray-400 hover:text-red-600 transition-colors uppercase tracking-widest">
                                                <i class="ph-bold ph-trash"></i> Batalkan
                                            </button>
                                        </form>
                                    @endif

                                    @if(in_array($item->status, ['ditolak', 'lunas']))
                                        <span class="text-[10px] text-gray-400 font-bold italic uppercase">Tidak ada aksi</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="opacity-30">
                                    <i class="ph-duotone ph-article text-6xl mb-2"></i>
                                    <p class="font-bold">Belum ada riwayat pinjaman.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>