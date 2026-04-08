<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampGear Hub - Sewa Peralatan Camping</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --forest: #2D4A1E;
            --forest-mid: #3D6428;
            --leaf: #5E8C32;
            --lime: #8DC63F;
            --cream: #F8F4EC;
            --bark: #8B6F47;
            --stone: #1C1C1C;
            --mist: #EEF2E8;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--cream);
            color: var(--stone);
        }

        /* ── HEADER ─────────────────────────────── */
        header {
            background-color: var(--forest);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .header-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
            height: 68px;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            flex-shrink: 0;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: var(--lime);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .logo-text {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.2rem;
            color: #fff;
            line-height: 1;
        }

        .logo-sub {
            font-size: 0.6rem;
            color: var(--lime);
            letter-spacing: 0.15em;
            text-transform: uppercase;
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
        }

        /* Search */
        .search-wrap {
            flex: 1;
            display: flex;
            max-width: 560px;
        }

        .search-wrap input {
            flex: 1;
            padding: 0.6rem 1.1rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            border: none;
            border-radius: 8px 0 0 8px;
            background: rgba(255,255,255,0.12);
            color: #fff;
            outline: none;
            transition: background 0.2s;
        }

        .search-wrap input::placeholder { color: rgba(255,255,255,0.45); }
        .search-wrap input:focus { background: rgba(255,255,255,0.2); }

        .search-wrap button {
            padding: 0 1.1rem;
            background: var(--lime);
            border: none;
            border-radius: 0 8px 8px 0;
            color: var(--forest);
            font-size: 0.9rem;
            cursor: pointer;
            transition: background 0.2s;
        }

        .search-wrap button:hover { background: #a3d44d; }

        /* Nav links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-left: auto;
        }

        .nav-icon-btn {
            color: rgba(255,255,255,0.8);
            font-size: 1.1rem;
            transition: color 0.2s;
            text-decoration: none;
            position: relative;
        }

        .nav-icon-btn:hover { color: var(--lime); }

        .nav-text-btn {
            color: rgba(255,255,255,0.8);
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .nav-text-btn:hover { color: #fff; background: rgba(255,255,255,0.1); }

        .nav-cta {
            background: var(--lime);
            color: var(--forest) !important;
            font-weight: 600 !important;
            padding: 0.45rem 1rem !important;
        }

        .nav-cta:hover { background: #a3d44d !important; }

        /* ── HERO / BANNER ─────────────────────── */
        .hero-section {
            max-width: 1280px;
            margin: 2.5rem auto 0;
            padding: 0 1.5rem;
        }

        .hero-card {
            background: var(--forest);
            border-radius: 20px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 360px;
            position: relative;
        }

        .hero-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .hero-video-wrap {
            position: relative;
            background: #000;
            overflow: hidden;
        }

        .hero-video-wrap video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .hero-content {
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(141, 198, 63, 0.2);
            border: 1px solid rgba(141, 198, 63, 0.4);
            color: var(--lime);
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 0.35rem 0.8rem;
            border-radius: 100px;
            width: fit-content;
            margin-bottom: 1.25rem;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.4rem;
            font-weight: 900;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 1rem;
        }

        .hero-title span {
            color: var(--lime);
        }

        .hero-desc {
            color: rgba(255,255,255,0.65);
            font-size: 0.95rem;
            line-height: 1.65;
            margin-bottom: 1.75rem;
        }

        .hero-stats {
            display: flex;
            gap: 1.5rem;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
        }

        .stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--lime);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.5);
            margin-top: 0.2rem;
        }

        /* ── SECTION HEADING ───────────────────── */
        .section-wrap {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .section-header {
            display: flex;
            align-items: baseline;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--forest);
        }

        .section-line {
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, #c8d8b0, transparent);
        }

        /* ── KATEGORI ──────────────────────────── */
        .categories-section {
            margin-top: 3rem;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 0.75rem;
        }

        .category-card {
            text-decoration: none;
            background: #fff;
            border: 1px solid #e2ead4;
            border-radius: 14px;
            padding: 1.1rem 0.5rem;
            text-align: center;
            transition: all 0.25s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .category-card:hover {
            border-color: var(--leaf);
            background: var(--mist);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(61,100,40,0.1);
        }

        .cat-icon-wrap {
            width: 46px;
            height: 46px;
            background: var(--mist);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: var(--leaf);
            border: 1px solid #c8d8b0;
            transition: background 0.2s;
        }

        .category-card:hover .cat-icon-wrap {
            background: var(--leaf);
            color: #fff;
            border-color: var(--leaf);
        }

        .cat-name {
            font-size: 0.78rem;
            font-weight: 500;
            color: #3a4a2e;
            line-height: 1.3;
        }

        /* ── PRODUK ────────────────────────────── */
        .products-section {
            margin-top: 3rem;
            padding-bottom: 4rem;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.1rem;
        }

        .product-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2ead4;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 36px rgba(45,74,30,0.13);
            border-color: #b5cda0;
        }

        .product-img-wrap {
            background: var(--mist);
            aspect-ratio: 4/3;
            overflow: hidden;
            position: relative;
        }

        .product-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 1rem;
            transition: transform 0.35s ease;
        }

        .product-card:hover .product-img-wrap img {
            transform: scale(1.06);
        }

        .rent-badge {
            position: absolute;
            top: 0.6rem;
            left: 0.6rem;
            background: var(--forest);
            color: var(--lime);
            font-size: 0.62rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
        }

        .product-info {
            padding: 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--stone);
            line-height: 1.4;
            margin-bottom: auto;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price-row {
            display: flex;
            align-items: baseline;
            gap: 0.3rem;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid #eef2e8;
        }

        .product-price {
            font-family: 'Playfair Display', serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--leaf);
        }

        .product-unit {
            font-size: 0.72rem;
            color: #888;
        }

        /* Empty state */
        .empty-state {
            background: #fff;
            border: 1px dashed #c8d8b0;
            border-radius: 16px;
            padding: 3rem 2rem;
            text-align: center;
            grid-column: 1 / -1;
        }

        .empty-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #b5cda0;
        }

        .empty-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            color: var(--forest);
            margin-bottom: 0.5rem;
        }

        .empty-desc {
            font-size: 0.85rem;
            color: #888;
        }

        /* ── FOOTER ────────────────────────────── */
        footer {
            background: var(--forest);
            color: rgba(255,255,255,0.75);
            padding: 3.5rem 0 2rem;
        }

        .footer-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1.5fr;
            gap: 3rem;
            margin-bottom: 2.5rem;
        }

        .footer-brand-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            color: #fff;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-brand-icon {
            width: 30px;
            height: 30px;
            background: var(--lime);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .footer-desc {
            font-size: 0.85rem;
            line-height: 1.7;
            color: rgba(255,255,255,0.5);
        }

        .footer-heading {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--lime);
            margin-bottom: 1rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .footer-links a {
            font-size: 0.88rem;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover { color: var(--lime); }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 0.6rem;
            font-size: 0.88rem;
            color: rgba(255,255,255,0.6);
        }

        .footer-contact-item i {
            color: var(--lime);
            width: 14px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .footer-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1.5rem;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.35);
        }

        .footer-bottom strong {
            color: rgba(255,255,255,0.7);
        }

        /* ── RESPONSIVE ────────────────────────── */
        @media (max-width: 768px) {
            .hero-card {
                grid-template-columns: 1fr;
            }

            .hero-video-wrap {
                min-height: 220px;
            }

            .hero-title {
                font-size: 1.8rem;
            }

            .hero-stats {
                gap: 1rem;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 0.4rem;
                text-align: center;
            }

            .header-inner {
                flex-wrap: wrap;
                height: auto;
                padding: 0.75rem 1rem;
                gap: 0.75rem;
            }

            .search-wrap {
                order: 3;
                max-width: 100%;
                width: 100%;
            }
        }
    </style>
