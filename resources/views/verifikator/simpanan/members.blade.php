<x-app-layout title="Input Simpanan Anggota">

    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Data Anggota</h1>
            <p class="text-sm text-gray-500">Pilih anggota untuk input simpanan manual atau melihat riwayat.</p>
        </div>
        <form method="GET" class="relative w-full sm:w-auto">
            <input type="text" name="search" placeholder="Cari Nama / NIP..." value="{{ request('search') }}"
                   class="pl-10 pr-4 py-2.5 rounded-xl border-gray-300 text-sm focus:border-red-600 focus:ring-red-600 w-full sm:w-72 shadow-sm transition-shadow">
            <i class="ph-bold ph-magnifying-glass absolute left-3 top-3 text-gray-400"></i>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden" x-data="simpananHandler()">

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-900 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Identitas Anggota</th>
                        <th class="px-6 py-4 text-center">Unit Kerja</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($members as $m)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center font-bold border border-red-100 shrink-0 overflow-hidden">
                                    @if($m->profile && $m->profile->foto_profil_path)
                                        <img src="{{ asset('storage/'.$m->profile->foto_profil_path) }}" class="h-full w-full object-cover">
                                    @else
                                        {{ substr($m->profile->nama_lengkap ?? $m->name, 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">{{ $m->profile->nama_lengkap ?? $m->name }}</div>
                                    <div class="text-xs text-gray-500 font-mono">NIP: {{ $m->nip }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-md text-xs font-bold border border-gray-200">
                                {{ $m->profile->unitKerja->nama_unit ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button type="button" 
                                        @click="openInputModal({{ $m->id }}, '{{ $m->profile->nama_lengkap ?? $m->name }}')"
                                        class="inline-flex items-center gap-1.5 bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-green-700 shadow-sm transition-all active:scale-95">
                                    <i class="ph-bold ph-plus"></i> Input
                                </button>
                                
                                <button type="button" 
                                        @click="fetchHistory({{ $m->id }}, '{{ $m->profile->nama_lengkap ?? $m->name }}', '{{ $m->nip }}')"
                                        class="inline-flex items-center gap-1.5 bg-white border border-gray-300 text-gray-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-gray-50 hover:text-red-700 transition-all active:scale-95">
                                    <i class="ph-bold ph-clock-counter-clockwise"></i> History
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                            Data anggota tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $members->links() }}
        </div>

        <x-popup name="openInput" title="Input Simpanan" icon="<i class='ph-bold ph-money'></i>" maxWidth="max-w-lg">
            <div class="p-6">
                <form action="{{ route('verifikator.simpanan.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="user_id" :value="selectedUser">

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Nama Anggota</label>
                        <div class="relative">
                            <i class="ph-fill ph-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" :value="selectedName" disabled 
                                   class="w-full bg-gray-50 border-gray-200 rounded-xl text-gray-600 font-bold pl-9 pr-4 py-2.5 cursor-not-allowed text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Nominal (Rp) <span class="text-red-600">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold text-sm">Rp</span>
                            <input type="hidden" name="jumlah" :value="displayJumlah.replace(/\./g, '')">
                            <input type="text" x-model="displayJumlah" @input="formatRupiah()" required placeholder="0" inputmode="numeric"
                                   class="w-full pl-10 rounded-xl border-gray-300 py-3 px-4 text-lg font-medium text-gray-900 focus:border-red-600 focus:ring-red-600">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Tanggal Potong <span class="text-red-600">*</span></label>
                        <input type="date" name="tanggal_potong" value="{{ date('Y-m-d') }}" required
                               class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-sm focus:border-red-600 focus:ring-red-600">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="2" placeholder="Contoh: Iuran Wajib Januari 2026..."
                                  class="w-full rounded-xl border-gray-300 py-2.5 px-4 text-sm focus:border-red-600 focus:ring-red-600"></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-red-700 text-white font-bold py-3.5 rounded-xl hover:bg-red-800 transition-all shadow-lg shadow-red-700/20 active:scale-[0.98]">
                            <i class="ph-bold ph-floppy-disk mr-2"></i> SIMPAN DATA
                        </button>
                    </div>
                </form>
            </div>
        </x-popup>

        <x-popup name="openHistory" title="Riwayat Simpanan" icon="<i class='ph-bold ph-clock-counter-clockwise'></i>" maxWidth="max-w-4xl">
            <div class="flex flex-col h-full bg-white">
                <div class="px-6 py-4 bg-white border-b border-gray-100 flex justify-between items-center sticky top-0 z-20">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 bg-red-50 text-red-600 rounded-full flex items-center justify-center">
                            <i class="ph-fill ph-user text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900" x-text="selectedName"></p>
                            <p class="text-xs text-gray-500 font-mono">NIP: <span x-text="selectedNip"></span></p>
                        </div>
                    </div>
                    <div class="text-right">
                         <p class="text-[10px] text-gray-400 font-bold uppercase">Filter Tahun</p>
                         <select x-model="selectedYear" @change="fetchHistory(selectedUser, selectedName, selectedNip, selectedYear)" 
                                class="bg-gray-50 text-gray-900 text-xs font-bold border-gray-200 rounded-lg focus:ring-red-600 focus:border-red-600 py-2 pl-3 pr-8 shadow-sm">
                            @for ($i = date('Y'); $i >= 2020; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                
                <div class="flex-1 overflow-y-auto min-h-[450px]" x-html="historyContent"></div>
            </div>
        </x-popup>

    </div>

    <script>
        function simpananHandler() {
            return {
                openInput: false,
                openHistory: false,
                selectedUser: null,
                selectedName: '',
                selectedNip: '',
                displayJumlah: '',
                historyContent: '',
                selectedYear: new Date().getFullYear(),

                formatRupiah() {
                    let value = this.displayJumlah.replace(/[^0-9]/g, '');
                    this.displayJumlah = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                },

                openInputModal(id, name) {
                    this.openInput = true;
                    this.selectedUser = id;
                    this.selectedName = name;
                    this.displayJumlah = '';
                },

                fetchHistory(id, name, nip, year = null) {
                    this.selectedUser = id; 
                    this.selectedName = name;
                    this.selectedNip = nip;
                    
                    if(year) this.selectedYear = year; 
                    this.openHistory = true;
                    
                    this.historyContent = `<div class="h-64 w-full flex flex-col items-center justify-center text-gray-400">
                                             <i class="ph-bold ph-spinner animate-spin text-4xl mb-3 text-red-600"></i>
                                             <span class="text-sm font-medium">Sinkronisasi data...</span>
                                           </div>`;
                    
                    fetch(`/verifikator/simpanan/${id}/history-data?year=${this.selectedYear}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(response => {
                        if (response.redirected || response.status === 401 || response.status === 419) {
                            window.location.reload();
                            return Promise.reject('Session expired');
                        }
                        return response.text();
                    })
                    .then(html => {
                        this.historyContent = html;
                    })
                    .catch(error => {
                        if (error !== 'Session expired') {
                            this.historyContent = `<div class="h-64 flex flex-col items-center justify-center text-red-500">
                                                    <i class="ph-fill ph-warning-circle text-3xl mb-2"></i>
                                                    <span class="font-bold">Gagal memuat data riwayat.</span>
                                                   </div>`;
                        }
                    });
                },

                deleteHistory(id) {
                    if (!confirm('Apakah Anda yakin ingin membatalkan transaksi simpanan ini? Data akan dihapus secara permanen.')) return;

                    fetch(`/verifikator/simpanan/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.fetchHistory(this.selectedUser, this.selectedName, this.selectedNip, this.selectedYear);
                        } else {
                            alert(data.message || 'Gagal menghapus data.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan sistem.');
                    });
                }
            }
        }
    </script>
</x-app-layout>