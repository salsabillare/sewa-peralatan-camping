<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | CampGear Hub Kasir</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f8f5ff;
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }

        .sidebar {
            width: 230px;
            height: 100vh;
            background-color: #bda9f4;
            color: #4b0082;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-shadow: 2px 0 8px rgba(0,0,0,0.05);
        }

        .sidebar h2 {
            font-size: 1.4rem;
            margin-bottom: 25px;
            font-weight: bold;
            text-align: center;
            color: #4b0082;
        }

        .sidebar a {
            color: #4b0082;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: 0.3s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #d7c8fb;
            color: #4b0082;
        }

        .content {
            margin-left: 250px;
            padding: 30px;
        }

        .page-header {
            background-color: #e9e0ff;
            padding: 20px;
            border-radius: 12px;
            color: #4b0082;
            margin-bottom: 25px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-logout {
            background-color: #a78bfa;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #8b5cf6;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>CampGear Hub</h2>

        {{-- ✅ Dashboard Kasir --}}
        <a href="{{ route('kasir.dashboard') }}" class="{{ request()->is('kasir/dashboard') ? 'active' : '' }}">
            <i class="fa fa-chart-line"></i> Dashboard
        </a>

        {{-- ✅ Produk Kasir --}}
        <a href="{{ route('kasir.products.index') }}" class="{{ request()->is('kasir/products*') ? 'active' : '' }}">
            <i class="fa fa-box"></i> Produk
        </a>

        {{-- ✅ Transaksi Kasir --}}
        <a href="{{ route('kasir.transactions.index') }}" class="{{ request()->is('kasir/transactions*') ? 'active' : '' }}">
            <i class="fa fa-receipt"></i> Transaksi
        </a>

        {{-- 🟣 Order Online Kasir (Langkah 5) --}}
        <a href="{{ route('kasir.orders.index') }}" class="{{ request()->is('kasir/orders*') ? 'active' : '' }}">
            <i class="fa fa-shopping-cart"></i> Order Online
        </a>

        {{-- 🔹 Logout --}}
        <form action="{{ route('logout') }}" method="POST" style="margin-top:auto;">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fa fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>

    <div class="content">
        <div class="page-header">
            <span>@yield('title')</span>
        </div>

        @yield('content')
    </div>
</body>
</html>
