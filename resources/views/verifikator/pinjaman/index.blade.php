<x-app-layout title="Daftar Ajuan Pinjaman">

    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Antrean Verifikasi</h1>
            <p class="text-sm text-gray-500">Daftar pengajuan yang menunggu persetujuan Anda.</p>
        </div>
        <a href="{{ route('verifikator.dashboard') }}" class="text-sm font-bold text-gray-500 hover:text-red-700 flex items-center gap-2 transition-colors">
            <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-900 font-bold uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Tanggal Pengajuan</th>
                        <th class="px-6 py-4">Anggota</th>
                        <th class="px-6 py-4">Nominal</th>
                        <th class="px-6 py-4">Tenor</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pinjaman as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                {{ $item->created_at->format('d M Y') }}
                                <span class="block text-xs text-gray-400 font-normal">{{ $item->created_at->format('H:i') }} WIB</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $item->user->profile->nama_lengkap }}</div>
                                <div class="text-xs text-gray-500">{{ $item->user->profile->unit_kerja }}</div>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-900">
                                Rp {{ number_format($item->jumlah_pengajuan, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded font-bold text-xs">
                                    {{ $item->durasi_bulan }} Bulan
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('verifikator.pinjaman.show', $item->id) }}" 
                                   class="inline-flex items-center gap-2 bg-red-700 text-white px-4 py-2 rounded-lg font-bold text-xs hover:bg-red-800 transition-transform active:scale-95 shadow-md shadow-red-700/20">
                                    <i class="ph-bold ph-magnifying-glass"></i> Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">
                                <div class="mb-3 flex justify-center">
                                    <div class="h-12 w-12 bg-green-50 rounded-full flex items-center justify-center">
                                        <i class="ph-duotone ph-check-circle text-2xl text-green-600"></i>
                                    </div>
                                </div>
                                Tidak ada antrean. Semua pengajuan telah diproses.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>