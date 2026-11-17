<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="seller-avatar" style="width:40px;height:40px;font-size:1rem">
                            <?= strtoupper(substr(session('name'), 0, 1)) ?>
                        </div>
                        <div>
                            <div class="fw-700 small"><?= session('name') ?></div>
                            <div class="text-muted" style="font-size:.75rem">Buyer</div>
                        </div>
                    </div>
                    <hr class="my-2">
                    <a href="<?= base_url('buyer/dashboard') ?>" class="sidebar-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="<?= base_url('buyer/cart') ?>"      class="sidebar-link"><i class="bi bi-cart2"></i> Keranjang</a>
                    <a href="<?= base_url('buyer/orders') ?>"    class="sidebar-link active"><i class="bi bi-bag-check"></i> Pesanan Saya</a>
                    <a href="<?= base_url('jasa') ?>"            class="sidebar-link"><i class="bi bi-grid"></i> Jelajahi Jasa</a>
                    <a href="<?= base_url('buyer/profile') ?>"   class="sidebar-link"><i class="bi bi-person"></i> Profil</a>
                    <hr class="my-2">
                    <a href="<?= base_url('auth/logout') ?>" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>

        <!-- Main -->
        <div class="col-md-9">
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="<?= base_url('buyer/orders') ?>" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="fw-800 mb-0">Detail Pesanan</h4>
            </div>

            <!-- Header Order -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--ki-radius)">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Kode Order</div>
                            <div class="fw-800 fs-5"><code><?= esc($order['order_code']) ?></code></div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small mb-1">Status</div>
                            <span class="status-badge badge-<?= $order['status'] ?>">
                                <?= ucfirst(str_replace('_', ' ', $order['status'])) ?>
                            </span>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small mb-1">Pembayaran</div>
                            <span class="badge <?= $order['payment_status'] === 'paid' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                <?= ucfirst($order['payment_status']) ?>
                            </span>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Tanggal Order</div>
                            <div class="fw-600"><?= date('d F Y, H:i', strtotime($order['created_at'])) ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small mb-1">Total Pembayaran</div>
                            <div class="fw-800 fs-5" style="color:var(--ki-blue)">
                                Rp <?= number_format($order['total_amount'], 0, ',', '.') ?>
                            </div>
                        </div>
                        <?php if (!empty($order['note'])): ?>
                            <div class="col-12">
                                <div class="text-muted small mb-1">Catatan</div>
                                <div><?= esc($order['note']) ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Item-item Order -->
            <h6 class="fw-700 mb-3">Item dalam Pesanan</h6>

            <?php foreach ($items as $item): ?>
                <div class="card border-0 shadow-sm mb-3" style="border-radius:var(--ki-radius)">
                    <div class="card-body p-4">
                        <div class="d-flex gap-3">
                            <img src="<?= $item['thumbnail']
                                ? base_url('uploads/thumbnails/' . $item['thumbnail'])
                                : 'https://placehold.co/80x65/E8F0FE/1A73E8?text=Jasa' ?>"
                                 style="width:80px;height:65px;object-fit:cover;border-radius:8px;flex-shrink:0">

                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="fw-700"><?= esc($item['title']) ?></div>
                                        <div class="text-muted small">
                                            Seller: <?= esc($item['seller_name'] ?? '-') ?>
                                        </div>
                                    </div>
                                    <span class="status-badge badge-<?= $item['status'] ?>">
                                        <?= ucfirst(str_replace('_', ' ', $item['status'])) ?>
                                    </span>
                                </div>

                                <div class="d-flex gap-4 mt-2">
                                    <div>
                                        <span class="text-muted small">Harga: </span>
                                        <span class="fw-700" style="color:var(--ki-blue)">
                                            Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-muted small">Qty: </span>
                                        <span class="fw-600"><?= $item['quantity'] ?>x</span>
                                    </div>
                                </div>

                                <?php if (!empty($item['custom_request'])): ?>
                                    <div class="mt-2 p-2 rounded-2" style="background:var(--ki-blue-light);font-size:.85rem">
                                        <strong>Request:</strong> <?= esc($item['custom_request']) ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($item['note_seller'])): ?>
                                    <div class="mt-2 p-2 rounded-2" style="background:var(--ki-green-light);font-size:.85rem">
                                        <strong>Catatan Seller:</strong> <?= esc($item['note_seller']) ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Tombol aksi -->
                                <div class="d-flex gap-2 mt-3">
                                    <?php if (!empty($item['delivery_file'])): ?>
                                        <a href="<?= base_url('uploads/deliveries/' . $item['delivery_file']) ?>"
                                           class="btn btn-sm btn-success" download>
                                            <i class="bi bi-download me-1"></i>Download Hasil
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($item['status'] === 'delivered'): ?>
                                        <form action="<?= base_url('buyer/orders/selesai/' . $item['id']) ?>"
                                              method="POST"
                                              onsubmit="return confirm('Konfirmasi pesanan sudah selesai dan sesuai?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-check-circle me-1"></i>Konfirmasi Selesai
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($item['status'] === 'completed'): ?>
                                        <?php
                                            $reviewModel = new \App\Models\ReviewModel();
                                            $hasReview = $reviewModel->where('order_item_id', $item['id'])->first();
                                        ?>
                                        <?php if (!$hasReview): ?>
                                            <a href="<?= base_url('buyer/review/' . $item['id']) ?>"
                                               class="btn btn-sm btn-warning">
                                                <i class="bi bi-star me-1"></i>Beri Ulasan
                                            </a>
                                        <?php else: ?>
                                            <span class="btn btn-sm btn-light disabled">
                                                <i class="bi bi-star-fill me-1 text-warning"></i>Sudah Diulas
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>