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
                    <a href="<?= base_url('seller/dashboard') ?>"   class="sidebar-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    <a href="<?= base_url('seller/jasa') ?>"        class="sidebar-link"><i class="bi bi-briefcase"></i> Jasa Saya</a>
                    <a href="<?= base_url('seller/jasa/tambah') ?>" class="sidebar-link"><i class="bi bi-plus-circle"></i> Tambah Jasa</a>
                    <a href="<?= base_url('seller/pesanan') ?>"     class="sidebar-link active"><i class="bi bi-bag-check"></i> Pesanan Masuk</a>
                    <a href="<?= base_url('seller/earnings') ?>"    class="sidebar-link"><i class="bi bi-cash-stack"></i> Pendapatan</a>
                    <a href="<?= base_url('seller/profile') ?>"     class="sidebar-link"><i class="bi bi-person"></i> Profil</a>
                    <hr class="my-2">
                    <a href="<?= base_url('auth/logout') ?>" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>

        <!-- Main -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-800 mb-0">Pesanan Masuk</h4>
            </div>

            <!-- Filter Status -->
            <div class="d-flex gap-2 mb-4 flex-wrap">
                <?php
                $statuses = [
                    ''            => 'Semua',
                    'pending'     => 'Pending',
                    'confirmed'   => 'Dikonfirmasi',
                    'in_progress' => 'Dikerjakan',
                    'delivered'   => 'Terkirim',
                    'completed'   => 'Selesai',
                    'cancelled'   => 'Dibatalkan',
                ];
                foreach ($statuses as $key => $label):
                ?>
                    <a href="<?= base_url('seller/pesanan?status=' . $key) ?>"
                       class="category-pill <?= $status === $key ? 'active' : '' ?>">
                        <?= $label ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <?php if (empty($pesanan)): ?>
                <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        <p>Belum ada pesanan<?= $status ? ' dengan status ' . $status : '' ?>.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                    <div class="table-responsive">
                        <table class="table table-ki align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Kode Order</th>
                                    <th>Jasa</th>
                                    <th>Buyer</th>
                                    <th>Subtotal</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pesanan as $item): ?>
                                    <tr>
                                        <td><code><?= esc($item['order_code']) ?></code></td>
                                        <td>
                                            <div class="fw-600 small" style="max-width:150px">
                                                <?= esc($item['title']) ?>
                                            </div>
                                        </td>
                                        <td class="text-muted small"><?= esc($item['buyer_name'] ?? '-') ?></td>
                                        <td class="fw-700">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                        <td>
                                            <span class="status-badge badge-<?= $item['status'] ?>">
                                                <?= ucfirst(str_replace('_', ' ', $item['status'])) ?>
                                            </span>
                                        </td>
                                        <td class="text-muted small">
                                            <?= date('d M Y', strtotime($item['order_date'] ?? $item['created_at'])) ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('seller/pesanan/' . $item['id']) ?>"
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