<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use Illuminate\Validation\Rule;

class PelangganApiController extends Controller
{
    public function index()
    {
        return response()->json(Pelanggan::all(), 200);
    }

    public function store(Request $request)
    {
        $raw = preg_replace('/[^0-9]/', '', $request->nomor_telepon);
        $number = preg_replace('/^0/', '', $raw);
        $formatted = '+62-' . substr($number, 0, 3) . '-' . substr($number, 3, 4) . '-' . substr($number, 7);
        $request->merge(['nomor_telepon' => $formatted]);

        $validated = $request->validate([
            'namaPelanggan' => 'required',
            'alamat' => 'required',
            'nomor_telepon' => [
                'required',
                'regex:/^\+62\-[0-9]{3}\-[0-9]{4}\-[0-9]{3,4}$/',
                'unique:pelanggan,nomor_telepon'
            ],
            'email' => 'required|email',
            'jenis_pelanggan' => 'required|in:biasa,member,vip',
        ]);

        $pelanggan = Pelanggan::create($validated);

        return response()->json([
            'message' => 'Pelanggan berhasil ditambahkan.',
            'data' => $pelanggan
        ], 201);
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::find($id);
        if (!$pelanggan) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($pelanggan);
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::find($id);
        if (!$pelanggan) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $raw = preg_replace('/[^0-9]/', '', $request->nomor_telepon);
        $number = preg_replace('/^0/', '', $raw);
        $formatted = '+62-' . substr($number, 0, 3) . '-' . substr($number, 3, 4) . '-' . substr($number, 7);
        $request->merge(['nomor_telepon' => $formatted]);

        $validated = $request->validate([
            'namaPelanggan' => 'required',
            'alamat' => 'required',
            'nomor_telepon' => [
                'required',
                'regex:/^\+62\-[0-9]{3}\-[0-9]{4}\-[0-9]{3,4}$/',
                Rule::unique('pelanggan', 'nomor_telepon')->ignore($id, 'PelangganID')
            ],
            'email' => 'required|email',
            'jenis_pelanggan' => 'required|in:biasa,member,vip',
        ]);

        $pelanggan->update($validated);

        return response()->json([
            'message' => 'Pelanggan berhasil diperbarui.',
            'data' => $pelanggan
        ]);
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::find($id);
        if (!$pelanggan) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $pelanggan->delete();
        return response()->json(['message' => 'Pelanggan berhasil dihapus.']);
    }
}
