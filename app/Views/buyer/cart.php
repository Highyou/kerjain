<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <h4 class="fw-800 mb-4">🛒 Keranjang Belanja</h4>

    <?php if (empty($items)): ?>
        <div class="text-center py-5 text-muted">
            <i class="bi bi-cart2 fs-1 d-block mb-2"></i>
            <p>Keranjang kosong. <a href="<?= base_url('jasa') ?>">Cari jasa dulu!</a></p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <div class="col-md-8">
                <?php foreach ($items as $item): ?>
                    <div class="card border-0 shadow-sm mb-3" style="border-radius:var(--ki-radius)">
                        <div class="card-body p-3 d-flex gap-3 align-items-center">
                            <img src="<?= $item['thumbnail'] ? '/uploads/thumbnails/'.$item['thumbnail'] : 'https://placehold.co/80x60/E8F0FE/1A73E8?text=Jasa' ?>"
                                 style="width:80px;height:60px;object-fit:cover;border-radius:8px">
                            <div class="flex-grow-1">
                                <div class="fw-700"><?= esc($item['title']) ?></div>
                                <small class="text-muted">Seller: <?= esc($item['seller_name']) ?></small>
                            </div>
                            <div class="text-end">
                                <div class="fw-800" style="color:var(--ki-blue)">Rp <?= number_format($item['price'],0,',','.') ?></div>
                                <form action="<?= base_url('buyer/cart/remove/'.$item['id']) ?>" method="POST" class="mt-1">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                    <div class="card-body p-4">
                        <h6 class="fw-700 mb-3">Ringkasan</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total (<?= count($items) ?> item)</span>
                            <span class="fw-800">Rp <?= number_format($total,0,',','.') ?></span>
                        </div>
                        <hr>
                        <a href="<?= base_url('buyer/checkout') ?>" class="btn-ki-primary btn w-100">
                            <i class="bi bi-credit-card me-2"></i>Checkout Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>