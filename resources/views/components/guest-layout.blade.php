<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Login' }} - Koperasi DKK Semarang</title>
    
    <link rel="shortcut icon" href="{{ asset('images/logo-dinkes.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="h-full font-sans antialiased text-gray-900">

    <div class="min-h-screen bg-gray-50 relative overflow-hidden flex items-center justify-center py-10 sm:py-12 px-4 sm:px-6 lg:px-8">

        <div class="absolute top-0 left-0 w-full h-2 bg-red-700"></div>

        <div class="absolute -bottom-48 -left-48 w-[28rem] h-[28rem] bg-gray-200 rounded-full opacity-35 blur-3xl"></div>
        <div class="absolute top-16 right-10 w-40 h-40 bg-red-100 rounded-full opacity-25 blur-2xl"></div>

        <div class="w-full max-w-[460px] relative z-10">

            <div class="bg-white rounded-2xl border border-gray-200 shadow-2xl shadow-gray-200/70 overflow-hidden">
                <div class="h-1.5 bg-red-700"></div>

                <div class="py-10 px-8">
                    {{ $slot }}
                </div>
            </div>

            <p class="mt-8 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} Dinas Kesehatan Kota Semarang.
            </p>
        </div>
    </div>

</body>
</html>