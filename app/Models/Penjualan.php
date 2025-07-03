<?php

namespace App\Models; 

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model; 

class Penjualan extends Model {
    use HasFactory; 

    protected $table = 'penjualan'; 
    protected $primaryKey = 'PenjualanID'; 
    protected $fillable = ['tanggalPenjualan', 'totalHarga', 'pelangganID']; 

    public function pelanggan() { 
        return $this->belongsTo(Pelanggan::class, 'pelangganID', 'PelangganID'); 
    } 

    public function detailPenjualan() { 
        return $this->hasMany(DetailPenjualan::class, 'penjualanID', 'PenjualanID'); 
    }
}
