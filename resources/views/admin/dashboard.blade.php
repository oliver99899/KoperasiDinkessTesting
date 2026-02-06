<x-app-layout title="Dashboard Admin">
    <div class="bg-white rounded-2xl p-8 mb-8 border border-gray-200 shadow-sm relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Administrator</h1>
            <p class="text-gray-500 mt-1 flex items-center gap-2">
                <i class="ph-fill ph-calendar-blank"></i>
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
            </p>
        </div>
        <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-red-50 to-transparent"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4 transition hover:shadow-md">
            <div class="h-14 w-14 bg-gray-100 text-gray-600 rounded-xl flex items-center justify-center text-3xl">
                <i class="ph-fill ph-users"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Akun</p>
                <h3 class="text-3xl font-extrabold text-gray-900 mt-1">{{ $totalUser }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4 transition hover:shadow-md">
            <div class="h-14 w-14 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-3xl">
                <i class="ph-fill ph-user-check"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">User Aktif</p>
                <h3 class="text-3xl font-extrabold text-gray-900 mt-1">{{ $userActive }}</h3>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4 transition hover:shadow-md">
            <div class="h-14 w-14 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center text-3xl">
                <i class="ph-fill ph-hourglass"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-orange-600 uppercase tracking-wider">Perlu Setup</p>
                <h3 class="text-3xl font-extrabold text-gray-900 mt-1">{{ $userNew }}</h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="ph-bold ph-clock-counter-clockwise text-lg"></i>
                5 Registrasi Terakhir
            </h3>
            <a href="{{ route('admin.users.index') }}" class="text-sm font-bold text-red-700 hover:text-red-800 flex items-center gap-1 transition-colors">
                Lihat Semua <i class="ph-bold ph-arrow-right"></i>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-900 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Identitas</th>
                        <th class="px-6 py-4 text-center">Role</th>
                        <th class="px-6 py-4 text-center">Status Akun</th>
                        <th class="px-6 py-4 text-right">Waktu Daftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $u)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold uppercase border border-gray-200">
                                    {{ substr($u->name, 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-900">{{ $u->profile->nama_lengkap ?? $u->name }}</span>
                                    <span class="text-xs text-gray-500">NIP. {{ $u->nip ?? $u->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($u->role == 'admin')
                                <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2 py-1 rounded-md text-xs font-bold border border-gray-200">
                                    <i class="ph-bold ph-shield"></i> Admin
                                </span>
                            @elseif($u->role == 'verifikator')
                                <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 px-2 py-1 rounded-md text-xs font-bold border border-purple-200">
                                    <i class="ph-bold ph-stamp"></i> Verifikator
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-2 py-1 rounded-md text-xs font-bold border border-blue-200">
                                    <i class="ph-bold ph-user"></i> Anggota
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($u->status_akun == 'active')
                                <span class="inline-flex items-center gap-1 text-green-700 font-bold text-xs bg-green-50 px-2 py-1 rounded-full border border-green-100">
                                    <i class="ph-fill ph-check-circle"></i> Aktif
                                </span>
                            @elseif($u->status_akun == 'new')
                                <span class="inline-flex items-center gap-1 text-orange-700 font-bold text-xs bg-orange-50 px-2 py-1 rounded-full border border-orange-100">
                                    <i class="ph-fill ph-hourglass"></i> Baru
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-red-700 font-bold text-xs bg-red-50 px-2 py-1 rounded-full border border-red-100">
                                    <i class="ph-fill ph-prohibit"></i> {{ ucfirst($u->status_akun) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-right">
                            {{ $u->created_at->diffForHumans() }} 
                            <span class="text-xs text-gray-400 block">
                                {{ $u->created_at->format('d M Y H:i') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">Belum ada data registrasi terbaru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>