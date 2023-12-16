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
        Schema::create('DetailTransaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->bigInteger('id_user')->unsigned();
            $table->integer('jumlah_pesanan')->default(0);
            $table->decimal('total_harga', 8, 2)->default(0);
            $table->string('metode_pembayaran', 255);
            $table->timestamps(0);
            $table->timestamp('waktu_transaksi')->nullable();
            $table->string('nama_pelanggan', 255)->nullable(); 
            $table->foreign('id_user')->references('id_user')->on('User');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DetailTransaksi');
    }
};
