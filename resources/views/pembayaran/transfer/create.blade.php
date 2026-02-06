<x-app-layout title="Konfirmasi Pembayaran">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Kirim Bukti Transfer</h1>
            <a href="{{ route('pinjaman.index') }}" class="text-sm font-bold text-gray-500 hover:text-red-700 transition-colors">Batal</a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-200 overflow-hidden">
            {{-- Header Nominal --}}
            <div class="bg-red-700 p-6 text-white">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-md">
                        <i class="ph-bold ph-receipt text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold opacity-70 uppercase tracking-widest">Total Tagihan Angsuran</p>
                        <h2 class="text-2xl font-black">Rp {{ number_format($nominal, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>

            {{-- INFORMASI REKENING TUJUAN --}}
            <div class="px-8 py-6 bg-gradient-to-br from-red-50 to-indigo-50 border-b border-red-100">
                <div class="flex items-center gap-2 mb-4">
                    <span class="flex h-2 w-2 rounded-full bg-red-600 animate-pulse"></span>
                    <p class="text-[11px] font-black text-red-900 uppercase tracking-widest">Rekening Tujuan Transfer</p>
                </div>
                
                <div class="bg-white rounded-2xl p-5 border border-red-200 shadow-sm flex items-center justify-between group hover:border-red-400 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 rounded-xl bg-red-600 flex flex-col items-center justify-center text-white shrink-0 shadow-inner">
                            <span class="text-[8px] font-bold leading-none uppercase">BANK</span>
                            <span class="text-[11px] font-black leading-none">JATENG</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Nomor Rekening Bendahara</p>
                            <div class="flex items-center gap-2">
                                <p id="no_rekening" class="text-xl font-black text-red-950 tracking-wider">502301000456539</p>
                            </div>
                            <p class="text-[11px] font-bold text-red-700 uppercase mt-0.5">KOPERASI DINKES SEMARANG</p>
                        </div>
                    </div>
                    
                    {{-- Tombol Copy --}}
                    <button type="button" 
                            onclick="copyAccount()" 
                            class="h-11 w-11 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white active:scale-90 transition-all shadow-sm"
                            title="Salin Nomor Rekening">
                        <i id="copy-icon" class="ph-bold ph-copy text-xl"></i>
                    </button>
                </div>
            </div>

            <form action="{{ route('pembayaran.transfer.store', [$pinjaman->id, $angsuran_ke]) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Angsuran Ke</label>
                        <div class="px-4 py-3 bg-gray-50 rounded-xl border border-gray-200 font-bold text-gray-700 italic">
                            Bulan ke-{{ $angsuran_ke }}
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Tanggal Transfer</label>
                        <input type="date" name="tanggal_transfer" required value="{{ date('Y-m-d') }}"
                               class="w-full rounded-xl border-gray-300 py-3 px-4 font-bold focus:border-red-600 focus:ring-red-600">
                    </div>
                </div>

                <div x-data="{ fileName: '', filePreview: null }">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Unggah Bukti Transaksi</label>
                    <div class="relative group">
                        <input type="file" name="bukti" required accept=".jpg,.jpeg,.png,.pdf" 
                               @change="
                                fileName = $event.target.files[0].name;
                                if ($event.target.files[0].type.includes('image')) {
                                    const reader = new FileReader();
                                    reader.onload = (e) => { filePreview = e.target.result; };
                                    reader.readAsDataURL($event.target.files[0]);
                                } else {
                                    filePreview = null;
                                }
                               "
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        
                        <div :class="filePreview ? 'h-48' : 'h-32'" class="border-2 border-dashed border-gray-300 rounded-2xl flex flex-col items-center justify-center group-hover:border-red-500 group-hover:bg-red-50 transition-all overflow-hidden">
                            <template x-if="!filePreview">
                                <div class="text-center">
                                    <i class="ph-bold ph-cloud-arrow-up text-3xl text-gray-400 group-hover:text-red-600 mb-2"></i>
                                    <p class="text-sm font-bold text-gray-500" x-text="fileName ? fileName : 'Pilih file slip transfer'"></p>
                                </div>
                            </template>
                            <template x-if="filePreview">
                                <img :src="filePreview" class="h-full w-full object-contain p-2">
                            </template>
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2 italic">*Format: JPG, PNG, PDF (Maks. 5MB)</p>
                    @error('bukti') <p class="text-xs text-red-600 mt-2 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white font-black py-4 rounded-2xl shadow-lg shadow-red-700/20 transition-all transform active:scale-[0.98] flex items-center justify-center gap-3">
                        <i class="ph-bold ph-paper-plane-tilt text-xl"></i>
                        KIRIM KONFIRMASI
                    </button>
                    <div class="flex items-start gap-3 mt-6 p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                        <i class="ph-fill ph-warning-circle text-yellow-600 text-lg shrink-0"></i>
                        <p class="text-[10px] text-yellow-800 leading-relaxed font-medium uppercase tracking-tighter">
                            Peringatan: Pastikan Anda melakukan transfer terlebih dahulu ke rekening di atas sebelum mengirim konfirmasi ini agar proses verifikasi berjalan lancar.
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function copyAccount() {
            const accountNumber = document.getElementById('no_rekening').innerText;
            navigator.clipboard.writeText(accountNumber).then(() => {
                const icon = document.getElementById('copy-icon');
                icon.classList.remove('ph-copy');
                icon.classList.add('ph-check-circle', 'text-green-500');
                
                // Alert mini atau toast bisa ditambahkan di sini
                
                setTimeout(() => {
                    icon.classList.remove('ph-check-circle', 'text-green-500');
                    icon.classList.add('ph-copy');
                }, 2000);
            });
        }
    </script>
</x-app-layout>