<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Penjualan per jam hari ini (untuk grafik batang harian)
        $penjualanHariIni = DB::table('penjualan')
            ->select(DB::raw('HOUR(created_at) as jam'), DB::raw('COUNT(*) as total'))
            ->whereDate('created_at', Carbon::today())
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('jam')
            ->get();

        $labelsJam = range(0, 23);
        $dataJam = [];

        foreach ($labelsJam as $jam) {
            $found = $penjualanHariIni->firstWhere('jam', $jam);
            $dataJam[] = $found ? $found->total : 0;
        }

        // Grafik Doughnut
        $produkTerjual = DB::table('detail_penjualan')
            ->join('produk', 'detail_penjualan.produkID', '=', 'produk.produkID')
            ->select('produk.namaProduk', DB::raw('SUM(detail_penjualan.jumlahProduk) as total'))
            ->groupBy('produk.namaProduk')
            ->get();

        $labelsProduk = $produkTerjual->pluck('namaProduk');
        $dataProduk = $produkTerjual->pluck('total');

        // Penjualan per hari (7 hari terakhir)
        $penjualanHarian = DB::table('penjualan')
            ->select(DB::raw('DATE(created_at) as tanggal'), DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal')
            ->get();

        $tanggalLabels = [];
        $dataPenjualan = [];

        $tanggalPeriode = collect(range(0, 6))->map(function ($i) {
            return Carbon::now()->subDays(6 - $i)->format('Y-m-d');
        });

        foreach ($tanggalPeriode as $tgl) {
            $tanggalLabels[] = Carbon::parse($tgl)->format('d M');
            $match = $penjualanHarian->firstWhere('tanggal', $tgl);
            $dataPenjualan[] = $match ? $match->total : 0;
        }

        // Penjualan 1 Bulan Terakhir (30 hari)
        $penjualanBulanan = DB::table('penjualan')
            ->select(DB::raw('DATE(created_at) as tanggal'), DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', Carbon::now()->subDays(29)->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal')
            ->get();

        $labelsBulan = [];
        $dataBulan = [];

        $periodeBulan = collect(range(0, 29))->map(function ($i) {
            return Carbon::now()->subDays(29 - $i)->format('Y-m-d');
        });

        foreach ($periodeBulan as $tgl) {
            $labelsBulan[] = Carbon::parse($tgl)->format('d M');
            $match = $penjualanBulanan->firstWhere('tanggal', $tgl);
            $dataBulan[] = $match ? $match->total : 0;
        }

        // Produk dengan stok menipis (< 10)
        $stokMenipisData = DB::table('produk')
            ->where('stok', '<', 10)
            ->select('namaProduk', 'stok')
            ->get();

        $produkStokMenipis = $stokMenipisData->pluck('namaProduk');
        $stokMenipis = $stokMenipisData->pluck('stok');

        // Top 5 Produk Terlaris
        $terlaris = DB::table('detail_penjualan')
            ->join('produk', 'detail_penjualan.produkID', '=', 'produk.produkID')
            ->select('produk.namaProduk', DB::raw('SUM(detail_penjualan.jumlahProduk) as total'))
            ->groupBy('produk.namaProduk')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $produkTerlaris = $terlaris->pluck('namaProduk');
        $jumlahTerlaris = $terlaris->pluck('total');

        // Produk Kurang Diminati
        $kurangDiminati = DB::table('produk')
            ->leftJoin('detail_penjualan', 'produk.produkID', '=', 'detail_penjualan.produkID')
            ->select('produk.namaProduk', DB::raw('COALESCE(SUM(detail_penjualan.jumlahProduk), 0) as total'))
            ->groupBy('produk.namaProduk')
            ->orderBy('total')
            ->limit(5)
            ->get();

        $produkSepi = $kurangDiminati->pluck('namaProduk');
        $jumlahSepi = $kurangDiminati->pluck('total');

        return view('dashboard', compact(
            'labelsJam',
            'dataJam',
            'labelsProduk',
            'dataProduk',
            'tanggalLabels',
            'dataPenjualan',
            'labelsBulan',
            'dataBulan',
            'produkStokMenipis',
            'stokMenipis',
            'produkTerlaris',
            'jumlahTerlaris',
            'produkSepi',
            'jumlahSepi'
        ));
    }
}
