<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('skor_pengguna', function (Blueprint $table) {
            $table->id('id_skor');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_level');
            $table->integer('jumlah_benar')->default(0);
            $table->text('nama_level')->nullable();
            $table->integer('jumlah_bintang')->default(0);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_level')->references('id_level')->on('level')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skor_pengguna');
    }
};
