<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'email' => 'admin@dinkes.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status_akun' => 'active'
        ]);

        User::create([
            'email' => 'staff@dinkes.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status_akun' => 'new' 
        ]);
        
        Simpanan::create([
            'user_id' => 2, 
            'jenis_simpanan' => 'pokok',
            'jumlah' => 100000, 
            'tanggal_bayar' => now()->subMonths(2), 
            'keterangan' => 'Simpanan Pokok Awal Masuk',
        ]);

        Simpanan::create([
            'user_id' => 2,
            'jenis_simpanan' => 'wajib',
            'jumlah' => 50000, 
            'tanggal_bayar' => now()->subMonth(),
            'keterangan' => 'Potongan Gaji Bulan Lalu',
        ]);

        Simpanan::create([
            'user_id' => 2,
            'jenis_simpanan' => 'wajib',
            'jumlah' => 50000, 
            'tanggal_bayar' => now(),
            'keterangan' => 'Potongan Gaji Bulan Ini',
        ]);

        Pinjaman::create([
            'user_id' => 2,
            'jumlah_pengajuan' => 5000000,
            'durasi_bulan' => 10,
            'alasan' => 'Renovasi Rumah',
            'status' => 'approved',
            'tanggal_pengajuan' => now()->subMonths(2),
            'tanggal_disetujui' => now()->subMonths(2),
            'sisa_tagihan' => 4000000,
        ]);
    }
}