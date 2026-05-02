<x-app-layout title="Ajukan Pinjaman">
    <div class="w-full max-w-5xl mx-auto" x-data="loanCalculator()">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Formulir Pengajuan</h1>
                <p class="text-sm text-gray-500 font-medium">Dana akan diproses setelah diverifikasi oleh bendahara.</p>
            </div>
            <a href="{{ route('pinjaman.index') }}" class="text-sm font-bold text-gray-500 hover:text-red-700 flex items-center gap-2 transition-colors">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-red-700 px-6 py-4 flex items-center gap-3">
                    <div class="h-10 w-10 bg-white/10 rounded-lg flex items-center justify-center text-white">
                        <i class="ph-bold ph-hand-coins text-xl"></i>
                    </div>
                    <h2 class="text-lg font-bold text-white">Rincian Pengajuan</h2>
                </div>
                
                <form action="{{ route('pinjaman.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase mb-2 tracking-wider">Jumlah Pinjaman</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-semibold">Rp</span>
                                <input type="text" x-model="formattedAmount" @input="updateAmount" required placeholder="0"
                                    class="w-full rounded-xl border-gray-300 py-3 pl-12 pr-4 text-gray-900 font-bold focus:border-red-600 focus:ring-red-600 text-lg">
                                <input type="hidden" name="jumlah_pengajuan" x-model="amount">
                            </div>
                            @error('jumlah_pengajuan') <p class="text-[10px] text-red-600 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase mb-2 tracking-wider">Jangka Waktu</label>
                            <select name="durasi_bulan" x-model="tenor" required @change="calculate()"
                                class="w-full rounded-xl border-gray-300 py-3 font-semibold text-gray-900 focus:border-red-600 focus:ring-red-600 cursor-pointer">
                                <option value="0">-- Pilih Durasi --</option>
                                <option value="3">3 Bulan</option>
                                <option value="6">6 Bulan</option>
                                <option value="12">12 Bulan</option>
                                <option value="24">24 Bulan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-500 uppercase mb-2 tracking-wider">Keperluan Pinjaman</label>
                            <textarea name="alasan_pengajuan" rows="3" required placeholder="Contoh: Biaya pendidikan anak..."
                                class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 font-medium focus:border-red-600 focus:ring-red-600 transition-all"></textarea>
                        </div>

                        <div x-data="fileUploader()">
                            <label class="block text-xs font-black text-gray-500 uppercase mb-2 tracking-wider">Dokumen Syarat (PDF/JPG)</label>
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-red-50 hover:border-red-300 transition-all group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center">
                                    <i class="ph-bold ph-cloud-arrow-up text-2xl text-red-700 group-hover:scale-110 transition-transform"></i>
                                    <span class="text-xs text-gray-500 font-bold mt-2">Klik untuk Pilih File</span>
                                    <p class="text-[10px] text-gray-400 mt-1 px-4">KTP / Slip Gaji (Maks. 5MB)</p>
                                </div>
                                <input type="file" id="fileInput" name="dokumen_pendukung[]" multiple class="hidden" accept=".pdf,.jpg,.jpeg,.png" @change="addFiles">
                            </label>

                            <div class="mt-4 space-y-2" x-show="files.length > 0">
                                <template x-for="(file, index) in files" :key="index">
                                    <div class="flex items-center gap-3 p-2 bg-gray-50 border border-gray-200 rounded-xl group hover:border-red-200 transition-colors">
                                        <button type="button" @click="removeFile(index)" class="h-8 w-8 shrink-0 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-400 hover:text-red-600 hover:border-red-200 transition-all">
                                            <i class="ph-bold ph-x"></i>
                                        </button>
                                        <div class="flex items-center gap-2 overflow-hidden flex-1 cursor-pointer" @click="previewFile(file)">
                                            <i :class="file.type.includes('pdf') ? 'ph-fill ph-file-pdf text-red-600' : 'ph-fill ph-image text-blue-600'" class="text-lg"></i>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-[10px] font-bold text-gray-700 truncate" x-text="file.name"></p>
                                                <p class="text-[9px] text-gray-400" x-text="(file.size / 1024).toFixed(0) + ' KB'"></p>
                                            </div>
                                            <i class="ph-bold ph-eye text-gray-300 group-hover:text-red-500 transition-colors pr-2"></i>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            @error('dokumen_pendukung.*') <p class="text-[10px] text-red-600 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- BAGIAN YANG DIUBAH: Tombol Submit & Teks Helper --}}
                    <div class="pt-4 border-t border-gray-100 mt-4">
                        <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white font-black py-4 px-6 rounded-xl shadow-lg transition-all active:scale-95 tracking-wide uppercase flex items-center justify-center gap-2">
                            KIRIM PENGAJUAN SEKARANG
                        </button>
                        
                        <p class="text-[11px] text-gray-500 text-center mt-3 flex items-center justify-center gap-1.5 font-medium">
                            <i class="ph-fill ph-info text-blue-500 text-sm"></i> 
                            File surat pengajuan otomatis terunduh setelah diklik. Cetak dan serahkan ke Bendahara.
                        </p>
                    </div>
                </form>
            </div>

            <div class="space-y-6">
                <div class="bg-gray-900 rounded-2xl p-6 text-white shadow-xl">
                    <h3 class="text-sm font-bold opacity-60 uppercase tracking-widest mb-4 text-center">Simulasi Cicilan</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between border-b border-white/10 pb-2">
                            <span class="text-xs opacity-70">Pokok / Bulan</span>
                            <span class="font-bold font-mono" x-text="formatCurrency(pokokPerBulan)"></span>
                        </div>
                        <div class="flex justify-between border-b border-white/10 pb-2">
                            <span class="text-xs opacity-70">Bunga (1%) / Bulan</span>
                            <span class="font-bold text-red-400 font-mono" x-text="formatCurrency(bungaPerBulan)"></span>
                        </div>
                        <div class="pt-2 text-center">
                            <p class="text-[10px] opacity-50 uppercase font-bold mb-1">Total Angsuran Bulanan</p>
                            <p class="text-3xl font-black text-green-400 font-mono" x-text="formatCurrency(totalPerBulan)"></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex gap-3">
                    <i class="ph-fill ph-info text-blue-600 text-xl shrink-0"></i>
                    <p class="text-[10px] text-blue-700 leading-relaxed font-medium">
                        Pengajuan akan diperiksa oleh bendahara. Klik ikon mata pada file untuk meninjau dokumen sebelum dikirim.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fileUploader() {
            return {
                files: [],
                addFiles(e) {
                    const newFiles = Array.from(e.target.files);
                    this.files = [...this.files, ...newFiles];
                    this.syncInput();
                },
                removeFile(index) {
                    this.files.splice(index, 1);
                    this.syncInput();
                },
                syncInput() {
                    const dataTransfer = new DataTransfer();
                    this.files.forEach(file => dataTransfer.items.add(file));
                    document.getElementById('fileInput').files = dataTransfer.files;
                },
                previewFile(file) {
                    const url = URL.createObjectURL(file);
                    window.open(url, '_blank');
                }
            }
        }

        function loanCalculator() {
            return {
                amount: 0,
                formattedAmount: '',
                tenor: 0,
                pokokPerBulan: 0,
                bungaPerBulan: 0,
                totalPerBulan: 0,
                updateAmount() {
                    let val = this.formattedAmount.replace(/[^0-9]/g, '');
                    this.amount = val ? parseInt(val) : 0;
                    this.formattedAmount = this.formatCurrencyRaw(val);
                    this.calculate();
                },
                calculate() {
                    if (this.amount > 0 && this.tenor > 0) {
                        this.pokokPerBulan = this.amount / this.tenor;
                        this.bungaPerBulan = this.amount * 0.01;
                        this.totalPerBulan = this.pokokPerBulan + this.bungaPerBulan;
                    } else {
                        this.pokokPerBulan = 0; this.bungaPerBulan = 0; this.totalPerBulan = 0;
                    }
                },
                formatCurrency(val) {
                    return 'Rp' + new Intl.NumberFormat('id-ID').format(Math.round(val));
                },
                formatCurrencyRaw(val) {
                    if (!val) return '';
                    return new Intl.NumberFormat('id-ID').format(val);
                }
            }
        }
    </script>
</x-app-layout>