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
        Schema::create('transaksi_masuks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nama_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('bulan_id')->constrained('bulan_pembayaran')->onDelete('cascade');
            $table->enum('minggu_1', ['LUNAS', 'BELUM LUNAS']);
            $table->enum('minggu_2', ['LUNAS', 'BELUM LUNAS']);
            $table->enum('minggu_3', ['LUNAS', 'BELUM LUNAS']);
            $table->enum('minggu_4', ['LUNAS', 'BELUM LUNAS']);
            $table->enum('status_lunas', ['LUNAS', 'BELUM LUNAS']);
            $table->string('description')->nullable();
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_masuks');
    }
};
