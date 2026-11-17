<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <div class="row g-4">

        <!-- KONTEN UTAMA -->
        <div class="col-md-8">

            <!-- Breadcrumb -->
            <nav class="mb-3" style="font-size:.85rem">
                <a href="<?= base_url('jasa') ?>" class="text-decoration-none" style="color:var(--ki-blue)">Jasa</a>
                <span class="mx-2 text-muted">›</span>
                <span class="text-muted"><?= esc($service['category_name'] ?? '') ?></span>
                <span class="mx-2 text-muted">›</span>
                <span class="text-muted"><?= esc(substr($service['title'], 0, 40)) ?>...</span>
            </nav>

            <!-- Judul -->
            <h2 class="fw-800 mb-3" style="font-size:1.6rem;line-height:1.3">
                <?= esc($service['title']) ?>
            </h2>

            <!-- Info Seller -->
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="seller-avatar" style="width:44px;height:44px;font-size:1.1rem">
                    <?= strtoupper(substr($service['seller_name'], 0, 1)) ?>
                </div>
                <div>
                    <div class="fw-700"><?= esc($service['seller_name']) ?></div>
                    <div class="text-muted small">
                        <?= esc($service['university'] ?? '') ?>
                        <?php if (!empty($service['university'])): ?>
                            <span class="mx-1">•</span>
                        <?php endif; ?>
                        <span class="rating-stars">★</span>
                        <strong><?= number_format($service['seller_rating'] ?? 0, 1) ?></strong>
                    </div>
                </div>
                <div class="ms-auto d-flex gap-2">
                    <span class="badge" style="background:var(--ki-blue-light);color:var(--ki-blue)">
                        <?= esc($service['category_name'] ?? '') ?>
                    </span>
                    <?php if ($service['total_orders'] > 0): ?>
                        <span class="badge bg-success">
                            <?= $service['total_orders'] ?>+ order
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Thumbnail -->
            <div class="mb-4">
                <img src="<?= $service['thumbnail']
                    ? base_url('uploads/thumbnails/' . $service['thumbnail'])
                    : 'https://placehold.co/800x400/E8F0FE/1A73E8?text=' . urlencode($service['title']) ?>"
                     class="w-100 rounded-3" style="max-height:400px;object-fit:cover">
            </div>

            <!-- Deskripsi -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--ki-radius)">
                <div class="card-body p-4">
                    <h5 class="fw-700 mb-3">Tentang Jasa Ini</h5>
                    <div style="line-height:1.8;color:var(--ki-gray-600)">
                        <?= nl2br(esc($service['description'])) ?>
                    </div>

                    <?php if (!empty($service['tags'])): ?>
                        <div class="mt-3 d-flex flex-wrap gap-2">
                            <?php foreach (explode(' ', $service['tags']) as $tag): ?>
                                <?php if (trim($tag)): ?>
                                    <span class="badge" style="background:var(--ki-gray-100);color:var(--ki-gray-600)">
                                        #<?= esc(trim($tag)) ?>
                                    </span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Info Seller -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--ki-radius)">
                <div class="card-body p-4">
                    <h5 class="fw-700 mb-3">Tentang Seller</h5>
                    <div class="d-flex gap-3">
                        <div class="seller-avatar" style="width:56px;height:56px;font-size:1.4rem;flex-shrink:0">
                            <?= strtoupper(substr($service['seller_name'], 0, 1)) ?>
                        </div>
                        <div>
                            <div class="fw-700 mb-1"><?= esc($service['seller_name']) ?></div>
                            <?php if (!empty($service['university'])): ?>
                                <div class="text-muted small mb-1">
                                    <i class="bi bi-mortarboard me-1"></i>
                                    <?= esc($service['university']) ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($service['seller_bio'])): ?>
                                <p class="text-muted small mb-0"><?= esc($service['seller_bio']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ulasan -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--ki-radius)">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <h5 class="fw-700 mb-0">Ulasan</h5>
                        <div class="d-flex align-items-center gap-1">
                            <span class="rating-stars fs-5">★</span>
                            <span class="fw-800 fs-5"><?= number_format($service['rating_avg'], 1) ?></span>
                            <span class="text-muted">(<?= $service['total_reviews'] ?> ulasan)</span>
                        </div>
                    </div>

                    <?php if (empty($reviews)): ?>
                        <div class="text-center py-3 text-muted">
                            <i class="bi bi-chat-square fs-2 d-block mb-2"></i>
                            Belum ada ulasan untuk jasa ini.
                        </div>
                    <?php else: ?>
                        <?php foreach ($reviews as $review): ?>
                            <div class="d-flex gap-3 mb-4 pb-4 border-bottom">
                                <div class="seller-avatar" style="flex-shrink:0">
                                    <?= strtoupper(substr($review['buyer_name'] ?? 'U', 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="fw-700 small"><?= esc($review['buyer_name'] ?? 'User') ?></span>
                                        <span class="rating-stars">
                                            <?= str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']) ?>
                                        </span>
                                    </div>
                                    <p class="text-muted small mb-1"><?= esc($review['comment'] ?? '') ?></p>
                                    <small class="text-muted">
                                        <?= date('d M Y', strtotime($review['created_at'])) ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- REKOMENDASI -->
            <?php if (!empty($recommendations)): ?>
                <div class="mb-4">
                    <h5 class="fw-800 mb-3">✨ Rekomendasi untuk Kamu</h5>
                    <div class="row g-3">
                        <?php foreach ($recommendations as $rec): ?>
                            <div class="col-sm-6 col-md-4">
                                <a href="<?= base_url('jasa/' . $rec['slug']) ?>" class="text-decoration-none">
                                    <div class="service-card h-100">
                                        <img src="<?= $rec['thumbnail']
                                            ? base_url('uploads/thumbnails/' . $rec['thumbnail'])
                                            : 'https://placehold.co/300x160/E8F0FE/1A73E8?text=Jasa' ?>"
                                             alt="<?= esc($rec['title']) ?>"
                                             style="height:140px">
                                        <div class="card-body">
                                            <div class="service-title" style="-webkit-line-clamp:2">
                                                <?= esc($rec['title']) ?>
                                            </div>
                                            <div class="service-price mt-1" style="font-size:.95rem">
                                                Rp <?= number_format($rec['price'], 0, ',', '.') ?>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- SIDEBAR ORDER -->
        <div class="col-md-4">
            <div style="position:sticky;top:80px">
                <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                    <div class="card-body p-4">

                        <!-- Harga -->
                        <div class="service-price mb-1" style="font-size:1.6rem">
                            Rp <?= number_format($service['price'], 0, ',', '.') ?>
                        </div>
                        <div class="text-muted small mb-4">per order</div>

                        <!-- Info -->
                        <div class="d-flex flex-column gap-2 mb-4">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small"><i class="bi bi-clock me-2"></i>Waktu Pengerjaan</span>
                                <span class="fw-600 small"><?= $service['delivery_days'] ?> hari</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small"><i class="bi bi-arrow-repeat me-2"></i>Revisi</span>
                                <span class="fw-600 small">
                                    <?php
                                        $rev = $service['revision_count'];
                                        echo $rev == 0 ? 'Tidak ada' : ($rev == 99 ? 'Unlimited' : $rev . 'x');
                                    ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small"><i class="bi bi-star me-2"></i>Rating</span>
                                <span class="fw-600 small">
                                    ★ <?= number_format($service['rating_avg'], 1) ?>
                                    (<?= $service['total_reviews'] ?>)
                                </span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small"><i class="bi bi-bag me-2"></i>Total Order</span>
                                <span class="fw-600 small"><?= $service['total_orders'] ?>+</span>
                            </div>
                        </div>

                        <?php if (session()->has('user_id') && session('role') === 'buyer'): ?>
                            <!-- Form tambah ke keranjang -->
                            <div class="mb-3">
                                <label class="form-label fw-600 small">Permintaan Khusus (opsional)</label>
                                <textarea id="customRequest" class="form-control" rows="3"
                                          placeholder="Jelaskan kebutuhan spesifik kamu..."></textarea>
                            </div>

                            <button onclick="addToCart(<?= $service['id'] ?>)"
                                    class="btn-ki-primary btn w-100 py-2 mb-2" id="btnCart">
                                <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                            </button>

                            <a href="<?= base_url('buyer/cart') ?>"
                               class="btn btn-outline-success w-100 py-2">
                                <i class="bi bi-lightning me-2"></i>Beli Langsung
                            </a>

                        <?php elseif (session()->has('user_id') && session('role') === 'seller'): ?>
                            <div class="alert alert-info text-center mb-0" style="font-size:.85rem">
                                <i class="bi bi-info-circle me-1"></i>
                                Akun seller tidak bisa membeli jasa.
                            </div>

                        <?php else: ?>
                            <a href="<?= base_url('auth/login') ?>" class="btn-ki-primary btn w-100 py-2 mb-2">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login untuk Memesan
                            </a>
                            <a href="<?= base_url('auth/register') ?>" class="btn btn-light w-100 py-2">
                                Daftar Gratis
                            </a>
                        <?php endif; ?>

                        <div class="text-center mt-3 text-muted" style="font-size:.78rem">
                            <i class="bi bi-shield-check me-1"></i>Transaksi aman & terlindungi
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->section('scripts') ?>
<script>
function addToCart(serviceId) {
    const customRequest = document.getElementById('customRequest').value;
    const btn = document.getElementById('btnCart');

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass me-2"></i>Menambahkan...';

    fetch('<?= base_url('buyer/cart/add') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>',
            service_id: serviceId,
            custom_request: customRequest
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Ditambahkan!';
            btn.className = 'btn-ki-green btn w-100 py-2 mb-2';
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang';
                btn.className = 'btn-ki-primary btn w-100 py-2 mb-2';
                btn.disabled = false;
            }, 2000);
        } else {
            alert(data.message || 'Gagal menambahkan ke keranjang');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang';
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang';
    });
}
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>