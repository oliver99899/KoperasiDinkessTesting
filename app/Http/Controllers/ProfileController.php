<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Profile;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function showSetupForm() {
        return view('user.setup_profile');
    }

    public function storeSetup(Request $request) {
        $user = Auth::user();

        $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'nik'            => [
                'required', 
                'numeric', 
                'digits:16', 
                Rule::unique('profiles', 'nik')->ignore($user->id, 'user_id')
            ],
            'jenis_kelamin'  => 'required|in:L,P',
            'unit_kerja'     => 'required|string|max:100',
            'no_hp'          => 'required|numeric',
            'alamat'         => 'required|string',
            'nama_bank'      => 'required|string',
            'nomor_rekening' => 'required|string',
        ], [
            'nik.unique' => 'NIK ini sudah terdaftar di sistem.',
            'nik.digits' => 'NIK harus 16 digit.',
        ]);

        DB::transaction(function() use ($request, $user) {
            
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama_lengkap'   => $request->nama_lengkap,
                    'nik'            => $request->nik,
                    'jenis_kelamin'  => $request->jenis_kelamin,
                    'unit_kerja'     => $request->unit_kerja,
                    'no_hp'          => $request->no_hp,
                    'alamat'         => $request->alamat,
                    'nama_bank'      => $request->nama_bank,
                    'nomor_rekening' => $request->nomor_rekening,
                ]
            );

            $user->update(['status_akun' => 'active']);
        });

        return redirect()->route('dashboard')->with('success', 'Akun berhasil diaktifkan! Selamat datang.');
    }

    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile; 

        return view('user.settings', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        $request->validate([
            'no_hp'   => 'required|numeric',
            'alamat'  => 'required|string|max:500',
            'password'=> 'nullable|string|min:6|confirmed',
        ]);

        $profile->update([
            'no_hp'  => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => bcrypt($request->password)
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}