</head>

<body>

<!-- ═══════════════ HEADER ═══════════════ -->
<header>
    <div class="header-inner">

        <a href="/" class="logo">
            <div class="logo-icon">⛺</div>
            <div>
                <div class="logo-text">CampGear Hub</div>
                <div class="logo-sub">Rental Outdoor</div>
            </div>
        </a>

        <form action="{{ route('welcome') }}" method="GET" class="search-wrap">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari tenda, carrier, sleeping bag...">
            <button type="submit">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>

        <div class="nav-links">
            @auth
                <a href="{{ route('cart.index') }}" class="nav-icon-btn" title="Keranjang">
                    <i class="fa-solid fa-backpack"></i>
                </a>
                <a href="{{ url('/shop') }}" class="nav-text-btn">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="nav-icon-btn" title="Keranjang">
                    <i class="fa-solid fa-backpack"></i>
                </a>
                <a href="{{ route('login') }}" class="nav-text-btn">Masuk</a>
                <a href="{{ route('register') }}" class="nav-text-btn nav-cta">Daftar</a>
            @endauth
        </div>

    </div>
</header>


<!-- ═══════════════ HERO / BANNER ═══════════════ -->
<section class="hero-section">
    <div class="hero-card">

        <!-- VIDEO -->
        <div class="hero-video-wrap">
            <video width="100%" height="100%" controls>
                <source src="{{ asset('storage/videos/hiking.mp4') }}" type="video/mp4">
                Browser Anda tidak mendukung video HTML5.
            </video>
        </div>

        <!-- TEXT -->
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fa-solid fa-star" style="font-size:10px"></i>
                Terpercaya sejak 2020
            </div>

            <h1 class="hero-title">
                Peralatan Camping<br>
                <span>Kualitas Premium,</span><br>
                Harga Terjangkau
            </h1>

            <p class="hero-desc">
                Dari tenda hingga carrier — kami siap melengkapi setiap petualangan outdoor impian Anda dengan peralatan terbaik.
            </p>

            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-num">200+</span>
                    <span class="stat-label">Produk tersedia</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">4.9★</span>
                    <span class="stat-label">Rating pelanggan</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">1 hari</span>
                    <span class="stat-label">Min. sewa</span>
                </div>
            </div>
        </div>

    </div>
