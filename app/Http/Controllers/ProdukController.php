<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::with('kategori')->get();
        return view('produk.index', compact('produk'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('produk.create', compact('kategori'));
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
            return back()
                ->withErrors(['kategori_id' => 'Sesuaikan dengan kategorinya'])
                ->withInput();
        }

        $existingProduct = Produk::where('namaProduk', $request->namaProduk)->first();
        $message = $existingProduct ? 'Stok produk berhasil ditambahkan.' : 'Produk baru berhasil ditambahkan.';

        Produk::createOrUpdateStock($request->only(['namaProduk', 'harga', 'stok', 'kategori_id']));

        return redirect()->route('produk.index')->with('success', $message);
    }

    public function show(string $id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.show', compact('produk'));
    }

    public function edit(string $id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = Kategori::all();
        return view('produk.edit', compact('produk', 'kategori'));
    }

    public function update(Request $request, string $id)
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
            return back()
                ->withErrors(['kategori_id' => 'Sesuaikan dengan kategorinya'])
                ->withInput();
        }

        $produk = Produk::findOrFail($id);
        $produk->update($request->only(['namaProduk', 'harga', 'stok', 'kategori_id']));

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
