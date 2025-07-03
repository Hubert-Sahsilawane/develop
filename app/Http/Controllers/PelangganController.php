<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::all();
        return view('pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
{
    // Normalisasi sebelum validasi
    $raw = preg_replace('/[^0-9]/', '', $request->nomor_telepon);
    $number = preg_replace('/^0/', '', $raw); // hapus 0 depan jika ada
    $formatted = '+62-' . substr($number, 0, 3) . '-' . substr($number, 3, 4) . '-' . substr($number, 7);

    // Set ke request sebelum validasi
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
    ], [
        'nomor_telepon.unique' => 'Maaf, nomor ini sudah ada. Tolong ganti nomor lain.',
    ]);

    Pelanggan::create($validated);
    return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
}

    public function show($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggan.show', compact('pelanggan'));
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
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
            Rule::unique('pelanggan', 'nomor_telepon')->ignore($id, 'PelangganID')
        ],
        'email' => 'required|email',
        'jenis_pelanggan' => 'required|in:biasa,member,vip',
    ], [
        'nomor_telepon.unique' => 'Maaf, nomor ini sudah ada. Tolong ganti nomor lain.',
    ]);

    $pelanggan = Pelanggan::findOrFail($id);
    $pelanggan->update($validated);

    return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui.');
}

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
