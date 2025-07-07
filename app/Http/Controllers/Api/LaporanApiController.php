<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use Carbon\Carbon;

class LaporanApiController extends Controller
{
    /**
     * Menampilkan laporan penjualan dalam rentang tanggal (format JSON).
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $penjualan = Penjualan::whereBetween('tanggalPenjualan', [$startDate, $endDate])
            ->with(['pelanggan', 'detailPenjualan.produk'])
            ->orderBy('tanggalPenjualan', 'desc')
            ->get();

        return response()->json([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'data' => $penjualan
        ]);
    }

    /**
     * Hapus transaksi berdasarkan ID.
     */
    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);

        if (!$penjualan) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        $penjualan->delete(); // asumsinya sudah cascade delete ke detail_penjualan
        return response()->json(['message' => 'Transaksi berhasil dihapus']);
    }
}
