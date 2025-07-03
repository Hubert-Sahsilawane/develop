@extends('layouts.app')

@section('content')
<div class="alert alert-primary" role="alert">
    Hai, <strong>{{ Auth::user()->name }}</strong>! Anda login sebagai <strong>{{ ucfirst(Auth::user()->role) }}</strong>.
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body" style="height: 350px;">
                <h5 class="card-title">Produk Terjual (Total)</h5>
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body" style="height: 350px;">
                <h5 class="card-title">Grafik Penjualan 7 Hari Terakhir</h5>
                <canvas id="dailyChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body" style="height: 350px;">
                <h5 class="card-title">Grafik Penjualan Selama 1 Bulan</h5>
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body" style="height: 350px;">
                <h5 class="card-title">Produk dengan Stok Menipis (&lt; 10)</h5>
                <canvas id="lowStockChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body" style="height: 350px;">
                <h5 class="card-title">Top 5 Barang Terlaris</h5>
                <canvas id="topProductChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body" style="height: 350px;">
                <h5 class="card-title">Barang yang Kurang Diminati</h5>
                <canvas id="lowDemandChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    const dailyCtx = document.getElementById('dailyChart').getContext('2d');

    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: @json($labelsProduk),
            datasets: [{
                data: @json($dataProduk),
                backgroundColor: [
                    '#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b0', '#6610f2',
                    '#6f42c1', '#fd7e14', '#20c997', '#0d6efd'
                ]
            }]
        },
        options: { responsive: true }
    });

    new Chart(dailyCtx, {
        type: 'bar',
        data: {
            labels: @json($tanggalLabels),
            datasets: [{
                label: 'Jumlah Penjualan',
                data: @json($dataPenjualan),
                backgroundColor: 'rgba(255, 99, 132, 0.7)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: @json($labelsBulan),
            datasets: [{
                label: 'Penjualan Harian',
                data: @json($dataBulan),
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false
            }]
        },
        options: { responsive: true }
    });

    new Chart(document.getElementById('lowStockChart'), {
        type: 'bar',
        data: {
            labels: @json($produkStokMenipis),
            datasets: [{
                label: 'Stok Tersisa',
                data: @json($stokMenipis),
                backgroundColor: 'rgba(255, 206, 86, 0.8)'
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            scales: { x: { beginAtZero: true } }
        }
    });

    new Chart(document.getElementById('topProductChart'), {
        type: 'bar',
        data: {
            labels: @json($produkTerlaris),
            datasets: [{
                label: 'Jumlah Terjual',
                data: @json($jumlahTerlaris),
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            scales: { x: { beginAtZero: true } }
        }
    });

    new Chart(document.getElementById('lowDemandChart'), {
        type: 'doughnut',
        data: {
            labels: @json($produkSepi),
            datasets: [{
                data: @json($jumlahSepi),
                backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56']
            }]
        },
        options: { responsive: true }
    });
</script>
@endsection
