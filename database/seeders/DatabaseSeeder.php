<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UnitKerjaSeeder::class,
        ]);

        $admin = User::updateOrCreate(
            ['nip' => '00000000'],
            [
                'name' => 'Admin Utama',
                'email' => null,
                'password' => Hash::make('password'),
                'status_akun' => 'new',
            ]
        );
        $admin->syncRoles(['admin', 'anggota']);

        $verifikator = User::updateOrCreate(
            ['nip' => '22222222'],
            [
                'name' => 'Verifikator Utama',
                'email' => null,
                'password' => Hash::make('password'),
                'status_akun' => 'new',
            ]
        );
        $verifikator->syncRoles(['verifikator', 'anggota']);

        $anggota = User::updateOrCreate(
            ['nip' => '11111111'],
            [
                'name' => 'Anggota Koperasi',
                'email' => null,
                'password' => Hash::make('password'),
                'status_akun' => 'new',
            ]
        );
        $anggota->syncRoles(['anggota']);
    }
}