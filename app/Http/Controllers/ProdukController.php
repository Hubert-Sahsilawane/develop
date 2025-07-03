<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::all();
        return view('produk.index', compact('produk'));
    }

    public function create()
    {
        $this->authorizeAdminOrKasir();
        return view('produk.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdminOrKasir();

        $request->validate([
            'namaProduk' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer'
        ]);

        $existingProduct = Produk::where('namaProduk', $request->namaProduk)->first();
        $message = $existingProduct ? 'Stok produk berhasil ditambahkan.' : 'Produk baru berhasil ditambahkan.';

        Produk::createOrUpdateStock($request->all());

        return redirect()->route('produk.index')->with('success', $message);
    }

    public function show(string $id)
    {
        $this->authorizeAdminOrKasir();
        $produk = Produk::findOrFail($id);
        return view('produk.show', compact('produk'));
    }

    public function edit(string $id)
    {
        $this->authorizeAdminOrKasir();
        $produk = Produk::findOrFail($id);
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, string $id)
    {
        $this->authorizeAdminOrKasir();

        $request->validate([
            'namaProduk' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update($request->all());

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->authorizeAdminOrKasir();

        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function authorizeAdminOrKasir()
    {
        if (!in_array(Auth::user()->role, ['admin', 'kasir'])) {
            abort(403, 'Akses ditolak. Hanya admin atau kasir yang diizinkan.');
        }
    }
}
