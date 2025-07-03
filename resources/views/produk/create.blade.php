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
        <button type="submit" class="btn btn-success">Simpan</button> 
    </form> 
</div> 
@endsection 
