<x-app-layout title="Verifikasi Pembayaran">
    <div class="mb-6 flex flex-col xl:flex-row xl:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Verifikasi Pembayaran</h1>
            <p class="text-sm text-gray-500 font-medium">Kelola pembayaran angsuran anggota baik manual maupun transfer.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto" x-data="{ openInput: false }">
            <button @click="openInput = true" 
                    class="inline-flex items-center justify-center gap-2 bg-red-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-red-800 transition-all shadow-lg shadow-red-700/20 active:scale-95">
                <i class="ph-bold ph-plus-circle text-lg"></i>
                Input Pembayaran Manual
            </button>

            <form method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative w-full sm:w-64">
                    <input type="text" name="search" placeholder="Cari Nama / NIP..." value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-300 text-sm focus:border-red-600 focus:ring-red-600 shadow-sm">
                    <i class="ph-bold ph-magnifying-glass absolute left-3 top-3 text-gray-400"></i>
                </div>
            </form>

            <x-popup name="openInput" title="Input Pembayaran Angsuran" icon="<i class='ph-bold ph-wallet'></i>" maxWidth="max-w-lg">
                <div class="p-6">
                    <form action="{{ route('verifikator.angsuran.store') }}" method="POST" class="space-y-5" 
                          x-data="{ 
                              selectedLoan: '', 
                              tagihan: 0,
                              sisa: 0,
                              displayAmount: '',
                              loans: {{ $activeLoans->toJson() }},
                              updateInfo() {
                                  let loan = this.loans.find(l => l.id == this.selectedLoan);
                                  if (loan) {
                                      this.tagihan = Math.round(loan.tagihan_bulanan);
                                      this.sisa = Math.round(loan.sisa_pinjaman);
                                      this.displayAmount = this.formatNumber(this.tagihan);
                                  } else {
                                      this.tagihan = 0; this.sisa = 0; this.displayAmount = '';
                                  }
                              },
                              formatNumber(num) {
                                  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                              },
                              formatRupiah() {
                                  let value = this.displayAmount.replace(/[^0-9]/g, '');
                                  this.displayAmount = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                              }
                          }">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 tracking-wider">Pilih Pinjaman Anggota</label>
                            <select name="pinjaman_id" x-model="selectedLoan" @change="updateInfo()" required 
                                    class="w-full rounded-xl border-gray-300 py-2.5 px-3 text-sm font-semibold focus:border-red-600 focus:ring-red-600">
                                <option value="">-- Cari Nama / Nomor Pinjaman --</option>
                                <template x-for="loan in loans" :key="loan.id">
                                    <option :value="loan.id" x-text="loan.label_display"></option>
                                </template>
                            </select>
                        </div>

                        <div x-show="selectedLoan" class="bg-red-50 border border-red-100 rounded-2xl p-4 flex justify-between items-center shadow-sm" x-transition>
                            <div>
                                <p class="text-[10px] text-red-600 font-bold uppercase mb-1">Estimasi Tagihan</p>
                                <p class="text-xl font-black text-gray-900">
                                    Rp <span x-text="Number(tagihan).toLocaleString('id-ID')"></span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-gray-500 font-bold uppercase mb-1 tracking-tighter">Sisa Pinjaman</p>
                                <p class="text-sm font-bold text-gray-700">
                                    Rp <span x-text="Number(sisa).toLocaleString('id-ID')"></span>
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Metode Bayar</label>
                                <select name="metode_bayar" required class="w-full rounded-xl border-gray-300 py-2.5 px-3 text-sm focus:border-red-600 font-medium">
                                    <option value="potong_gaji">Potong Gaji</option>
                                    <option value="tunai">Setor Tunai</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Tanggal Input</label>
                                <input type="date" name="tanggal_potong" value="{{ date('Y-m-d') }}" required 
                                       class="w-full rounded-xl border-gray-300 py-2.5 text-sm font-medium">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Nominal Bayar (Final)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold text-sm">Rp</span>
                                <input type="hidden" name="jumlah_bayar" :value="displayAmount.replace(/\./g, '')">
                                <input type="text" x-model="displayAmount" @input="formatRupiah()" required 
                                       class="w-full pl-10 rounded-xl border-gray-300 py-3.5 text-lg font-black text-red-700 focus:ring-red-600">
                            </div>
                            <p class="text-[9px] text-gray-400 mt-2 italic font-medium">*Nominal akan otomatis memotong sisa pinjaman anggota.</p>
                        </div>

                        <button type="submit" class="w-full bg-red-700 text-white font-black py-4 rounded-xl hover:bg-red-800 shadow-lg shadow-red-700/20 active:scale-[0.98] transition-all">
                            SIMPAN PEMBAYARAN
                        </button>
                    </form>
                </div>
            </x-popup>
        </div>
    </div>

    @if(isset($antreanTransfer) && $antreanTransfer->count() > 0)
    <div class="mb-8 bg-amber-50 border border-amber-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 bg-amber-100/50 border-b border-amber-200 flex items-center justify-between">
            <h3 class="text-sm font-black text-amber-800 uppercase tracking-widest flex items-center gap-2">
                <i class="ph-bold ph-warning-circle text-lg"></i>
                {{ $antreanTransfer->count() }} Pengajuan Transfer Menunggu Verifikasi
            </h3>
            <a href="{{ route('verifikator.pembayaran.transfer.index') }}" class="text-xs font-bold text-amber-900 hover:underline">Lihat Semua <i class="ph-bold ph-arrow-right"></i></a>
        </div>
        <div class="divide-y divide-amber-100">
            @foreach($antreanTransfer->take(5) as $at)
            <div class="px-6 py-4 flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-gray-900">{{ $at->pinjaman->user->profile->nama_lengkap ?? 'Anggota' }}</p>
                    <p class="text-[10px] text-amber-700 font-medium tracking-tight">Pinjaman #{{ $at->pinjaman->nomor_pinjaman }} • Angsuran Ke-{{ $at->angsuran_ke }}</p>
                </div>
                <a href="{{ route('verifikator.pembayaran.transfer.show', $at->id) }}" class="bg-amber-600 text-white px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider hover:bg-amber-700 transition-colors">
                    Periksa Bukti
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-widest">Riwayat Pembayaran Berhasil</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-gray-900 font-bold uppercase text-[10px] tracking-widest border-b">
                    <tr>
                        <th class="px-6 py-4">Anggota</th>
                        <th class="px-6 py-4">Informasi Pinjaman</th>
                        <th class="px-6 py-4 text-right">Nominal Bayar</th>
                        <th class="px-6 py-4 text-center">Metode / Tgl</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Input Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($angsuran as $a)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $a->pinjaman->user->profile->nama_lengkap ?? 'Anggota' }}</div>
                            <div class="text-[10px] font-mono text-gray-400 uppercase">NIP: {{ $a->pinjaman->user->nip }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-bold text-red-700">#{{ $a->pinjaman->nomor_pinjaman }}</div>
                            <div class="text-[10px] text-gray-500 uppercase font-medium">Cicilan Ke-{{ $a->angsuran_ke }}</div>
                        </td>
                        <td class="px-6 py-4 text-right font-black text-gray-900 whitespace-nowrap">
                            Rp {{ number_format($a->jumlah_bayar, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col items-center">
                                <span class="px-2 py-0.5 bg-gray-100 border rounded text-[9px] font-bold uppercase text-gray-600 mb-1">
                                    {{ str_replace('_', ' ', $a->metode_bayar) }}
                                </span>
                                <span class="text-[10px] text-gray-400 font-medium">{{ $a->tanggal_potong->format('d M Y') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($a->verified_at)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-green-50 text-green-700 border border-green-100 uppercase">
                                    <i class="ph-fill ph-check-circle"></i> Berhasil
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-yellow-50 text-yellow-700 border border-yellow-100 uppercase animate-pulse">
                                    <i class="ph-fill ph-clock"></i> Diproses
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="text-[10px] font-bold text-gray-600">{{ $a->creator->profile->nama_lengkap ?? 'System' }}</div>
                            <div class="text-[9px] text-gray-400 font-mono tracking-tighter">{{ $a->creator->nip ?? '-' }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-gray-400 italic">Belum ada riwayat angsuran yang tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($angsuran->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $angsuran->links() }}
        </div>
        @endif
    </div>
</x-app-layout>