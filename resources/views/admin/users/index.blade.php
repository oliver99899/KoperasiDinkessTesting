<x-app-layout title="Kelola Pengguna">

    {{-- Header & Tombol Tambah --}}
    <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Pengguna</h1>
            <p class="text-sm text-gray-500">Kelola akun Admin, Verifikator, dan Anggota.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-red-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md hover:bg-red-800 transition-transform active:scale-95 flex items-center gap-2">
            <i class="ph-bold ph-user-plus"></i>
            Tambah Pengguna Baru
        </a>
    </div>

    {{-- Tabel User --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-900 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Nama & Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- Kolom Nama --}}
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $u->profile->nama_lengkap ?? 'User Baru' }}</div>
                                <div class="text-xs text-gray-500">{{ $u->email }}</div>
                            </td>

                            {{-- Kolom Role --}}
                            <td class="px-6 py-4">
                                @if($u->role == 'admin')
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-800 px-2.5 py-1 rounded-md text-xs font-bold border border-gray-200">
                                        <i class="ph-fill ph-shield-check"></i> Admin
                                    </span>
                                @elseif($u->role == 'verifikator')
                                    <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 px-2.5 py-1 rounded-md text-xs font-bold border border-purple-200">
                                        <i class="ph-fill ph-stamp"></i> Verifikator
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-2.5 py-1 rounded-md text-xs font-bold border border-blue-200">
                                        <i class="ph-fill ph-user"></i> Anggota
                                    </span>
                                @endif
                            </td>

                            {{-- Kolom Status --}}
                            <td class="px-6 py-4">
                                <span class="text-green-600 font-bold text-xs flex items-center gap-1">
                                    <i class="ph-fill ph-check-circle"></i> Aktif
                                </span>
                            </td>

                            {{-- Kolom Aksi (Edit & Hapus) --}}
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.users.edit', $u->id) }}" 
                                       class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 border border-transparent hover:border-blue-100 transition-all"
                                       title="Edit User">
                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Data terkait simpanan/pinjaman juga akan terhapus permanen.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 rounded-lg text-red-600 hover:bg-red-50 border border-transparent hover:border-red-100 transition-all"
                                                title="Hapus User">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">
                                Belum ada data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>