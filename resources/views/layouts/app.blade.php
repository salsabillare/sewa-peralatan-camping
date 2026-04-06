<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | CampGear Hub</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f8f5ff;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            transition: background 0.3s ease;
        }

        .sidebar {
            width: 230px;
            height: 100vh;
            background-color: #C8E6C9;
            color: #2E7D32;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            padding: 20px;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 8px rgba(0,0,0,0.05);
        }

        .sidebar h2 {
            font-size: 1.4rem;
            margin-bottom: 25px;
            font-weight: bold;
            text-align: center;
            color: #2E7D32;
        }

        .sidebar a {
            color: #2E7D32;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }

        .sidebar a:hover, .sidebar a.active {
            background-color: #A5D6A7;
            color: #1B5E20;
        }

        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            background-color: #A5D6A7;
            color: #2E7D32;
            border: none;
            border-radius: 8px;
            padding: 10px;
            cursor: pointer;
            z-index: 1100;
            transition: 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .sidebar-toggle:hover {
            background-color: #81C784;
        }

        .content {
            margin-left: 250px;
            padding: 30px;
            transition: margin-left 0.3s ease;
        }

        .page-header {
            background-color: #E8F5E9;
            padding: 20px;
            border-radius: 12px;
            color: #2E7D32;
            margin-bottom: 25px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            font-weight: 600;
            font-size: 1.2rem;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            width: 300px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .modal h3 {
            color: #2E7D32;
            margin-bottom: 15px;
        }

        .modal button {
            margin: 5px;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
        }

        .modal .btn-yes {
            background-color: #81C784;
            color: white;
        }

        .modal .btn-yes:hover {
            background-color: #66BB6A;
        }

        .modal .btn-cancel {
            background-color: #A5D6A7;
            color: #2E7D32;
        }

        .modal .btn-cancel:hover {
            background-color: #81C784;
        }

        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
            }

            .sidebar.open {
                left: 0;
            }

            .content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }
        }
    </style>
</head>

<body>
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h2>CampGear Hub</h2>

        {{-- 🔹 Sidebar Admin --}}
        @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="fa fa-chart-line"></i> Dashboard
            </a>
            <a href="{{ route('products.index') }}" class="{{ request()->is('products*') ? 'active' : '' }}">
                <i class="fa fa-box"></i> Produk
            </a>
            <a href="{{ route('categories.index') }}" class="{{ request()->is('categories*') ? 'active' : '' }}">
                <i class="fa fa-folder-open"></i> Kategori
            </a>
            <a href="{{ route('orders.index') }}" class="{{ request()->is('orders*') ? 'active' : '' }}">
                <i class="fa fa-receipt"></i> Pesanan
            </a>
            <a href="{{ route('users.index') }}" class="{{ request()->is('users*') ? 'active' : '' }}">
                <i class="fa fa-users"></i> Manajemen User
            </a>
            <a href="{{ route('admin.transactions.index') }}"
   class="{{ request()->is('admin/transactions*') ? 'active' : '' }}">
   <i class="fa fa-exchange-alt"></i> Transaksi Kasir
</a>

        @endif

        {{-- 🔹 Sidebar Kasir --}}
        @if(auth()->check() && auth()->user()->role === 'kasir')
            <a href="{{ route('kasir.dashboard') }}" class="{{ request()->is('kasir/dashboard') ? 'active' : '' }}">
                <i class="fa fa-cash-register"></i> Dashboard
            </a>
            <a href="{{ route('kasir.products.index') }}" class="{{ request()->is('kasir/products*') ? 'active' : '' }}">
                <i class="fa fa-box"></i> Produk
            </a>
           <a href="{{ route('kasir.transactions.index') }}" class="{{ request()->is('kasir/transactions*') ? 'active' : '' }}">
    <i class="fa fa-exchange-alt"></i> Transaksi
</a>
        @endif

        <!-- 🔹 Menu Logout di bagian bawah -->
        <div style="margin-top:auto;">
            <a href="#" id="logoutLink">
                <i class="fa fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <!-- Modal Konfirmasi Logout -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <h3>Yakin ingin logout?</h3>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-yes">Ya, Logout</button>
            </form>
            <button class="btn-cancel" id="cancelLogout">Batal</button>
        </div>
    </div>

    <div class="content">
        <div class="page-header">
            <span>@yield('title')</span>
        </div>

        @yield('content')
    </div>

    <script>
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const logoutLink = document.getElementById('logoutLink');
        const logoutModal = document.getElementById('logoutModal');
        const cancelLogout = document.getElementById('cancelLogout');

        toggleBtn.addEventListener('click', () => sidebar.classList.toggle('open'));
        logoutLink.addEventListener('click', (e) => { e.preventDefault(); logoutModal.style.display = 'flex'; });
        cancelLogout.addEventListener('click', () => logoutModal.style.display = 'none');
        window.onclick = (event) => { if (event.target === logoutModal) logoutModal.style.display = 'none'; };
    </script>
</body>
</html>
