<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use App\Models\Angsuran;
use App\Models\PembayaranAngsuran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class VerifikatorController extends Controller
{
    public function index()
    {
        $totalAnggota = User::role('anggota')->where('status_akun', 'active')->count();
        
        $totalSimpanan = Simpanan::whereNotNull('verified_at')->sum('jumlah');
        $totalPendapatanBunga = Angsuran::whereNotNull('verified_at')->sum('bunga_bayar');
        $totalAset = $totalSimpanan + $totalPendapatanBunga;      

        $pendingLoan = Pinjaman::whereIn('status', ['diajukan', 'verifikasi'])->count();

        $recentSimpanan = Simpanan::with(['user.profile', 'creator.profile'])
            ->whereHas('user') 
            ->whereNotNull('verified_at')
            ->latest('tanggal_potong')
            ->take(5)
            ->get();

        return view('verifikator.dashboard', compact(
            'totalAnggota', 
            'totalAset', 
            'totalSimpanan', 
            'totalPendapatanBunga', 
            'pendingLoan', 
            'recentSimpanan'
        ));
    }

    public function downloadLaporanTahunan(Request $request)
    {
        $tahun = $request->get('year', date('Y'));

        $simpanan = Simpanan::with('user.profile')
            ->whereHas('user')
            ->whereYear('tanggal_potong', $tahun)
            ->whereNotNull('verified_at')
            ->get()
            ->map(function ($item) {
                return (object)[
                    'tanggal' => $item->tanggal_potong,
                    'user' => $item->user,
                    'deskripsi' => 'Simpanan Periode ' . $item->periode,
                    'tipe' => 'masuk',
                    'nominal' => $item->jumlah
                ];
            });

        $pinjaman = Pinjaman::with('user.profile')
            ->whereHas('user')
            ->whereYear('tanggal_cair', $tahun)
            ->where('status', 'dicairkan')
            ->get()
            ->map(function ($item) {
                return (object)[
                    'tanggal' => $item->tanggal_cair,
                    'user' => $item->user,
                    'deskripsi' => 'Pencairan Pinjaman (#' . $item->nomor_pinjaman . ')',
                    'tipe' => 'keluar',
                    'nominal' => $item->jumlah_disetujui
                ];
            });

        $angsuran = Angsuran::with('pinjaman.user.profile')
            ->whereHas('pinjaman.user')
            ->whereYear('tanggal_potong', $tahun)
            ->whereNotNull('verified_at')
            ->get()
            ->map(function ($item) {
                return (object)[
                    'tanggal' => $item->tanggal_potong,
                    'user' => $item->pinjaman->user,
                    'deskripsi' => 'Angsuran ke-' . $item->angsuran_ke,
                    'tipe' => 'masuk',
                    'nominal' => $item->jumlah_bayar
                ];
            });

        $transaksi = $simpanan->concat($pinjaman)->concat($angsuran)->sortByDesc('tanggal');

        $akumulasi_simpanan = Simpanan::whereNotNull('verified_at')->sum('jumlah');
        $akumulasi_bunga = Angsuran::whereNotNull('verified_at')->sum('bunga_bayar');
        
        $data = [
            'tahun' => $tahun,
            'transaksi' => $transaksi,
            'total_masuk' => $simpanan->sum('nominal') + $angsuran->sum('nominal'),
            'total_keluar' => $pinjaman->sum('nominal'),
            'akumulasi_simpanan' => $akumulasi_simpanan,
            'akumulasi_bunga' => $akumulasi_bunga,
            'total_aset' => $akumulasi_simpanan + $akumulasi_bunga,
        ];

        $pdf = Pdf::loadView('pdf.laporan_tahunan', $data);
        return $pdf->download("Laporan_Keuangan_Koperasi_{$tahun}.pdf");
    }

    public function members(Request $request)
    {
        $query = User::role('anggota')
            ->with(['profile.unitKerja'])
            ->where('status_akun', 'active');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($subQ) use ($search) {
                        $subQ->where('nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('nik', 'like', "%{$search}%");
                    });
            });
        }

        $members = $query->paginate(15)->withQueryString();
        return view('verifikator.simpanan.members', compact('members'));
    }

    public function storeSimpanan(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jumlah' => 'required|numeric|min:1000',
            'tanggal_potong' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $periode = Carbon::parse($validated['tanggal_potong'])->format('Y-m');

        $exists = Simpanan::where('user_id', $validated['user_id'])
            ->where('periode', $periode)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Gagal: Anggota sudah memiliki catatan simpanan periode ' . $periode);
        }

        if ($validated['user_id'] == Auth::id()) {
            return back()->with('error', 'Akses Ditolak: Bendahara tidak bisa input data sendiri.');
        }

        try {
            DB::transaction(function () use ($validated, $periode) {
                Simpanan::create([
                    'user_id' => $validated['user_id'],
                    'periode' => $periode,
                    'jumlah' => $validated['jumlah'],
                    'tanggal_potong' => $validated['tanggal_potong'],
                    'keterangan' => $validated['keterangan'],
                    'created_by' => Auth::id(),
                    'verified_at' => now(),
                ]);
            });

            return back()->with('success', 'Data simpanan berhasil dicatat.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat menyimpan.');
        }
    }

    public function destroySimpanan($id)
    {
        try {
            $simpanan = Simpanan::withTrashed()->findOrFail($id);
            $simpanan->forceDelete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }

    public function getHistoryData(Request $request, $id)
    {
        $year = $request->get('year', date('Y'));
        $history = Simpanan::where('user_id', $id)
            ->whereYear('tanggal_potong', $year)
            ->orderByDesc('tanggal_potong')
            ->get();

        return view('verifikator.simpanan.history-table', compact('history'));
    }

    public function indexPinjaman(Request $request)
    {
        $query = Pinjaman::with(['user.profile'])
            ->whereHas('user') 
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($subQ) use ($search) {
                        $subQ->where('nama_lengkap', 'like', "%{$search}%");
                    });
            });
        }

        $pinjaman = $query->paginate(10)->withQueryString();
        return view('verifikator.pinjaman.index', compact('pinjaman'));
    }

    public function showPinjaman($id)
    {
        $pinjaman = Pinjaman::with(['user.profile', 'user.simpanan', 'user.pinjaman', 'angsuran'])->findOrFail($id);

        if ($pinjaman->cicilan_pokok_bulanan == 0) {
            $pinjaman->simulasi_pokok = round($pinjaman->jumlah_pengajuan / $pinjaman->durasi_bulan);
            $pinjaman->simulasi_bunga = round($pinjaman->jumlah_pengajuan * ($pinjaman->bunga_persen / 100));
        } else {
            $pinjaman->simulasi_pokok = $pinjaman->cicilan_pokok_bulanan;
            $pinjaman->simulasi_bunga = $pinjaman->cicilan_bunga_bulanan;
        }

        $totalSimpananUser = Simpanan::where('user_id', $pinjaman->user_id)->whereNotNull('verified_at')->sum('jumlah');
        $pinjamanAktifLain = Pinjaman::where('user_id', $pinjaman->user_id)->where('sisa_pinjaman', '>', 0)->where('id', '!=', $pinjaman->id)->count();

        return view('verifikator.pinjaman.show', compact('pinjaman', 'totalSimpananUser', 'pinjamanAktifLain'));
    }

    public function approvePinjaman($id)
    {
        return DB::transaction(function () use ($id) {
            $pinjaman = Pinjaman::lockForUpdate()->findOrFail($id);
            if ($pinjaman->user_id == Auth::id()) return back()->with('error', 'Otoritas ditolak.');

            $pokokDisetujui = (float) $pinjaman->jumlah_pengajuan;
            $tenor = (int) $pinjaman->durasi_bulan;
            $bungaPersen = (float) $pinjaman->bunga_persen;

            $cicilanPokok = round($pokokDisetujui / $tenor);
            $totalBunga = round($pokokDisetujui * ($bungaPersen / 100) * $tenor);
            $cicilanBunga = round($totalBunga / $tenor);
            $totalPinjaman = $pokokDisetujui + $totalBunga;

            $pinjaman->update([
                'status' => 'dicairkan',
                'jumlah_disetujui' => $pokokDisetujui,
                'cicilan_pokok_bulanan' => $cicilanPokok,
                'cicilan_bunga_bulanan' => $cicilanBunga,
                'total_bunga' => $totalBunga,
                'total_pinjaman' => $totalPinjaman,
                'sisa_pinjaman' => $totalPinjaman,
                'tanggal_cair' => now(),
                'jatuh_tempo_berikutnya' => now()->addMonth(),
                'decided_by' => Auth::id(),
                'decided_at' => now(),
            ]);

            return redirect()->route('verifikator.pinjaman.index')->with('success', 'Pinjaman dicairkan.');
        });
    }

    public function indexAngsuran(Request $request)
    {
        $activeLoans = Pinjaman::with(['user.profile'])
            ->whereHas('user')
            ->where('sisa_pinjaman', '>', 0)
            ->where('status', 'dicairkan')
            ->get()
            ->map(function($loan) {
                $loan->tagihan_bulanan = round($loan->cicilan_pokok_bulanan + $loan->cicilan_bunga_bulanan);
                $nama = $loan->user->profile->nama_lengkap ?? $loan->user->name ?? 'Akun Dihapus';
                $loan->label_display = $nama . ' (#' . $loan->nomor_pinjaman . ')';
                return $loan;
            });

        $antreanTransfer = PembayaranAngsuran::with(['pinjaman.user.profile'])->whereHas('pinjaman.user')->where('status', 'pending')->get();

        $query = Angsuran::with(['pinjaman.user.profile', 'creator.profile'])->whereHas('pinjaman.user')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pinjaman.user', function($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%")->orWhereHas('profile', function($sq) use ($search) { $sq->where('nama_lengkap', 'like', "%{$search}%"); });
            });
        }

        $angsuran = $query->paginate(15)->withQueryString();
        return view('verifikator.angsuran.index', compact('angsuran', 'activeLoans', 'antreanTransfer'));
    }

    public function storeAngsuran(Request $request)
    {
        $validated = $request->validate([
            'pinjaman_id' => 'required|exists:pinjaman,id',
            'jumlah_bayar' => 'required|numeric|min:1',
            'tanggal_potong' => 'required|date',
            'metode_bayar' => 'required|in:tunai,potong_gaji,transfer',
        ]);

        return DB::transaction(function () use ($validated) {
            $pinjaman = Pinjaman::lockForUpdate()->findOrFail($validated['pinjaman_id']);
            if ($pinjaman->user_id == Auth::id()) throw new \Exception('Otoritas Ditolak.');
            if ($validated['jumlah_bayar'] > $pinjaman->sisa_pinjaman) throw new \Exception('Nominal melebihi sisa tagihan.');

            $angsuranKe = Angsuran::where('pinjaman_id', $pinjaman->id)->max('angsuran_ke') + 1;

            Angsuran::create([
                'pinjaman_id' => $pinjaman->id,
                'angsuran_ke' => $angsuranKe,
                'pokok_bayar' => $pinjaman->cicilan_pokok_bulanan,
                'bunga_bayar' => $pinjaman->cicilan_bunga_bulanan,
                'jumlah_bayar' => $validated['jumlah_bayar'],
                'metode_bayar' => $validated['metode_bayar'],
                'tanggal_potong' => $validated['tanggal_potong'],
                'created_by' => Auth::id(),
                'verified_at' => now(),
            ]);

            $sisa = max(0, $pinjaman->sisa_pinjaman - $validated['jumlah_bayar']);
            $pinjaman->update([
                'sisa_pinjaman' => $sisa,
                'status' => ($sisa <= 0) ? 'lunas' : $pinjaman->status,
                'jatuh_tempo_berikutnya' => ($sisa > 0) ? Carbon::parse($pinjaman->jatuh_tempo_berikutnya)->addMonth() : null,
            ]);

            return back()->with('success', 'Angsuran berhasil dicatat.');
        });
    }

    public function rejectPinjaman(Request $request, $id)
    {
        $pinjaman = Pinjaman::findOrFail($id);
        if ($pinjaman->user_id == Auth::id()) return back()->with('error', 'Akses Ditolak.');

        $pinjaman->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
            'decided_by' => Auth::id(),
            'decided_at' => now(),
        ]);

        return redirect()->route('verifikator.pinjaman.index')->with('success', 'Pengajuan ditolak.');
    }
}