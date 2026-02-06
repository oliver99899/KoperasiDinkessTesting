<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pinjaman', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('nomor_pinjaman')->unique();

            $table->decimal('jumlah_pengajuan', 15, 2);
            $table->decimal('jumlah_disetujui', 15, 2)->nullable();

            $table->unsignedInteger('durasi_bulan');
            $table->decimal('bunga_persen', 5, 2)->default(0);

            $table->decimal('cicilan_pokok_bulanan', 15, 2)->default(0);
            $table->decimal('cicilan_bunga_bulanan', 15, 2)->default(0);

            $table->decimal('total_bunga', 15, 2)->default(0);
            $table->decimal('total_pinjaman', 15, 2)->default(0);
            $table->decimal('sisa_pinjaman', 15, 2)->default(0);

            $table->text('alasan_pengajuan');
            $table->json('dokumen_syarat')->nullable();

            $table->enum('status', ['diajukan', 'verifikasi', 'disetujui', 'ditolak', 'dicairkan', 'lunas'])
                ->default('diajukan')
                ->index();

            $table->text('alasan_penolakan')->nullable();

            $table->date('tanggal_pengajuan');
            $table->date('tanggal_cair')->nullable();
            $table->date('jatuh_tempo_berikutnya')->nullable();

            $table->foreignId('decided_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('decided_at')->nullable();

            $table->char('signature', 64)->unique();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('tanggal_pengajuan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};
