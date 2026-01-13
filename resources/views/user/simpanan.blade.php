<x-app-layout title="Simpanan">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <div class="md:col-span-3 bg-gradient-to-r from-red-700 to-red-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <p class="text-red-100 text-sm font-medium mb-1">Total Saldo Simpanan Anda</p>
                    <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                        Rp {{ number_format($totalSaldo, 0, ',', '.') }}
                    </h2>
                    <p class="text-xs text-red-200 mt-2 bg-white/10 inline-block px-3 py-1 rounded-full">
                        <i class="ph-fill ph-check-circle mr-1"></i> Data Terverifikasi Sistem
                    </p>
                </div>
                
                <div class="h-16 w-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/30">
                    <i class="ph-fill ph-wallet text-3xl text-white"></i>
                </div>
            </div>

            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-black opacity-10 rounded-full blur-3xl"></div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center">
            <div class="flex items-center gap-3 mb-2">
                <div class="h-8 w-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                    <i class="ph-fill ph-lock-key"></i>
                </div>
                <span class="text-xs font-bold text-gray-500 uppercase">Simpanan Pokok</span>
            </div>
            <p class="text-xl font-extrabold text-gray-900">Rp {{ number_format($saldoPokok, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center">
            <div class="flex items-center gap-3 mb-2">
                <div class="h-8 w-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="ph-fill ph-calendar-check"></i>
                </div>
                <span class="text-xs font-bold text-gray-500 uppercase">Simpanan Wajib</span>
            </div>
            <p class="text-xl font-extrabold text-gray-900">Rp {{ number_format($saldoWajib, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex flex-col justify-center">
            <div class="flex items-center gap-3 mb-2">
                <div class="h-8 w-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center">
                    <i class="ph-fill ph-piggy-bank"></i>
                </div>
                <span class="text-xs font-bold text-gray-500 uppercase">Simpanan Sukarela</span>
            </div>
            <p class="text-xl font-extrabold text-gray-900">Rp {{ number_format($saldoSukarela, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                <i class="ph-fill ph-clock-counter-clockwise text-gray-400"></i>
                Riwayat Transaksi
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Jenis</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4">Metode</th>
                        <th class="px-6 py-4 text-right">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayatSimpanan as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                {{ $item->tanggal_bayar->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="capitalize px-2.5 py-1 rounded-md text-xs font-bold border 
                                    {{ $item->jenis_simpanan == 'wajib' ? 'bg-blue-50 text-blue-700 border-blue-100' : 
                                      ($item->jenis_simpanan == 'pokok' ? 'bg-purple-50 text-purple-700 border-purple-100' : 'bg-green-50 text-green-700 border-green-100') }}">
                                    {{ ucfirst($item->jenis_simpanan) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 italic">
                                {{ $item->keterangan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 capitalize">
                                {{ str_replace('_', ' ', $item->metode_bayar) }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900">
                                + {{ number_format($item->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="ph-duotone ph-receipt text-4xl text-gray-300"></i>
                                    <span>Belum ada riwayat simpanan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>