<x-app-layout title="Pengaturan Akun">

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
            <i class="ph-fill ph-check-circle text-xl"></i>
            <p class="text-sm font-bold">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- BAGIAN KIRI: CARD PROFILE --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden relative">
                <div class="h-24 bg-red-700"></div>
                <div class="px-6 pb-6 text-center">
                    <div class="relative -mt-12 mb-4 inline-flex">
                        <div class="h-24 w-24 rounded-full bg-white p-1 shadow-md">
                            <div class="h-full w-full rounded-full bg-gray-100 flex items-center justify-center text-gray-500 text-3xl font-bold border border-gray-200 uppercase">
                                {{ substr($profile->nama_lengkap ?? Auth::user()->name, 0, 1) }}
                            </div>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">{{ $profile->nama_lengkap ?? Auth::user()->name }}</h3>
                    <p class="text-sm text-gray-500 mb-4">{{ $profile->unitKerja->nama_unit ?? 'Unit Kerja Belum Diset' }}</p>
                    
                    <div class="border-t border-gray-100 pt-4 text-left space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">NIK</span>
                            <span class="font-medium text-gray-900 font-mono">{{ $profile->nik ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">NIP</span>
                            <span class="font-medium text-gray-900 font-mono">{{ Auth::user()->nip ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Status</span>
                            <span class="px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-700 uppercase">Aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: FORM EDIT --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="h-8 w-8 bg-red-50 rounded-lg flex items-center justify-center text-red-700">
                        <i class="ph-bold ph-pencil-simple"></i>
                    </div>
                    <h3 class="font-bold text-gray-900">Edit Biodata</h3>
                </div>

                <form action="{{ route('settings.update') }}" method="POST" class="p-6 md:p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor HP (WhatsApp)</label>
                        <div class="relative">
                            <i class="ph-bold ph-whatsapp-logo absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg pointer-events-none"></i>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $profile->no_hp ?? '') }}" required 
                                   class="w-full rounded-xl border-gray-300 py-3 pl-12 pr-4 text-gray-900 font-medium shadow-sm focus:border-red-600 focus:ring-red-600 placeholder:text-gray-400 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Domisili</label>
                        <div class="relative">
                            <i class="ph-bold ph-map-pin absolute left-4 top-4 text-gray-500 text-lg pointer-events-none"></i>
                            <textarea name="alamat" rows="3" required 
                                      class="w-full rounded-xl border-gray-300 py-3 pl-12 pr-4 text-gray-900 font-medium shadow-sm focus:border-red-600 focus:ring-red-600 placeholder:text-gray-400 transition-all">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end border-t border-gray-100">
                        <button type="submit" class="bg-red-700 hover:bg-red-800 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-red-700/20 transition-all transform active:scale-[0.98] flex items-center gap-2">
                            <i class="ph-bold ph-floppy-disk"></i>
                            SIMPAN PERUBAHAN
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</x-app-layout>