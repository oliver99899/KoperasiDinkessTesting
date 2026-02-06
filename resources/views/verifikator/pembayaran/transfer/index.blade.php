<x-app-layout title="Verifikasi Transfer">
    <div class="py-8 px-4">
        <div class="mb-8">
            <h1 class="text-2xl font-black text-gray-900">Verifikasi Transfer Anggota</h1>
            <p class="text-sm text-gray-500 font-medium">Daftar unggahan bukti transfer yang perlu divalidasi ke rekening koperasi.</p>
        </div>

        <div class="bg-white rounded-[1.5rem] border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b">
                        <tr>
                            <th class="px-6 py-4">Peminjam</th>
                            <th class="px-6 py-4">Pinjaman</th>
                            <th class="px-6 py-4 text-center">Bulan Ke</th>
                            <th class="px-6 py-4 text-center">Tgl Transfer</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($items as $item)
                            <tr class="hover:bg-red-50/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $item->pinjaman->user->name }}</div>
                                    <div class="text-[10px] text-gray-400 font-mono">NIP: {{ $item->pinjaman->user->nip }}</div>
                                </td>
                                <td class="px-6 py-4 font-bold text-red-700">#{{ $item->pinjaman->nomor_pinjaman }}</td>
                                <td class="px-6 py-4 text-center font-black text-gray-700">Ke-{{ $item->angsuran_ke }}</td>
                                <td class="px-6 py-4 text-center text-gray-500">{{ $item->tanggal_transfer->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('verifikator.pembayaran.transfer.show', $item->id) }}" 
                                       class="inline-flex items-center gap-2 bg-gray-900 text-white px-4 py-2 rounded-xl text-[10px] font-black hover:bg-red-700 transition-all uppercase tracking-wider">
                                        <i class="ph-bold ph-eye"></i> Periksa Bukti
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-gray-400 italic font-medium">Tidak ada antrean verifikasi transfer.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>