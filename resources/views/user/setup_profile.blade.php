<x-app-layout title="Aktivasi Keanggotaan – Koperasi DKK Semarang" :hideTopTitle="true">

    {{-- ✅ Kartu dan banner dibuat 1 kolom center --}}
    <div class="w-full max-w-4xl mx-auto">

        {{-- Banner Aktivasi --}}
        <div class="mb-6 lg:mb-8 rounded-2xl overflow-hidden shadow-sm border border-gray-200 bg-white">
            <div class="bg-red-700 px-6 py-6">
                <h1 class="text-xl lg:text-2xl font-extrabold text-white">Aktivasi Keanggotaan</h1>
                <p class="text-white/85 mt-1 text-sm">
                    Lengkapi biodata untuk mengaktifkan fitur simpanan & pinjaman.
                </p>
            </div>
            <div class="px-6 py-4 text-sm text-gray-600">
                Pastikan data sesuai dokumen resmi. Data akan diverifikasi oleh Admin.
            </div>
        </div>

        <form action="{{ route('profile.store') }}" method="POST">
            @csrf

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

                {{-- IDENTITAS --}}
                <div class="p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-xl bg-red-50 flex items-center justify-center text-red-700 shrink-0 border border-red-100">
                            <i class="ph-fill ph-identification-card text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-gray-900">Identitas Pegawai</h3>
                            <p class="text-xs text-gray-500">Data sesuai dokumen resmi (KTP).</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Nama Lengkap <span class="text-red-700">*</span></label>
                            <input type="text" name="nama_lengkap" required
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm
                                          focus:border-red-600 focus:ring-red-600 text-sm placeholder:text-gray-400"
                                   placeholder="Sesuai KTP">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">NIK <span class="text-red-700">*</span></label>
                            <input type="text" inputmode="numeric" name="nik" required
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm
                                          focus:border-red-600 focus:ring-red-600 text-sm placeholder:text-gray-400"
                                   placeholder="16 digit NIK">
                            <p class="mt-2 text-xs text-gray-500">Contoh: 3374xxxxxxxxxxxx (tanpa spasi/tanda baca).</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Jenis Kelamin <span class="text-red-700">*</span></label>
                            <select name="jenis_kelamin" required
                                    class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm
                                           focus:border-red-600 focus:ring-red-600 text-sm">
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Unit Kerja <span class="text-red-700">*</span></label>
                            <input type="text" name="unit_kerja" required
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm
                                          focus:border-red-600 focus:ring-red-600 text-sm placeholder:text-gray-400"
                                   placeholder="Bidang / Bagian">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Nomor HP (WhatsApp) <span class="text-red-700">*</span></label>
                            <input type="text" inputmode="numeric" name="no_hp" required
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm
                                          focus:border-red-600 focus:ring-red-600 text-sm placeholder:text-gray-400"
                                   placeholder="08xxxxxxxxxx">
                            <p class="mt-2 text-xs text-gray-500">Gunakan format 08xxxxxxxxxx.</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Alamat Domisili <span class="text-red-700">*</span></label>
                            <textarea name="alamat" rows="2" required
                                      class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm
                                             focus:border-red-600 focus:ring-red-600 text-sm placeholder:text-gray-400"
                                      placeholder="Jalan, RT/RW, Kelurahan, Kecamatan..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- ✅ PEMISAH GARIS MERAH (bukan ganti background) --}}
                <div class="h-px bg-gray-300/30"></div>

                {{-- REKENING --}}
                <div class="p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-xl bg-red-50 flex items-center justify-center text-red-700 shrink-0 border border-red-100">
                            <i class="ph-fill ph-bank text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-gray-900">Data Rekening</h3>
                            <p class="text-xs text-gray-500">Untuk pencatatan simpanan/pinjaman.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Nama Bank <span class="text-red-700">*</span></label>
                            <select name="nama_bank" required
                                    class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm
                                           focus:border-red-600 focus:ring-red-600 text-sm">
                                <option value="BPD Jateng">Bank Jateng</option>
                                <option value="BRI">BRI</option>
                                <option value="BNI">BNI</option>
                                <option value="Mandiri">Mandiri</option>
                                <option value="BCA">BCA</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Nomor Rekening <span class="text-red-700">*</span></label>
                            <input type="text" inputmode="numeric" name="nomor_rekening" required
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm
                                          focus:border-red-600 focus:ring-red-600 text-sm placeholder:text-gray-400"
                                   placeholder="Contoh: 1234567890">
                        </div>
                    </div>
                </div>

                {{-- FOOTER FORM --}}
                <div class="px-6 lg:px-8 py-5 border-t border-gray-200 bg-white flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-gray-500 italic">
                        * Data akan diverifikasi oleh Admin.
                    </div>
                    <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2
                                   text-sm font-bold text-white bg-red-700 py-3 px-8 rounded-xl shadow-sm
                                   hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600
                                   transition-transform active:scale-[0.98]">
                        <i class="ph-bold ph-floppy-disk"></i>
                        SIMPAN
                    </button>
                </div>

            </div>
        </form>
    </div>

</x-app-layout>
