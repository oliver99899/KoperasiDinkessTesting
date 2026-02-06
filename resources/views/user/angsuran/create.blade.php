<x-app-layout title="Konfirmasi Angsuran">

    <div class="max-w-xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('angsuran.index') }}" class="text-sm font-semibold text-gray-500 hover:text-red-700 flex items-center gap-2 transition-colors">
                <i class="ph-bold ph-arrow-left"></i> Kembali ke Riwayat
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-red-700 px-6 py-5 border-b border-red-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-white/10 rounded-lg flex items-center justify-center text-white">
                        <i class="ph-bold ph-currency-circle-dollar text-xl"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-white">Konfirmasi Pembayaran</h2>
                </div>
                <div class="text-white/60">
                    <i class="ph-fill ph-shield-check text-2xl"></i>
                </div>
            </div>

            <div class="p-6 bg-red-50 border-b border-red-100">
                <div class="flex justify-between items-center text-sm mb-1">
                    <span class="text-gray-600 font-medium">Sisa Tagihan Saat Ini:</span>
                    <span class="font-semibold text-gray-900 text-lg">Rp {{ number_format($pinjaman->sisa_pinjaman, 0, ',', '.') }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    @php
                        $total = $pinjaman->total_pinjaman > 0 ? $pinjaman->total_pinjaman : 1;
                        $persen = 100 - (($pinjaman->sisa_pinjaman / $total) * 100);
                        $persen = max(0, min(100, $persen));
                    @endphp
                    <div class="bg-red-600 h-2 rounded-full transition-all duration-700 shadow-[0_0_8px_rgba(220,38,38,0.4)]" style="width: {{ $persen }}%"></div>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-[10px] text-gray-500 font-semibold uppercase tracking-wider">Informasi Saldo Terenkripsi</span>
                    <span class="text-xs text-red-600 font-semibold">Progress Pelunasan: {{ round($persen) }}%</span>
                </div>
            </div>

            <form action="{{ route('angsuran.store', $pinjaman->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Pembayaran</label>
                    <div class="relative group">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-semibold pointer-events-none group-focus-within:text-red-600 transition-colors">Rp</span>
                        <input type="number" 
                               name="jumlah_bayar" 
                               id="jumlah_bayar"
                               required 
                               min="1000" 
                               max="{{ $pinjaman->sisa_pinjaman }}"
                               placeholder="0"
                               class="w-full rounded-xl border-gray-300 py-3.5 pl-12 pr-4 text-gray-900 font-semibold text-lg shadow-sm focus:border-red-600 focus:ring-red-600 transition-all">
                    </div>
                    <div class="flex justify-between mt-2 px-1">
                        <p class="text-[11px] text-gray-500 font-medium">*Input nominal sesuai bukti transfer</p>
                        <button type="button" 
                                onclick="document.getElementById('jumlah_bayar').value = {{ $pinjaman->sisa_pinjaman }}"
                                class="text-[11px] text-red-700 font-semibold hover:underline">Bayar Lunas</button>
                    </div>
                    @error('jumlah_bayar')
                        <p class="text-xs text-red-600 mt-2 font-semibold flex items-center gap-1">
                            <i class="ph-bold ph-warning-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Transaksi</label>
                        <div class="relative">
                            <i class="ph-bold ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="date" name="tanggal_potong" value="{{ date('Y-m-d') }}" required
                                   class="w-full rounded-xl border-gray-300 py-2.5 pl-11 pr-4 text-sm font-medium text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 transition-all">
                        </div>
                        @error('tanggal_potong')
                            <p class="text-xs text-red-600 mt-2 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Metode Transfer</label>
                        <div class="relative">
                            <i class="ph-bold ph-bank absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <select name="metode_bayar" class="w-full rounded-xl border-gray-300 py-2.5 pl-11 pr-10 text-sm font-medium text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 appearance-none bg-white">
                                <option value="transfer">Transfer Bank</option>
                                <option value="potong_gaji">Potong Gaji</option>
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Tambahan (Opsional)</label>
                    <textarea name="keterangan" rows="2" placeholder="Contoh: Pembayaran angsuran ke-5..."
                              class="w-full rounded-xl border-gray-300 py-3 px-4 text-sm font-medium text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 transition-all"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Unggah Bukti Transaksi</label>
                    <div class="relative group border-2 border-dashed border-gray-200 rounded-xl p-4 hover:border-red-300 transition-colors bg-gray-50">
                        <input type="file" name="bukti_bayar" required accept="image/*"
                               class="absolute inset-0 opacity-0 cursor-pointer z-10">
                        <div class="flex flex-col items-center justify-center py-2">
                            <i class="ph-bold ph-cloud-arrow-up text-3xl text-gray-400 group-hover:text-red-600 transition-colors"></i>
                            <p class="text-xs font-semibold text-gray-500 mt-2">Klik atau drop gambar di sini</p>
                            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-tighter">JPG, PNG (Max 5MB)</p>
                        </div>
                    </div>
                    @error('bukti_bayar')
                        <p class="text-xs text-red-600 mt-2 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white font-semibold py-4 rounded-xl shadow-lg shadow-red-700/30 transition-all transform active:scale-[0.97] flex items-center justify-center gap-3">
                        <i class="ph-bold ph-shield-check text-xl"></i>
                        Kirim Konfirmasi Aman
                    </button>
                    <p class="text-[10px] text-center text-gray-400 mt-4 leading-relaxed uppercase tracking-widest font-medium">
                        Setiap transaksi akan diverifikasi oleh bendahara dan dicatat dalam audit trail sistem
                    </p>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>