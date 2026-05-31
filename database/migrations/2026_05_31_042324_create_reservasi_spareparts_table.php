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
        Schema::create('reservasi_spareparts', function (Blueprint $table) {

            $table->id();

            $table->foreignId('reservasi_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('sparepart_id')
                ->constrained('sparepart')
                ->cascadeOnDelete();

            $table->integer('qty')->default(1);

            $table->decimal('harga', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasi_spareparts');
    }
};
