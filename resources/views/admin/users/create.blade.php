<x-app-layout title="Undang Pengguna Baru">

    <div class="w-full max-w-3xl mx-auto">

        <div class="flex mb-4">
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center gap-2 text-sm font-bold text-gray-600 hover:text-red-700 transition-colors">
                <i class="ph-bold ph-arrow-left"></i>
                Kembali ke Daftar Pengguna
            </a>
        </div>

        @if (session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex gap-3 shadow-sm animate-fade-in-down">
            <div class="shrink-0 text-green-600">
                <i class="ph-fill ph-check-circle text-2xl"></i>
            </div>
            <div>
                <h4 class="font-bold text-green-900 text-sm">Berhasil Terkirim!</h4>
                <p class="text-sm text-green-700 mt-1 leading-relaxed">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if (session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex gap-3 shadow-sm animate-fade-in-down">
            <div class="shrink-0 text-red-600">
                <i class="ph-fill ph-warning-circle text-2xl"></i>
            </div>
            <div>
                <h4 class="font-bold text-red-900 text-sm">Gagal Mengirim Email</h4>
                <p class="text-sm text-red-700 mt-1 leading-relaxed">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                
                <div class="bg-red-700 px-6 py-6 border-b border-red-800">
                    <h1 class="text-xl font-extrabold text-white">Undang Pengguna Baru</h1>
                    <p class="text-white/85 mt-1 text-sm">
                        Kirim undangan aktivasi akun ke email pegawai.
                    </p>
                </div>

                <div class="p-6 lg:p-8">

                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-8 flex gap-4">
                        <div class="shrink-0 text-blue-600">
                            <i class="ph-fill ph-info text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-blue-900 text-sm">Sistem Undangan Otomatis</h4>
                            <p class="text-xs text-blue-700 mt-1 leading-relaxed">
                                Anda tidak perlu membuat password atau mengisi biodata. Sistem akan mengirimkan link aktivasi ke email yang didaftarkan. Pengguna akan melengkapi data diri (NIK, Nama, Password) secara mandiri saat aktivasi.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Alamat Email Dinas <span class="text-red-700">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                                    <i class="ph-bold ph-envelope"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" required 
                                    class="w-full pl-10 rounded-xl border-gray-300 py-3 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 sm:text-sm" 
                                    placeholder="contoh: pegawai@semarangkota.go.id">
                            </div>
                            @error('email')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Peran Aplikasi <span class="text-red-700">*</span></label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                                    <i class="ph-bold ph-shield-check"></i>
                                </span>
                                <select name="role" required class="w-full pl-10 rounded-xl border-gray-300 py-3 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 sm:text-sm">
                                    <option value="" disabled selected>Pilih Peran...</option>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Anggota (Peminjam)</option>
                                    <option value="verifikator" {{ old('role') == 'verifikator' ? 'selected' : '' }}>Verifikator (Bendahara)</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator IT</option>
                                </select>
                            </div>
                            @error('role')
                                <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="px-6 lg:px-8 py-5 border-t border-gray-200 bg-gray-50 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-sm font-bold text-white bg-red-700 py-3 px-6 rounded-xl shadow-sm hover:bg-red-800 focus:ring-2 focus:ring-offset-2 focus:ring-red-700 transition-all">
                        <i class="ph-bold ph-paper-plane-right"></i>
                        Kirim Undangan Aktivasi
                    </button>
                </div>

            </div>
        </form>
    </div>

</x-app-layout>