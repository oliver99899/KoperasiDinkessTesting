<x-app-layout title="Review Bukti Transfer">
    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-black text-gray-900">Detail Bukti Transfer</h1>
            <a href="{{ route('verifikator.pembayaran.transfer.index') }}" class="text-sm font-bold text-gray-400 hover:text-red-600 transition-colors">Kembali</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Foto/PDF Bukti --}}
            <div class="bg-gray-100 rounded-[2rem] overflow-hidden border border-gray-200">
                @php
                    $ext = pathinfo($item->bukti_path, PATHINFO_EXTENSION);
                @endphp

                @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                    <a href="{{ asset('storage/' . $item->bukti_path) }}" target="_blank">
                        <img src="{{ asset('storage/' . $item->bukti_path) }}" class="w-full h-auto hover:scale-105 transition-transform duration-500">
                    </a>
                    <p class="p-4 text-center text-[10px] text-gray-400 font-bold uppercase">Klik gambar untuk memperbesar</p>
                @else
                    <div class="flex flex-col items-center justify-center py-16 gap-4">
                        <i class="ph-fill ph-file-pdf text-6xl text-red-600"></i>
                        <p class="text-sm font-bold text-gray-600">File PDF</p>
                        <a href="{{ asset('storage/' . $item->bukti_path) }}" target="_blank"
                        class="bg-red-700 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-red-800 transition-all">
                            Buka PDF
                        </a>
                    </div>
                @endif
            </div>

            {{-- Form Verifikasi --}}
            <div class="space-y-6">
                <div class="bg-white p-8 rounded-[2rem] border border-gray-200 shadow-sm">
                    <div class="mb-6">
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-1">Nominal Tagihan</p>
                        <p class="text-3xl font-black text-gray-900">Rp {{ number_format($item->pinjaman->cicilan_pokok_bulanan + $item->pinjaman->cicilan_bunga_bulanan, 0, ',', '.') }}</p>
                    </div>

                    <div class="space-y-4 mb-8 border-t border-gray-100 pt-6">
                        <div class="flex justify-between text-sm"><span class="text-gray-500">Peminjam</span><span class="font-bold text-gray-900">{{ $item->pinjaman->user->name }}</span></div>
                        <div class="flex justify-between text-sm"><span class="text-gray-500">Bulan Angsuran</span><span class="font-bold text-red-600">Ke-{{ $item->angsuran_ke }}</span></div>
                        <div class="flex justify-between text-sm"><span class="text-gray-500">Tgl Transfer</span><span class="font-bold text-gray-900">{{ $item->tanggal_transfer->format('d F Y') }}</span></div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <form action="{{ route('verifikator.pembayaran.transfer.approve', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 text-white font-black py-4 rounded-2xl hover:bg-green-700 transition-all shadow-lg shadow-green-100 uppercase text-xs tracking-widest">
                                Validasi Pembayaran
                            </button>
                        </form>
                        
                        <div x-data="{ openReject: false }">
                            <button @click="openReject = true" class="w-full bg-red-50 text-red-600 font-black py-4 rounded-2xl hover:bg-red-100 transition-all uppercase text-xs tracking-widest border border-red-100">
                                Tolak Bukti
                            </button>
                            
                            <div x-show="openReject" class="mt-4 p-4 bg-red-50 rounded-2xl border border-red-100">
                                <form action="{{ route('verifikator.pembayaran.transfer.reject', $item->id) }}" method="POST">
                                    @csrf
                                    <textarea name="alasan_penolakan" required placeholder="Alasan penolakan..." class="w-full rounded-xl border-red-200 text-sm focus:ring-red-600 mb-3"></textarea>
                                    <button type="submit" class="w-full bg-red-600 text-white font-bold py-2 rounded-xl text-[10px] uppercase">Kirim Penolakan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>