<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $daftarKategori = ['PS', 'STIK PS', 'KASET PS', 'LAPTOP', 'HP', 'HEADPHONE', 'CASSAN'];

        foreach ($daftarKategori as $nama) {
            Kategori::firstOrCreate(['nama_kategori' => $nama]);
        }
    }
}
