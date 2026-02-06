<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('angsuran', function (Blueprint $table) {
            $table->enum('metode_bayar', ['tunai', 'potong_gaji', 'transfer'])
                ->default('potong_gaji')
                ->after('jumlah_bayar')
                ->index();
        });
    }

    public function down(): void
    {
        Schema::table('angsuran', function (Blueprint $table) {
            $table->dropColumn('metode_bayar');
        });
    }
};
