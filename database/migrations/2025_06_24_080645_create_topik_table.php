<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopikTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('topik', function (Blueprint $table) {
    $table->id('id_topik');
    $table->unsignedBigInteger('id_level'); // Harus match dengan tipe dari level.id_level
    $table->string('nama_topik');
    $table->timestamps();

    $table->foreign('id_level')->references('id_level')->on('level')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topik');
    }
}
