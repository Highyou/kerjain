<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php /* FILE: app/Views/admin/transaksi_detail.php */ ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 p-0">
            <div class="sidebar">
                <div class="px-3 mb-3">
                    <span class="badge" style="background:var(--ki-blue)">ADMIN PANEL</span>
                    <div class="mt-2 small fw-600"><?= session('name') ?></div>
                </div>
                <hr class="my-2">
                <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="<?= base_url('admin/users') ?>"     class="sidebar-link"><i class="bi bi-people"></i> Kelola User</a>
                <a href="<?= base_url('admin/jasa') ?>"      class="sidebar-link"><i class="bi bi-briefcase"></i> Kelola Jasa</a>
                <a href="<?= base_url('admin/kategori') ?>"  class="sidebar-link"><i class="bi bi-tags"></i> Kategori</a>
                <a href="<?= base_url('admin/transaksi') ?>" class="sidebar-link active"><i class="bi bi-credit-card"></i> Transaksi</a>
                <a href="<?= base_url('admin/laporan') ?>"   class="sidebar-link"><i class="bi bi-bar-chart"></i> Laporan</a>
                <hr class="my-2">
                <a href="<?= base_url('auth/logout') ?>" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>

        <div class="col-md-10 py-4 px-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="<?= base_url('admin/transaksi') ?>" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="fw-800 mb-0">Detail Transaksi</h4>
            </div>

            <!-- Header Order -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--ki-radius)">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="text-muted small mb-1">Kode Order</div>
                            <div class="fw-800"><code><?= esc($order['order_code']) ?></code></div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small mb-1">Total</div>
                            <div class="fw-800 fs-5" style="color:var(--ki-blue)">
                                Rp <?= number_format($order['total_amount'], 0, ',', '.') ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small mb-1">Status Order</div>
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
                        <div class="col-md-3">
                            <div class="text-muted small mb-1">Tanggal</div>
                            <div class="fw-600 small">
                                <?= date('d F Y, H:i', strtotime($order['created_at'])) ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small mb-1">Metode Bayar</div>
                            <div class="fw-600 small"><?= esc($order['payment_method'] ?? '-') ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tambahkan setelah info order, sebelum daftar items -->
            <?php if ($order['payment_status'] === 'unpaid'): ?>
                <div class="alert alert-warning d-flex align-items-center justify-content-between mt-3 mb-0">
                    <div>
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Belum dibayar</strong> — Konfirmasi setelah menerima bukti transfer dari buyer.
                    </div>
                    <form action="<?= base_url('admin/transaksi/konfirmasi-bayar/' . $order['id']) ?>"
                        method="POST"
                        onsubmit="return confirm('Konfirmasi pembayaran order ini?')">
                        <?= csrf_field() ?>
                        <button class="btn btn-success btn-sm">
                            <i class="bi bi-check-circle me-2"></i>Konfirmasi Bayar
                        </button>
                    </form>
                </div>
            <?php elseif ($order['payment_status'] === 'paid'): ?>
                <div class="alert alert-success mb-0 mt-3">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    Pembayaran sudah dikonfirmasi pada
                    <?= date('d M Y H:i', strtotime($order['paid_at'] ?? $order['updated_at'])) ?>
                </div>
            <?php endif; ?>

            <!-- Item-item -->
            <h6 class="fw-700 mb-3">Item dalam Order</h6>
            <?php foreach ($items as $item): ?>
                <div class="card border-0 shadow-sm mb-3" style="border-radius:var(--ki-radius)">
                    <div class="card-body p-3">
                        <div class="d-flex gap-3 align-items-center">
                            <img src="<?= $item['thumbnail']
                                ? base_url('uploads/thumbnails/' . $item['thumbnail'])
                                : 'https://placehold.co/70x55/E8F0FE/1A73E8?text=J' ?>"
                                 style="width:70px;height:55px;object-fit:cover;border-radius:8px">
                            <div class="flex-grow-1">
                                <div class="fw-700"><?= esc($item['title']) ?></div>
                                <div class="text-muted small">
                                    Seller: <?= esc($item['seller_name'] ?? '-') ?>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-800" style="color:var(--ki-blue)">
                                    Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                                </div>
                                <span class="status-badge badge-<?= $item['status'] ?>">
                                    <?= ucfirst(str_replace('_', ' ', $item['status'])) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
