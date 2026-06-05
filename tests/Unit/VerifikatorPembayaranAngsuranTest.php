<?php

namespace Tests\Unit;

use App\Http\Controllers\VerifikatorPembayaranAngsuranController;
use App\Models\Angsuran;
use App\Models\PembayaranAngsuran;
use App\Models\Pinjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class VerifikatorPembayaranAngsuranTest extends TestCase
{
    use RefreshDatabase;

    public function test_mengubah_status_pinjaman_menjadi_lunas_saat_sisa_saldo_nol(): void
    {
        $user = User::factory()->create([
            'nip' => '198001012026060001',
            'status_akun' => 'active',
        ]);

        $this->actingAs($user);

        $pinjaman = Pinjaman::create([
            'user_id' => $user->id,
            'nomor_pinjaman' => 'PJ-TEST-001',
            'jumlah_pengajuan' => 100000,
            'jumlah_disetujui' => 100000,
            'durasi_bulan' => 1,
            'bunga_persen' => 0,
            'cicilan_pokok_bulanan' => 100000,
            'cicilan_bunga_bulanan' => 0,
            'total_bunga' => 0,
            'total_pinjaman' => 100000,
            'sisa_pinjaman' => 100000,
            'alasan_pengajuan' => 'Kebutuhan pengujian unit testing.',
            'dokumen_syarat' => null,
            'status' => 'dicairkan',
            'tanggal_pengajuan' => now()->toDateString(),
            'tanggal_cair' => now()->toDateString(),
            'jatuh_tempo_berikutnya' => now()->addMonth()->toDateString(),
        ]);

        $pembayaran = PembayaranAngsuran::create([
            'pinjaman_id' => $pinjaman->id,
            'angsuran_ke' => 1,
            'tanggal_transfer' => now()->toDateString(),
            'bukti_path' => 'bukti/test-transfer.jpg',
            'status' => 'pending',
            'submitted_by' => $user->id,
        ]);

        $request = Request::create(
            "/verifikator/pembayaran/transfer/{$pembayaran->id}/approve",
            'POST'
        );

        $controller = new VerifikatorPembayaranAngsuranController();
        $controller->approve($request, $pembayaran->id);

        $pinjaman->refresh();
        $pembayaran->refresh();

        $this->assertEquals(0, (float) $pinjaman->sisa_pinjaman);
        $this->assertEquals('lunas', $pinjaman->status);
        $this->assertEquals('approved', $pembayaran->status);

        $this->assertDatabaseHas('angsuran', [
            'pinjaman_id' => $pinjaman->id,
            'angsuran_ke' => 1,
            'jumlah_bayar' => 100000,
            'metode_bayar' => 'transfer',
            'created_by' => $user->id,
        ]);

        $this->assertEquals(1, Angsuran::count());
    }
}