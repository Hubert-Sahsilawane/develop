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
        Schema::create('pelanggan', function (Blueprint $table) { 
            $table->id('PelangganID'); 
            $table->string('namaPelanggan'); 
            $table->text('alamat'); 
            $table->string('nomor_telepon'); 
            $table->timestamps(); 
        }); 
    } 
 
    /**
     * Reverse the migrations.
     */
    public function down() { 
        Schema::dropIfExists('pelanggan'); 
    } 
}; 

