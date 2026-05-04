<?php

namespace Database\Seeders;

use App\Models\RekeningKoperasi;
use Illuminate\Database\Seeder;

class RekeningKoperasiSeeder extends Seeder
{
    public function run(): void
    {
        RekeningKoperasi::firstOrCreate(
            ['nomor_rekening' => '502301000456539'],
            [
                'nama_bank'      => 'Bank Jateng',
                'atas_nama'      => 'Koperasi Dinkes Semarang',
                'is_active'      => true,
                'keterangan'     => 'Rekening utama koperasi',
            ]
        );
    }
}