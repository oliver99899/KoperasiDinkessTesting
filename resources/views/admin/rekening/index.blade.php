<x-app-layout title="Kelola Rekening Koperasi">
    <div x-data="{ openAdd: false }">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Rekening Koperasi</h1>
                <p class="text-sm text-gray-500 font-medium">Kelola rekening tujuan transfer pembayaran angsuran.</p>
            </div>
            <button @click="openAdd = true"
                    class="bg-red-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-red-800 shadow-lg shadow-red-700/20 flex items-center gap-2 active:scale-95 transition-all">
                <i class="ph-bold ph-plus"></i> Tambah Rekening
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-900 font-bold uppercase text-xs border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Bank & Rekening</th>
                            <th class="px-6 py-4">Atas Nama</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($rekening as $rek)
                        <tr class="hover:bg-gray-50 transition-colors" x-data="{ openEdit: false }">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $rek->nama_bank }}</div>
                                <div class="font-mono text-sm text-gray-600 mt-0.5">{{ $rek->nomor_rekening }}</div>
                            </td>
                            <td class="px-6 py-4 font-medium">{{ $rek->atas_nama }}</td>
                            <td class="px-6 py-4 text-gray-500 italic">{{ $rek->keterangan ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($rek->is_active)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-bold border border-green-100">
                                        <i class="ph-fill ph-check-circle"></i> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-bold border border-gray-200">
                                        <i class="ph-fill ph-x-circle"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button @click="openEdit = true"
                                            class="h-8 w-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-yellow-50 hover:text-yellow-600 transition-all">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </button>
                                    @if(!$rek->is_active)
                                    <form action="{{ route('admin.rekening.destroy', $rek->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus rekening ini?')">
                                        @csrf @method('DELETE')
                                        <button class="h-8 w-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-red-50 hover:text-red-600 transition-all">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>

                            {{-- Edit Popup --}}
                            <x-popup name="openEdit" title="Edit Rekening" icon="<i class='ph-bold ph-bank'></i>">
                                <div class="p-6">
                                    <form action="{{ route('admin.rekening.update', $rek->id) }}" method="POST" class="space-y-5">
                                        @csrf @method('PUT')
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Nama Bank</label>
                                            <input type="text" name="nama_bank" value="{{ $rek->nama_bank }}" required
                                                   class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-sm font-medium focus:border-red-600 focus:ring-red-600">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Nomor Rekening</label>
                                            <input type="text" name="nomor_rekening" value="{{ $rek->nomor_rekening }}" required
                                                   class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-sm font-medium focus:border-red-600 focus:ring-red-600">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Atas Nama</label>
                                            <input type="text" name="atas_nama" value="{{ $rek->atas_nama }}" required
                                                   class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-sm font-medium focus:border-red-600 focus:ring-red-600">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Keterangan</label>
                                            <input type="text" name="keterangan" value="{{ $rek->keterangan }}"
                                                   class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-sm font-medium focus:border-red-600 focus:ring-red-600">
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" name="is_active" value="1" id="active_{{ $rek->id }}"
                                                   {{ $rek->is_active ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-red-600 focus:ring-red-600">
                                            <label for="active_{{ $rek->id }}" class="text-sm font-bold text-gray-700">
                                                Jadikan rekening aktif
                                            </label>
                                        </div>
                                        <p class="text-[10px] text-gray-400 italic -mt-3">* Mengaktifkan rekening ini akan menonaktifkan rekening lainnya.</p>
                                        <button type="submit"
                                                class="w-full bg-red-700 text-white font-bold py-3 rounded-xl hover:bg-red-800 transition-all">
                                            Simpan Perubahan
                                        </button>
                                    </form>
                                </div>
                            </x-popup>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Belum ada data rekening.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tambah Rekening Popup --}}
        <x-popup name="openAdd" title="Tambah Rekening" icon="<i class='ph-bold ph-bank'></i>">
            <div class="p-6">
                <form action="{{ route('admin.rekening.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Nama Bank</label>
                        <input type="text" name="nama_bank" required placeholder="Contoh: Bank Jateng"
                               class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-sm font-medium focus:border-red-600 focus:ring-red-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Nomor Rekening</label>
                        <input type="text" name="nomor_rekening" required placeholder="Contoh: 502301000456539"
                               class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-sm font-medium focus:border-red-600 focus:ring-red-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Atas Nama</label>
                        <input type="text" name="atas_nama" required placeholder="Contoh: Koperasi Dinkes Semarang"
                               class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-sm font-medium focus:border-red-600 focus:ring-red-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Keterangan</label>
                        <input type="text" name="keterangan" placeholder="Opsional..."
                               class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-sm font-medium focus:border-red-600 focus:ring-red-600">
                    </div>
                    <button type="submit"
                            class="w-full bg-red-700 text-white font-bold py-3 rounded-xl hover:bg-red-800 transition-all">
                        Tambah Rekening
                    </button>
                </form>
            </div>
        </x-popup>
    </div>
</x-app-layout>