<x-app-layout title="Cetak Laporan Keuangan">

    <div class="mb-8">
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Cetak Laporan Koperasi</h1>
        <p class="text-sm text-gray-500 font-medium">Filter rentang tanggal untuk mengunduh rekapitulasi arus kas.</p>
    </div>

    <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm max-w-2xl">
        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
            <div class="h-12 w-12 rounded-xl bg-red-50 flex items-center justify-center text-red-700">
                <i class="ph-fill ph-file-pdf text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 text-lg">Laporan Arus Kas</h3>
                <p class="text-xs text-gray-500">Format file: PDF (.pdf)</p>
            </div>
        </div>

        {{-- Form yang dipindah dari Dashboard --}}
        <form action="{{ route('verifikator.laporan.tahunan') }}" method="GET" class="flex flex-col gap-4">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Mulai Tanggal</label>
                    <input type="date" name="start_date" required value="{{ date('Y-m-01') }}" 
                           class="w-full font-bold border-gray-200 focus:ring-red-500 focus:border-red-500 rounded-xl bg-gray-50 px-4 py-3 text-gray-700 cursor-pointer">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Sampai Tanggal</label>
                    <input type="date" name="end_date" required value="{{ date('Y-m-t') }}" 
                           class="w-full font-bold border-gray-200 focus:ring-red-500 focus:border-red-500 rounded-xl bg-gray-50 px-4 py-3 text-gray-700 cursor-pointer">
                </div>
            </div>

            <button type="submit" class="mt-4 flex justify-center items-center gap-2 bg-red-700 hover:bg-red-800 text-white px-6 py-3 rounded-xl text-sm font-black transition-all active:scale-95 w-full">
                <i class="ph-bold ph-download-simple text-lg"></i>
                UNDUH LAPORAN SEKARANG
            </button>
        </form>
    </div>

</x-app-layout>