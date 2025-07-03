<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; max-width: 300px; margin: auto; }
        .struk { border: 1px dashed #000; padding: 10px; }
        .center { text-align: center; }
        .footer { margin-top: 10px; font-size: 12px; text-align: center; }
        table td { padding: 2px 4px; }
    </style>
</head>
<body onload="window.print()">

<div class="struk">
    <h4 class="center">JojoMidi</h4> 
    <p class="center">Jl. Baros Pasar</p> 
    <hr> 

    <p><strong>Tanggal Transaksi:</strong> {{ \Carbon\Carbon::parse($penjualan->tanggalPenjualan)->format('d M Y H:i') }}</p>
    <p><strong>Pelanggan:</strong> {{ $penjualan->pelanggan->namaPelanggan }}</p>
    <p><strong>Kasir:</strong> {{ auth()->user()->name }}</p>

    <table class="table table-borderless"> 
        <thead> 
            <tr> 
                <th>Produk</th> 
                <th>Qty</th> 
                <th>Subtotal</th> 
            </tr> 
        </thead> 
        <tbody> 
            @foreach ($penjualan->detailPenjualan as $detail) 
            <tr> 
                <td>{{ $detail->produk->namaProduk }}</td> 
                <td>{{ $detail->jumlahProduk }}</td> 
                <td>Rp {{ number_format($detail->subTotal, 0, ',', '.') }}</td> 
            </tr> 
            @endforeach 
        </tbody> 
    </table> 

    <table class="w-100">
        <hr>
        <tr>
            <td>Subtotal</td>
            <td class="text-end">Rp {{ number_format($totalHargaSebelumDiskon, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Diskon ({{ ucfirst($pelanggan->jenis_pelanggan) }} - {{ $diskonRate * 100 }}%)</td>
            <td class="text-end">-Rp {{ number_format($diskon, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Total Setelah Diskon</strong></td>
            <td class="text-end"><strong>Rp {{ number_format($totalHarga, 0, ',', '.') }}</strong></td>
        </tr>
        <tr><td colspan="2"><hr></td></tr>
        <tr>
            <td>Bayar</td>
            <td class="text-end">Rp {{ number_format($bayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembalian</td>
            <td class="text-end">Rp {{ number_format($kembalian, 0, ',', '.') }}</td>
        </tr>
        <tr><td colspan="2"><hr></td></tr>
    </table>

    <hr> 
    <h5 class="center">Total: Rp {{ number_format($penjualan->totalHarga, 0, ',', '.') }}</h5> 

    <p class="footer">Terima kasih telah berbelanja!</p>
    <div class="text-center mt-2">
        <button onclick="window.print()" class="btn btn-primary">Cetak Ulang</button>
        <a href="{{ route('laporan.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

</body>
</html>
