<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bunga_pinjaman', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tenor_bulan')->unique();
            $table->decimal('persen', 5, 2)->default(1.00);
            $table->string('keterangan')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bunga_pinjaman');
    }
};