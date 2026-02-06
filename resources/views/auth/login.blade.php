<x-guest-layout title="Login">

    <div class="text-center mb-8">
        <div class="inline-block p-2 bg-white rounded-2xl shadow-sm border border-gray-100 mb-4">
            <img
                src="{{ asset('images/logo-dinkes.png') }}"
                alt="Logo Dinas Kesehatan"
                class="h-20 w-20 object-contain hover:scale-105 transition-transform duration-300"
            >
        </div>

        <h1 class="text-2xl font-bold text-gray-900 tracking-tight leading-snug">
            Koperasi Dinas Kesehatan <br>
            <span class="text-gray-900">Kota Semarang</span>
        </h1>
    </div>

    <div class="relative flex py-2 items-center mb-6">
        <div class="flex-grow border-t border-gray-300"></div>
        <span class="flex-shrink-0 mx-4 text-gray-500 text-xs font-bold uppercase tracking-widest">
            Silakan Masuk
        </span>
        <div class="flex-grow border-t border-gray-300"></div>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl bg-red-50 p-4 border border-red-200 flex items-start gap-3 animate-pulse" role="alert">
            <i class="ph-fill ph-warning-circle text-red-600 text-xl mt-0.5"></i>
            <div>
                <p class="text-sm text-red-800 font-bold">Gagal Masuk</p>
                <ul class="text-xs text-red-700 mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form class="space-y-6" action="{{ route('login.post') }}" method="POST" novalidate>
        @csrf

        <div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="ph ph-identification-card text-gray-500 text-xl group-focus-within:text-red-700 transition-colors"></i>
                </div>

                <input
                    id="nip"
                    name="nip"
                    type="text"
                    inputmode="numeric"
                    autocomplete="username"
                    required
                    value="{{ old('nip') }}"
                    placeholder="Nomor Induk Pegawai"
                    class="block w-full rounded-xl border-gray-300 py-3.5 pl-12 pr-4 text-gray-900 placeholder:text-gray-400 
                           shadow-sm focus:border-red-600 focus:ring-red-600 sm:text-sm transition-all font-medium"
                >
            </div>
            @error('nip')
                <p class="mt-2 text-xs text-red-600 font-semibold ml-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="ph ph-lock-key text-gray-500 text-xl group-focus-within:text-red-700 transition-colors"></i>
                </div>

                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                    required
                    placeholder="Kata Sandi"
                    class="block w-full rounded-xl border-gray-300 py-3.5 pl-12 pr-12 text-gray-900 placeholder:text-gray-400 
                           shadow-sm focus:border-red-600 focus:ring-red-600 sm:text-sm transition-all font-medium"
                >

                <button
                    type="button"
                    onclick="togglePassword()"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                    title="Lihat Password"
                >
                    <i id="pwIcon" class="ph ph-eye text-xl"></i>
                </button>
            </div>
            @error('password')
                <p class="mt-2 text-xs text-red-600 font-semibold ml-1">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="submit"
            class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-xl shadow-md 
                   text-sm font-bold text-white bg-red-700 hover:bg-red-800 
                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 
                   transition-transform active:scale-[0.98]"
        >
            MASUK APLIKASI
            <i class="ph-bold ph-arrow-right"></i>
        </button>
    </form>

    <div class="pt-6 text-center border-t border-gray-300 mt-8">
        <p class="text-xs text-gray-400 leading-relaxed">
            Kendala akses akun? Hubungi <span class="font-bold text-gray-700">Administrator IT</span>.
        </p>
    </div>

    <script>
        function togglePassword() {
            const pw = document.getElementById('password');
            const ic = document.getElementById('pwIcon');
            if (pw.type === 'password') {
                pw.type = 'text';
                ic.classList.replace('ph-eye', 'ph-eye-slash');
                ic.classList.add('text-red-700');
            } else {
                pw.type = 'password';
                ic.classList.replace('ph-eye-slash', 'ph-eye');
                ic.classList.remove('text-red-700');
            }
        }
    </script>

</x-guest-layout>