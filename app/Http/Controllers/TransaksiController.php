<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Menampilkan form transaksi.
     */
    public function create()
    {
        $pelanggan = Pelanggan::all();
        $produk = Produk::all();
        return view('transaksi.create', compact('pelanggan', 'produk'));
    }

    /**
     * Menyimpan transaksi ke database.
     */
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

            // Cek stok semua produk terlebih dahulu
            foreach ($request->produkID as $key => $produkID) {
                $produk = Produk::findOrFail($produkID);
                $jumlah = $request->jumlahProduk[$key];

                if ($produk->stok <= 0 || $produk->stok < $jumlah) {
                    DB::rollBack();
                    return back()->with('error', 'Stok produk "' . $produk->namaProduk . '" tidak mencukupi atau habis.');
                }
            }

            // Simpan transaksi penjualan
            $penjualan = Penjualan::create([
                'tanggalPenjualan' => now(),
                'totalHarga' => 0,
                'pelangganID' => $request->pelangganID,
            ]);

            // Simpan detail penjualan dan kurangi stok
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
                return back()->with('error', 'Jumlah bayar kurang dari total harga.');
            }

            $kembalian = $request->bayar - $totalHargaSetelahDiskon;

            $penjualan->update(['totalHarga' => $totalHargaSetelahDiskon]);

            session()->flash('totalHargaSebelumDiskon', $totalHargaSebelumDiskon);
            session()->flash('diskon', $diskonRate);
            session()->flash('totalHarga', $totalHargaSetelahDiskon);
            session()->flash('pelanggan', $pelanggan);
            session()->flash('bayar', $request->bayar);
            session()->flash('kembalian', $kembalian);

            DB::commit();

            return redirect()->route('transaksi.printStruk', ['id' => $penjualan->PenjualanID]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan struk transaksi.
     */
    public function printStruk($id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'detailPenjualan.produk'])->findOrFail($id);
        $pelanggan = $penjualan->pelanggan;

        $totalHargaSebelumDiskon = $penjualan->detailPenjualan->sum(function ($detail) {
            return $detail->subTotal;
        });

        $diskonRate = $pelanggan->getDiskon(); 
        $diskon = $totalHargaSebelumDiskon * $diskonRate;
        $totalHarga = $totalHargaSebelumDiskon - $diskon;

        $bayar = session('bayar');
        $kembalian = session('kembalian');

        return view('transaksi.struk', compact(
            'penjualan',
            'pelanggan',
            'totalHargaSebelumDiskon',
            'diskonRate',
            'diskon',
            'totalHarga',
            'bayar',
            'kembalian'
        ));
    }
}