</section>


<!-- ═══════════════ KATEGORI ═══════════════ -->
<section class="section-wrap categories-section" style="margin-top: 3rem;">
    <div class="section-header">
        <h2 class="section-title">Kategori Peralatan</h2>
        <div class="section-line"></div>
    </div>

    <div class="category-grid">
        @foreach ($categories as $category)
            <a href="{{ route('welcome', ['category' => $category->id]) }}" class="category-card">
                <div class="cat-icon-wrap">
                    <i class="fa-solid fa-leaf"></i>
                </div>
                <span class="cat-name">{{ $category->name }}</span>
            </a>
        @endforeach
    </div>
</section>


<!-- ═══════════════ PRODUK ═══════════════ -->
<section class="section-wrap products-section">
    <div class="section-header">
        <h2 class="section-title">Peralatan Sewa</h2>
        <div class="section-line"></div>
    </div>

    <div class="product-grid">
        @if ($products->isEmpty())
            <div class="empty-state">
                <div class="empty-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                <p class="empty-title">Produk tidak ditemukan</p>
                <p class="empty-desc">Coba ubah kata kunci pencarian atau pilih kategori yang berbeda.</p>
            </div>
        @else
            @foreach ($products as $product)
            <div class="product-card">
                <a href="{{ route('product.show', $product->id) }}" class="product-img-wrap">
                    <span class="rent-badge">Sewa</span>
                    <img src="{{ asset('storage/'.$product->image) }}"
                         alt="{{ $product->name }}">
                </a>

                <div class="product-info">
                    <p class="product-name">{{ $product->name }}</p>
                    <div class="product-price-row">
                        <span class="product-price">Rp {{ number_format($product->price,0,',','.') }}</span>
                        <span class="product-unit">/ hari</span>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</section>


<!-- ═══════════════ FOOTER ═══════════════ -->
<footer>
    <div class="footer-inner">

        <div class="footer-grid">

            <!-- Brand -->
            <div>
                <div class="footer-brand-name">
                    <div class="footer-brand-icon">⛺</div>
                    CampGear Hub
                </div>
                <p class="footer-desc">
                    Toko sewa peralatan camping terpercaya dengan kualitas terbaik untuk mendukung setiap petualangan outdoor Anda. Lengkap, terjangkau, dan selalu siap.
                </p>
            </div>

            <!-- Nav -->
            <div>
                <p class="footer-heading">Navigasi</p>
                <ul class="footer-links">
                    <li><a href="{{ route('welcome') }}">Beranda</a></li>
                    <li><a href="{{ route('shop.index') }}">Toko</a></li>
                    @auth
                        <li><a href="{{ route('customer.orders.index') }}">Pesanan Saya</a></li>
                    @endauth
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <p class="footer-heading">Hubungi Kami</p>
                <div class="footer-contact-item">
                    <i class="fa-solid fa-phone"></i>
                    <span>+62 882 3836 2615</span>
                </div>
                <div class="footer-contact-item">
                    <i class="fa-solid fa-envelope"></i>
                    <span>info@campgearhub.com</span>
                </div>
                <div class="footer-contact-item">
                    <i class="fa-solid fa-map-marker-alt"></i>
                    <span>Yogyakarta, Indonesia</span>
                </div>
            </div>

        </div>

        <hr class="footer-divider">

        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} <strong>CampGear Hub</strong> &mdash; Sewa Peralatan Camping Terpercaya</span>
            <span>Dikembangkan oleh <strong>Salsabilla Fitri Choirunnisa</strong></span>
        </div>

    </div>
</footer>

</body>
</html>