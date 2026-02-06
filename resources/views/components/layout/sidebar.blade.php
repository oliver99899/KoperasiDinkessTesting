<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl lg:shadow-none transform transition-transform duration-300 flex flex-col"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
    
    <div class="h-16 flex items-center justify-center bg-red-700 px-4 shrink-0 shadow-sm z-10">
        <div class="flex items-center gap-3 text-white">
            <div class="bg-white p-1.5 rounded-xl shadow-md shrink-0">
                <img src="{{ asset('images/logo-dinkes.png') }}" alt="Logo" class="h-8 w-8 object-contain">
            </div>
            <div class="leading-tight text-left">
                <span class="block font-semibold tracking-wide text-base">KOPERASI</span>
                <span class="block text-[10px] opacity-90 font-semibold tracking-wider">DKK SEMARANG</span>
            </div>
        </div>
    </div>

    @php
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');
        $isVerifikator = $user->hasRole('verifikator');
        $isAnggota = $user->hasRole('anggota');

        $mode = session('sidebar_mode');

        if (!in_array($mode, ['anggota', 'admin', 'verifikator'], true)) {
            if ($isAdmin) $mode = 'admin';
            elseif ($isVerifikator) $mode = 'verifikator';
            else $mode = 'anggota';
        }

        if ($mode === 'admin' && !$isAdmin) $mode = $isVerifikator ? 'verifikator' : 'anggota';
        if ($mode === 'verifikator' && !$isVerifikator) $mode = $isAdmin ? 'admin' : 'anggota';
        if ($mode === 'anggota' && !$isAnggota) $mode = $isAdmin ? 'admin' : ($isVerifikator ? 'verifikator' : 'anggota');
    @endphp

    <div class="flex-1 overflow-y-auto py-4 custom-scrollbar">
        <div class="px-3 mb-3">
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-2 flex items-center gap-2">
                @if($isAnggota || $isAdmin || $isVerifikator)
                    <form action="{{ route('ui.mode') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="mode" value="anggota">
                        <button type="submit"
                                class="w-full px-3 py-2 rounded-xl text-[11px] font-bold flex items-center justify-center gap-2 transition
                                {{ $mode === 'anggota' ? 'bg-white shadow-sm border border-gray-200 text-gray-900' : 'text-gray-600 hover:bg-white/70' }}">
                            <i class="ph-bold ph-user"></i> Anggota
                        </button>
                    </form>
                @endif

                @if($isVerifikator)
                    <form action="{{ route('ui.mode') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="mode" value="verifikator">
                        <button type="submit"
                                class="w-full px-3 py-2 rounded-xl text-[11px] font-bold flex items-center justify-center gap-2 transition
                                {{ $mode === 'verifikator' ? 'bg-white shadow-sm border border-gray-200 text-gray-900' : 'text-gray-600 hover:bg-white/70' }}">
                            <i class="ph-bold ph-stamp"></i> Bendahara
                        </button>
                    </form>
                @endif

                @if($isAdmin)
                    <form action="{{ route('ui.mode') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="mode" value="admin">
                        <button type="submit"
                                class="w-full px-3 py-2 rounded-xl text-[11px] font-bold flex items-center justify-center gap-2 transition
                                {{ $mode === 'admin' ? 'bg-white shadow-sm border border-gray-200 text-gray-900' : 'text-gray-600 hover:bg-white/70' }}">
                            <i class="ph-bold ph-shield"></i> Admin
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <nav class="space-y-1 px-3">
            @if($mode === 'anggota')
                <x-sidebar-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="ph-bold ph-house" class="font-semibold">
                    Dashboard Anggota
                </x-sidebar-link>

                <x-sidebar-link href="{{ route('simpanan.index') }}" :active="request()->routeIs('simpanan.*')" icon="ph-bold ph-wallet" class="font-semibold">
                    Simpanan Saya
                </x-sidebar-link>

                <x-sidebar-link href="{{ route('pinjaman.index') }}" :active="request()->routeIs('pinjaman.*')" icon="ph-bold ph-hand-coins" class="font-semibold">
                    Pinjaman Saya
                </x-sidebar-link>

                <x-sidebar-link href="{{ route('angsuran.index') }}" :active="request()->routeIs('angsuran.*')" icon="ph-bold ph-receipt" class="font-semibold">
                    Riwayat Angsuran
                </x-sidebar-link>
            @endif

            @if($mode === 'verifikator')
                <x-sidebar-link href="{{ route('verifikator.dashboard') }}" :active="request()->routeIs('verifikator.dashboard')" icon="ph-bold ph-chart-pie" class="font-semibold">
                    Dashboard Bendahara
                </x-sidebar-link>

                <x-sidebar-link href="{{ route('verifikator.simpanan.members') }}" :active="request()->routeIs('verifikator.simpanan.*')" icon="ph-bold ph-users-three" class="font-semibold">
                    Kelola Simpanan
                </x-sidebar-link>

                <x-sidebar-link href="{{ route('verifikator.pinjaman.index') }}" :active="request()->routeIs('verifikator.pinjaman.*')" icon="ph-bold ph-hand-coins" class="font-semibold">
                    Verifikasi Pinjaman
                </x-sidebar-link>

                <x-sidebar-link href="{{ route('verifikator.angsuran.index') }}" :active="request()->routeIs('verifikator.angsuran.*')" icon="ph-bold ph-receipt" class="font-semibold">
                    Verifikasi Angsuran
                </x-sidebar-link>
            @endif

            @if($mode === 'admin')
                <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="ph-bold ph-gauge" class="font-semibold">
                    Dashboard Admin
                </x-sidebar-link>

                <x-sidebar-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')" icon="ph-bold ph-users" class="font-semibold">
                    Manajemen User
                </x-sidebar-link>

                <x-sidebar-link href="{{ route('admin.unit-kerja.index') }}" :active="request()->routeIs('admin.unit-kerja.*')" icon="ph-bold ph-buildings" class="font-semibold">
                    Unit Kerja
                </x-sidebar-link>
            @endif
        </nav>
    </div>

    <div class="p-4 border-t border-gray-100 bg-white shrink-0">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl shadow-sm text-sm font-semibold text-white bg-red-700 hover:bg-red-800 transition-transform active:scale-[0.98]">
                <i class="ph-bold ph-sign-out text-lg"></i> KELUAR
            </button>
        </form>
    </div>
</aside>
