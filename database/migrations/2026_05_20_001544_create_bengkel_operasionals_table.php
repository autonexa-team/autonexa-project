<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bengkel_operasionals', function (Blueprint $table) {

            $table->id();

            // relasi ke bengkel
            $table->foreignId('bengkel_id')
                  ->constrained()
                  ->onDelete('cascade');

            // nama hari
            $table->enum('hari', [
                'senin',
                'selasa',
                'rabu',
                'kamis',
                'jumat',
                'sabtu',
                'minggu'
            ]);

            // status buka / tutup
            $table->boolean('is_buka')
                  ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bengkel_operasionals');
    }
};