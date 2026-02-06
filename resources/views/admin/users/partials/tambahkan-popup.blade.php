<x-popup name="openCreate" title="Registrasi Pegawai Baru">
    <x-slot name="icon">
        <i class="ph-bold ph-user-plus text-xl"></i>
    </x-slot>

    <div class="p-6 bg-white h-full">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">NIP</label>
                        <div class="relative">
                            <i class="ph-bold ph-identification-card absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            <input type="text" name="nip" required placeholder="Masukkan NIP..."
                                   class="w-full pl-12 pr-4 py-3 rounded-xl border-gray-300 text-sm focus:border-red-600 focus:ring-red-600 font-medium shadow-sm transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Password Awal</label>
                        <div class="relative">
                            <i class="ph-bold ph-key absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            <input type="password" name="password" required placeholder="Password..."
                                   class="w-full pl-12 pr-4 py-3 rounded-xl border-gray-300 text-sm focus:border-red-600 focus:ring-red-600 font-medium shadow-sm transition-all">
                        </div>
                    </div>
                </div>
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Unit Kerja</label>
                        <div class="relative">
                            <i class="ph-bold ph-hospital absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            <select name="unit_kerja_id" required class="w-full pl-12 pr-10 py-3 rounded-xl border-gray-300 text-sm focus:border-red-600 focus:ring-red-600 bg-white font-medium shadow-sm appearance-none transition-all">
                                <option value="" disabled selected>Pilih unit kerja...</option>
                                @foreach($unitKerja as $uk)
                                    <option value="{{ $uk->id }}">{{ $uk->nama_unit }}</option>
                                @endforeach
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Role Aplikasi</label>
                        <div class="relative">
                            <i class="ph-bold ph-shield-check absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                            <select name="role" required class="w-full pl-12 pr-10 py-3 rounded-xl border-gray-300 text-sm focus:border-red-600 focus:ring-red-600 bg-white font-medium shadow-sm appearance-none transition-all">
                                <option value="" disabled selected>Pilih peran...</option>
                                <option value="anggota">Anggota</option>
                                <option value="verifikator">Verifikator</option>
                                <option value="admin">Admin IT</option>
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-6 border-t border-gray-100 mt-6">
                <button type="button" @click="openCreate = false" class="px-6 py-3 rounded-xl border border-gray-300 font-semibold text-sm hover:bg-gray-50 transition-all">Batal</button>
                <button type="submit" class="px-8 py-3 rounded-xl bg-red-700 text-white font-semibold text-sm hover:bg-red-800 transition-all active:scale-95">Simpan Data</button>
            </div>
        </form>
    </div>
</x-popup>