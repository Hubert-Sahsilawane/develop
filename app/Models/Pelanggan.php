<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $primaryKey = 'PelangganID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['namaPelanggan', 'alamat', 'nomor_telepon', 'jenis_pelanggan', 'email'];

    public function penjualan(): HasMany
    {
        return $this->hasMany(Penjualan::class, 'pelangganID', 'PelangganID');
    }

    public function getJenisPelangganLabel(): string
    {
        return match ($this->jenis_pelanggan) {
            'vip' => 'VIP (10% Diskon)',
            'member' => 'Member (5% Diskon)',
            default => 'Biasa',
        };
    }

    public function getDiskon(): float
    {
        return match ($this->jenis_pelanggan) {
            'vip' => 0.10,
            'member' => 0.05,
            default => 0.0,
        };
    }
}
