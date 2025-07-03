<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request; 
use App\Models\Penjualan; 
use App\Models\DetailPenjualan; 
use App\Models\Pelanggan; 
use Carbon\Carbon; 

class LaporanController extends Controller 
{ 
    /** 
     * Menampilkan laporan penjualan berdasarkan rentang tanggal. 
     */ 
    public function index(Request $request) 
    { 
        // Ambil tanggal dari request, jika tidak ada gunakan bulan ini 
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString()); 
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString()); 

        // Ambil data penjualan dalam rentang tanggal 
        $penjualan = Penjualan::whereBetween('tanggalPenjualan', [$startDate, $endDate]) 
            ->with(['pelanggan', 'detailPenjualan.produk']) 
            ->orderBy('tanggalPenjualan', 'desc') 
            ->get(); 

        return view('laporan.index', compact('penjualan', 'startDate', 'endDate')); 
    } 

    /**
     * Menghapus data penjualan berdasarkan ID.
     */
    public function destroy($id)
    {
        $penjualan = Penjualan::findOrFail($id);
        $penjualan->delete(); // Akan menghapus juga detail_penjualan karena relasi cascade

        return redirect()->route('laporan.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
