<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_karyawan', function (Blueprint $table) {
            $table->increments('id');              // int PK auto increment
            $table->string('nama', 100);
            $table->string('jabatan', 100);
            $table->string('alamat', 255)->nullable();
            $table->string('no_telp', 20)->nullable();
            // kalau mau pakai timestamps:
            // $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_karyawan');
    }
};
