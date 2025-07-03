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
    if (!Schema::hasColumn('produk', 'kategori_id')) {
    Schema::table('produk', function (Blueprint $table) {
        $table->unsignedBigInteger('kategori_id')->nullable()->after('stok');
        $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('set null');
    });
}
}

public function down()
{
    Schema::table('produk', function (Blueprint $table) {
        $table->dropForeign(['kategori_id']);
        $table->dropColumn('kategori_id');
    });
}
};
