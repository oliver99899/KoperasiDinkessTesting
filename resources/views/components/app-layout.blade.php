<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Koperasi' }} - Koperasi DKK Semarang</title>

    <link rel="shortcut icon" href="{{ asset('images/logo-dinkes.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>[x-cloak]{display:none !important}</style>
</head>

<body class="h-full font-sans antialiased text-gray-900" x-data="{ sidebarOpen: false }">

    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-sm shadow-black/10 transform transition-transform duration-300 lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        
        <div class="h-16 flex items-center justify-center bg-red-700 px-4">
            <div class="flex items-center gap-3 text-white">
                <div class="bg-white p-1.5 rounded-xl shadow-md shrink-0">
                    <img src="{{ asset('images/logo-dinkes.png') }}" alt="Logo" class="h-9 w-9 object-contain">
                </div>
                <div class="leading-tight text-left">
                    <span class="block font-extrabold tracking-wide text-base">KOPERASI</span>
                    <span class="block text-[10px] opacity-90 font-semibold tracking-wider">DKK SEMARANG</span>
                </div>
            </div>
        </div>

        <nav class="p-3 space-y-2 overflow-y-auto h-[calc(100vh-4rem)] flex flex-col">
            <div class="flex-1">
                <p class="px-2 text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 mt-2">Menu Utama</p>

                @if(Auth::user()->role == 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-red-50 text-red-700 border-l-4 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="ph ph-chart-pie-slice text-lg"></i> Dashboard Admin
                    </a>
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center gap-3 px-3 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-red-50 text-red-700 border-l-4 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="ph ph-users text-lg"></i> Kelola Pengguna
                    </a>

                @elseif(Auth::user()->role == 'verifikator')
                    <a href="{{ route('verifikator.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('verifikator.dashboard') ? 'bg-red-50 text-red-700 border-l-4 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="ph ph-stamp text-lg"></i> Dashboard
                    </a>
                    <a href="{{ route('verifikator.simpanan.create') }}" 
                       class="flex items-center gap-3 px-3 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('verifikator.simpanan.*') ? 'bg-red-50 text-red-700 border-l-4 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="ph ph-money text-lg"></i> Input Simpanan
                    </a>
                    <a href="{{ route('verifikator.pinjaman.index') }}" 
                       class="flex items-center gap-3 px-3 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('verifikator.pinjaman.*') ? 'bg-red-50 text-red-700 border-l-4 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="ph ph-file-text text-lg"></i> Ajuan Pinjaman
                    </a>

                @else
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-3 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-red-50 text-red-700 border-l-4 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="ph ph-squares-four text-lg"></i> Dashboard
                    </a>
                    <a href="{{ route('simpanan.index') }}" 
                       class="flex items-center gap-3 px-3 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('simpanan.*') ? 'bg-red-50 text-red-700 border-l-4 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="ph ph-piggy-bank text-lg"></i> Simpanan
                    </a>
                    <a href="{{ route('pinjaman.index') }}" 
                       class="flex items-center gap-3 px-3 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('pinjaman.*') ? 'bg-red-50 text-red-700 border-l-4 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="ph ph-hand-coins text-lg"></i> Pinjaman
                    </a>
                    <a href="{{ route('settings.edit') }}" 
                       class="flex items-center gap-3 px-3 py-3 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('settings.*') ? 'bg-red-50 text-red-700 border-l-4 border-red-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="ph ph-gear text-lg"></i> Pengaturan
                    </a>
                @endif
            </div>

            <div class="pt-4 border-t border-gray-100 mt-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl shadow-sm text-sm font-bold text-white bg-red-700 hover:bg-red-800 transition-transform active:scale-[0.98]">
                        <i class="ph-bold ph-sign-out text-lg"></i> KELUAR
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <div x-cloak x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden"></div>

    <div class="lg:pl-64 flex flex-col min-h-screen">
        <header class="h-16 bg-red-700 sticky top-0 z-30 shadow-sm shadow-black/10">
            <div class="h-full px-4 lg:px-8">
                <div class="h-full max-w-6xl mx-auto flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden inline-flex items-center justify-center h-10 w-10 rounded-xl text-white/90 hover:bg-white/10">
                            <i class="ph ph-list text-2xl"></i>
                        </button>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-3 bg-white/10 border border-white/15 rounded-xl px-3 py-2">
                            <div class="text-right leading-tight">
                                <p class="text-sm font-extrabold text-white">
                                    {{ Auth::user()->profile->nama_lengkap ?? Auth::user()->email }}
                                </p>
                                <p class="text-xs text-white/80 capitalize">
                                    {{ Auth::user()->role == 'admin' ? 'Administrator' : (Auth::user()->role == 'verifikator' ? 'Bendahara' : 'Anggota') }}
                                </p>
                            </div>
                        </div>
                        <div class="h-10 w-10 bg-white rounded-full flex items-center justify-center border border-white/40 text-red-700">
                            <i class="ph-bold ph-user text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main id="main-content" class="flex-1 bg-gray-50">
            <div class="p-4 lg:p-8">
                <div class="max-w-6xl mx-auto">
                    @if(session('success'))
                        <div x-data="{ show: true }" 
                             x-init="setTimeout(() => show = false, 3000)" 
                             x-show="show"
                             x-transition.duration.500ms
                             class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 flex items-center gap-3">
                            <i class="ph-fill ph-check-circle text-xl"></i>
                            <span class="font-bold">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div x-data="{ show: true }" 
                             x-init="setTimeout(() => show = false, 3000)" 
                             x-show="show"
                             x-transition.duration.500ms
                             class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
                            <div class="flex items-center gap-3 mb-2">
                                <i class="ph-fill ph-warning-circle text-xl"></i>
                                <span class="font-bold">Terjadi Kesalahan:</span>
                            </div>
                            <ul class="list-disc list-inside text-sm ml-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </div>
        </main>

        <footer class="bg-white border-t border-gray-200">
            <div class="px-4 lg:px-8 py-4">
                <div class="max-w-6xl mx-auto text-center text-xs text-gray-500">
                    &copy; {{ date('Y') }} Dinas Kesehatan Kota Semarang
                </div>
            </div>
        </footer>
    </div>
</body>
</html>