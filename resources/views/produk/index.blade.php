@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Daftar Produk</h2>

    {{-- Tombol kembali ke dashboard --}}
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>

    {{-- Tombol tambah produk (admin & kasir, owner) --}}
    @if(in_array(Auth::user()->role, ['admin', 'kasir', 'owner']))
        {{-- Hanya tampilkan tombol jika user adalah admin, kasir, atau owner --}}
        <a href="{{ route('produk.create') }}" class="btn btn-primary mb-3 ms-2">Tambah Produk</a>
    @endif

    @if (session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th> <!-- Kolom ID ditambahkan -->
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($produk as $p)
                <tr>
                    <td>{{ $p->produkID }}</td> <!-- Menampilkan ID produk -->
                    <td>{{ $p->namaProduk }}</td>
                    <td>{{ $p->kategori->nama_kategori ?? '-' }}</td>
                    <td>Rp {{ number_format($p->harga, 2, ',', '.') }}</td>
                    <td>{{ $p->stok }}</td>
                    <td>
                        @if(in_array(Auth::user()->role, ['admin', 'kasir', 'owner']))
                            <a href="{{ route('produk.edit', $p->produkID) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('produk.destroy', $p->produkID) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus produk ini?')">Hapus</button>
                            </form>
                        @else
                            <span class="text-muted">Akses terbatas</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada produk.</td> <!-- Ubah colspan dari 5 ke 6 -->
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
