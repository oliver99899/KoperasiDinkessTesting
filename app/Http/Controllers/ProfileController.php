<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function showSetupForm()
    {
        $user = Auth::user();
        
        if ($user->status_akun !== 'new') {
            return $this->redirectBasedOnRole($user);
        }

        return view('user.setup_profile', compact('user'));
    }

    public function storeSetup(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email'          => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password'       => ['required', 'confirmed', Password::defaults()],
            'nama_lengkap'   => ['required', 'string', 'max:255'],
            'nik'            => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/', Rule::unique('profiles', 'nik')->ignore($user->profile->id ?? 0)],
            'no_hp'          => ['required', 'numeric', 'min_digits:10'],
            'alamat'         => ['required', 'string', 'max:500'],
            'tanggal_lahir'  => ['required', 'date', 'before:today'],
            'jenis_kelamin'  => ['required', 'in:L,P'],
            'nama_bank'      => ['required', 'string', 'max:50'],
            'nomor_rekening' => ['required', 'numeric'],
            'foto_profil'    => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ], [
            'foto_profil.max' => 'Ukuran gambar terlalu besar! Maksimal 5MB.',
        ]);

        DB::beginTransaction();

        try {
            $profileData = [
                'nama_lengkap'   => $request->nama_lengkap,
                'nik'            => $request->nik,
                'jenis_kelamin'  => $request->jenis_kelamin,
                'no_hp'          => $request->no_hp,
                'alamat'         => $request->alamat,
                'tanggal_lahir'  => $request->tanggal_lahir,
                'nama_bank'      => $request->nama_bank,
                'nomor_rekening' => $request->nomor_rekening,
                'activated_at'   => now(),
            ];

            if ($request->hasFile('foto_profil')) {
                $oldFoto = $user->profile->foto_profil_path ?? null;
                if ($oldFoto) {
                    Storage::disk('public')->delete($oldFoto);
                }
                
                $path = $request->file('foto_profil')->store('profile-photos', 'public');
                $profileData['foto_profil_path'] = $path;
                $profileData['foto_profil_updated_at'] = now();
            }

            $user->update([
                'name'              => $request->nama_lengkap, 
                'email'             => $request->email,
                'password'          => Hash::make($request->password),
                'status_akun'       => 'active',
                'email_verified_at' => now(),
            ]);

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );

            DB::commit();

            return $this->redirectBasedOnRole($user, 'Profil Anda berhasil dilengkapi. Selamat bergabung.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Kegagalan sistem: ' . $e->getMessage())->withInput();
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
            'email'            => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'no_hp'            => ['required', 'numeric', 'min_digits:10'],
            'alamat'           => ['required', 'string', 'max:500'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password'         => ['nullable', 'confirmed', Password::defaults()],
            'nama_bank'        => ['required', 'string', 'max:50'],
            'nomor_rekening'   => ['required', 'numeric'],
            'foto_profil'      => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ], [
            'foto_profil.max' => 'Ukuran gambar terlalu besar! Maksimal 5MB.',
        ]);

        DB::beginTransaction();

        try {
            $userUpdate = ['email' => $request->email];
            if ($request->filled('password')) {
                $userUpdate['password'] = Hash::make($request->password);
            }
            $user->update($userUpdate);

            $profileData = [
                'no_hp'          => $request->no_hp,
                'alamat'         => $request->alamat,
                'nama_bank'      => $request->nama_bank,
                'nomor_rekening' => $request->nomor_rekening,
            ];

            if ($request->hasFile('foto_profil')) {
                if ($profile->foto_profil_path) {
                    Storage::disk('public')->delete($profile->foto_profil_path);
                }
                $profileData['foto_profil_path'] = $request->file('foto_profil')->store('profile-photos', 'public');
                $profileData['foto_profil_updated_at'] = now();
            }

            $profile->update($profileData);

            DB::commit();
            return back()->with('success', 'Perubahan data profil berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui profil.');
        }
    }

    private function redirectBasedOnRole($user, $message = null)
    {
        $route = match (true) {
            $user->hasRole('admin')       => 'admin.dashboard',
            $user->hasRole('verifikator') => 'verifikator.dashboard',
            default                       => 'dashboard',
        };

        $redirect = redirect()->route($route);
        return $message ? $redirect->with('success', $message) : $redirect;
    }
}