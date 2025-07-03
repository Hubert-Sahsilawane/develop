@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Laporan Penjualan</h2>

    <!-- Tombol Kembali ke Dashboard -->
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Kembali</a>

    <form method="GET" action="{{ route('laporan.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label>Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-control">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Total Harga</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualan as $p)
            <tr>
                <td>{{ \Carbon\Carbon::parse($p->tanggalPenjualan)->format('d M Y') }}</td>
                <td>{{ $p->pelanggan->namaPelanggan }}</td>
                <td>Rp {{ number_format($p->totalHarga, 2, ',', '.') }}</td>
                <td>
                    <!-- Tombol Lihat -->
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $p->PenjualanID }}">
                        Lihat
                    </button>

                    <!-- Tombol Hapus -->
                    <form action="{{ route('penjualan.destroy', $p->PenjualanID) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>

                    <!-- Modal Detail -->
                    <div class="modal fade" id="detailModal{{ $p->PenjualanID }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Penjualan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <ul class="list-group">
                                        @foreach ($p->detailPenjualan as $detail)
                                        <li class="list-group-item">
                                            {{ $detail->produk->namaProduk }} ({{ $detail->jumlahProduk }}x) - 
                                            Rp {{ number_format($detail->subTotal, 2, ',', '.') }}
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ route('transaksi.printStruk', $p->PenjualanID) }}" target="_blank" class="btn btn-success btn-sm">
                                        Print Struk
                                    </a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
