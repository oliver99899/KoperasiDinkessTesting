@props(['title' => 'Koperasi'])

<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - Koperasi DKK Semarang</title>

    <link rel="shortcut icon" href="{{ asset('images/logo-dinkes.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #e5e7eb; border-radius: 20px; }
    </style>
</head>
<body class="h-full font-sans antialiased text-gray-900 bg-gray-50" x-data="{ sidebarOpen: false }">

    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         @click="sidebarOpen = false" 
         class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden" 
         x-cloak>
    </div>

    <x-layout.sidebar />

    <div class="lg:pl-64 flex flex-col min-h-screen transition-all duration-300">
        
        <x-layout.header />

        <main class="flex-1 bg-gray-50 w-full relative z-0">
            <div class="p-4 lg:p-8 max-w-7xl mx-auto w-full">
                
                <x-layout.alert />

                {{ $slot }}
                
            </div>
        </main>

        <x-layout.footer />
    </div>
</body>
</html>