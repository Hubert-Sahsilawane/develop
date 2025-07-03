<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() 
{ 
    Schema::table('pelanggan', function (Blueprint $table) { 
        $table->string('jenis_pelanggan')->default('biasa'); // biasa, member, vip 
    }); 
} 


    /**
     * Reverse the migrations.
     */
   public function down() 
{ 
    Schema::table('pelanggan', function (Blueprint $table) { 
        $table->dropColumn('jenis_pelanggan'); 
    }); 
} 

};
