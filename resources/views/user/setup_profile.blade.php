<x-app-layout title="Aktivasi Akun" :hideTopTitle="true">

    <div class="w-full max-w-4xl mx-auto">

        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex gap-3">
                <div class="shrink-0 text-green-600">
                    <i class="ph-fill ph-check-circle text-2xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-green-900 text-sm">Berhasil!</h4>
                    <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex gap-3">
                <div class="shrink-0 text-red-600">
                    <i class="ph-fill ph-x-circle text-2xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-red-900 text-sm">Gagal Menyimpan!</h4>
                    <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex gap-3">
                <div class="shrink-0 text-red-600">
                    <i class="ph-fill ph-warning-circle text-2xl"></i>
                </div>
                <div>
                    <h4 class="font-bold text-red-900 text-sm">Terdapat Kesalahan Input</h4>
                    <ul class="list-disc list-inside text-sm text-red-700 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="mb-6 lg:mb-8 rounded-2xl overflow-hidden shadow-sm border border-gray-200 bg-white">
            <div class="bg-red-700 px-6 py-6">
                <h1 class="text-xl lg:text-2xl font-extrabold text-white">
                    {{ auth()->user()->role === 'verifikator' ? 'Setup Profil Verifikator' : 'Aktivasi Keanggotaan' }}
                </h1>
                <p class="text-white/85 mt-1 text-sm">
                    Lengkapi data diri Anda untuk mengaktifkan akun.
                </p>
            </div>
        </div>

        <form action="{{ route('profile.store') }}" method="POST">
            @csrf

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

                <div class="p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-xl bg-red-50 flex items-center justify-center text-red-700 shrink-0 border border-red-100">
                            <i class="ph-fill ph-identification-card text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-gray-900">Identitas Diri</h3>
                            <p class="text-xs text-gray-500">Data pribadi sesuai KTP.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Nama Lengkap <span class="text-red-700">*</span></label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required autocomplete="name"
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm placeholder:text-gray-400"
                                   placeholder="Nama lengkap sesuai KTP">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">NIK <span class="text-red-700">*</span></label>
                            <input type="text" inputmode="numeric" name="nik" value="{{ old('nik') }}" required minlength="16" maxlength="16"
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm placeholder:text-gray-400"
                                   placeholder="16 digit NIK KTP">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Jenis Kelamin <span class="text-red-700">*</span></label>
                            <select name="jenis_kelamin" required
                                    class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                                <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Unit Kerja (Bidang) <span class="text-red-700">*</span></label>
                            <select name="unit_kerja" required
                                    class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                                <option value="" disabled {{ old('unit_kerja') ? '' : 'selected' }}>Pilih Unit Kerja</option>
                                <option value="Kesehatan Masyarakat" {{ old('unit_kerja') == 'Kesehatan Masyarakat' ? 'selected' : '' }}>Kesehatan Masyarakat</option>
                                <option value="Sumber Daya Kesehatan" {{ old('unit_kerja') == 'Sumber Daya Kesehatan' ? 'selected' : '' }}>Sumber Daya Kesehatan</option>
                                <option value="Pencegahan dan Pengendalian Penyakit" {{ old('unit_kerja') == 'Pencegahan dan Pengendalian Penyakit' ? 'selected' : '' }}>Pencegahan dan Pengendalian Penyakit</option>
                                <option value="Sumber Pelayanan Kesehatan" {{ old('unit_kerja') == 'Sumber Pelayanan Kesehatan' ? 'selected' : '' }}>Sumber Pelayanan Kesehatan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Nomor HP (WhatsApp) <span class="text-red-700">*</span></label>
                            <input type="tel" inputmode="numeric" name="no_hp" value="{{ old('no_hp') }}" required
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm placeholder:text-gray-400"
                                   placeholder="08xxxxxxxxxx">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Alamat Domisili <span class="text-red-700">*</span></label>
                            <textarea name="alamat" rows="2" required
                                      class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm placeholder:text-gray-400"
                                      placeholder="Nama Jalan, RT/RW, Kelurahan, Kecamatan">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->status_akun === 'new')
                <div class="h-px bg-gray-200"></div>

                <div class="p-6 lg:p-8 bg-white-50/50">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-xl bg-red-100 flex items-center justify-center text-red-700 shrink-0 border border-red-200">
                            <i class="ph-fill ph-lock-key text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-gray-900">Keamanan Akun</h3>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Password Baru <span class="text-red-700">*</span></label>
                            <input type="password" name="password" required
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm placeholder:text-gray-400""
                                   placeholder="Minimal 8 karakter">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Konfirmasi Password <span class="text-red-700">*</span></label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm placeholder:text-gray-400""
                                   placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>
                @endif

                {{-- HANYA TAMPIL JIKA ROLE ADALAH USER (ANGGOTA) --}}
                @if(auth()->user()->role === 'user')
                    <div class="h-px bg-gray-200"></div>

                    <div class="p-6 lg:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="h-10 w-10 rounded-xl bg-red-50 flex items-center justify-center text-red-700 shrink-0 border border-red-100">
                                <i class="ph-fill ph-bank text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-extrabold text-gray-900">Data Rekening</h3>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-800 mb-2">Nama Bank <span class="text-red-700">*</span></label>
                                <select name="nama_bank" required
                                        class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                                    <option value="BPD Jateng" {{ old('nama_bank') == 'BPD Jateng' ? 'selected' : '' }}>Bank Jateng</option>
                                    <option value="BRI" {{ old('nama_bank') == 'BRI' ? 'selected' : '' }}>BRI</option>
                                    <option value="BNI" {{ old('nama_bank') == 'BNI' ? 'selected' : '' }}>BNI</option>
                                    <option value="Mandiri" {{ old('nama_bank') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                    <option value="BCA" {{ old('nama_bank') == 'BCA' ? 'selected' : '' }}>BCA</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-800 mb-2">Nomor Rekening <span class="text-red-700">*</span></label>
                                <input type="text" inputmode="numeric" name="nomor_rekening" value="{{ old('nomor_rekening') }}" required
                                       class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm placeholder:text-gray-400"
                                       placeholder="Nomor rekening valid">
                            </div>
                        </div>
                    </div>
                @endif

                <div class="px-6 lg:px-8 py-5 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-gray-500 italic">
                        * Pastikan data yang diisi benar.
                    </div>
                    <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-sm font-bold text-white bg-red-700 py-3 px-8 rounded-xl shadow-lg shadow-red-700/20 hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 transition-all active:scale-[0.98]">
                        <i class="ph-bold ph-check-circle"></i>
                        SIMPAN & AKTIFKAN
                    </button>
                </div>

            </div>
        </form>
    </div>

</x-app-layout>