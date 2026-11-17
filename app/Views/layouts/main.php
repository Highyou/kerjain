<!DOCTYPE html>
<!-- app/Views/layouts/main.php — Layout utama KerjaIn -->
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'KerjaIn' ?> — Marketplace Jasa Mahasiswa</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ──────────────────────────────────────
           CSS VARIABLES — KerjaIn Design System
           ────────────────────────────────────── */
        :root {
            --ki-blue:       #1A73E8;
            --ki-blue-dark:  #1557B0;
            --ki-blue-light: #E8F0FE;
            --ki-green:      #34A853;
            --ki-green-light:#E6F4EA;
            --ki-white:      #FFFFFF;
            --ki-gray-50:    #F8F9FA;
            --ki-gray-100:   #F1F3F4;
            --ki-gray-300:   #DADCE0;
            --ki-gray-600:   #5F6368;
            --ki-gray-800:   #202124;
            --ki-shadow-sm:  0 1px 3px rgba(0,0,0,.08);
            --ki-shadow:     0 2px 10px rgba(0,0,0,.1);
            --ki-shadow-lg:  0 8px 30px rgba(0,0,0,.12);
            --ki-radius:     12px;
            --ki-radius-sm:  8px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--ki-gray-50);
            color: var(--ki-gray-800);
            min-height: 100vh;
        }

        /* ── NAVBAR ── */
        .navbar-kerjain {
            background: var(--ki-white) !important;
            box-shadow: var(--ki-shadow-sm);
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--ki-blue) !important;
            letter-spacing: -0.5px;
        }

        .navbar-brand span { color: var(--ki-green); }

        .nav-link {
            font-weight: 500;
            color: var(--ki-gray-600) !important;
            padding: 0.5rem 0.9rem !important;
            border-radius: 8px;
            transition: all .2s;
        }

        .nav-link:hover, .nav-link.active {
            background: var(--ki-blue-light);
            color: var(--ki-blue) !important;
        }

        .btn-cart {
            position: relative;
        }

        .cart-badge {
            position: absolute;
            top: -6px; right: -6px;
            background: var(--ki-green);
            color: white;
            border-radius: 50%;
            width: 18px; height: 18px;
            font-size: 11px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
        }

        /* ── BUTTONS ── */
        .btn-ki-primary {
            background: var(--ki-blue);
            color: white;
            border: none;
            border-radius: var(--ki-radius-sm);
            font-weight: 600;
            padding: 0.6rem 1.4rem;
            transition: all .2s;
        }

        .btn-ki-primary:hover {
            background: var(--ki-blue-dark);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(26,115,232,.3);
        }

        .btn-ki-green {
            background: var(--ki-green);
            color: white;
            border: none;
            border-radius: var(--ki-radius-sm);
            font-weight: 600;
            padding: 0.6rem 1.4rem;
            transition: all .2s;
        }

        .btn-ki-green:hover { background: #2d9248; color: white; }

        .btn-ki-outline {
            background: transparent;
            color: var(--ki-blue);
            border: 2px solid var(--ki-blue);
            border-radius: var(--ki-radius-sm);
            font-weight: 600;
            padding: 0.55rem 1.4rem;
            transition: all .2s;
        }

        .btn-ki-outline:hover {
            background: var(--ki-blue);
            color: white;
        }

        /* ── CARDS JASA ── */
        .service-card {
            background: var(--ki-white);
            border-radius: var(--ki-radius);
            border: 1px solid var(--ki-gray-300);
            overflow: hidden;
            transition: all .25s;
            cursor: pointer;
        }

        .service-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--ki-shadow-lg);
            border-color: var(--ki-blue);
        }

        .service-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .service-card .card-body { padding: 1rem 1.1rem; }

        .service-card .seller-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .seller-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: var(--ki-blue-light);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: var(--ki-blue);
            flex-shrink: 0;
        }

        .service-title {
            font-weight: 600;
            font-size: 0.92rem;
            color: var(--ki-gray-800);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }

        .service-price {
            font-weight: 800;
            font-size: 1.05rem;
            color: var(--ki-blue);
        }

        .rating-stars { color: #FFC107; font-size: 0.8rem; }

        /* ── CATEGORY PILLS ── */
        .category-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 50px;
            background: var(--ki-white);
            border: 1.5px solid var(--ki-gray-300);
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--ki-gray-600);
            cursor: pointer;
            transition: all .2s;
            text-decoration: none;
        }

        .category-pill:hover, .category-pill.active {
            background: var(--ki-blue);
            border-color: var(--ki-blue);
            color: white;
        }

        /* ── DASHBOARD STATS ── */
        .stat-card {
            background: var(--ki-white);
            border-radius: var(--ki-radius);
            padding: 1.4rem;
            border-left: 4px solid var(--ki-blue);
            box-shadow: var(--ki-shadow-sm);
        }

        .stat-card.green { border-left-color: var(--ki-green); }
        .stat-card.orange { border-left-color: #FB8C00; }
        .stat-card.purple { border-left-color: #9C27B0; }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--ki-gray-800);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.82rem;
            color: var(--ki-gray-600);
            font-weight: 500;
            margin-top: 4px;
        }

        /* ── SIDEBAR DASHBOARD ── */
        .sidebar {
            background: var(--ki-white);
            min-height: calc(100vh - 70px);
            border-right: 1px solid var(--ki-gray-300);
            padding: 1.5rem 0;
            position: sticky;
            top: 70px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.65rem 1.5rem;
            color: var(--ki-gray-600);
            font-weight: 500;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all .15s;
        }

        .sidebar-link:hover, .sidebar-link.active {
            background: var(--ki-blue-light);
            color: var(--ki-blue);
        }

        .sidebar-link i { font-size: 1.1rem; }

        /* ── HERO ── */
        .hero-section {
            background: linear-gradient(135deg, var(--ki-blue) 0%, #0052CC 100%);
            color: white;
            padding: 5rem 0 4rem;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255,255,255,.05);
            border-radius: 50%;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,.04);
            border-radius: 50%;
        }

        .hero-title {
            font-size: 2.6rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            opacity: .9;
            font-weight: 400;
        }

        .search-box {
            background: white;
            border-radius: 50px;
            padding: 6px 6px 6px 20px;
            display: flex;
            align-items: center;
            max-width: 560px;
            box-shadow: 0 8px 30px rgba(0,0,0,.15);
        }

        .search-box input {
            border: none;
            outline: none;
            flex: 1;
            font-size: 0.95rem;
            color: var(--ki-gray-800);
            background: transparent;
        }

        .search-box button {
            background: var(--ki-green);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 0.55rem 1.4rem;
            font-weight: 600;
            transition: all .2s;
        }

        .search-box button:hover { background: #2d9248; }

        /* ── ALERTS ── */
        .alert-ki {
            border: none;
            border-radius: var(--ki-radius-sm);
            font-weight: 500;
        }

        /* ── FORMS ── */
        .form-control, .form-select {
            border-radius: var(--ki-radius-sm);
            border: 1.5px solid var(--ki-gray-300);
            font-size: 0.93rem;
            padding: 0.6rem 0.9rem;
            transition: border-color .2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--ki-blue);
            box-shadow: 0 0 0 3px rgba(26,115,232,.15);
        }

        .form-label { font-weight: 600; font-size: 0.88rem; color: var(--ki-gray-600); }

        /* ── TABLES ── */
        .table-ki { font-size: 0.9rem; }
        .table-ki thead th {
            background: var(--ki-gray-100);
            font-weight: 700;
            color: var(--ki-gray-600);
            border: none;
            padding: 0.85rem 1rem;
        }
        .table-ki tbody td { padding: 0.85rem 1rem; vertical-align: middle; }
        .table-ki tbody tr:hover { background: var(--ki-gray-50); }

        /* ── STATUS BADGES ── */
        .badge-pending    { background: #FFF3E0; color: #E65100; }
        .badge-active     { background: var(--ki-green-light); color: #1E8E3E; }
        .badge-completed  { background: #E8F5E9; color: #2E7D32; }
        .badge-cancelled  { background: #FFEBEE; color: #C62828; }
        .badge-in-progress{ background: var(--ki-blue-light); color: var(--ki-blue-dark); }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 600;
        }

        /* ── FOOTER ── */
        .footer-ki {
            background: var(--ki-gray-800);
            color: rgba(255,255,255,.7);
            padding: 2.5rem 0;
            margin-top: 4rem;
            font-size: 0.88rem;
        }

        .footer-ki a { color: rgba(255,255,255,.7); text-decoration: none; }
        .footer-ki a:hover { color: white; }
    </style>
</head>
<body>

<!-- ══ NAVBAR ══ -->
<nav class="navbar navbar-expand-lg navbar-kerjain">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url('/') ?>">KerjaIn<span>.</span></a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('jasa') ?>">
                        <i class="bi bi-grid me-1"></i>Jelajahi Jasa
                    </a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <?php if (session()->has('user_id')): ?>
                    <?php if (session('role') === 'buyer'): ?>
                        <a href="<?= base_url('buyer/cart') ?>" class="btn btn-light btn-sm btn-cart position-relative">
                            <i class="bi bi-cart2"></i>
                        </a>
                    <?php endif; ?>

                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            <span><?= session('name') ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <a class="dropdown-item" href="<?= base_url(session('role') . '/dashboard') ?>">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= base_url(session('role') . '/profile') ?>">
                                    <i class="bi bi-person me-2"></i>Profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= base_url('auth/login') ?>" class="btn-ki-outline btn">Login</a>
                    <a href="<?= base_url('auth/register') ?>" class="btn-ki-primary btn">Daftar Gratis</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- ══ FLASH MESSAGES ══ -->
<div class="container mt-3">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-ki alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i><?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-ki alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-ki alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <ul class="mb-0 mt-1">
                <?php foreach (session()->getFlashdata('errors') as $err): ?>
                    <li><?= $err ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
</div>

<!-- ══ KONTEN HALAMAN ══ -->
<?= $this->renderSection('content') ?>

<!-- ══ FOOTER ══ -->
<footer class="footer-ki">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="text-white fw-800 mb-2">KerjaIn<span style="color:var(--ki-green)">.</span></h5>
                <p>Platform jasa mahasiswa terpercaya. Temukan talent terbaik dari kampus untuk kebutuhan Anda.</p>
            </div>
            <div class="col-md-2">
                <h6 class="text-white fw-700 mb-3">Platform</h6>
                <ul class="list-unstyled">
                    <li class="mb-1"><a href="<?= base_url('jasa') ?>">Jelajahi Jasa</a></li>
                    <li class="mb-1"><a href="<?= base_url('auth/register') ?>">Daftar Seller</a></li>
                    <li class="mb-1"><a href="<?= base_url('how-it-works') ?>">Cara Kerja</a></li>
                </ul>
            </div>
            <div class="col-md-2">
                <h6 class="text-white fw-700 mb-3">Kategori</h6>
                <ul class="list-unstyled">
                    <li class="mb-1"><a href="<?= base_url('kategori/desain-grafis') ?>">Desain</a></li>
                    <li class="mb-1"><a href="<?= base_url('kategori/pemrograman') ?>">Pemrograman</a></li>
                    <li class="mb-1"><a href="<?= base_url('kategori/penulisan') ?>">Penulisan</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="text-white fw-700 mb-3">Kontak</h6>
                <p><i class="bi bi-envelope me-2"></i>halo@kerjain.id</p>
                <p><i class="bi bi-instagram me-2"></i>@kerjain.id</p>
                <div class="d-flex gap-3 mt-2">
                    <a href="#"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="#"><i class="bi bi-twitter-x fs-5"></i></a>
                    <a href="#"><i class="bi bi-tiktok fs-5"></i></a>
                </div>
            </div>
        </div>
        <hr style="border-color:rgba(255,255,255,.1); margin: 1.5rem 0;">
        <p class="text-center mb-0">© <?= date('Y') ?> KerjaIn — Marketplace Jasa Mahasiswa Indonesia</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-hide alerts
document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => {
        const alert = bootstrap.Alert.getOrCreateInstance(el);
        if (alert) alert.close();
    }, 5000);
});
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
