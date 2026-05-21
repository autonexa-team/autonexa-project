<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slot_reservasis', function (Blueprint $table) {

            $table->id();

            // relasi ke bengkel
            $table->foreignId('bengkel_id')
                  ->constrained()
                  ->onDelete('cascade');

            // jam slot
            $table->time('jam_mulai');

            $table->time('jam_selesai');

            // kuota kendaraan
            $table->integer('kuota')
                  ->default(5);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slot_reservasis');
    }
};