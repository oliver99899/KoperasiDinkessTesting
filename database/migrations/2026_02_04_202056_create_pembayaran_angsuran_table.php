<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_angsuran', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pinjaman_id')
                ->constrained('pinjaman')
                ->restrictOnDelete();

            $table->unsignedInteger('angsuran_ke');

            $table->date('tanggal_transfer');
            $table->string('bukti_path');

            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->index();

            $table->foreignId('submitted_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            $table->text('alasan_penolakan')->nullable();

            $table->char('signature', 64)->unique();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['pinjaman_id', 'angsuran_ke']);
            $table->index(['pinjaman_id', 'angsuran_ke', 'status']);
            $table->index(['submitted_by', 'status']);
            $table->index('tanggal_transfer');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_angsuran');
    }
};
