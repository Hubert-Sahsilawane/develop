<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->id('detailID');
            $table->unsignedBigInteger('penjualanID');
            $table->unsignedBigInteger('produkID');
            $table->integer('jumlahProduk');
            $table->decimal('subTotal', 10, 2);
            $table->timestamps();

            // Foreign key
            $table->foreign('penjualanID')->references('PenjualanID')->on('penjualan')->onDelete('cascade');
            $table->foreign('produkID')->references('produkID')->on('produk')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};
