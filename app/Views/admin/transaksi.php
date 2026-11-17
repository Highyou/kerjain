<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php /* FILE: app/Views/admin/transaksi.php */ ?>

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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-800 mb-0">Monitoring Transaksi</h4>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                <div class="table-responsive">
                    <table class="table table-ki align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Kode Order</th>
                                <th>Buyer</th>
                                <th>Total</th>
                                <th>Pembayaran</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orders)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        Belum ada transaksi
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><code><?= esc($order['order_code']) ?></code></td>
                                        <td class="small fw-600"><?= esc($order['buyer_name'] ?? '-') ?></td>
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
                                        <!-- Ganti bagian kolom Aksi di tabel transaksi.php -->
                                        <td>
                                            <div class="d-flex gap-1">
                                                <?php if ($order['payment_status'] === 'unpaid'): ?>
                                                    <form action="<?= base_url('admin/transaksi/konfirmasi-bayar/' . $order['id']) ?>"
                                                        method="POST">
                                                        <?= csrf_field() ?>
                                                        <button class="btn btn-sm btn-success" title="Konfirmasi Bayar">
                                                            <i class="bi bi-check-circle"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <a href="<?= base_url('admin/transaksi/' . $order['id']) ?>"
                                                class="btn btn-sm btn-light">Detail</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (isset($pager)): ?>
                    <div class="p-3 d-flex justify-content-center"><?= $pager->links() ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
