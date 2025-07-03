@extends('layouts.app')

@section('content')
@php use Illuminate\Support\Str; @endphp
<div class="container">
    <h2>Edit Pelanggan</h2>
    <form action="{{ route('pelanggan.update', $pelanggan->PelangganID) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Nama Pelanggan</label>
            <input type="text" name="namaPelanggan" class="form-control" value="{{ $pelanggan->namaPelanggan }}" required>
        </div>

        <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required>{{ $pelanggan->alamat }}</textarea>
        </div>

        <div class="mb-3">
            <label>Nomor Telepon</label>
            <div class="input-group">
                <span class="input-group-text">+62</span>
                <input 
                    type="text" 
                    id="nomor_telepon" 
                    name="nomor_telepon" 
                    class="form-control {{ $errors->has('nomor_telepon') ? 'is-invalid' : '' }}"
                    value="{{ old('nomor_telepon', Str::startsWith($pelanggan->nomor_telepon, '+62') ? preg_replace('/[^0-9]/', '', substr($pelanggan->nomor_telepon, 3)) : $pelanggan->nomor_telepon) }}"
                    required 
                    placeholder="81234567890">
            </div>
            @if ($errors->has('nomor_telepon'))
                <div class="text-danger mt-1 fw-bold">
                    {{ $errors->first('nomor_telepon') }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="jenis_pelanggan">Jenis Pelanggan</label>
            <select name="jenis_pelanggan" id="jenis_pelanggan" class="form-control" required>
                <option value="biasa" {{ $pelanggan->jenis_pelanggan == 'biasa' ? 'selected' : '' }}>Biasa</option>
                <option value="member" {{ $pelanggan->jenis_pelanggan == 'member' ? 'selected' : '' }}>Member (5% Diskon)</option>
                <option value="vip" {{ $pelanggan->jenis_pelanggan == 'vip' ? 'selected' : '' }}>VIP (10% Diskon)</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
