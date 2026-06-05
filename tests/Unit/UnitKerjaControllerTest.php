<?php

namespace Tests\Unit;

use App\Http\Controllers\UnitKerjaController;
use App\Models\UnitKerja;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UnitKerjaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_menolak_duplikasi_nama_unit_kerja(): void
    {
        UnitKerja::create([
            'nama_unit' => 'Dinas Kesehatan',
            'jenis' => 'dinas',
            'alamat' => 'Semarang',
            'telepon' => '081234567890',
        ]);

        $request = Request::create('/admin/unit-kerja', 'POST', [
            'nama_unit' => 'Dinas Kesehatan',
            'jenis' => 'dinas',
            'alamat' => 'Semarang',
            'telepon' => '081234567890',
        ]);

        $controller = new UnitKerjaController();

        try {
            $controller->store($request);
            $this->fail('ValidationException tidak terjadi saat nama unit kerja duplikat.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('nama_unit', $exception->errors());
        }

        $this->assertDatabaseCount('unit_kerja', 1);
    }
}