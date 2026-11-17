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
                            <div class="text-muted" style="font-size:.75rem">Seller</div>
                        </div>
                    </div>
                    <hr class="my-2">
                    <a href="<?= base_url('seller/dashboard') ?>"  class="sidebar-link active"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="<?= base_url('seller/jasa') ?>"       class="sidebar-link"><i class="bi bi-briefcase"></i> Jasa Saya</a>
                    <a href="<?= base_url('seller/jasa/tambah') ?>" class="sidebar-link"><i class="bi bi-plus-circle"></i> Tambah Jasa</a>
                    <a href="<?= base_url('seller/pesanan') ?>"    class="sidebar-link"><i class="bi bi-bag-check"></i> Pesanan Masuk</a>
                    <a href="<?= base_url('seller/earnings') ?>"   class="sidebar-link"><i class="bi bi-cash-stack"></i> Pendapatan</a>
                    <a href="<?= base_url('seller/profile') ?>"    class="sidebar-link"><i class="bi bi-person"></i> Profil</a>
                    <hr class="my-2">
                    <a href="<?= base_url('auth/logout') ?>" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-800 mb-0">Halo, <?= session('name') ?> 👋</h4>
                    <p class="text-muted small mb-0">Selamat datang di Dashboard Seller</p>
                </div>
                <a href="<?= base_url('seller/jasa/tambah') ?>" class="btn-ki-green btn">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Jasa
                </a>
            </div>

            <!-- Stat Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-number"><?= $total_jasa ?></div>
                        <div class="stat-label"><i class="bi bi-briefcase me-1"></i>Total Jasa</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card green">
                        <div class="stat-number"><?= $total_pesanan ?></div>
                        <div class="stat-label"><i class="bi bi-bag me-1"></i>Total Pesanan</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card orange">
                        <div class="stat-number"><?= $pending_orders ?></div>
                        <div class="stat-label"><i class="bi bi-clock me-1"></i>Pesanan Pending</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card purple">
                        <div class="stat-number" style="font-size:1.2rem">
                            Rp <?= number_format($total_revenue, 0, ',', '.') ?>
                        </div>
                        <div class="stat-label"><i class="bi bi-cash me-1"></i>Total Pendapatan</div>
                    </div>
                </div>
            </div>

            <!-- Pesanan Terbaru -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--ki-radius)">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-700 mb-0">Pesanan Terbaru</h6>
                        <a href="<?= base_url('seller/pesanan') ?>"
                           style="color:var(--ki-blue);font-weight:600;font-size:.85rem">Lihat Semua →</a>
                    </div>
                    <?php if (empty($recent_orders)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Belum ada pesanan masuk.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-ki align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Jasa</th>
                                        <th>Buyer</th>
                                        <th>Harga</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_orders as $item): ?>
                                        <tr>
                                            <td class="fw-600 small"><?= esc($item['title']) ?></td>
                                            <td class="text-muted small"><?= esc($item['buyer_name'] ?? '-') ?></td>
                                            <td class="fw-700">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                            <td>
                                                <span class="status-badge badge-<?= $item['status'] ?>">
                                                    <?= ucfirst($item['status']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Jasa Saya -->
            <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-700 mb-0">Jasa Saya</h6>
                        <a href="<?= base_url('seller/jasa') ?>"
                           style="color:var(--ki-blue);font-weight:600;font-size:.85rem">Kelola Jasa →</a>
                    </div>
                    <?php if (empty($my_services)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-briefcase fs-1 d-block mb-2"></i>
                            Belum ada jasa. <a href="<?= base_url('seller/jasa/tambah') ?>">Tambah sekarang!</a>
                        </div>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php foreach ($my_services as $svc): ?>
                                <div class="col-md-6">
                                    <div class="d-flex gap-3 align-items-center p-2 border rounded-3">
                                        <img src="<?= $svc['thumbnail']
                                            ? base_url('uploads/thumbnails/' . $svc['thumbnail'])
                                            : 'https://placehold.co/60x50/E8F0FE/1A73E8?text=Jasa' ?>"
                                             style="width:60px;height:50px;object-fit:cover;border-radius:8px">
                                        <div class="flex-grow-1">
                                            <div class="fw-600 small"><?= esc($svc['title']) ?></div>
                                            <div class="fw-700" style="color:var(--ki-blue);font-size:.85rem">
                                                Rp <?= number_format($svc['price'], 0, ',', '.') ?>
                                            </div>
                                        </div>
                                        <span class="status-badge badge-<?= $svc['status'] ?>">
                                            <?= ucfirst($svc['status']) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>