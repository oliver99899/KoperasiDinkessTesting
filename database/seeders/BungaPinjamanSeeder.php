<?php

namespace Database\Seeders;

use App\Models\BungaPinjaman;
use Illuminate\Database\Seeder;

class BungaPinjamanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['tenor_bulan' => 3,  'persen' => 1.00, 'keterangan' => 'Bunga pinjaman tenor 3 bulan'],
            ['tenor_bulan' => 6,  'persen' => 1.00, 'keterangan' => 'Bunga pinjaman tenor 6 bulan'],
            ['tenor_bulan' => 12, 'persen' => 1.25, 'keterangan' => 'Bunga pinjaman tenor 12 bulan'],
            ['tenor_bulan' => 24, 'persen' => 1.50, 'keterangan' => 'Bunga pinjaman tenor 24 bulan'],
        ];

        foreach ($data as $item) {
            BungaPinjaman::firstOrCreate(
                ['tenor_bulan' => $item['tenor_bulan']],
                $item
            );
        }
    }
}