<div class="overflow-hidden h-full flex flex-col">
    <div class="overflow-x-auto overflow-y-auto custom-scrollbar flex-grow">
        <table class="w-full text-sm text-gray-600 border-separate border-spacing-0">
            <thead class="bg-gray-50 text-gray-900 font-bold sticky top-0 z-10 shadow-sm border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left border-b">Tanggal Potong</th>
                    <th class="px-6 py-4 text-left border-b">Keterangan</th>
                    <th class="px-6 py-4 text-right border-b">Jumlah</th>
                    <th class="px-6 py-4 text-center border-b">Aksi</th>
                </tr>
            </thead>
            
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($history as $h)
                <tr class="hover:bg-blue-50/30 transition-colors group">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                        <div class="flex flex-col text-left">
                            <span class="text-sm font-bold">{{ \Carbon\Carbon::parse($h->tanggal_potong)->translatedFormat('d F Y') }}</span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-tighter">Periode: {{ $h->periode }}</span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-left">
                        <span class="text-xs text-gray-500 line-clamp-1 italic">
                            {{ $h->keterangan ?? '-' }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-right font-black text-gray-900">
                        Rp {{ number_format($h->jumlah, 0, ',', '.') }}
                    </td>

                    <td class="px-6 py-4 text-center">
                        <button type="button" 
                                @click="deleteHistory({{ $h->id }})"
                                class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700 transition-all active:scale-90"
                                title="Hapus Permanen">
                            <i class="ph-bold ph-trash-simple text-lg"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center justify-center opacity-40">
                            <i class="ph-duotone ph-clipboard-text text-6xl mb-4 text-gray-300"></i>
                            <p class="text-sm font-bold">Belum ada catatan simpanan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($history->isNotEmpty())
    <div class="bg-gray-50 border-t border-gray-200 p-4 sticky bottom-0 z-10 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <div class="flex justify-between items-center px-2">
            <span class="text-[10px] uppercase font-black tracking-widest text-gray-400">Akumulasi Saldo Tahun Ini</span>
            <div class="text-right leading-tight">
                <span class="text-2xl font-black text-blue-900">
                    Rp {{ number_format($history->sum('jumlah'), 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>
    @endif
</div>