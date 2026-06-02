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
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bengkel_id')->constrained()->cascadeOnDelete();

            
            $table->date('tanggal'); // 🔥 INI YANG KURANG
            $table->time('waktu');   // 🔥 ini juga penting

            $table->text('keluhan');
            $table->string('status')->default('pending');
            $table->text('hasil_service')->nullable();
            $table->decimal('total_biaya', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};
