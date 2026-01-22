<x-app-layout title="Dashboard">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600">
                <i class="ph-fill ph-money text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Aset Koperasi</p>
                <h3 class="text-xl font-extrabold text-gray-900">Rp {{ number_format($totalAset, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
                <i class="ph-fill ph-file-text text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Menunggu Verifikasi</p>
                <h3 class="text-xl font-extrabold text-gray-900">{{ $pendingLoan }} Pengajuan</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                <i class="ph-fill ph-users-three text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Anggota Aktif</p>
                <h3 class="text-xl font-extrabold text-gray-900">{{ $totalAnggota }} Orang</h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-900">Riwayat Transaksi Terakhir</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Anggota</th>
                        <th class="px-6 py-3">Jenis</th>
                        <th class="px-6 py-3 text-right">Nominal</th>
                        <th class="px-6 py-3">Via</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentSimpanan as $simpanan)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3 font-medium">{{ $simpanan->tanggal_bayar->format('d M Y') }}</td>
                        <td class="px-6 py-3">
                            <div class="font-bold text-gray-900">{{ $simpanan->user->profile->nama_lengkap }}</div>
                            <div class="text-xs text-gray-500">{{ $simpanan->user->profile->nik }}</div>
                        </td>
                        <td class="px-6 py-3">
                            <span class="capitalize px-2 py-1 rounded-md text-xs font-bold 
                                {{ $simpanan->jenis_simpanan == 'wajib' ? 'bg-blue-100 text-blue-700' : 
                                  ($simpanan->jenis_simpanan == 'pokok' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700') }}">
                                {{ $simpanan->jenis_simpanan }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-right font-bold text-gray-900">
                            Rp {{ number_format($simpanan->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3 text-gray-500 capitalize">{{ str_replace('_', ' ', $simpanan->metode_bayar) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">Belum ada data transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>