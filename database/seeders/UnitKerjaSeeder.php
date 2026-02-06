<?php

namespace Database\Seeders;

use App\Models\UnitKerja;
use Illuminate\Database\Seeder;

class UnitKerjaSeeder extends Seeder
{
    public function run(): void
    {
        UnitKerja::firstOrCreate(
            ['nama_unit' => 'Dinas Kesehatan Kota Semarang'],
            ['jenis' => 'dinas', 'alamat' => null, 'telepon' => null]
        );

        $puskesmas = [
            'Puskesmas Bulu Lor',
            'Puskesmas Telogosari Wetan',
            'Puskesmas Pandanaran',
            'Puskesmas Lamper Tengah',
            'Puskesmas Genuk',
            'Puskesmas Rowosari',
            'Puskesmas Poncol',
            'Puskesmas Ngaliyan',
            'Puskesmas Sekaran',
            'Puskesmas Ngemplak Simongan',
            'Puskesmas Srondol',
            'Puskesmas Bangetayu',
            'Puskesmas Candilama',
            'Puskesmas Gunungpati',
            'Puskesmas Gayamsari',
            'Puskesmas Ngesrep',
            'Puskesmas Kedungmundu',
            'Puskesmas Halmahera',
            'Puskesmas Lebdosari',
            'Puskesmas Manyaran',
            'Puskesmas Mangkang',
            'Puskesmas Bugangan',
            'Puskesmas Padangsari',
            'Puskesmas Pegandan',
            'Puskesmas Pudakpayung',
            'Puskesmas Tambakaji',
            'Puskesmas Karangmalang',
            'Puskesmas Purwoyoso',
            'Puskesmas Telogosari Kulon',
            'Puskesmas Kagok',
            'Puskesmas Karangdoro',
            'Puskesmas Miroto',
            'Puskesmas Mijen',
            'Puskesmas Bandarharjo',
            'Puskesmas Karangayu',
            'Puskesmas Krobokan',
            'Puskesmas Karanganyar',
        ];

        foreach ($puskesmas as $nama) {
            UnitKerja::firstOrCreate(
                ['nama_unit' => $nama],
                ['jenis' => 'puskesmas', 'alamat' => null, 'telepon' => null]
            );
        }
    }
}
