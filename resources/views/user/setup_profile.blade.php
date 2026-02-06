<x-app-layout title="Aktivasi Akun">
    <div class="w-full max-w-4xl mx-auto">
        <div class="mb-6 lg:mb-8 rounded-2xl overflow-hidden shadow-sm border border-gray-200 bg-white text-left">
            <div class="bg-red-700 px-6 py-6">
                <h1 class="text-xl lg:text-2xl font-extrabold text-white text-left">
                    Aktivasi Keanggotaan
                </h1>
                <p class="text-white/85 mt-1 text-sm text-left">
                    Lengkapi data diri Anda untuk mengaktifkan fitur simpan pinjam di Koperasi.
                </p>
            </div>
        </div>

        <form action="{{ route('profile.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 lg:p-8">
                    
                    <div class="flex flex-col items-center justify-center mb-10 pb-8 border-b border-gray-100">
                        <div x-data="{photoName: null, photoPreview: null}" class="text-center">
                            <input type="file" name="foto_profil" class="hidden" x-ref="photo"
                                   accept="image/png, image/jpeg, image/jpg"
                                   x-on:change="
                                        if($event.target.files[0].size > 5 * 1024 * 1024) {
                                            alert('Ukuran file maksimal 5MB!');
                                            $event.target.value = '';
                                            return;
                                        }
                                        photoName = $event.target.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($event.target.files[0]);
                                   ">
                            
                            <div class="relative inline-block">
                                <div class="h-32 w-32 rounded-full bg-gray-50 flex items-center justify-center border-4 border-white shadow-md overflow-hidden" x-show="! photoPreview">
                                    <i class="ph-duotone ph-user-circle text-7xl text-gray-300"></i>
                                </div>
                                <div class="h-32 w-32 rounded-full border-4 border-white shadow-md overflow-hidden" x-show="photoPreview" style="display: none;">
                                    <span class="block w-full h-full bg-cover bg-no-repeat bg-center"
                                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4 flex flex-col items-center">
                                <button type="button" 
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 rounded-xl font-bold text-[10px] text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition active:scale-95" 
                                        x-on:click.prevent="$refs.photo.click()">
                                    <i class="ph-bold ph-camera"></i>
                                    Pilih Foto Profil
                                </button>
                                <p class="text-[9px] text-gray-400 mt-2 font-medium italic uppercase tracking-tighter">
                                    Maksimal 5MB (JPG, JPEG, PNG)
                                </p>
                            </div>
                            @error('foto_profil') <p class="text-red-600 text-[10px] mt-2 font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mb-8">
                        <div class="h-10 w-10 rounded-xl bg-red-50 flex items-center justify-center text-red-700 shrink-0 border border-red-100">
                            <i class="ph-fill ph-identification-card text-xl"></i>
                        </div>
                        <div class="text-left">
                            <h3 class="text-lg font-extrabold text-gray-900">Identitas Diri</h3>
                            <p class="text-xs text-gray-500">Data pribadi wajib sesuai Kartu Tanda Penduduk.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Nama Lengkap <span class="text-red-700">*</span></label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', auth()->user()->name) }}" required
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Unit Kerja (Terkunci)</label>
                            <input type="text" value="{{ auth()->user()->profile->unitKerja->nama_unit ?? 'Dinas Kesehatan Kota Semarang' }}" readonly disabled
                                   class="w-full rounded-xl border-gray-200 bg-gray-100 py-3 px-4 text-gray-500 shadow-sm text-sm cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">NIK <span class="text-red-700">*</span></label>
                            <input type="text" inputmode="numeric" name="nik" value="{{ old('nik') }}" required minlength="16" maxlength="16"
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Jenis Kelamin <span class="text-red-700">*</span></label>
                            <select name="jenis_kelamin" required
                                    class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                                <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Nomor WhatsApp <span class="text-red-700">*</span></label>
                            <input type="tel" name="no_hp" value="{{ old('no_hp') }}" required
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Tanggal Lahir <span class="text-red-700">*</span></label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Alamat Domisili <span class="text-red-700">*</span></label>
                            <textarea name="alamat" rows="2" required
                                      class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm"
                                      placeholder="Contoh: Jl. Ahmad Yani No. 1, Semarang">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="h-px bg-gray-100 mx-8"></div>

                <div class="p-6 lg:p-8 bg-gray-50/50">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="h-10 w-10 rounded-xl bg-red-100 flex items-center justify-center text-red-700 shrink-0 border border-red-100">
                            <i class="ph-fill ph-lock-key text-xl"></i>
                        </div>
                        <div class="text-left">
                            <h3 class="text-lg font-extrabold text-gray-900">Keamanan Akun</h3>
                            <p class="text-xs text-gray-500">Konfirmasi email dan buat password untuk login selanjutnya.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Alamat Email <span class="text-red-700">*</span></label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Password Baru <span class="text-red-700">*</span></label>
                            <input type="password" name="password" required
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm"
                                   placeholder="Minimal 8 karakter">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Konfirmasi Password <span class="text-red-700">*</span></label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm"
                                   placeholder="Ulangi password">
                        </div>
                    </div>
                </div>

                <div class="h-px bg-gray-100 mx-8"></div>

                <div class="p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="h-10 w-10 rounded-xl bg-red-50 flex items-center justify-center text-red-700 shrink-0 border border-red-100">
                            <i class="ph-fill ph-bank text-xl"></i>
                        </div>
                        <div class="text-left">
                            <h3 class="text-lg font-extrabold text-gray-900">Data Perbankan</h3>
                            <p class="text-xs text-gray-500">Rekening ini digunakan untuk pencairan dana simpan pinjam.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Nama Bank <span class="text-red-700">*</span></label>
                            <select name="nama_bank" required
                                    class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                                <option value="Bank Jateng" {{ old('nama_bank') == 'Bank Jateng' ? 'selected' : '' }}>Bank Jateng</option>
                                <option value="BRI" {{ old('nama_bank') == 'BRI' ? 'selected' : '' }}>BRI</option>
                                <option value="BNI" {{ old('nama_bank') == 'BNI' ? 'selected' : '' }}>BNI</option>
                                <option value="Mandiri" {{ old('nama_bank') == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                <option value="BCA" {{ old('nama_bank') == 'BCA' ? 'selected' : '' }}>BCA</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Nomor Rekening <span class="text-red-700">*</span></label>
                            <input type="text" inputmode="numeric" name="nomor_rekening" value="{{ old('nomor_rekening') }}" required
                                   class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm"
                                   placeholder="Masukkan nomor rekening">
                        </div>
                    </div>
                </div>

                <div class="px-6 lg:px-8 py-6 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">* Pastikan data sudah valid</p>
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 text-sm font-bold text-white bg-red-700 py-3.5 px-10 rounded-2xl shadow-lg shadow-red-700/20 hover:bg-red-800 transition-all active:scale-[0.98]">
                        <i class="ph-bold ph-rocket-launch text-lg"></i>
                        AKTIFKAN SEKARANG
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>