<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $primaryKey = 'produkID';
    protected $fillable = ['namaProduk', 'harga', 'stok', 'kategori_id'];

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'produkID', 'ProdukID');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
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
            // Produk sudah ada, tambahkan stok
            $existingProduct->stok += $data['stok'];

            // Update kategori_id kalau dikirim
            if (isset($data['kategori_id'])) {
                $existingProduct->kategori_id = $data['kategori_id'];
            }

            // Update harga kalau dikirim
            if (isset($data['harga'])) {
                $existingProduct->harga = $data['harga'];
            }

            $existingProduct->save();
            return $existingProduct;
        }

        // Produk belum ada, buat baru
        return self::create($data);
    }
}
