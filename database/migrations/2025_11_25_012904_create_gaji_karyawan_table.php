<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gaji_karyawan', function (Blueprint $table) {
            $table->increments('id_gaji');      // int PK auto increment

            // FK ke data_karyawan.id
            $table->unsignedInteger('id_karyawan');

            $table->enum('bulan', [
                'Januari','Februari','Maret','April','Mei','Juni',
                'Juli','Agustus','September','Oktober','November','Desember'
            ]);

            $table->year('tahun');

            $table->decimal('gaji_pokok', 12, 2)->default(0);
            $table->decimal('tunjangan', 12, 2)->default(0);
            $table->decimal('potongan', 12, 2)->default(0);
            $table->decimal('total_gaji', 12, 2)->default(0);

            // Foreign key
            $table->foreign('id_karyawan')
                  ->references('id')->on('data_karyawan')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gaji_karyawan');
    }
};

