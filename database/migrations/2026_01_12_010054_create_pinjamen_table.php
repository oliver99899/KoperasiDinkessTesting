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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->decimal('jumlah_pengajuan', 15, 2);
            $table->integer('durasi_bulan');
            $table->string('alasan')->nullable();
            
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_disetujui')->nullable();
            
            $table->decimal('sisa_tagihan', 15, 2)->default(0); 

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pinjaman');
    }
};