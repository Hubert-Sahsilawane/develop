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
        Schema::create('produk', function (Blueprint $table) { 
            $table->id('produkID'); 
            $table->string('namaProduk'); 
            $table->decimal('harga', 10, 2); 
            $table->integer('stok'); 
            $table->timestamps(); 
        }); 
    } 
 
    /**
     * Reverse the migrations.
     */
    public function down() { 
        Schema::dropIfExists('produk'); 
    } 
}; 
