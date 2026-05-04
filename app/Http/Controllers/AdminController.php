<?php

namespace App\Http\Controllers;

use App\Models\RekeningKoperasi;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $totalUser = User::count();
        $userActive = User::where('status_akun', 'active')->count();
        $userNew = User::where('status_akun', 'new')->count();

        $users = User::with(['profile.unitKerja', 'roles'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($u) {
                $u->role = $u->roles->pluck('name')->contains('admin')
                    ? 'admin'
                    : ($u->roles->pluck('name')->contains('verifikator') ? 'verifikator' : 'anggota');
                return $u;
            });

        $unitKerja = UnitKerja::orderBy('nama_unit', 'asc')->get();

        return view('admin.dashboard', compact('totalUser', 'userActive', 'userNew', 'users', 'unitKerja'));
    }

    public function users(Request $request)
    {
        $query = User::with(['profile.unitKerja', 'roles']);

        $unitKerja = UnitKerja::orderBy('nama_unit', 'asc')->get();

        if ($request->filled('search')) {
            $search = (string) $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%")
                    ->orWhereHas('profile', function ($p) use ($search) {
                        $p->where('nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('nik', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('status_akun')) {
            $query->where('status_akun', $request->status_akun);
        }

        if ($request->filled('unit_kerja_id')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('unit_kerja_id', $request->unit_kerja_id);
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        $users->getCollection()->transform(function ($u) {
            $u->role = $u->roles->pluck('name')->contains('admin')
                ? 'admin'
                : ($u->roles->pluck('name')->contains('verifikator') ? 'verifikator' : 'anggota');
            return $u;
        });

        return view('admin.users.index', compact('users', 'unitKerja'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'nip' => ['required', 'numeric', 'unique:users,nip', 'digits_between:8,20'],
            'role' => ['required', 'in:admin,verifikator,anggota'],
            'unit_kerja_id' => ['required', 'exists:unit_kerja,id'],
            'password' => ['required', Password::defaults()],
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => 'Menunggu Aktivasi',
                'nip' => $request->nip,
                'password' => Hash::make($request->password),
                'status_akun' => 'new',
            ]);

            if ($request->role === 'admin') {
                $user->assignRole(['anggota', 'admin']);
            } elseif ($request->role === 'verifikator') {
                $user->assignRole(['anggota', 'verifikator']);
            } else {
                $user->assignRole(['anggota']);
            }

            $user->profile()->create([
                'nama_lengkap' => 'Menunggu Aktivasi',
                'unit_kerja_id' => $request->unit_kerja_id,
                'activated_at' => null,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Akun berhasil didaftarkan. Instruksikan pengguna untuk melakukan aktivasi profil.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('USER_STORE_FAILED: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat menyimpan data.')->withInput();
        }
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::with('profile')->findOrFail($id);

        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'numeric', Rule::unique('users', 'nip')->ignore($user->id)],
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', 'in:admin,verifikator,anggota'],
            'status_akun' => ['required', 'in:active,new,retired,blocked'],
            'unit_kerja_id' => ['required', 'exists:unit_kerja,id'],
            'password' => ['nullable', 'confirmed', Password::min(8)], 
        ]);

        DB::beginTransaction();

        try {
            $userData = [
                'name' => $request->nama_lengkap,
                'nip' => $request->nip,
                'status_akun' => $request->status_akun,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            if ($request->role === 'admin') {
                $user->syncRoles(['anggota', 'admin']);
            } elseif ($request->role === 'verifikator') {
                $user->syncRoles(['anggota', 'verifikator']);
            } else {
                $user->syncRoles(['anggota']);
            }

            $user->profile()->update([
                'nama_lengkap' => $request->nama_lengkap,
                'unit_kerja_id' => $request->unit_kerja_id,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Data profil dan akses pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('USER_UPDATE_FAILED: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal memperbarui data pengguna.');
        }
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Prosedur keamanan: Anda tidak dapat menghapus akun Anda sendiri.');
        }

        DB::beginTransaction();

        try {
            if ($user->profile) {
                $user->profile()->delete();
            }

            $user->delete();

            DB::commit();

            return back()->with('success', 'Seluruh data pengguna telah dipindahkan ke arsip (Soft Delete).');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('USER_DELETE_FAILED: ' . $e->getMessage());

            return back()->with('error', 'Gagal memproses penghapusan pengguna.');
        }
    }

    public function impersonate($id)
    {
        $targetUser = User::findOrFail($id);
        $originalAdmin = Auth::user();

        if ($targetUser->id === $originalAdmin->id) {
            return back()->with('error', 'Anda sudah berada di akun ini.');
        }

        if ($targetUser->hasRole('admin')) {
            return back()->with('error', 'Akses ditolak: Fitur impersonasi tidak diizinkan antar sesama Administrator.');
        }

        Log::critical('SECURITY_AUDIT: Admin ID ' . $originalAdmin->id . ' menginisiasi Impersonasi ke User ID ' . $targetUser->id . ' pada ' . now());

        session()->put('admin_impersonator_id', $originalAdmin->id);

        Auth::login($targetUser);
        session()->regenerate();

        $targetUser->update([
            'active_session_id' => session()->getId(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Mode Penyamaran Aktif.');
    }

    public function stopImpersonate()
    {
        if (!session()->has('admin_impersonator_id')) {
            return redirect()->route('dashboard');
        }

        $adminId = session()->pull('admin_impersonator_id');
        $originalAdmin = User::find($adminId);

        if (!$originalAdmin || !$originalAdmin->hasRole('admin')) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Sesi otoritas admin tidak valid.');
        }

        Auth::login($originalAdmin);
        session()->regenerate();

        $originalAdmin->update([
            'active_session_id' => session()->getId(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Mode penyamaran dihentikan.');
    }

        public function indexRekening()
    {
        $rekening = RekeningKoperasi::orderByDesc('is_active')->orderBy('nama_bank')->get();
        return view('admin.rekening.index', compact('rekening'));
    }

    public function storeRekening(Request $request)
    {
        $request->validate([
            'nama_bank'      => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50',
            'atas_nama'      => 'required|string|max:255',
            'keterangan'     => 'nullable|string|max:255',
        ]);

        RekeningKoperasi::create([
            'nama_bank'      => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama'      => $request->atas_nama,
            'is_active'      => false,
            'keterangan'     => $request->keterangan,
            'updated_by'     => Auth::id(),
        ]);

        return back()->with('success', 'Rekening baru berhasil ditambahkan.');
    }

    public function updateRekening(Request $request, $id)
    {
        $request->validate([
            'nama_bank'      => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50',
            'atas_nama'      => 'required|string|max:255',
            'keterangan'     => 'nullable|string|max:255',
            'is_active'      => 'boolean',
        ]);

        $rekening = RekeningKoperasi::findOrFail($id);

        // Kalau rekening ini diaktifkan, nonaktifkan yang lain
        if ($request->boolean('is_active')) {
            RekeningKoperasi::where('id', '!=', $id)->update(['is_active' => false]);
        }

        $rekening->update([
            'nama_bank'      => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama'      => $request->atas_nama,
            'is_active'      => $request->boolean('is_active'),
            'keterangan'     => $request->keterangan,
            'updated_by'     => Auth::id(),
        ]);

        return back()->with('success', 'Data rekening berhasil diperbarui.');
    }

    public function destroyRekening($id)
    {
        $rekening = RekeningKoperasi::findOrFail($id);

        if ($rekening->is_active) {
            return back()->with('error', 'Rekening aktif tidak dapat dihapus. Aktifkan rekening lain terlebih dahulu.');
        }

        $rekening->delete();
        return back()->with('success', 'Rekening berhasil dihapus.');
    }
}
