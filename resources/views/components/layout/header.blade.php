<header class="h-16 bg-red-700 sticky top-0 z-30 shadow-md transition-all duration-300">
    <div class="h-full px-4 lg:px-8">
        <div class="h-full max-w-7xl mx-auto flex items-center justify-between">
            
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden inline-flex items-center justify-center h-10 w-10 rounded-xl text-white/90 hover:bg-white/10 active:bg-white/20 transition-colors focus:outline-none focus:ring-2 focus:ring-white/30">
                    <i class="ph ph-list text-2xl"></i>
                </button>
            </div>

            @php
                $user = auth()->user();
                $isAdmin = $user->hasRole('admin');
                $isVerifikator = $user->hasRole('verifikator');
                $profile = $user->profile;
            @endphp

            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-3 bg-white/10 border border-white/15 rounded-xl px-3 py-1.5 backdrop-blur-sm">
                    <div class="text-right leading-tight">
                        <p class="text-sm font-extrabold text-white truncate max-w-[150px]">
                            {{ $profile?->nama_lengkap ?? $user->name }}
                        </p>
                        <p class="text-[10px] text-white/80 uppercase tracking-wide">
                            @if($isAdmin)
                                Administrator
                            @elseif($isVerifikator)
                                Bendahara
                            @else
                                Anggota
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Bagian Foto Profil --}}
                <div class="h-10 w-10 bg-white rounded-full flex items-center justify-center border-2 border-white/30 shadow-sm shrink-0 overflow-hidden">
                    @if($profile && $profile->foto_profil_path && \Storage::disk('public')->exists($profile->foto_profil_path))
                        <img src="{{ asset('storage/' . $profile->foto_profil_path) }}" 
                             alt="Profile" 
                             class="h-full w-full object-cover">
                    @else
                        {{-- Fallback jika tidak ada foto: Tampilkan inisial atau icon --}}
                        <div class="h-full w-full bg-red-50 flex items-center justify-center text-red-700 font-black text-sm uppercase">
                            {{ substr($profile?->nama_lengkap ?? $user->name, 0, 1) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>