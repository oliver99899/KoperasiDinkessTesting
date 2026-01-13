<x-app-layout title="Edit Pengguna">

    <div class="w-full max-w-4xl mx-auto">

        <div class="flex mb-4">
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center gap-2 text-sm font-bold text-red-800 hover:text-red-700 transition-colors">
                <i class="ph-bold ph-arrow-left"></i>
                Batal
            </a>
        </div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                
                {{-- HEADER MERAH --}}
                <div class="bg-red-700 px-6 py-6 border-b border-red-800">
                    <h1 class="text-xl lg:text-2xl font-extrabold text-white">Edit Data Pengguna</h1>
                    <p class="text-white/85 mt-1 text-sm">
                        Perbarui data login dan biodata lengkap anggota.
                    </p>
                </div>

                {{-- ISI FORM --}}
                
                {{-- Data Login --}}
                <div class="p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-xl bg-red-50 flex items-center justify-center text-red-700 shrink-0 border border-red-100">
                            <i class="ph-fill ph-lock-key text-xl"></i>
                        </div>
                        <h3 class="text-lg font-extrabold text-gray-900">Akses Login</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Email</label>
                            <input type="email" name="email" value="{{ $user->email }}" required class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Role</label>
                            <select name="role" required class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Anggota</option>
                                <option value="verifikator" {{ $user->role == 'verifikator' ? 'selected' : '' }}>Bendahara</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Reset Password (Opsional)</label>
                            <input type="password" name="password" placeholder="Isi hanya jika ingin mengganti password" class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                        </div>
                    </div>
                </div>

                <div class="h-px bg-gray-100"></div>

                {{-- Biodata --}}
                <div class="p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-xl bg-red-50 flex items-center justify-center text-red-700 shrink-0 border border-red-100">
                            <i class="ph-fill ph-identification-card text-xl"></i>
                        </div>
                        <h3 class="text-lg font-extrabold text-gray-900">Biodata Lengkap</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ $user->profile->nama_lengkap }}" required class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">NIK</label>
                            <input type="text" name="nik" value="{{ $user->profile->nik }}" required class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Jenis Kelamin</label>
                            <select name="jenis_kelamin" required class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                                <option value="L" {{ $user->profile->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $user->profile->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Unit Kerja</label>
                            <input type="text" name="unit_kerja" value="{{ $user->profile->unit_kerja }}" required class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">No HP</label>
                            <input type="text" name="no_hp" value="{{ $user->profile->no_hp }}" required class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Alamat</label>
                            <textarea name="alamat" rows="2" required class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">{{ $user->profile->alamat }}</textarea>
                        </div>
                    </div>
                </div>

                 <div class="h-px bg-gray-100"></div>

                 {{-- Bank --}}
                 <div class="p-6 lg:p-8">
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">
                         <div>
                             <label class="block text-sm font-bold text-gray-800 mb-2">Nama Bank</label>
                             <input type="text" name="nama_bank" value="{{ $user->profile->nama_bank }}" class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                         </div>
                         <div>
                             <label class="block text-sm font-bold text-gray-800 mb-2">No Rekening</label>
                             <input type="text" name="no_rekening" value="{{ $user->profile->nomor_rekening }}" class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                         </div>
                     </div>
                 </div>

                {{-- FOOTER --}}
                <div class="px-6 lg:px-8 py-5 border-t border-gray-200 bg-gray-50 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-sm font-bold text-white bg-red-700 py-3 px-8 rounded-xl shadow-sm hover:bg-red-800 transition-transform active:scale-[0.98]">
                        <i class="ph-bold ph-floppy-disk"></i> SIMPAN PERUBAHAN
                    </button>
                </div>
            </div>
        </form>
    </div>

</x-app-layout>