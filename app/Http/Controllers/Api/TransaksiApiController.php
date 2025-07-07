<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\DB;

class TransaksiApiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'pelangganID' => 'required|exists:pelanggan,PelangganID',
            'produkID' => 'required|array',
            'produkID.*' => 'exists:produk,produkID',
            'jumlahProduk' => 'required|array',
            'jumlahProduk.*' => 'integer|min:1',
            'bayar' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $pelanggan = Pelanggan::findOrFail($request->pelangganID);
            $diskonRate = $pelanggan->getDiskon();
            $totalHargaSebelumDiskon = 0;

            // Validasi stok
            foreach ($request->produkID as $key => $produkID) {
                $produk = Produk::findOrFail($produkID);
                $jumlah = $request->jumlahProduk[$key];
                if ($produk->stok < $jumlah) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Stok tidak mencukupi untuk produk: ' . $produk->namaProduk
                    ], 422);
                }
            }

            // Buat penjualan
            $penjualan = Penjualan::create([
                'tanggalPenjualan' => now(),
                'totalHarga' => 0,
                'pelangganID' => $request->pelangganID,
                'bayar' => 0,
                'kembalian' => 0,
            ]);

            foreach ($request->produkID as $key => $produkID) {
                $produk = Produk::findOrFail($produkID);
                $jumlah = $request->jumlahProduk[$key];
                $subTotal = $produk->harga * $jumlah;

                DetailPenjualan::create([
                    'penjualanID' => $penjualan->PenjualanID,
                    'produkID' => $produkID,
                    'jumlahProduk' => $jumlah,
                    'subTotal' => $subTotal,
                ]);

                $produk->stok -= $jumlah;
                $produk->save();

                $totalHargaSebelumDiskon += $subTotal;
            }

            $diskon = $totalHargaSebelumDiskon * $diskonRate;
            $totalHargaSetelahDiskon = $totalHargaSebelumDiskon - $diskon;

            if ($request->bayar < $totalHargaSetelahDiskon) {
                DB::rollBack();
                return response()->json(['message' => 'Uang tidak cukup'], 422);
            }

            $kembalian = $request->bayar - $totalHargaSetelahDiskon;

            $penjualan->update([
                'totalHarga' => $totalHargaSetelahDiskon,
                'bayar' => $request->bayar,
                'kembalian' => $kembalian,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Transaksi berhasil',
                'penjualan_id' => $penjualan->PenjualanID,
                'total' => $totalHargaSetelahDiskon,
                'diskon' => $diskon,
                'bayar' => $request->bayar,
                'kembalian' => $kembalian
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
