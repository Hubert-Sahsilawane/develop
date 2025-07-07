<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;

class ProdukApiController extends Controller
{
    public function index()
    {
        $produk = Produk::with('kategori')->get();
        return response()->json($produk);
    }

    public function store(Request $request)
    {
        $request->validate([
            'namaProduk' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'kategori_id' => 'required|exists:kategori,id'
        ]);

        $kategori = Kategori::find($request->kategori_id);
        $namaProduk = strtolower($request->namaProduk);
        $namaKategori = strtolower($kategori->nama_kategori);

        if (!str_starts_with($namaProduk, $namaKategori)) {
            return response()->json(['message' => 'Nama produk harus sesuai dengan kategori'], 422);
        }

        $existing = Produk::where('namaProduk', $request->namaProduk)->first();
        $message = $existing ? 'Stok produk berhasil ditambahkan.' : 'Produk baru berhasil ditambahkan.';

        Produk::createOrUpdateStock($request->only(['namaProduk', 'harga', 'stok', 'kategori_id']));

        return response()->json(['message' => $message], 201);
    }

    public function show($id)
    {
        $produk = Produk::with('kategori')->find($id);
        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }
        return response()->json($produk);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'namaProduk' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'kategori_id' => 'required|exists:kategori,id'
        ]);

        $kategori = Kategori::find($request->kategori_id);
        $namaProduk = strtolower($request->namaProduk);
        $namaKategori = strtolower($kategori->nama_kategori);

        if (!str_starts_with($namaProduk, $namaKategori)) {
            return response()->json(['message' => 'Nama produk harus sesuai dengan kategori'], 422);
        }

        $produk = Produk::find($id);
        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $produk->update($request->only(['namaProduk', 'harga', 'stok', 'kategori_id']));

        return response()->json(['message' => 'Produk berhasil diperbarui']);
    }

    public function destroy($id)
    {
        $produk = Produk::find($id);
        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $produk->delete();
        return response()->json(['message' => 'Produk berhasil dihapus']);
    }
}
