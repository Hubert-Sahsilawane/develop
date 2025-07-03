@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Produk</h2>
    <form action="{{ route('produk.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="namaProduk" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="text" name="harga" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" required>
        </div>
        <div class="mb-3">
    <label>Kategori</label>
    <select name="kategori_id" class="form-control @error('kategori_id') is-invalid @enderror">
        <option value="">-- Pilih Kategori --</option>
        @foreach ($kategori as $k)
            <option value="{{ $k->id }}" {{ old('kategori_id', $produk->kategori_id ?? '') == $k->id ? 'selected' : '' }}>
                {{ $k->nama_kategori }}
            </option>
        @endforeach
    </select>
    @error('kategori_id')
        <div class="text-danger mt-1">{{ $message }}</div>
    @enderror
</div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection

@section('scripts')
    <!-- jQuery & Select2 CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#kategoriSelect').select2({
                dropdownParent: $('.container'),
                placeholder: "-- Pilih Kategori --",
                width: '100%'
            });
        });
    </script>
@endsection
