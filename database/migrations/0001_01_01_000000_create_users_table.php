<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('nama_unit')->unique();
            $table->enum('jenis', ['dinas', 'puskesmas'])->index();
            $table->text('alamat')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 30)->unique();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->enum('status_akun', ['new', 'active', 'retired', 'blocked'])
                ->default('new')
                ->index();

            $table->char('activation_token_hash', 64)->nullable()->index();
            $table->timestamp('activation_expires_at')->nullable();

            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();

            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('unit_kerja_id')
                ->nullable()
                ->constrained('unit_kerja')
                ->nullOnDelete();

            $table->string('nama_lengkap')->index();
            $table->string('nik', 16)->nullable()->unique();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->date('tanggal_bergabung')->nullable();

            $table->string('nama_bank')->nullable();
            $table->string('nomor_rekening', 50)->nullable();

            $table->string('foto_profil_path')->nullable();
            $table->timestamp('foto_profil_updated_at')->nullable();

            $table->timestamp('activated_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('nip', 30)->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->foreignId('user_id')
                ->nullable()
                ->index()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('users');
        Schema::dropIfExists('unit_kerja');
    }
};
