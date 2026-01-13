<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ? $title . ' - ' : '' }}Koperasi DKK Semarang</title>
    
    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('images/logo-dinkes.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        /* Aman untuk elemen yang nanti memakai Alpine (opsional) */
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="h-full font-sans antialiased text-gray-900">

    {{-- BACKGROUND WRAPPER --}}
    <div class="min-h-screen bg-gray-50 relative overflow-hidden flex items-center justify-center py-10 sm:py-12 px-4 sm:px-6 lg:px-8">

        {{-- Top bar institusional --}}
        <div class="absolute top-0 left-0 w-full h-2 bg-red-700"></div>

        {{-- Dekorasi background (subtle, formal) --}}
        <div class="absolute -bottom-48 -left-48 w-[28rem] h-[28rem] bg-gray-200 rounded-full opacity-35 blur-3xl"></div>
        <div class="absolute top-16 right-10 w-40 h-40 bg-red-100 rounded-full opacity-25 blur-2xl"></div>

        {{-- CONTENT --}}
        <div class="w-full max-w-[460px] relative z-10">

            {{-- Card --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-2xl shadow-gray-200/70 overflow-hidden">
                {{-- Accent top border (konsisten dengan app-layout/login) --}}
                <div class="h-1.5 bg-red-700"></div>

                <div class="py-10 px-8">
                    {{ $slot }}
                </div>
            </div>

            {{-- Footer --}}
            <p class="mt-8 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} Dinas Kesehatan Kota Semarang.
            </p>
        </div>
    </div>

</body>
</html>
