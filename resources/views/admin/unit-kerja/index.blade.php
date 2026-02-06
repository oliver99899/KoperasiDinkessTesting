<x-app-layout title="Kelola Unit Kerja">

    <div x-data="{
        showModal: {{ $errors->any() ? 'true' : 'false' }},
        isEdit: false,
        modalTitle: 'Tambah Unit Kerja',
        // Inisialisasi data awal
        form: { id: '', nama_unit: '', jenis: 'puskesmas', alamat: '', telepon: '' },
        
        openAdd() {
            this.isEdit = false;
            this.form = { id: '', nama_unit: '', jenis: 'puskesmas', alamat: '', telepon: '' };
            this.modalTitle = 'Tambah Unit Kerja';
            this.showModal = true;
        },
        
        openEdit(data) {
            this.isEdit = true;
            // Gunakan JSON.parse(JSON.stringify) untuk memutus referensi object agar tidak lag
            this.form = JSON.parse(JSON.stringify(data));
            this.modalTitle = 'Edit Unit Kerja';
            this.showModal = true;
        }
    }">

        {{-- HEADER & BUTTON --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Unit Kerja</h1>
                <p class="text-sm text-gray-500 font-medium">Daftar lokasi dinas dan puskesmas aktif.</p>
            </div>
            <button @click="openAdd()" 
                    class="bg-red-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-red-800 shadow-lg shadow-red-700/20 flex items-center gap-2 active:scale-95 transition-all">
                <i class="ph-bold ph-plus"></i> Tambah Unit
            </button>
        </div>

        {{-- SEARCH BAR --}}
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm mb-6">
            <form method="GET" action="{{ route('admin.unit-kerja.index') }}">
                <div class="relative group">
                    <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-red-600 transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama unit atau alamat..." 
                           class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-red-500 focus:border-red-500 transition-all shadow-sm bg-gray-50/30">
                </div>
            </form>
        </div>

        {{-- TABLE SECTION --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transform-gpu">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 border-collapse">
                    <thead class="bg-gray-50/80 text-gray-900 font-bold uppercase text-[10px] tracking-widest border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Nama Unit</th>
                            <th class="px-6 py-4">Jenis</th>
                            <th class="px-6 py-4">Kontak & Alamat</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($units as $unit)
                        <tr class="hover:bg-red-50/30 transition-colors group">
                            <td class="px-6 py-4 font-bold text-gray-900">
                                {{ $unit->nama_unit }}
                            </td>
                            <td class="px-6 py-4">
                                @if($unit->jenis == 'dinas')
                                    <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg text-[10px] font-black border border-blue-100 uppercase">
                                        <i class="ph-fill ph-buildings"></i> Dinas
                                    </span>
                                @elseif($unit->jenis == 'puskesmas')
                                    <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 px-2.5 py-1 rounded-lg text-[10px] font-black border border-green-100 uppercase">
                                        <i class="ph-fill ph-first-aid"></i> Puskesmas
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2.5 py-1 rounded-lg text-[10px] font-black border border-gray-200 uppercase">
                                        {{ $unit->jenis }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs">
                                <div class="flex items-center gap-2 text-gray-500 mb-1">
                                    <i class="ph-bold ph-phone text-gray-400"></i> 
                                    <span class="font-bold">{{ $unit->telepon ?? '-' }}</span>
                                </div>
                                <div class="flex items-start gap-2 text-gray-400">
                                    <i class="ph-bold ph-map-pin shrink-0"></i> 
                                    <span class="line-clamp-1 italic font-medium">{{ $unit->alamat ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    {{-- Pass data ke function openEdit --}}
                                    <button @click="openEdit({{ json_encode($unit) }})" 
                                            class="h-8 w-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-yellow-50 hover:text-yellow-600 transition-all active:scale-90">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </button>
                                    <form action="{{ route('admin.unit-kerja.destroy', $unit->id) }}" method="POST" onsubmit="return confirm('Hapus unit ini?')">
                                        @csrf @method('DELETE')
                                        <button class="h-8 w-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-red-50 hover:text-red-600 transition-all active:scale-90">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-20 text-gray-400 italic font-medium">Belum ada data unit kerja.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($units->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $units->withQueryString()->links() }}
            </div>
            @endif
        </div>

        {{-- POPUP COMPONENT (Efisien & Halus) --}}
        <x-popup name="showModal" alpineTitle="modalTitle" title="" icon="<i class='ph-bold ph-buildings'></i>" maxWidth="max-w-2xl">
            
            {{-- Container Form (Local Scope agar tidak mengganggu tabel) --}}
            <div class="p-8 bg-white h-full" x-data="{
                localTelepon: '',
                validateTelepon(e) {
                    let val = e.target.value.replace(/\D/g, '');
                    if (val.length > 15) val = val.substring(0, 15);
                    this.localTelepon = val;
                    $parent.form.telepon = val; {{-- Sinkron ke global state --}}
                }
            }" x-init="localTelepon = $parent.form.telepon">

                <form :action="isEdit ? '/admin/unit-kerja/' + form.id : '{{ route('admin.unit-kerja.store') }}'" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="_method" :value="isEdit ? 'PUT' : 'POST'">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- Input Nama Unit --}}
                        <div class="md:col-span-2 group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 group-focus-within:text-red-700 transition-colors">Nama Unit Kerja <span class="text-red-600">*</span></label>
                            <div class="relative">
                                <i class="ph-bold ph-buildings absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg transition-colors group-focus-within:text-red-700"></i>
                                <input type="text" name="nama_unit" x-model="form.nama_unit" required placeholder="Contoh: Puskesmas Pandanaran"
                                       class="w-full pl-12 pr-4 py-3.5 rounded-2xl border-gray-200 bg-gray-50 text-sm focus:border-red-600 focus:ring-red-600 focus:bg-white placeholder-gray-400 shadow-sm transition-all font-bold text-gray-900">
                            </div>
                        </div>

                        {{-- Input Jenis Unit --}}
                        <div class="group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 group-focus-within:text-red-700 transition-colors">Jenis Unit <span class="text-red-600">*</span></label>
                            <div class="relative">
                                <i class="ph-bold ph-tag absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg group-focus-within:text-red-700"></i>
                                <select name="jenis" x-model="form.jenis" required 
                                        class="w-full pl-12 pr-10 py-3.5 rounded-2xl border-gray-200 bg-gray-50 text-sm font-bold text-gray-900 focus:border-red-600 focus:ring-red-600 focus:bg-white shadow-sm appearance-none transition-all">
                                    <option value="puskesmas">Puskesmas</option>
                                    <option value="dinas">Dinas</option>
                                    <option value="rs">Rumah Sakit</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>

                        {{-- Input Telepon --}}
                        <div class="group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 group-focus-within:text-red-700">Nomor Telepon</label>
                            <div class="relative">
                                <i class="ph-bold ph-phone absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg group-focus-within:text-red-700"></i>
                                <input type="text" inputmode="numeric" name="telepon" x-model="form.telepon" @input="validateTelepon"
                                       placeholder="024xxxxxx (Min. 12)"
                                       class="w-full pl-12 pr-4 py-3.5 rounded-2xl border-gray-200 bg-gray-50 text-sm font-bold text-gray-900 focus:border-red-600 focus:ring-red-600 focus:bg-white shadow-sm transition-all">
                            </div>
                            <p x-show="form.telepon.length > 0 && form.telepon.length < 12" 
                               x-transition class="text-red-600 text-[9px] mt-2 font-black uppercase tracking-tighter">
                                <i class="ph-bold ph-warning-circle"></i> Minimal 12 digit!
                            </p>
                        </div>

                        {{-- Input Alamat --}}
                        <div class="md:col-span-2 group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 group-focus-within:text-red-700">Alamat Lengkap</label>
                            <div class="relative">
                                <i class="ph-bold ph-map-pin absolute left-4 top-4 text-gray-400 text-lg group-focus-within:text-red-700"></i>
                                <textarea name="alamat" x-model="form.alamat" rows="3" placeholder="Jl. Pandanaran No. 79..."
                                          class="w-full pl-12 pr-4 py-3.5 rounded-2xl border-gray-200 bg-gray-50 text-sm font-bold text-gray-900 focus:border-red-600 focus:ring-red-600 focus:bg-white shadow-sm transition-all"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- FOOTER BUTTONS --}}
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-6 border-t border-gray-100 mt-6 transform-gpu">
                        <button type="button" @click="showModal = false" 
                                class="px-8 py-3.5 rounded-2xl bg-gray-100 text-gray-600 font-black text-[10px] uppercase tracking-widest hover:bg-gray-200 transition-all active:scale-95">
                            Batal
                        </button>
                        <button type="submit" 
                                :disabled="form.telepon.length > 0 && form.telepon.length < 12"
                                :class="form.telepon.length > 0 && form.telepon.length < 12 ? 'opacity-40 grayscale cursor-not-allowed' : ''"
                                class="px-10 py-3.5 rounded-2xl bg-red-700 text-white font-black text-[10px] uppercase tracking-widest shadow-xl shadow-red-700/30 hover:bg-red-800 transition-all active:scale-95">
                            Simpan Data
                        </button>
                    </div>
                </form>
            </div>
            
        </x-popup>

    </div>
</x-app-layout>