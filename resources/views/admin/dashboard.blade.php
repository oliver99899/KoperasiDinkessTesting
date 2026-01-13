<x-app-layout title="Dashboard Admin">
    
    {{-- Header Dashboard --}}
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

    {{-- STATISTIK UTAMA --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        
        {{-- Card 1: Total Anggota --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="h-14 w-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-3xl">
                <i class="ph-fill ph-users"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Anggota</p>
                <h3 class="text-3xl font-extrabold text-gray-900 mt-1">{{ $totalAnggota }}</h3>
            </div>
        </div>

        {{-- Card 2: Total Aset --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="h-14 w-14 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-3xl">
                <i class="ph-fill ph-money"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Aset Koperasi</p>
                <h3 class="text-3xl font-extrabold text-gray-900 mt-1">
                    Rp {{ number_format($totalAset, 0, ',', '.') }}
                </h3>
            </div>
        </div>

    </div>

    {{-- TABEL 5 USER TERBARU --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        {{-- Header Tabel Bersih (Tanpa Tombol Redundan) --}}
        <div class="px-6 py-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 flex items-center gap-2">
                <i class="ph-bold ph-user-plus text-lg"></i>
                5 Registrasi Terakhir
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-900 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Nama Lengkap</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Waktu Daftar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $u)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900">
                            {{ $u->profile->nama_lengkap ?? 'User Baru' }}
                        </td>
                        <td class="px-6 py-4">{{ $u->email }}</td>
                        <td class="px-6 py-4">
                            @if($u->role == 'admin')
                                <span class="bg-gray-100 text-gray-800 px-2.5 py-1 rounded-md text-xs font-bold border border-gray-200">Admin</span>
                            @elseif($u->role == 'verifikator')
                                <span class="bg-purple-50 text-purple-700 px-2.5 py-1 rounded-md text-xs font-bold border border-purple-200">Verifikator</span>
                            @else
                                <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-md text-xs font-bold border border-blue-200">Anggota</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $u->created_at->diffForHumans() }} 
                            <span class="text-xs text-gray-400 block">
                                {{ $u->created_at->format('d M Y H:i') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada data anggota.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>