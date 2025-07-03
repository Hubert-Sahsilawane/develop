@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Daftar Pelanggan</h2>

    {{-- Tombol kembali --}}
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>

    {{-- Tombol tambah pelanggan (khusus admin) --}}
    @if(Auth::user()->role === 'admin')
        <a href="{{ route('pelanggan.create') }}" class="btn btn-primary mb-3 ms-2">Tambah Pelanggan</a>
    @endif

    @if (session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Nomor Telepon</th>
                <th>Jenis Pelanggan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pelanggan as $p)
                <tr>
                    <td>{{ $p->namaPelanggan }}</td>
                    <td>{{ $p->alamat }}</td>
                    <td>{{ $p->nomor_telepon }}</td>
                    <td>
                        @if ($p->jenis_pelanggan == 'vip')
                            VIP (10% Diskon)
                        @elseif ($p->jenis_pelanggan == 'member')
                            Member (5% Diskon)
                        @else
                            Biasa
                        @endif
                    </td>
                    <td>
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('pelanggan.edit', $p->PelangganID) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('pelanggan.destroy', $p->PelangganID) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        @else
                            <span class="text-muted">Akses terbatas</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data pelanggan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
