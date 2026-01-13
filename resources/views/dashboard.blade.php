<x-app-layout title="Dashboard">

    {{-- Grid Kartu Statistik / Menu Cepat --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Kartu Selamat Datang --}}
        <div class="md:col-span-3 bg-white rounded-2xl p-6 border border-gray-200 shadow-sm relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900">
                        Halo, {{ Auth::user()->profile->nama_lengkap ?? Auth::user()->email }}!
                    </h2>
                    <p class="text-gray-500 mt-1 text-sm">
                        Selamat datang di Portal Koperasi Dinas Kesehatan Kota Semarang.
                    </p>
                </div>
                <div class="hidden md:block">
                    <i class="ph-duotone ph-buildings text-6xl text-gray-200"></i>
                </div>
            </div>
            
            {{-- Background Decoration --}}
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-red-50 rounded-full blur-2xl opacity-50"></div>
        </div>

        {{-- Kartu Statistik 1: Simpanan --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100">
                    <i class="ph-fill ph-wallet text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Total Simpanan</p>
                    <h3 class="text-xl font-extrabold text-gray-900">
                        Rp {{ number_format($totalSimpanan, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- Kartu Statistik 2: Pinjaman --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600 border border-orange-100">
                    <i class="ph-fill ph-money text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Sisa Pinjaman</p>
                    <h3 class="text-xl font-extrabold text-gray-900">
                        Rp {{ number_format($sisaPinjaman, 0, ',', '.') }}
                    </h3>
                </div>
            </div>
        </div>

        {{-- Kartu Statistik 3: Info --}}
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 border border-purple-100">
                    <i class="ph-fill ph-calendar-check text-2xl"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">Bergabung Sejak</p>
                    <h3 class="text-xl font-bold text-gray-900">{{ Auth::user()->created_at->format('d M Y') }}</h3>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>