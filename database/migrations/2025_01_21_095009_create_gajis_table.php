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
        Schema::create('gajis', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique();
            $table->string('nama');
            $table->year('tahun_masuk');
            $table->bigInteger('gaji_pokok');
            $table->string('jabatan');
            $table->bigInteger('jumlah_pelanggan');
            $table->bigInteger('jam_lembur');
            $table->bigInteger('peningkatan_penjualan');
            $table->bigInteger('gaji_akhir');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gajis');
    }
};
