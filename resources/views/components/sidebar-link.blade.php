@props(['active', 'href', 'icon'])

@php
$classes = ($active ?? false)
            ? 'group flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-red-700 bg-red-50 rounded-xl transition-all duration-200'
            : 'group flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-gray-600 hover:text-red-600 hover:bg-gray-50 rounded-xl transition-all duration-200';

$iconClasses = ($active ?? false)
            ? 'text-lg text-red-600'
            : 'text-lg text-gray-400 group-hover:text-red-500 transition-colors';
@endphp

<a {{ $attributes->merge(['href' => $href, 'class' => $classes]) }}>
    @if($icon)
        <i class="{{ $icon }} {{ $iconClasses }}"></i>
    @endif
    
    <span class="truncate">{{ $slot }}</span>

    @if($active)
        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-red-600 shadow-[0_0_8px_rgba(185,28,28,0.6)]"></span>
    @endif
</a>