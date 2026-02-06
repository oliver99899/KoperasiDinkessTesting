<x-popup name="openEdit" title="Edit Data Pengguna">
    <x-slot name="icon">
        <i class="ph-bold ph-pencil-simple text-xl"></i>
    </x-slot>

    <div class="p-6 bg-white h-full">
        <div x-data="{ activeTab: 'biodata' }">
            <div x-show="isLoading" class="py-12 text-center text-gray-500">
                <i class="ph-bold ph-spinner animate-spin text-3xl mb-3 text-red-600"></i>
                <p class="text-sm font-semibold animate-pulse">Sedang memproses...</p>
            </div>

            <form x-show="!isLoading" :action="editForm.action" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="flex border-b border-gray-200 gap-6">
                    <button type="button" @click="activeTab = 'biodata'" 
                            :class="activeTab === 'biodata' ? 'border-red-600 text-red-700' : 'border-transparent text-gray-500'"
                            class="pb-3 text-sm font-semibold border-b-2 transition-colors outline-none">Biodata Pegawai</button>
                    <button type="button" @click="activeTab = 'akun'" 
                            :class="activeTab === 'akun' ? 'border-red-600 text-red-700' : 'border-transparent text-gray-500'"
                            class="pb-3 text-sm font-semibold border-b-2 transition-colors outline-none">Pengaturan Akun</button>
                </div>

                <div x-show="activeTab === 'biodata'" x-transition>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" x-model="editForm.nama_lengkap" required class="w-full px-4 py-3 rounded-xl border-gray-300 text-sm font-medium shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">NIP</label>
                            <input type="text" name="nip" x-model="editForm.nip" required class="w-full px-4 py-3 rounded-xl border-gray-300 text-sm font-medium shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Unit Kerja</label>
                            <select name="unit_kerja_id" x-model="editForm.unit_kerja_id" required class="w-full px-4 py-3 rounded-xl border-gray-300 text-sm bg-white font-medium shadow-sm">
                                <option value="">Pilih unit kerja...</option>
                                @foreach($unitKerja as $uk)
                                    <option value="{{ $uk->id }}">{{ $uk->nama_unit }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'akun'" x-transition>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Email</label>
                            <input type="email" name="email" x-model="editForm.email" class="w-full px-4 py-3 rounded-xl border-gray-300 text-sm font-medium">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Role Aplikasi</label>
                                <select name="role" x-model="editForm.role" required class="w-full px-4 py-3 rounded-xl border-gray-300 text-sm bg-white font-medium">
                                    <option value="anggota">Anggota</option>
                                    <option value="verifikator">Verifikator</option>
                                    <option value="admin">Administrator</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Status Akun</label>
                                <select name="status_akun" x-model="editForm.status_akun" required class="w-full px-4 py-3 rounded-xl border-gray-300 text-sm bg-white font-medium">
                                    <option value="active">Active</option>
                                    <option value="suspended">Suspended</option>
                                    <option value="blocked">Blocked</option>
                                    <option value="new">New</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Password</label>
                                <input type="password" name="password" class="w-full px-4 py-3 rounded-xl border-gray-300 text-sm font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="w-full px-4 py-3 rounded-xl border-gray-300 text-sm font-medium">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-6 border-t border-gray-100 mt-6">
                    <button type="button" @click="openEdit = false" class="px-6 py-3 rounded-xl border border-gray-300 font-semibold text-sm">Batal</button>
                    <button type="submit" class="px-8 py-3 rounded-xl bg-red-700 text-white font-semibold text-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-popup>