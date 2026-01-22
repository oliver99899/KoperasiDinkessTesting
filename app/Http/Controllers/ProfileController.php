<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function showSetupForm()
    {
        return view('user.setup_profile');
    }

    public function storeSetup(Request $request)
    {
        $user = Auth::user();
        $isUser = $user->role === 'user';

        $rules = [
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
            'password'       => $user->status_akun === 'new' ? ['required', 'confirmed', Password::defaults()] : 'nullable',
        ];

        if ($isUser) {
            $rules['nama_bank'] = 'required|string';
            $rules['nomor_rekening'] = 'required|string';
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            $profileData = [
                'nama_lengkap'   => $request->nama_lengkap,
                'nik'            => $request->nik,
                'jenis_kelamin'  => $request->jenis_kelamin,
                'unit_kerja'     => $request->unit_kerja,
                'no_hp'          => $request->no_hp,
                'alamat'         => $request->alamat,
            ];

            if ($isUser) {
                $profileData['nama_bank'] = $request->nama_bank;
                $profileData['nomor_rekening'] = $request->nomor_rekening;
            }

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );

            if ($user->status_akun === 'new') {
                $user->update([
                    'password' => Hash::make($request->password),
                    'status_akun' => 'active',
                    'activation_token' => null,
                    'email_verified_at' => now(),
                ]);
            }

            DB::commit();

            if ($user->role === 'verifikator') {
                return redirect()->route('verifikator.dashboard')->with('success', 'Profil verifikator berhasil disimpan.');
            }

            return redirect()->route('dashboard')->with('success', 'Profil berhasil disimpan dan akun telah aktif.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data. Silakan coba lagi.');
        }
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
            'no_hp'            => 'required|numeric',
            'alamat'           => 'required|string|max:500',
            'current_password' => 'nullable|required_with:password|current_password',
            'password'         => ['nullable', 'confirmed', Password::defaults()],
        ]);

        DB::beginTransaction();

        try {
            $profile->update([
                'no_hp'  => $request->no_hp,
                'alamat' => $request->alamat,
            ]);

            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            DB::commit();

            return back()->with('success', 'Pengaturan akun berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui profil.');
        }
    }
}