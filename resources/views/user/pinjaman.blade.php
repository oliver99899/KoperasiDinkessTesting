<x-app-layout title="Pinjaman">

    {{-- NOTIFIKASI SUKSES (Muncul setelah ajukan pinjaman) --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
            <i class="ph-fill ph-check-circle text-xl"></i>
            <div>
                <p class="text-sm font-bold">Berhasil!</p>
                <p class="text-xs">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Data Pinjaman</h1>
            <p class="text-sm text-gray-500">Daftar pengajuan dan status pinjaman Anda.</p>
        </div>
        
        {{-- TOMBOL AJUKAN (Sudah Aktif) --}}
        <a href="{{ route('pinjaman.create') }}" 
           class="bg-red-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md hover:bg-red-800 transition-transform active:scale-95 flex items-center gap-2">
            <i class="ph-bold ph-plus"></i>
            Ajukan Pinjaman Baru
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-900 font-bold uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Tanggal Pengajuan</th>
                        <th class="px-6 py-4">Keperluan</th>
                        <th class="px-6 py-4">Tenor</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Jumlah Pinjaman</th>
                        <th class="px-6 py-4 text-right">Sisa Tagihan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pinjaman as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                {{ $item->tanggal_pengajuan->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->alasan }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->durasi_bulan }} Bulan
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->status == 'pending')
                                    <span class="px-2.5 py-1 rounded-full bg-yellow-50 text-yellow-700 text-xs font-bold border border-yellow-200 inline-flex items-center gap-1">
                                        <i class="ph-fill ph-clock"></i> Menunggu
                                    </span>
                                @elseif($item->status == 'approved')
                                    <span class="px-2.5 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold border border-green-200 inline-flex items-center gap-1">
                                        <i class="ph-fill ph-check-circle"></i> Disetujui
                                    </span>
                                @elseif($item->status == 'rejected')
                                    <span class="px-2.5 py-1 rounded-full bg-red-50 text-red-700 text-xs font-bold border border-red-200 inline-flex items-center gap-1">
                                        <i class="ph-fill ph-x-circle"></i> Ditolak
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold border border-blue-200 inline-flex items-center gap-1">
                                        <i class="ph-fill ph-seal-check"></i> Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900">
                                Rp {{ number_format($item->jumlah_pengajuan, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-red-700">
                                Rp {{ number_format($item->sisa_tagihan, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                                <div class="mb-3 flex justify-center">
                                    <div class="h-12 w-12 bg-gray-50 rounded-full flex items-center justify-center">
                                        <i class="ph-duotone ph-file-dashed text-2xl text-gray-300"></i>
                                    </div>
                                </div>
                                Belum ada riwayat pinjaman. Silakan ajukan baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>