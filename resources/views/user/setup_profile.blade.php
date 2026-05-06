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
                        <div x-data="{ val: '{{ old('nama_lengkap', auth()->user()->name) }}', get ok() { return this.val.length >= 3 } }">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Nama Lengkap <span class="text-red-700">*</span></label>
                            <input type="text" name="nama_lengkap" x-model="val" required
                                :class="val.length > 0 ? (ok ? 'border-green-500 focus:border-green-500 focus:ring-green-500' : 'border-red-400 focus:border-red-400 focus:ring-red-400') : 'border-gray-300 focus:border-red-600 focus:ring-red-600'"
                                class="w-full rounded-xl py-3 px-4 text-gray-900 shadow-sm text-sm transition-all">
                            <div class="mt-1.5 flex items-center gap-1.5 text-[11px]" x-show="val.length > 0">
                                <i :class="ok ? 'ph-fill ph-check-circle text-green-600' : 'ph-fill ph-x-circle text-red-500'"></i>
                                <span :class="ok ? 'text-green-600' : 'text-red-500'" x-text="ok ? 'Nama valid' : 'Minimal 3 karakter'"></span>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Sesuai KTP, minimal 3 karakter</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Unit Kerja (Terkunci)</label>
                            <input type="text" value="{{ auth()->user()->profile->unitKerja->nama_unit ?? 'Dinas Kesehatan Kota Semarang' }}" readonly disabled
                                   class="w-full rounded-xl border-gray-200 bg-gray-100 py-3 px-4 text-gray-500 shadow-sm text-sm cursor-not-allowed">
                        </div>

                        <div x-data="{ val: '{{ old('nik') }}', get ok() { return /^[0-9]{16}$/.test(this.val) } }">
                            <label class="block text-sm font-bold text-gray-800 mb-2">NIK <span class="text-red-700">*</span></label>
                            <div class="relative">
                                <input type="text" name="nik" x-model="val" required maxlength="16" inputmode="numeric"
                                    :class="val.length > 0 ? (ok ? 'border-green-500 focus:border-green-500 focus:ring-green-500' : 'border-red-400 focus:border-red-400 focus:ring-red-400') : 'border-gray-300 focus:border-red-600 focus:ring-red-600'"
                                    class="w-full rounded-xl py-3 pl-4 pr-16 text-gray-900 shadow-sm text-sm transition-all">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[11px] font-bold"
                                    :class="val.length === 16 ? 'text-green-600' : 'text-gray-400'"
                                    x-text="val.length + '/16'"></span>
                            </div>
                            <div class="mt-1.5 flex items-center gap-1.5 text-[11px]" x-show="val.length > 0">
                                <i :class="ok ? 'ph-fill ph-check-circle text-green-600' : 'ph-fill ph-x-circle text-red-500'"></i>
                                <span :class="ok ? 'text-green-600' : 'text-red-500'" x-text="ok ? 'NIK valid' : 'Harus tepat 16 digit angka (' + val.length + '/16)'"></span>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">16 digit angka sesuai KTP</p>
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

                       <div x-data="{ val: '{{ old('no_hp') }}', get ok() { return /^[0-9]{10,15}$/.test(this.val) } }">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Nomor WhatsApp <span class="text-red-700">*</span></label>
                            <div class="relative">
                                <input type="text" name="no_hp" x-model="val" required inputmode="numeric"
                                    :class="val.length > 0 ? (ok ? 'border-green-500 focus:border-green-500 focus:ring-green-500' : 'border-red-400 focus:border-red-400 focus:ring-red-400') : 'border-gray-300 focus:border-red-600 focus:ring-red-600'"
                                    class="w-full rounded-xl py-3 px-4 text-gray-900 shadow-sm text-sm transition-all">
                            </div>
                            <div class="mt-1.5 flex items-center gap-1.5 text-[11px]" x-show="val.length > 0">
                                <i :class="ok ? 'ph-fill ph-check-circle text-green-600' : 'ph-fill ph-x-circle text-red-500'"></i>
                                <span :class="ok ? 'text-green-600' : 'text-red-500'" x-text="ok ? 'Nomor valid' : 'Minimal 10 digit angka'"></span>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Minimal 10 digit, contoh: 08123456789</p>
                        </div>
                        
                        <div x-data="{ 
                            val: '{{ old('tanggal_lahir') }}',
                            get ok() {
                                if (!this.val) return false;
                                const today = new Date();
                                const birth = new Date(this.val);
                                const age = today.getFullYear() - birth.getFullYear();
                                const m = today.getMonth() - birth.getMonth();
                                const actualAge = m < 0 || (m === 0 && today.getDate() < birth.getDate()) ? age - 1 : age;
                                return actualAge >= 17;
                            },
                            get ageText() {
                                if (!this.val) return '';
                                const today = new Date();
                                const birth = new Date(this.val);
                                const age = today.getFullYear() - birth.getFullYear();
                                const m = today.getMonth() - birth.getMonth();
                                const actualAge = m < 0 || (m === 0 && today.getDate() < birth.getDate()) ? age - 1 : age;
                                return actualAge + ' tahun';
                            }
                        }">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Tanggal Lahir <span class="text-red-700">*</span></label>
                            <input type="date" name="tanggal_lahir" x-model="val" required
                                :max="new Date(new Date().setFullYear(new Date().getFullYear()-17)).toISOString().split('T')[0]"
                                :class="val ? (ok ? 'border-green-500 focus:border-green-500 focus:ring-green-500' : 'border-red-400 focus:border-red-400 focus:ring-red-400') : 'border-gray-300 focus:border-red-600 focus:ring-red-600'"
                                class="w-full rounded-xl py-3 px-4 text-gray-900 shadow-sm text-sm transition-all">
                            <div class="mt-1.5 flex items-center gap-1.5 text-[11px]" x-show="val">
                                <i :class="ok ? 'ph-fill ph-check-circle text-green-600' : 'ph-fill ph-x-circle text-red-500'"></i>
                                <span :class="ok ? 'text-green-600' : 'text-red-500'" x-text="ok ? 'Usia ' + ageText + ' - valid' : 'Minimal usia 17 tahun'"></span>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Minimal usia 17 tahun</p>
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

                    <div x-data="{ val: '{{ old('email', auth()->user()->email) }}', get ok() { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.val) } }">
                        <label class="block text-sm font-bold text-gray-800 mb-2">Alamat Email <span class="text-red-700">*</span></label>
                        <input type="email" name="email" x-model="val" required
                            :class="val.length > 0 ? (ok ? 'border-green-500 focus:border-green-500 focus:ring-green-500' : 'border-red-400 focus:border-red-400 focus:ring-red-400') : 'border-gray-300 focus:border-red-600 focus:ring-red-600'"
                            class="w-full rounded-xl py-3 px-4 text-gray-900 shadow-sm text-sm transition-all">
                        <div class="mt-1.5 flex items-center gap-1.5 text-[11px]" x-show="val.length > 0">
                            <i :class="ok ? 'ph-fill ph-check-circle text-green-600' : 'ph-fill ph-x-circle text-red-500'"></i>
                            <span :class="ok ? 'text-green-600' : 'text-red-500'" x-text="ok ? 'Format email valid' : 'Format email tidak valid'"></span>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1">Contoh: nama@dinkes.semarangkota.go.id</p>
                    </div>

                        <div x-data="{
                            pwd: '',
                            pwdConfirm: '',
                            show: false,
                            showConfirm: false,
                            get hasMin() { return this.pwd.length >= 8 },
                            get hasUpper() { return /[A-Z]/.test(this.pwd) },
                            get hasLower() { return /[a-z]/.test(this.pwd) },
                            get hasNumber() { return /[0-9]/.test(this.pwd) },
                            get strength() {
                                const score = [this.hasMin, this.hasUpper, this.hasLower, this.hasNumber].filter(Boolean).length;
                                if (score <= 1) return { label: 'Lemah', color: 'bg-red-500', width: '25%', text: 'text-red-600' };
                                if (score === 2) return { label: 'Cukup', color: 'bg-yellow-500', width: '50%', text: 'text-yellow-600' };
                                if (score === 3) return { label: 'Sedang', color: 'bg-blue-500', width: '75%', text: 'text-blue-600' };
                                return { label: 'Kuat', color: 'bg-green-500', width: '100%', text: 'text-green-600' };
                            },
                            get pwdOk() { return this.hasMin && this.hasUpper && this.hasLower && this.hasNumber },
                            get confirmOk() { return this.pwdConfirm.length > 0 && this.pwd === this.pwdConfirm }
                        }">
                            {{-- Password --}}
                            <div class="mb-5">
                                <label class="block text-sm font-bold text-gray-800 mb-2">Password Baru <span class="text-red-700">*</span></label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password" x-model="pwd" required
                                        :class="pwd.length > 0 ? (pwdOk ? 'border-green-500 focus:border-green-500 focus:ring-green-500' : 'border-red-400 focus:border-red-400 focus:ring-red-400') : 'border-gray-300 focus:border-red-600 focus:ring-red-600'"
                                        class="w-full rounded-xl py-3 pl-4 pr-12 text-gray-900 shadow-sm text-sm transition-all"
                                        placeholder="Minimal 8 karakter">
                                    <button type="button" @click="show = !show"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i :class="show ? 'ph-bold ph-eye-slash' : 'ph-bold ph-eye'" class="text-lg"></i>
                                    </button>
                                </div>

                                {{-- Strength bar --}}
                                <div class="mt-2" x-show="pwd.length > 0">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[10px] text-gray-400 uppercase tracking-wider">Kekuatan Password</span>
                                        <span class="text-[11px] font-bold" :class="strength.text" x-text="strength.label"></span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full transition-all duration-300"
                                            :class="strength.color" :style="'width: ' + strength.width"></div>
                                    </div>
                                </div>

                                {{-- Checklist syarat --}}
                                <div class="mt-2 grid grid-cols-2 gap-1" x-show="pwd.length > 0">
                                    <div class="flex items-center gap-1.5 text-[11px]">
                                        <i :class="hasMin ? 'ph-fill ph-check-circle text-green-600' : 'ph-fill ph-x-circle text-red-400'"></i>
                                        <span :class="hasMin ? 'text-green-600' : 'text-red-400'">Minimal 8 karakter</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[11px]">
                                        <i :class="hasUpper ? 'ph-fill ph-check-circle text-green-600' : 'ph-fill ph-x-circle text-red-400'"></i>
                                        <span :class="hasUpper ? 'text-green-600' : 'text-red-400'">Huruf besar (A-Z)</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[11px]">
                                        <i :class="hasLower ? 'ph-fill ph-check-circle text-green-600' : 'ph-fill ph-x-circle text-red-400'"></i>
                                        <span :class="hasLower ? 'text-green-600' : 'text-red-400'">Huruf kecil (a-z)</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[11px]">
                                        <i :class="hasNumber ? 'ph-fill ph-check-circle text-green-600' : 'ph-fill ph-x-circle text-red-400'"></i>
                                        <span :class="hasNumber ? 'text-green-600' : 'text-red-400'">Angka (0-9)</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div>
                                <label class="block text-sm font-bold text-gray-800 mb-2">Konfirmasi Password <span class="text-red-700">*</span></label>
                                <div class="relative">
                                    <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" x-model="pwdConfirm" required
                                        :class="pwdConfirm.length > 0 ? (confirmOk ? 'border-green-500 focus:border-green-500 focus:ring-green-500' : 'border-red-400 focus:border-red-400 focus:ring-red-400') : 'border-gray-300 focus:border-red-600 focus:ring-red-600'"
                                        class="w-full rounded-xl py-3 pl-4 pr-12 text-gray-900 shadow-sm text-sm transition-all"
                                        placeholder="Ulangi password">
                                    <button type="button" @click="showConfirm = !showConfirm"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                        <i :class="showConfirm ? 'ph-bold ph-eye-slash' : 'ph-bold ph-eye'" class="text-lg"></i>
                                    </button>
                                </div>
                                <div class="mt-1.5 flex items-center gap-1.5 text-[11px]" x-show="pwdConfirm.length > 0">
                                    <i :class="confirmOk ? 'ph-fill ph-check-circle text-green-600' : 'ph-fill ph-x-circle text-red-500'"></i>
                                    <span :class="confirmOk ? 'text-green-600' : 'text-red-500'" x-text="confirmOk ? 'Password cocok' : 'Password tidak cocok'"></span>
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

                        <div x-data="{ val: '{{ old('nomor_rekening') }}', get ok() { return /^[0-9]{8,20}$/.test(this.val) } }">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Nomor Rekening <span class="text-red-700">*</span></label>
                            <input type="text" name="nomor_rekening" x-model="val" required inputmode="numeric"
                                :class="val.length > 0 ? (ok ? 'border-green-500 focus:border-green-500 focus:ring-green-500' : 'border-red-400 focus:border-red-400 focus:ring-red-400') : 'border-gray-300 focus:border-red-600 focus:ring-red-600'"
                                class="w-full rounded-xl py-3 px-4 text-gray-900 shadow-sm text-sm transition-all"
                                placeholder="Masukkan nomor rekening">
                            <div class="mt-1.5 flex items-center gap-1.5 text-[11px]" x-show="val.length > 0">
                                <i :class="ok ? 'ph-fill ph-check-circle text-green-600' : 'ph-fill ph-x-circle text-red-500'"></i>
                                <span :class="ok ? 'text-green-600' : 'text-red-500'" x-text="ok ? 'Nomor rekening valid' : 'Minimal 8 digit angka'"></span>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">Hanya angka, minimal 8 digit</p>
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