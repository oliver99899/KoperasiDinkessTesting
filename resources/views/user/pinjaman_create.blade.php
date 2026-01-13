<x-app-layout title="Ajukan Pinjaman">

    <div>
        {{-- Header & Tombol Kembali --}}
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Formulir Pengajuan</h1>
                <p class="text-sm text-gray-500">Isi detail pinjaman yang dibutuhkan.</p>
            </div>
            <a href="{{ route('pinjaman.index') }}" class="text-sm font-bold text-gray-500 hover:text-red-700 flex items-center gap-2 transition-colors">
                <i class="ph-bold ph-arrow-left"></i>
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            
            <div class="bg-red-700 px-6 py-4 border-b border-red-800 flex items-center gap-3">
                <div class="h-10 w-10 bg-white/10 rounded-lg flex items-center justify-center text-white">
                    <i class="ph-bold ph-hand-coins text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-white">Rincian Pengajuan</h2>
                </div>
            </div>
            
            <form id="formPinjaman" action="{{ route('pinjaman.store') }}" method="POST" class="p-6 md:p-8 space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- KOLOM KIRI --}}
                    <div class="space-y-6">
                        
                        {{-- Input Jumlah (Format Rupiah) --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Pinjaman</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold pointer-events-none">Rp</span>
                                
                                <input type="text" id="rupiah" required
                                    placeholder="Contoh: 5.000.000"
                                    class="w-full rounded-xl border-gray-300 py-3 pl-12 pr-4 text-gray-900 font-medium shadow-sm focus:border-red-600 focus:ring-red-600 placeholder:text-gray-400 transition-all">
                                
                                <input type="hidden" name="jumlah_pengajuan" id="jumlah_asli">
                            </div>
                            <p class="text-xs text-gray-500 mt-1.5 ml-1">Minimal: Rp 500.000 — Maksimal: Rp 10.000.000</p>
                        </div>

                        {{-- Input Tenor (UPDATE: Opsi Baru 1-12 Bulan) --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jangka Waktu (Tenor)</label>
                            <div class="relative">
                                <i class="ph-bold ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg pointer-events-none"></i>
                                
                                <select name="durasi_bulan" required 
                                        class="w-full rounded-xl border-gray-300 py-3 pl-12 pr-10 text-gray-900 font-medium shadow-sm focus:border-red-600 focus:ring-red-600 appearance-none transition-all cursor-pointer">
                                    <option value="">-- Pilih Durasi --</option>
                                    <option value="1">1 Bulan</option>
                                    <option value="3">3 Bulan</option>
                                    <option value="6">6 Bulan</option>
                                    <option value="9">9 Bulan</option>
                                    <option value="12">12 Bulan (1 Tahun)</option>
                                </select>
                                
                                <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>

                        {{-- Input Alasan --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Keperluan Pinjaman</label>
                            <textarea name="alasan" rows="3" required placeholder="Contoh: Renovasi rumah..."
                                    class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 font-medium shadow-sm focus:border-red-600 focus:ring-red-600 placeholder:text-gray-400 transition-all"></textarea>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: INFO --}}
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 h-fit">
                        <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="ph-fill ph-info text-blue-600"></i>
                            Informasi & Ketentuan
                        </h4>
                        <ul class="space-y-4 text-sm text-gray-600">
                            <li class="flex gap-3">
                                <span class="h-6 w-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold shrink-0">1</span>
                                <span>Bunga pinjaman <strong>1% Flat / Bulan</strong>.</span>
                            </li>
                            <li class="flex gap-3">
                                <span class="h-6 w-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold shrink-0">2</span>
                                <span>Maksimal potongan <strong>40% Gaji Pokok</strong>.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="pt-6 border-t border-gray-100 flex items-center justify-end">
                    <button type="submit" class="w-full sm:w-auto bg-red-700 hover:bg-red-800 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-red-700/20 transition-all transform active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="ph-bold ph-paper-plane-right"></i>
                        Ajukan
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- SCRIPT FORMAT RUPIAH --}}
    <script>
        const inputRupiah = document.getElementById('rupiah');
        const inputAsli = document.getElementById('jumlah_asli');
        const form = document.getElementById('formPinjaman');

        inputRupiah.addEventListener('keyup', function(e) {
            let value = this.value.replace(/[^,\d]/g, '').toString();
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            this.value = rupiah;
            inputAsli.value = value.replace(/\./g, '');
        });

        form.addEventListener('submit', function() {
            inputAsli.value = inputRupiah.value.replace(/\./g, '');
        });
    </script>

</x-app-layout>