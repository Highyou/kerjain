<?php
/*
=======================================================
PETUNJUK PELETAKAN FILE:
Pisahkan file ini menjadi file-file terpisah sesuai
komentar "=== FILE: app/Views/buyer/xxx.php ===" di bawah
=======================================================
*/
?>


<?php /* =================================================
   FILE: app/Views/buyer/orders.php
   ================================================= */ ?>
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
            <h4 class="fw-800 mb-4">Pesanan Saya</h4>

            <?php if (empty($orders)): ?>
                <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="bi bi-bag fs-1 d-block mb-3"></i>
                        <p class="mb-3">Belum ada pesanan.</p>
                        <a href="<?= base_url('jasa') ?>" class="btn-ki-primary btn">Mulai Belanja!</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                    <div class="table-responsive">
                        <table class="table table-ki align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Kode Order</th>
                                    <th>Total</th>
                                    <th>Pembayaran</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><code><?= esc($order['order_code']) ?></code></td>
                                        <td class="fw-700" style="color:var(--ki-blue)">
                                            Rp <?= number_format($order['total_amount'], 0, ',', '.') ?>
                                        </td>
                                        <td>
                                            <span class="badge <?= $order['payment_status'] === 'paid' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                                <?= ucfirst($order['payment_status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge badge-<?= $order['status'] ?>">
                                                <?= ucfirst(str_replace('_', ' ', $order['status'])) ?>
                                            </span>
                                        </td>
                                        <td class="text-muted small">
                                            <?= date('d M Y', strtotime($order['created_at'])) ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('buyer/orders/' . $order['id']) ?>"
                                               class="btn btn-sm btn-light">Detail</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>