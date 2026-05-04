<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->foreignId('bunga_id')
                ->nullable()
                ->after('bunga_persen')
                ->constrained('bunga_pinjaman')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->dropForeign(['bunga_id']);
            $table->dropColumn('bunga_id');
        });
    }
};