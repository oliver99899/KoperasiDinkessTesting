<x-app-layout title="Simpanan">

    <div class="mb-8">
        <div class="bg-gradient-to-r from-red-700 to-red-600 rounded-2xl p-8 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <p class="text-red-100 text-sm font-medium mb-2 uppercase tracking-wider">Total Saldo Simpanan (Iuran)</p>
                    <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight">
                        Rp {{ number_format($totalSaldo, 0, ',', '.') }}
                    </h2>
                    <p class="text-sm text-red-200 mt-3 bg-white/10 inline-flex items-center px-4 py-1.5 rounded-full backdrop-blur-sm border border-white/10">
                        <i class="ph-fill ph-check-circle mr-2"></i> Akumulasi Simpanan Terverifikasi
                    </p>
                </div>
                
                <div class="hidden md:flex h-24 w-24 bg-white/20 rounded-full items-center justify-center backdrop-blur-md border border-white/30 shadow-inner">
                    <i class="ph-fill ph-wallet text-5xl text-white drop-shadow-md"></i>
                </div>
            </div>

            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-black opacity-10 rounded-full blur-3xl"></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50">
            <h3 class="font-bold text-gray-900 text-lg flex items-center gap-2">
                <i class="ph-duotone ph-clock-counter-clockwise text-red-600 text-xl"></i>
                Riwayat Transaksi
            </h3>

            <form method="GET" action="{{ route('simpanan.index') }}" class="w-full sm:w-auto">
                <div class="relative">
                    <i class="ph-bold ph-calendar-blank absolute left-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                    <select name="tahun" onchange="this.form.submit()" 
                            class="w-full sm:w-48 pl-10 pr-8 py-2 text-sm font-bold text-gray-700 bg-white border border-gray-300 rounded-xl focus:ring-red-500 focus:border-red-500 shadow-sm cursor-pointer hover:border-red-300 transition-colors">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="bg-white text-gray-900 font-bold uppercase text-xs border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4">Metode Bayar</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Jumlah Masuk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayatSimpanan as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($item->tanggal_bayar)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4 italic">
                                {{ $item->keterangan ?? 'Iuran Bulanan' }}
                            </td>
                            <td class="px-6 py-4 capitalize">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-gray-100 border border-gray-200 text-gray-600">
                                    @if($item->metode_bayar == 'potong_gaji')
                                        <i class="ph-bold ph-scissors"></i>
                                    @elseif($item->metode_bayar == 'transfer')
                                        <i class="ph-bold ph-bank"></i>
                                    @else
                                        <i class="ph-bold ph-money"></i>
                                    @endif
                                    {{ str_replace('_', ' ', $item->metode_bayar) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                    <i class="ph-fill ph-check-circle"></i> Terverifikasi
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900">
                                + Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-400 italic">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="h-12 w-12 bg-gray-50 rounded-full flex items-center justify-center">
                                        <i class="ph-duotone ph-receipt text-3xl text-gray-300"></i>
                                    </div>
                                    <p>Tidak ada data simpanan untuk tahun {{ $selectedYear }}.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($riwayatSimpanan->count() > 0)
                <tfoot class="bg-gray-50 font-bold text-gray-900">
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-right uppercase text-xs tracking-wider">Total Masuk ({{ $selectedYear }})</td>
                        <td class="px-6 py-4 text-right text-red-700">
                            Rp {{ number_format($riwayatSimpanan->sum('jumlah'), 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

</x-app-layout>