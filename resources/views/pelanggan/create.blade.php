@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Tambah Pelanggan</h2>
        <form action="{{ route('pelanggan.store') }}" method="POST" onsubmit="return validateForm()">
            @csrf
            <div class="mb-3">
                <label>Nama Pelanggan</label>
                <input type="text" id="namaPelanggan" name="namaPelanggan" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
                @if ($errors->has('email'))
                    <div class="text-danger mt-1 fw-bold">
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>
            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" required></textarea>
            </div>

            <div class="mb-3">
                <label>Nomor Telepon</label>
                <div class="input-group">
                    <span class="input-group-text">+62</span>
                    <input type="text" id="nomor_telepon" name="nomor_telepon"
                        class="form-control {{ $errors->has('nomor_telepon') ? 'is-invalid' : '' }}"
                        value="{{ old('nomor_telepon') }}" required placeholder="81234567890">
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
                    <option value="biasa">Biasa</option>
                    <option value="member">Member (5% Diskon)</option>
                    <option value="vip">VIP (10% Diskon)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('pelanggan.index') }}'">Kembali</button>
        </form>
    </div>

    <script>
        function validateForm() {
            let nama = document.getElementById("namaPelanggan").value;
            let regex = /^[a-zA-Z\s]+$/;
            if (!regex.test(nama)) {
                alert("Nama hanya boleh mengandung huruf dan spasi!");
                return false;
            }

            let nomor = document.getElementById("nomor_telepon").value;
            if (!/^[0-9]{9,13}$/.test(nomor)) {
                alert("Nomor telepon harus berupa angka dan 9-12 digit setelah +62.");
                return false;
            }

            return true;
        }
    </script>
@endsection
