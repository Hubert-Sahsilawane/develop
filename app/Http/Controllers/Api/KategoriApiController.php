<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class KategoriApiController extends Controller
{
     public function index()
    {
        return response()->json(Kategori::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required',
        ]);

        $kategori = Kategori::create($validated);
        
        return response()->json([
            'message' => 'Kategori berhasil ditambahkan.',
            'data' => $kategori
        ], 201);
    }

    public function show($id)
    {
        $kategori = Kategori::find($id);
        if (!$kategori) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($kategori);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required',
        ]);
        
        $kategori = Kategori::find($id);
        if (!$kategori) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }
        
        $kategori->update($validated);
        return response()->json([
            'message' => 'Kategori berhasil diubah.',
            'data' => $kategori
        ], 201);
    }

    public function destroy($id)
    {
        $kategori = Kategori::find($id);
        if (!$kategori) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $kategori->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus.']);
    }
}