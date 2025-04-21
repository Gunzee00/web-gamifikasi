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
        Schema::create('matapelajaran', function (Blueprint $table) {
            $table->id('id_mataPelajaran'); // Akan otomatis menjadi unsignedBigInteger
            $table->string('nama_mataPelajaran')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_peljaran_');
    }
};
