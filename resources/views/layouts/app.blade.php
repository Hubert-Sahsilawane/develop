<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kasir App</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            display: flex;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background-color: #343a40;
            color: white;
            padding: 20px 15px;
            transition: width 0.3s;
            z-index: 1000;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar .nav-link {
            color: white;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: #495057;
            border-radius: 5px;
        }

        .sidebar .text-label {
            transition: opacity 0.3s;
        }

        .sidebar.collapsed .text-label {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar .logo {
            width: 40px;
            height: 40px;
        }

        .sidebar img.icon {
            width: 20px;
            height: 20px;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
            transition: margin-left 0.3s;
        }

        .content.collapsed {
            margin-left: 80px;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="{{ Auth::user()->role ?? 'guest' }}">
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar d-flex flex-column">
        <!-- Tombol titik tiga -->
        <button class="toggle-btn align-self-end" onclick="toggleSidebar()">☰</button>

        <!-- Logo Online -->
        <div class="mb-3 text-center">
            <img src="https://img.icons8.com/ios-filled/50/shop.png" alt="Logo" class="logo">
        </div>

        <h5 class="text-center mb-4 text-label">KasirApp</h5>

        <ul class="nav nav-pills flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <img src="https://img.icons8.com/ios-filled/20/dashboard.png" alt="Dashboard" class="icon">
                    <span class="text-label">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <img src="https://img.icons8.com/ios-filled/20/user.png" alt="Pengguna" class="icon">
                    <span class="text-label">Pengguna</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}" href="{{ route('pelanggan.index') }}">
                    <img src="https://img.icons8.com/ios-filled/20/conference-call.png" alt="Pelanggan" class="icon">
                    <span class="text-label">Pelanggan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('produk.*') ? 'active' : '' }}" href="{{ route('produk.index') }}">
                    <img src="https://img.icons8.com/ios-filled/20/box.png" alt="Produk" class="icon">
                    <span class="text-label">Produk</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('transaksi.*') ? 'active' : '' }}" href="{{ route('transaksi.create') }}">
                    <img src="https://img.icons8.com/ios-filled/20/cash-register.png" alt="Transaksi" class="icon">
                    <span class="text-label">Transaksi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                    <img src="https://img.icons8.com/ios-filled/20/report-card.png" alt="Laporan" class="icon">
                    <span class="text-label">Laporan</span>
                </a>
            </li>
        </ul>
        <hr>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2">
                <img src="https://img.icons8.com/ios-filled/20/logout-rounded.png" alt="Logout" class="icon">
                <span class="text-label">Logout</span>
            </button>
        </form>
    </div>

    <!-- Main Content -->
    <div id="main-content" class="content">
        @yield('content')
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('main-content');
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('collapsed');
        }
    </script>
</body>
</html>
