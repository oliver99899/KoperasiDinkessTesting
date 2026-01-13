<x-app-layout title="Input Simpanan">

    <div class="w-full max-w-4xl mx-auto">

        <div class="flex mb-4">
            <a href="{{ route('verifikator.dashboard') }}" 
               class="inline-flex items-center gap-2 text-sm font-bold text-red-800 hover:text-red-700 transition-colors">
                <i class="ph-bold ph-arrow-left"></i>
                Batal
            </a>
        </div>

        <form action="{{ route('verifikator.simpanan.store') }}" method="POST">
            @csrf

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                
                {{-- HEADER MERAH --}}
                <div class="bg-red-700 px-6 py-6 border-b border-red-800">
                    <h1 class="text-xl font-bold text-white">Input Simpanan</h1>
                </div>

                {{-- ISI FORM --}}
                <div class="p-6 lg:p-8">
                    
                    {{-- Section 1: Data Anggota --}}
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-xl bg-red-50 flex items-center justify-center text-red-700 shrink-0 border border-red-100">
                            <i class="ph-fill ph-user-list text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-gray-900">Data Anggota</h3>
                            <p class="text-xs text-gray-500">Pilih anggota penyetor dana.</p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-800 mb-2">Pilih Anggota <span class="text-red-700">*</span></label>
                        <select name="user_id" required class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                            <option value="">-- Cari Nama Anggota / NIK --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->profile->nama_lengkap }} (NIK: {{ $user->profile->nik }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="h-px bg-gray-100 mb-8"></div>

                    {{-- Section 2: Detail Transaksi --}}
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-xl bg-red-50 flex items-center justify-center text-red-700 shrink-0 border border-red-100">
                            <i class="ph-fill ph-money text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-gray-900">Detail Transaksi</h3>
                            <p class="text-xs text-gray-500">Informasi nominal dan metode pembayaran.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Jenis Simpanan <span class="text-red-700">*</span></label>
                            <select name="jenis_simpanan" required class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                                <option value="wajib">Simpanan Wajib (Bulanan)</option>
                                <option value="pokok">Simpanan Pokok (Awal)</option>
                                <option value="sukarela">Simpanan Sukarela</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Metode Pembayaran <span class="text-red-700">*</span></label>
                            <select name="metode_bayar" required class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                                <option value="potong_gaji">Potong Gaji</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="tunai">Setor Tunai</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Nominal (Rp) <span class="text-red-700">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" name="jumlah" required placeholder="100000"
                                       class="w-full rounded-xl border-gray-300 py-3 pl-10 pr-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Tanggal Transaksi <span class="text-red-700">*</span></label>
                            <input type="date" name="tanggal_bayar" value="{{ date('Y-m-d') }}" required
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Keterangan (Opsional)</label>
                            <textarea name="keterangan" rows="2" placeholder="Catatan tambahan..."
                                      class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm placeholder:text-gray-400"></textarea>
                        </div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="px-6 lg:px-8 py-5 border-t border-gray-200 bg-gray-50 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-sm font-bold text-white bg-red-700 py-3 px-8 rounded-xl shadow-sm hover:bg-red-800 transition-transform active:scale-[0.98]">
                        <i class="ph-bold ph-floppy-disk"></i> SIMPAN
                    </button>
                </div>

            </div>
        </form>
    </div>

</x-app-layout>