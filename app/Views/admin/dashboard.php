<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
 
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Admin -->
        <div class="col-md-2 p-0">
            <div class="sidebar">
                <div class="px-3 mb-3">
                    <span class="badge" style="background:var(--ki-blue)">ADMIN PANEL</span>
                </div>
                <a href="<?= base_url('admin/dashboard') ?>"  class="sidebar-link active"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="<?= base_url('admin/users') ?>"      class="sidebar-link"><i class="bi bi-people"></i> Kelola User</a>
                <a href="<?= base_url('admin/jasa') ?>"       class="sidebar-link"><i class="bi bi-briefcase"></i> Kelola Jasa</a>
                <a href="<?= base_url('admin/kategori') ?>"   class="sidebar-link"><i class="bi bi-tags"></i> Kategori</a>
                <a href="<?= base_url('admin/transaksi') ?>"  class="sidebar-link"><i class="bi bi-credit-card"></i> Transaksi</a>
                <a href="<?= base_url('admin/laporan') ?>"    class="sidebar-link"><i class="bi bi-bar-chart"></i> Laporan</a>
                <hr class="my-2">
                <a href="<?= base_url('auth/logout') ?>" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>
 
        <!-- Main Content -->
        <div class="col-md-10 py-4 px-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-800 mb-0">Admin Dashboard</h4>
                <span class="text-muted small"><?= date('l, d F Y') ?></span>
            </div>
 
            <!-- Stat Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-number"><?= number_format($total_users) ?></div>
                        <div class="stat-label"><i class="bi bi-people me-1"></i>Total User</div>
                        <div class="mt-2 d-flex gap-3">
                            <small class="text-muted"><?= $total_sellers ?> seller</small>
                            <small class="text-muted"><?= $total_buyers ?> buyer</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card green">
                        <div class="stat-number"><?= number_format($total_services) ?></div>
                        <div class="stat-label"><i class="bi bi-briefcase me-1"></i>Total Jasa</div>
                        <div class="mt-2">
                            <small class="text-warning fw-600"><?= $pending_services ?> pending</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card orange">
                        <div class="stat-number"><?= number_format($total_orders) ?></div>
                        <div class="stat-label"><i class="bi bi-bag me-1"></i>Total Order</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card purple">
                        <div class="stat-number" style="font-size:1.4rem">Rp <?= number_format($total_revenue/1e6, 1) ?>Jt</div>
                        <div class="stat-label"><i class="bi bi-cash-stack me-1"></i>Total Revenue</div>
                    </div>
                </div>
            </div>
 
            <div class="row g-3">
                <!-- Recent Orders -->
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm h-100" style="border-radius:var(--ki-radius)">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <h6 class="fw-700 mb-0">Order Terbaru</h6>
                                <a href="<?= base_url('admin/transaksi') ?>" style="color:var(--ki-blue);font-weight:600;font-size:.85rem">Semua →</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-ki mb-0">
                                    <thead><tr><th>Kode</th><th>Total</th><th>Status</th></tr></thead>
                                    <tbody>
                                        <?php foreach ($recent_orders as $o): ?>
                                            <tr>
                                                <td><code><?= $o['order_code'] ?></code></td>
                                                <td class="fw-600">Rp <?= number_format($o['total_amount'],0,',','.') ?></td>
                                                <td><span class="status-badge badge-<?= $o['status'] ?>"><?= ucfirst($o['status']) ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
 
                <!-- Top Sellers -->
                <div class="col-md-5">
                    <div class="card border-0 shadow-sm h-100" style="border-radius:var(--ki-radius)">
                        <div class="card-body p-4">
                            <h6 class="fw-700 mb-3">🏆 Top Seller</h6>
                            <?php foreach ($top_sellers as $i => $seller): ?>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="fw-800" style="color:var(--ki-blue);width:20px"><?= $i+1 ?></div>
                                    <div class="seller-avatar">
                                        <?= strtoupper(substr($seller['name'] ?? 'S', 0, 1)) ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-600 small"><?= esc($seller['name'] ?? '-') ?></div>
                                        <div class="text-muted" style="font-size:.75rem"><?= $seller['total_orders'] ?> order</div>
                                    </div>
                                    <div class="fw-700 small" style="color:var(--ki-green)">
                                        ★ <?= number_format($seller['rating_avg'] ?? 0, 1) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
 
            <!-- Pending Jasa Alert -->
            <?php if ($pending_services > 0): ?>
                <div class="alert alert-warning mt-3 d-flex align-items-center gap-3">
                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    <div>
                        <strong><?= $pending_services ?> jasa</strong> menunggu persetujuan admin.
                        <a href="<?= base_url('admin/jasa?status=pending') ?>" class="fw-700">Review sekarang →</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
 
<?= $this->endSection() ?>