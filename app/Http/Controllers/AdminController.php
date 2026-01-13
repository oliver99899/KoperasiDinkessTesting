<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Simpanan;

class AdminController extends Controller
{
    public function index()
    {
        $totalAset    = Simpanan::sum('jumlah');
        $totalAnggota = User::where('role', 'user')->where('status_akun', 'active')->count();
        $users        = User::with('profile')->where('role', 'user')->latest()->take(5)->get();

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
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,verifikator,user',
        ]);

        $user = User::create([
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'status_akun' => 'active',
        ]);

        $user->profile()->create([
            'nama_lengkap'   => $request->nama,
            'nik'            => time(),
            'unit_kerja'     => '-',
            'no_hp'          => '-',
            'alamat'         => '-',
            'jenis_kelamin'  => 'L',
            'nama_bank'      => '-',
            'nomor_rekening' => '-',
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan!');
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
            'email'         => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'          => 'required|in:admin,verifikator,user',
            'password'      => 'nullable|min:6',
            'nama'          => 'required|string|max:255',
            'nik'           => 'required|numeric',
            'jenis_kelamin' => 'required|in:L,P',
            'unit_kerja'    => 'required|string',
            'no_hp'         => 'required|string',
            'alamat'        => 'required|string',
            'nama_bank'     => 'nullable|string',
            'no_rekening'   => 'nullable|string',
        ]);

        $userData = [
            'email' => $request->email,
            'role'  => $request->role,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        $user->profile()->update([
            'nama_lengkap'   => $request->nama,
            'nik'            => $request->nik,
            'jenis_kelamin'  => $request->jenis_kelamin,
            'unit_kerja'     => $request->unit_kerja,
            'no_hp'          => $request->no_hp,
            'alamat'         => $request->alamat,
            'nama_bank'      => $request->nama_bank,
            'nomor_rekening' => $request->no_rekening,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Data profil pengguna berhasil diperbarui!');
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id == auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }
        
        $user->delete();
        
        return back()->with('success', 'User berhasil dihapus.');
    }
}