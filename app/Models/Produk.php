<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'produkID';
    protected $fillable = ['namaProduk', 'harga', 'stok'];

    public function detailPenjualan(){
        return $this->hasMany(DetailPenjualan::class, 'produkID', 'ProdukID');
    }

    /**
     * Create a new product or update stock if product already exists
     * 
     * @param array $data
     * @return Produk
     */
    public static function createOrUpdateStock(array $data)
    {
        $existingProduct = self::where('namaProduk', $data['namaProduk'])->first();

        if ($existingProduct) {
            // Product exists, update the stock
            $existingProduct->stok += $data['stok'];
            $existingProduct->save();
            return $existingProduct;
        }

        // Product doesn't exist, create new one
        return self::create($data);
    }
}