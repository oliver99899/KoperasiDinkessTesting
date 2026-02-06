@props(['name', 'title', 'icon' => null, 'maxWidth' => 'max-w-xl'])

<template x-teleport="body">
    <div x-show="{{ $name }}" 
         x-cloak
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6 overflow-hidden"
         aria-modal="true" role="dialog">
        
        {{-- Layer 1: Backdrop (Tanpa Blur, Hanya Gelap Tipis) --}}
        <div x-show="{{ $name }}" 
             x-transition:enter="transition opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="{{ $name }} = false" 
             {{-- Kita pakai bg-black/40 atau bg-gray-900/40 tanpa backdrop-blur --}}
             class="absolute inset-0 bg-gray-900/40"></div>
        
        {{-- Layer 2: Modal Content --}}
        <div x-show="{{ $name }}" 
             x-transition:enter="transition cubic-bezier(0.34, 1.56, 0.64, 1) duration-400"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             {{-- transform-gpu memastikan animasi diproses oleh GPU --}}
             class="relative bg-white rounded-[2rem] shadow-[0_20px_60px_rgba(0,0,0,0.2)] w-full {{ $maxWidth }} overflow-hidden flex flex-col max-h-[90vh] transform-gpu">
            
            {{-- Header --}}
            <div class="bg-red-700 px-8 py-5 flex justify-between items-center shrink-0 border-b border-red-800/50 relative z-10">
                <div class="flex items-center gap-4">
                    @if($icon)
                        <div class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center text-white text-xl">
                            {!! $icon !!}
                        </div>
                    @endif
                    <h3 class="text-white font-black text-lg tracking-tight uppercase">{{ $title }}</h3>
                </div>
                <button type="button" @click="{{ $name }} = false" 
                        class="text-white/60 hover:text-white transition-all focus:outline-none rounded-2xl p-2 hover:bg-white/10 active:scale-90">
                    <i class="ph-bold ph-x text-2xl"></i>
                </button>
            </div>
            
            {{-- Body --}}
            <div class="flex-1 overflow-y-auto p-0 custom-scrollbar bg-white">
                {{ $slot }}
            </div>
        </div>
    </div>
</template>