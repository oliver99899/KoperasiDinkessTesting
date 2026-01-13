<x-app-layout title="Tambah Pengguna">

    <div class="w-full max-w-4xl mx-auto">

        <div class="flex mb-4">
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center gap-2 text-sm font-bold text-red-800 hover:text-red-700 transition-colors">
                <i class="ph-bold ph-arrow-left"></i>
                Batal
            </a>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                
                {{-- HEADER MERAH --}}
                <div class="bg-red-700 px-6 py-6 border-b border-red-800">
                    <h1 class="text-xl lg:text-2xl font-extrabold text-white">Tambah Pengguna Baru</h1>
                    <p class="text-white/85 mt-1 text-sm">
                        Daftarkan pegawai atau admin baru ke dalam sistem.
                    </p>
                </div>

                {{-- ISI FORM --}}
                <div class="p-6 lg:p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-xl bg-red-50 flex items-center justify-center text-red-700 shrink-0 border border-red-100">
                            <i class="ph-fill ph-user-plus text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-extrabold text-gray-900">Informasi Akun</h3>
                            <p class="text-xs text-gray-500">Data untuk login sistem.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Nama Pegawai <span class="text-red-700">*</span></label>
                            <input type="text" name="nama" required class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm" placeholder="Nama Lengkap">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Email Dinas <span class="text-red-700">*</span></label>
                            <input type="email" name="email" required class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm" placeholder="email@semarangkota.go.id">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Password Awal <span class="text-red-700">*</span></label>
                            <input type="password" name="password" required class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm" placeholder="Minimal 6 karakter">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Role Aplikasi <span class="text-red-700">*</span></label>
                            <select name="role" required class="w-full rounded-xl border-gray-300 py-3 px-4 text-gray-900 shadow-sm focus:border-red-600 focus:ring-red-600 text-sm">
                                <option value="user">Anggota (User)</option>
                                <option value="verifikator">Bendahara (Verifikator)</option>
                                <option value="admin">Administrator IT</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="px-6 lg:px-8 py-5 border-t border-gray-200 bg-gray-50 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-sm font-bold text-white bg-red-700 py-3 px-8 rounded-xl shadow-sm hover:bg-red-800 transition-transform active:scale-[0.98]">
                        <i class="ph-bold ph-plus"></i> BUAT AKUN
                    </button>
                </div>

            </div>
        </form>
    </div>

</x-app-layout>