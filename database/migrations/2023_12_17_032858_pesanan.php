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
        Schema::create('Pesanan', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->bigInteger('id_transaksi')->unsigned();
            $table->bigInteger('id_menu')->unsigned();
            $table->integer('jumlah_pesanan');
            $table->decimal('total_harga', 8, 2);
            $table->timestamps(0);
            $table->text('catatan')->nullable();
 
            $table->foreign('id_menu')->references('id_menu')->on('Menu'); 
            $table->foreign('id_transaksi')->references('id_transaksi')->on('DetailTransaksi');
                 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Pesanan');
    }
};
