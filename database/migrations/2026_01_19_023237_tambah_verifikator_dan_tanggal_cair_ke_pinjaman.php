<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->unsignedBigInteger('verifikator_id')->nullable()->after('user_id');
            $table->foreign('verifikator_id')->references('id')->on('users')->onDelete('set null');
            
            $table->dateTime('tanggal_cair')->nullable()->after('tanggal_disetujui');
        });
    }

    public function down(): void
    {
        Schema::table('pinjaman', function (Blueprint $table) {
            $table->dropForeign(['verifikator_id']);
            $table->dropColumn(['verifikator_id', 'tanggal_cair']);
        });
    }
};