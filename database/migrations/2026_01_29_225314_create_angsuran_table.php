<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('angsuran', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pinjaman_id')
                ->constrained('pinjaman')
                ->restrictOnDelete();

            $table->unsignedInteger('angsuran_ke');

            $table->decimal('pokok_bayar', 15, 2);
            $table->decimal('bunga_bayar', 15, 2);
            $table->decimal('jumlah_bayar', 15, 2);

            $table->date('tanggal_potong');

            $table->string('keterangan')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamp('verified_at')->nullable();

            $table->char('signature', 64)->unique();

            $table->unique(['pinjaman_id', 'angsuran_ke']);

            $table->softDeletes();
            $table->timestamps();

            $table->index('tanggal_potong');
            $table->index(['pinjaman_id', 'tanggal_potong']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('angsuran');
    }
};
