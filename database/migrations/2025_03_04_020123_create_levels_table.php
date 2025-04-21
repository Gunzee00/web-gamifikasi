<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('levels', function (Blueprint $table) {
            $table->id('id_level');
            $table->string('level')->unique();
            $table->timestamps();
        });
    }

    /**
     * Membatalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
