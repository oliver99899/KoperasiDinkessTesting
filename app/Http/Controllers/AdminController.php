<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Simpanan;
use App\Mail\UndanganAktivasiAkun;

class AdminController extends Controller
{
    public function index()
    {
        $totalAset = Simpanan::sum('jumlah');
        $totalAnggota = User::where('role', 'user')->where('status', 'active')->count();
        $users = User::with('profile')->where('role', 'user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('totalAnggota', 'totalAset', 'users'));
    }

    public function users(Request $request)
    {
        $query = User::with('profile')->where('role', '!=', 'admin');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhereHas('profile', function($p) use ($search) {
                      $p->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%");
                  });
            });
        }

        $users = $query->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,verifikator,user',
        ]);

        DB::beginTransaction();

        try {
            $token = Str::random(60);
            
            $user = User::create([
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make(Str::random(32)),
                'status' => 'active',
                'status_akun' => 'new',
                'activation_token' => $token,
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data ke database. Silakan coba lagi.');
        }

        try {
            Mail::to($user->email)->send(new UndanganAktivasiAkun($user));

            return redirect()->route('admin.users.index')
                ->with('success', 'Undangan aktivasi berhasil dikirim ke email pengguna.');

        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('warning', 'Pengguna berhasil dibuat, namun email undangan gagal terkirim. Pesan Error: ' . $e->getMessage());
        }
    }

    public function editUser($id)
    {
        $user = User::with('profile')->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,verifikator,user',
            'status' => 'required|in:active,pending,inactive',
            'password' => 'nullable|min:8',
            'nama_lengkap' => 'nullable|string|max:255',
            'nik' => 'nullable|numeric',
            'unit_kerja' => 'nullable|string',
            'no_hp' => 'nullable|string',
            'alamat' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $userData = [
                'email' => $request->email,
                'role' => $request->role,
                'status' => $request->status,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            if ($user->profile) {
                $user->profile()->update([
                    'nama_lengkap' => $request->nama_lengkap,
                    'nik' => $request->nik,
                    'unit_kerja' => $request->unit_kerja,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                ]);
            } else {
                $user->profile()->create([
                    'nama_lengkap' => $request->nama_lengkap,
                    'nik' => $request->nik,
                    'unit_kerja' => $request->unit_kerja,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'jenis_kelamin' => 'L',
                ]);
            }

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data ke database.');
        }
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        DB::beginTransaction();

        try {
            $user->delete();
            DB::commit();
            return back()->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pengguna karena data terkait masih ada.');
        }
    }
}