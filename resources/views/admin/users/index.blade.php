<x-app-layout title="Manajemen Pengguna">
    <div x-data="{ 
        openCreate: {{ $errors->has('create') ? 'true' : 'false' }},
        openEdit: {{ $errors->has('edit') ? 'true' : 'false' }},
        isLoading: false,
        editForm: {
            action: '',
            nama_lengkap: '',
            nip: '',
            email: '',
            unit_kerja_id: '',
            role: 'anggota',
            status_akun: 'active',
            password: '',
            password_confirmation: ''
        },
        resetEditForm() {
            this.editForm = {
                action: '', nama_lengkap: '', nip: '', email: '', unit_kerja_id: '', 
                role: 'anggota', status_akun: 'active', password: '', password_confirmation: ''
            };
        },
        openEditModal(user) {
            this.isLoading = true; 
            this.openEdit = true;
            this.resetEditForm();
            setTimeout(() => {
                let url = '{{ route('admin.users.update', 0) }}';
                this.editForm.action = url.replace('/0', '/' + user.id);
                let profile = user.profile || {};
                this.editForm.nama_lengkap = profile.nama_lengkap || user.name;
                this.editForm.nip = user.nip;
                this.editForm.email = user.email;
                this.editForm.role = user.roles.length > 0 ? user.roles[0].name : 'anggota';
                this.editForm.status_akun = user.status_akun;
                this.editForm.unit_kerja_id = profile.unit_kerja_id || '';
                this.isLoading = false;
            }, 50);
        }
    }">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Manajemen Pengguna</h1>
                <p class="text-sm text-gray-500 font-medium">Kelola data anggota, verifikator, dan hak akses sistem.</p>
            </div>
            <button @click="openCreate = true" 
                    class="bg-red-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm hover:bg-red-800 shadow-lg shadow-red-700/20 flex items-center gap-2 active:scale-95 transition-all">
                <i class="ph-bold ph-user-plus text-lg"></i> Tambah User
            </button>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm mb-6">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Pencarian</label>
                        <div class="relative group">
                            <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-red-600 transition-colors"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / NIP..." 
                                   class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-red-500 focus:border-red-500 transition-all shadow-sm font-medium">
                        </div>
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Unit Kerja</label>
                        <div class="relative">
                            <i class="ph-bold ph-hospital absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <select name="unit_kerja_id" class="w-full pl-9 pr-8 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-red-500 focus:border-red-500 appearance-none bg-white cursor-pointer hover:border-red-300 transition-colors shadow-sm font-medium">
                                <option value="">Semua Unit Kerja</option>
                                @foreach($unitKerja as $uk)
                                    <option value="{{ $uk->id }}" {{ request('unit_kerja_id') == $uk->id ? 'selected' : '' }}>
                                        {{ $uk->nama_unit }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Role</label>
                        <div class="relative">
                            <i class="ph-bold ph-shield-check absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <select name="role" class="w-full pl-9 pr-8 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-red-500 focus:border-red-500 appearance-none bg-white cursor-pointer hover:border-red-300 transition-colors shadow-sm font-medium">
                                <option value="">Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="verifikator" {{ request('role') == 'verifikator' ? 'selected' : '' }}>Verifikator</option>
                                <option value="anggota" {{ request('role') == 'anggota' ? 'selected' : '' }}>Anggota</option>
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Status</label>
                        <div class="relative">
                            <i class="ph-bold ph-toggle-left absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <select name="status_akun" class="w-full pl-9 pr-8 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-red-500 focus:border-red-500 appearance-none bg-white cursor-pointer hover:border-red-300 transition-colors shadow-sm font-medium">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status_akun') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="new" {{ request('status_akun') == 'new' ? 'selected' : '' }}>New</option>
                                <option value="suspended" {{ request('status_akun') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                <option value="blocked" {{ request('status_akun') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                    <div class="md:col-span-1 flex gap-2">
                        <button type="submit" class="w-full bg-red-700 text-white py-2.5 rounded-xl text-sm font-semibold hover:bg-red-800 transition shadow-md shadow-red-700/20 active:scale-95">
                            <i class="ph-bold ph-funnel"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-900 font-semibold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">Pengguna</th>
                            <th class="px-6 py-4 text-center">Unit Kerja</th>
                            <th class="px-6 py-4 text-center">Role</th>
                            <th class="px-6 py-4 text-center">Status Akun</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center font-semibold uppercase border border-gray-200">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $user->profile->nama_lengkap ?? $user->name }}</div>
                                        <div class="text-xs text-gray-500 font-medium">NIP. {{ $user->nip }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-medium">
                                {{ $user->profile->unitKerja->nama_unit ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($user->hasRole('admin'))
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2.5 py-1 rounded-md text-xs font-semibold border border-gray-200">Admin</span>
                                @elseif($user->hasRole('verifikator'))
                                    <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 px-2.5 py-1 rounded-md text-xs font-semibold border border-purple-200">Verifikator</span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-2.5 py-1 rounded-md text-xs font-semibold border border-blue-200">Anggota</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $user->status_akun === 'active' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-orange-50 text-orange-700 border-orange-100' }}">
                                    {{ ucfirst($user->status_akun) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button @click="openEditModal({{ $user->load('roles') }})" 
                                            class="h-8 w-8 rounded-lg border border-gray-200 text-gray-500 hover:bg-yellow-50 hover:text-yellow-600 transition-all shadow-sm">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </button>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                        @csrf @method('DELETE')
                                        <button class="h-8 w-8 rounded-lg border border-gray-200 text-gray-500 hover:bg-red-50 hover:text-red-600 transition-all shadow-sm">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-12 text-gray-400 italic font-medium">Data tidak ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 font-medium">
                {{ $users->withQueryString()->links() }}
            </div>
            @endif
        </div>

        @include('admin.users.partials.tambahkan-popup')
        @include('admin.users.partials.edit-popup')
    </div>
</x-app-layout>