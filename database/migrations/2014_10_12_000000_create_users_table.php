<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user'); // Menggunakan id_user sebagai primary key
            $table->enum('role', ['admin', 'super_admin', 'user'])->default('user');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('gender', ['laki-laki', 'perempuan']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};