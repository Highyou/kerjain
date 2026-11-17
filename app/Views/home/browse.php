<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-800 mb-1">
                <?= isset($search_q) ? 'Hasil Pencarian: "' . esc($search_q) . '"' : 'Jelajahi Semua Jasa' ?>
            </h4>
            <p class="text-muted small mb-0">
                <?= count($services) ?> jasa ditemukan
            </p>
        </div>
    </div>

    <div class="row g-4">

        <!-- SIDEBAR FILTER -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                <div class="card-body p-3">
                    <h6 class="fw-700 mb-3">Filter & Urutkan</h6>

                    <form action="<?= base_url('jasa') ?>" method="GET">

                        <!-- Search -->
                        <?php if (isset($search_q)): ?>
                            <input type="hidden" name="search_q" value="<?= esc($search_q) ?>">
                        <?php endif; ?>

                        <!-- Kategori -->
                        <div class="mb-3">
                            <label class="form-label fw-600 small">Kategori</label>
                            <select name="kategori" class="form-select form-select-sm">
                                <option value="">Semua Kategori</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['slug'] ?>"
                                        <?= request()->getGet('kategori') === $cat['slug'] ? 'selected' : '' ?>>
                                        <?= esc($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Urutkan -->
                        <div class="mb-3">
                            <label class="form-label fw-600 small">Urutkan</label>
                            <select name="sort" class="form-select form-select-sm">
                                <option value="popular"  <?= request()->getGet('sort') === 'popular'  ? 'selected' : '' ?>>Terpopuler</option>
                                <option value="newest"   <?= request()->getGet('sort') === 'newest'   ? 'selected' : '' ?>>Terbaru</option>
                                <option value="cheapest" <?= request()->getGet('sort') === 'cheapest' ? 'selected' : '' ?>>Harga Terendah</option>
                                <option value="rating"   <?= request()->getGet('sort') === 'rating'   ? 'selected' : '' ?>>Rating Tertinggi</option>
                            </select>
                        </div>

                        <!-- Range Harga -->
                        <div class="mb-3">
                            <label class="form-label fw-600 small">Harga Minimum (Rp)</label>
                            <input type="number" name="min_price" class="form-control form-control-sm"
                                   placeholder="0" value="<?= request()->getGet('min_price') ?>">
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-600 small">Harga Maksimum (Rp)</label>
                            <input type="number" name="max_price" class="form-control form-control-sm"
                                   placeholder="1000000" value="<?= request()->getGet('max_price') ?>">
                        </div>

                        <button type="submit" class="btn-ki-primary btn w-100 btn-sm">
                            <i class="bi bi-funnel me-1"></i>Terapkan Filter
                        </button>
                        <a href="<?= base_url('jasa') ?>" class="btn btn-light w-100 btn-sm mt-2">
                            Reset
                        </a>
                    </form>
                </div>
            </div>

            <!-- Kategori Cepat -->
            <div class="card border-0 shadow-sm mt-3" style="border-radius:var(--ki-radius)">
                <div class="card-body p-3">
                    <h6 class="fw-700 mb-3">Kategori Populer</h6>
                    <?php foreach ($categories as $cat): ?>
                        <a href="<?= base_url('jasa?kategori=' . $cat['slug']) ?>"
                           class="d-flex align-items-center gap-2 py-2 text-decoration-none
                                  <?= request()->getGet('kategori') === $cat['slug'] ? 'text-primary fw-700' : 'text-muted' ?>"
                           style="border-bottom:1px solid var(--ki-gray-100);font-size:.88rem">
                            <i class="bi <?= $cat['icon'] ?>"></i>
                            <?= esc($cat['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- GRID JASA -->
        <div class="col-md-9">

            <!-- Filter aktif info -->
            <?php if (request()->getGet('kategori') || request()->getGet('sort')): ?>
                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                    <small class="text-muted">Filter aktif:</small>
                    <?php if (request()->getGet('kategori')): ?>
                        <span class="badge" style="background:var(--ki-blue-light);color:var(--ki-blue)">
                            Kategori: <?= request()->getGet('kategori') ?>
                        </span>
                    <?php endif; ?>
                    <?php if (request()->getGet('sort')): ?>
                        <span class="badge" style="background:var(--ki-blue-light);color:var(--ki-blue)">
                            Urut: <?= request()->getGet('sort') ?>
                        </span>
                    <?php endif; ?>
                    <a href="<?= base_url('jasa') ?>" class="text-danger small">× Reset</a>
                </div>
            <?php endif; ?>

            <?php if (empty($services)): ?>
                <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="bi bi-search fs-1 d-block mb-3"></i>
                        <h6 class="fw-700">Jasa tidak ditemukan</h6>
                        <p class="small">Coba ubah filter atau kata kunci pencarian</p>
                        <a href="<?= base_url('jasa') ?>" class="btn-ki-primary btn btn-sm">Lihat Semua Jasa</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($services as $svc): ?>
                        <div class="col-sm-6 col-lg-4">
                            <a href="<?= base_url('jasa/' . $svc['slug']) ?>" class="text-decoration-none">
                                <div class="service-card h-100">
                                    <img src="<?= $svc['thumbnail']
                                        ? base_url('uploads/thumbnails/' . $svc['thumbnail'])
                                        : 'https://placehold.co/400x200/E8F0FE/1A73E8?text=KerjaIn' ?>"
                                         alt="<?= esc($svc['title']) ?>">
                                    <div class="card-body">
                                        <div class="seller-info">
                                            <div class="seller-avatar">
                                                <?= strtoupper(substr($svc['seller_name'], 0, 1)) ?>
                                            </div>
                                            <small class="text-muted fw-500"><?= esc($svc['seller_name']) ?></small>
                                        </div>

                                        <div class="service-title"><?= esc($svc['title']) ?></div>

                                        <div class="d-flex align-items-center gap-1 mt-1">
                                            <span class="rating-stars">★</span>
                                            <small class="fw-600"><?= number_format($svc['rating_avg'], 1) ?></small>
                                            <small class="text-muted">(<?= $svc['total_reviews'] ?>)</small>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <div class="service-price">
                                                Rp <?= number_format($svc['price'], 0, ',', '.') ?>
                                            </div>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i><?= $svc['delivery_days'] ?>h
                                            </small>
                                        </div>

                                        <div class="mt-1">
                                            <span class="badge" style="background:var(--ki-blue-light);color:var(--ki-blue);font-size:.72rem">
                                                <?= esc($svc['category_name'] ?? '') ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Paginasi -->
                <?php if (isset($pager)): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?= $pager->links() ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>