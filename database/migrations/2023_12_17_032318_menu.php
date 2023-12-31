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
        Schema::create('Menu', function (Blueprint $table) {
            $table->id('id_menu');
            $table->string('nama_menu');
            $table->decimal('harga');
            $table->string('kategori');
            $table->string('image');
            $table->integer('jumlah_stok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Menu');
    }
};
