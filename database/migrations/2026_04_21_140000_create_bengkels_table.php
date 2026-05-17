<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bengkels', function (Blueprint $table) {

            $table->id();

            // informasi bengkel
            $table->string('nama');
            $table->text('alamat')->nullable();

            // koordinat lokasi
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // kota hasil geocoding
            $table->string('kota')->nullable();

            // kontak
            $table->string('telepon')->nullable();

            // thumbnail bengkel
            $table->string('foto')->nullable();

            // deskripsi bengkel
            $table->text('deskripsi')->nullable();

            // status bengkel
            $table->enum('status', ['aktif', 'nonaktif'])
                  ->default('aktif');

            // admin cabang pengelola
            $table->foreignId('admin_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bengkels');
    }
};