<x-app-layout title="Kelola Bunga Pinjaman">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Kelola Bunga Pinjaman</h1>
        <p class="text-sm text-gray-500 font-medium">Atur persentase bunga berdasarkan tenor pinjaman.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-widest">Daftar Konfigurasi Bunga</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($bungaList as $bunga)
            <div class="px-6 py-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <p class="font-black text-gray-900 text-lg">Tenor {{ $bunga->tenor_bulan }} Bulan</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $bunga->keterangan ?? '-' }}</p>
                    <p class="text-[10px] text-gray-400 mt-1">
                        Terakhir diubah: {{ $bunga->updated_at->translatedFormat('d F Y H:i') }}
                        @if($bunga->updatedBy)
                            oleh <span class="font-bold">{{ $bunga->updatedBy->profile->nama_lengkap ?? $bunga->updatedBy->name }}</span>
                        @endif
                    </p>
                </div>
                <form action="{{ route('verifikator.bunga.update', $bunga->id) }}" method="POST" class="flex items-end gap-3">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Bunga (%)</label>
                        <div class="relative">
                            <input type="number" name="persen" value="{{ $bunga->persen }}" 
                                   step="0.01" min="0.01" max="100" required
                                   class="w-32 rounded-xl border-gray-300 py-2.5 px-4 text-sm font-bold focus:border-red-600 focus:ring-red-600">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">%</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Keterangan</label>
                        <input type="text" name="keterangan" value="{{ $bunga->keterangan }}"
                               class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-sm focus:border-red-600 focus:ring-red-600"
                               placeholder="Opsional...">
                    </div>
                    <button type="submit" class="bg-red-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-red-800 transition-all active:scale-95 whitespace-nowrap">
                        Simpan
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>