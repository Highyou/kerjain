<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
 
<!-- HERO SECTION -->
<section class="hero-section">
    <div class="container position-relative" style="z-index:1">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-inline-flex align-items-center gap-2 bg-white bg-opacity-10 rounded-pill px-3 py-1 mb-3">
                    <span class="badge bg-success rounded-pill">BARU</span>
                    <small class="text-white">1.200+ mahasiswa aktif</small>
                </div>
                <h1 class="hero-title">Temukan Jasa Terbaik<br>dari <span style="color:#81C784">Mahasiswa</span> Indonesia</h1>
                <p class="hero-subtitle mt-3 mb-4">Desain, coding, penulisan, dan ratusan jasa lainnya dari mahasiswa berbakat. Harga terjangkau, kualitas terpercaya.</p>
 
                <!-- Search Box -->
                <div class="search-box">
                    <form action="<?= base_url('cari') ?>" method="GET" class="d-flex w-100 align-items-center">
                        <i class="bi bi-search me-2" style="color:#5F6368"></i>
                        <input type="text" name="q" placeholder="Cari jasa desain logo, web app, artikel..." class="flex-grow-1">
                        <button type="submit">Cari Jasa</button>
                    </form>
                </div>
 
                <div class="d-flex gap-2 flex-wrap mt-3">
                    <small class="text-white opacity-75">Populer:</small>
                    <?php foreach (['Desain Logo','Web App','Artikel','Video Editing'] as $tag): ?>
                        <a href="<?= base_url('cari?q=' . urlencode($tag)) ?>" class="badge bg-white bg-opacity-20 text-white text-decoration-none px-3 py-1 rounded-pill"><?= $tag ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
 
<!-- CATEGORY SECTION -->
<section class="py-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="fw-800 mb-0" style="font-size:1.4rem">Kategori Jasa</h2>
            <a href="/jasa" class="text-decoration-none" style="color:var(--ki-blue);font-weight:600">Lihat Semua →</a>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <?php foreach ($categories as $cat): ?>
                <a href="<?= base_url('kategori/' . $cat['slug']) ?>" class="category-pill">
                    <i class="bi <?= $cat['icon'] ?>"></i>
                    <?= $cat['name'] ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
 
<!-- STATS STRIP -->
<section class="py-3 border-top border-bottom" style="background:white">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-6 col-md-3">
                <div class="fw-800 fs-3" style="color:var(--ki-blue)">1.200+</div>
                <div class="text-muted small fw-500">Seller Aktif</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-800 fs-3" style="color:var(--ki-green)">5.000+</div>
                <div class="text-muted small fw-500">Jasa Tersedia</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-800 fs-3" style="color:#FB8C00">15.000+</div>
                <div class="text-muted small fw-500">Order Selesai</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="fw-800 fs-3" style="color:#9C27B0">4.8★</div>
                <div class="text-muted small fw-500">Rating Rata-rata</div>
            </div>
        </div>
    </div>
</section>
 
<!-- JASA TERBARU -->
<section class="py-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="fw-800 mb-0" style="font-size:1.4rem">🔥 Jasa Populer</h2>
            <a href="<?= base_url('jasa') ?>" class="text-decoration-none" style="color:var(--ki-blue);font-weight:600">Lihat Semua →</a>
        </div>
        <div class="row g-3">
            <?php foreach ($popularServices as $svc): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <a href="<?= base_url('jasa/' . $svc['slug']) ?>" class="text-decoration-none">
                        <div class="service-card h-100">
                            <img src="<?= $svc['thumbnail'] ? '/uploads/thumbnails/'.$svc['thumbnail'] : '/img/placeholder.jpg' ?>" alt="<?= esc($svc['title']) ?>">
                            <div class="card-body">
                                <div class="seller-info">
                                    <div class="seller-avatar"><?= strtoupper(substr($svc['seller_name'], 0, 1)) ?></div>
                                    <small class="text-muted fw-500"><?= esc($svc['seller_name']) ?></small>
                                </div>
                                <div class="service-title"><?= esc($svc['title']) ?></div>
                                <div class="d-flex align-items-center gap-1 mt-1">
                                    <span class="rating-stars">★</span>
                                    <small class="fw-600"><?= number_format($svc['rating_avg'],1) ?></small>
                                    <small class="text-muted">(<?= $svc['total_reviews'] ?>)</small>
                                </div>
                                <div class="service-price mt-2">
                                    Rp <?= number_format($svc['price'],0,',','.') ?>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
 
<!-- CTA SECTION -->
<section class="py-5" style="background: linear-gradient(135deg, var(--ki-green) 0%, #2d9248 100%); color:white">
    <div class="container text-center">
        <h2 class="fw-800 mb-2">Kamu Mahasiswa Berbakat?</h2>
        <p class="mb-4 opacity-90">Jual jasa kamu dan raih penghasilan tambahan. Gratis, mudah, dan langsung dari kampus!</p>
        <a href="<?= base_url('auth/register') ?>" class="btn btn-light fw-700 px-4 py-2 rounded-pill">Daftar Jadi Seller →</a>
    </div>
</section>
 
<?= $this->endSection() ?>