<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
 
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 p-0">
            <div class="sidebar">
                <div class="px-3 mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="seller-avatar" style="width:40px;height:40px;font-size:1rem">
                            <?= strtoupper(substr(session('name'), 0, 1)) ?>
                        </div>
                        <div>
                            <div class="fw-700 small"><?= session('name') ?></div>
                            <div class="text-muted" style="font-size:.75rem">Buyer</div>
                        </div>
                    </div>
                </div>
                <hr class="my-2">
                <a href="<?= base_url('buyer/dashboard') ?>" class="sidebar-link active"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="<?= base_url('buyer/cart') ?>" class="sidebar-link"><i class="bi bi-cart2"></i> Keranjang</a>
                <a href="<?= base_url('buyer/orders') ?>" class="sidebar-link"><i class="bi bi-bag-check"></i> Pesanan Saya</a>
                <a href="<?= base_url('jasa') ?>" class="sidebar-link"><i class="bi bi-grid"></i> Jelajahi Jasa</a>
                <a href="<?= base_url('buyer/profile') ?>" class="sidebar-link"><i class="bi bi-person"></i> Profil</a>
                <hr class="my-2">
                <a href="<?= base_url('auth/logout') ?>" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>
 
        <!-- Main Content -->
        <div class="col-md-10 py-4 px-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-800 mb-0">Halo, <?= session('name') ?> 👋</h4>
                    <p class="text-muted small mb-0">Selamat datang kembali di KerjaIn</p>
                </div>
                <a href="<?= base_url('jasa') ?>" class="btn-ki-primary btn"><i class="bi bi-search me-2"></i>Cari Jasa</a>
            </div>
 
            <!-- Stat Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-number"><?= $total_orders ?></div>
                                <div class="stat-label">Total Pesanan</div>
                            </div>
                            <i class="bi bi-bag-check fs-2" style="color:var(--ki-blue);opacity:.3"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card green">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-number"><?= $active_orders ?></div>
                                <div class="stat-label">Pesanan Aktif</div>
                            </div>
                            <i class="bi bi-clock-history fs-2" style="color:var(--ki-green);opacity:.3"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card orange">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stat-number"><?= $cart_count ?></div>
                                <div class="stat-label">Item di Keranjang</div>
                            </div>
                            <i class="bi bi-cart2 fs-2" style="color:#FB8C00;opacity:.3"></i>
                        </div>
                    </div>
                </div>
            </div>
 
            <!-- Recent Orders -->
            <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-700 mb-0">Pesanan Terbaru</h6>
                        <a href="/buyer/orders" style="color:var(--ki-blue);font-weight:600;font-size:.85rem">Lihat Semua →</a>
                    </div>
 
                    <?php if (empty($recent_orders)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-bag fs-1 d-block mb-2"></i>
                            Belum ada pesanan. <a href="<?= base_url('jasa') ?>">Cari jasa sekarang!</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-ki align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Kode Order</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_orders as $order): ?>
                                        <tr>
                                            <td><code><?= $order['order_code'] ?></code></td>
                                            <td class="fw-700">Rp <?= number_format($order['total_amount'],0,',','.') ?></td>
                                            <td>
                                                <span class="status-badge badge-<?= $order['status'] ?>">
                                                    <?= ucfirst(str_replace('_',' ',$order['status'])) ?>
                                                </span>
                                            </td>
                                            <td class="text-muted small"><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                                            <td>
                                                <a href="<?= base_url('buyer/orders/'.$order['id']) ?>" class="btn btn-sm btn-light">Detail</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
 
<?= $this->endSection() ?>