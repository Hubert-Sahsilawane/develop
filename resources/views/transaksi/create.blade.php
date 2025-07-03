@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Form Transaksi</h2>

    {{-- Tombol kembali --}}
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{!! session('error') !!}</div>
    @endif

    <form action="{{ route('transaksi.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Pilih Pelanggan</label>
            <select name="pelangganID" id="pelangganID" class="form-control" required>
                <option value="">-- Pilih Pelanggan --</option>
                @foreach ($pelanggan as $p)
                    <option value="{{ $p->PelangganID }}" data-diskon="{{ $p->getDiskon() }}">
                        {{ $p->namaPelanggan }} -
                        @if ($p->jenis_pelanggan == 'vip')
                            VIP (10% Diskon)
                        @elseif ($p->jenis_pelanggan == 'member')
                            Member (5% Diskon)
                        @else
                            Biasa (Tanpa Diskon)
                        @endif
                    </option>
                @endforeach
            </select>
        </div>

        <h4>Produk yang Dibeli</h4>
        <div id="produk-container">
            <div class="row mb-3 produk-item">
                <div class="col-md-3">
                    <select name="produkID[]" class="form-control select-produk" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($produk as $p)
                            <option value="{{ $p->produkID }}" data-harga="{{ $p->harga }}" data-stok="{{ $p->stok }}">
                                {{ $p->namaProduk }} - Rp {{ number_format($p->harga, 2, ',', '.') }} (Stok: {{ $p->stok }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="jumlahProduk[]" class="form-control jumlahProduk" placeholder="Jumlah" min="1" required>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control subTotal" placeholder="Subtotal" readonly>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control hargaDiskon" placeholder="Harga Diskon" readonly>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control hargaAkhir" placeholder="Total" readonly>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-produk">X</button>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-success mb-3" id="add-produk">Tambah Produk</button>

        <div class="mb-3">
            <label>Total Harga</label>
            <input type="text" id="totalHarga" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label>Total Harga Setelah Diskon</label>
            <input type="text" id="totalSetelahDiskon" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label>Bayar</label>
            <input type="number" name="bayar" id="bayar" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Kembalian</label>
            <input type="text" id="kembalian" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function getDiskonPersen() {
        const selected = document.querySelector('#pelangganID option:checked');
        return parseFloat(selected?.getAttribute('data-diskon') || 0);
    }

    function formatRupiah(angka) {
        return angka.toLocaleString('id-ID', {
            minimumFractionDigits: 0
        });
    }

    function unformatRupiah(rupiah) {
        if (!rupiah) return 0;
        return parseFloat(rupiah.toString().replace(/\./g, '').replace(/,/g, '.')) || 0;
    }

  function updateHarga() {
    let diskonPelanggan = parseFloat(document.querySelector('#pelangganID option:checked').dataset.diskon || 0);
    let totalHarga = 0;
    let totalSetelahDiskon = 0;

    document.querySelectorAll('.produk-item').forEach(function (item) {
        const select = item.querySelector('.select-produk');
        const harga = parseFloat(select.options[select.selectedIndex]?.dataset.harga || 0);
        const jumlah = parseInt(item.querySelector('.jumlahProduk').value || 0);
        const subtotal = harga * jumlah;
        const diskon = subtotal * diskonPelanggan; // FIXED HERE
        const hargaAkhir = subtotal - diskon;

        item.querySelector('.subTotal').value = subtotal.toLocaleString();
        item.querySelector('.hargaDiskon').value = diskon.toLocaleString();
        item.querySelector('.hargaAkhir').value = hargaAkhir.toLocaleString();

        totalHarga += subtotal;
        totalSetelahDiskon += hargaAkhir;
    });

    document.querySelector('#totalHarga').value = totalHarga.toLocaleString();
    document.querySelector('#totalSetelahDiskon').value = totalSetelahDiskon.toLocaleString();

    const bayar = parseFloat(document.querySelector('#bayar').value || 0);
    const kembalian = bayar - totalSetelahDiskon;
    document.querySelector('#kembalian').value = (kembalian > 0 ? kembalian : 0).toLocaleString();
}

    document.getElementById('add-produk').addEventListener('click', function () {
        const container = document.getElementById('produk-container');
        const clone = container.querySelector('.produk-item').cloneNode(true);

        clone.querySelectorAll('input').forEach(input => input.value = '');
        clone.querySelector('select').selectedIndex = 0;

        container.appendChild(clone);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-produk')) {
            const items = document.querySelectorAll('.produk-item');
            if (items.length > 1) {
                e.target.closest('.produk-item').remove();
                updateHarga();
            }
        }
    });

    document.addEventListener('input', function (e) {
        if (
            e.target.classList.contains('jumlahProduk') ||
            e.target.classList.contains('select-produk') ||
            e.target.id === 'bayar'
        ) {
            updateHarga();
        }
    });

    document.getElementById('pelangganID').addEventListener('change', updateHarga);
});

</script>
@endsection
