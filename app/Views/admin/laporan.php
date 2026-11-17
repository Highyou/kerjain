<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

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
                <a href="<?= base_url('admin/transaksi') ?>" class="sidebar-link"><i class="bi bi-credit-card"></i> Transaksi</a>
                <a href="<?= base_url('admin/laporan') ?>"   class="sidebar-link active"><i class="bi bi-bar-chart"></i> Laporan</a>
                <hr class="my-2">
                <a href="<?= base_url('auth/logout') ?>" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>

        <div class="col-md-10 py-4 px-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-800 mb-0">Laporan & Statistik</h4>
                <a href="<?= base_url('admin/laporan/export') ?>" class="btn-ki-green btn btn-sm">
                    <i class="bi bi-download me-2"></i>Export CSV
                </a>
            </div>

            <!-- Revenue per Bulan -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--ki-radius)">
                <div class="card-body p-4">
                    <h6 class="fw-700 mb-4">📈 Revenue per Bulan</h6>
                    <?php if (empty($monthlyRevenue)): ?>
                        <div class="text-center py-3 text-muted">Belum ada data revenue</div>
                    <?php else: ?>
                        <?php $maxVal = max(array_column($monthlyRevenue, 'total') ?: [1]); ?>
                        <?php foreach ($monthlyRevenue as $row): ?>
                            <?php $pct = $maxVal > 0 ? ($row['total'] / $maxVal) * 100 : 0; ?>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div style="width:80px;font-size:.8rem;color:var(--ki-gray-600);text-align:right">
                                    <?= date('M Y', strtotime($row['bulan'] . '-01')) ?>
                                </div>
                                <div class="flex-grow-1" style="background:var(--ki-gray-100);border-radius:50px;height:24px;overflow:hidden">
                                    <div style="width:<?= $pct ?>%;background:var(--ki-blue);height:100%;border-radius:50px;transition:.5s;display:flex;align-items:center;padding-left:8px">
                                        <?php if ($pct > 20): ?>
                                            <span style="font-size:.7rem;color:white;font-weight:700">
                                                <?= $row['jumlah_order'] ?> order
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div style="width:130px;font-size:.82rem;font-weight:700;color:var(--ki-blue)">
                                    Rp <?= number_format($row['total'], 0, ',', '.') ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row g-4">
                <!-- Jasa Terlaris -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100" style="border-radius:var(--ki-radius)">
                        <div class="card-body p-4">
                            <h6 class="fw-700 mb-3">🏆 Jasa Terlaris</h6>
                            <?php if (empty($topServices)): ?>
                                <div class="text-center py-3 text-muted">Belum ada data</div>
                            <?php else: ?>
                                <table class="table table-ki mb-0">
                                    <thead>
                                        <tr><th>#</th><th>Jasa</th><th>Terjual</th><th>Revenue</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($topServices as $i => $svc): ?>
                                            <tr>
                                                <td>
                                                    <?php if ($i === 0): ?>
                                                        <span style="font-size:1.2rem">🥇</span>
                                                    <?php elseif ($i === 1): ?>
                                                        <span style="font-size:1.2rem">🥈</span>
                                                    <?php elseif ($i === 2): ?>
                                                        <span style="font-size:1.2rem">🥉</span>
                                                    <?php else: ?>
                                                        <span class="fw-700 text-muted"><?= $i + 1 ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="fw-600 small" style="max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                                        <?= esc($svc['title']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge" style="background:var(--ki-blue-light);color:var(--ki-blue)">
                                                        <?= $svc['total_terjual'] ?>x
                                                    </span>
                                                </td>
                                                <td class="fw-700 small" style="color:var(--ki-green)">
                                                    Rp <?= number_format($svc['revenue'], 0, ',', '.') ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Seller Terbaik -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100" style="border-radius:var(--ki-radius)">
                        <div class="card-body p-4">
                            <h6 class="fw-700 mb-3">⭐ Seller Terbaik</h6>
                            <?php if (empty($topSellers)): ?>
                                <div class="text-center py-3 text-muted">Belum ada data</div>
                            <?php else: ?>
                                <?php foreach ($topSellers as $i => $seller): ?>
                                    <div class="d-flex align-items-center gap-3 mb-3 pb-3
                                        <?= $i < count($topSellers) - 1 ? 'border-bottom' : '' ?>">
                                        <div class="fw-800" style="width:24px;color:var(--ki-blue)">
                                            <?= $i + 1 ?>
                                        </div>
                                        <div class="seller-avatar">
                                            <?= strtoupper(substr($seller['name'] ?? 'S', 0, 1)) ?>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-600 small"><?= esc($seller['name'] ?? '-') ?></div>
                                            <div class="text-muted" style="font-size:.75rem">
                                                <?= $seller['total_pesanan'] ?> pesanan
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-700 small" style="color:var(--ki-green)">
                                                Rp <?= number_format($seller['revenue'], 0, ',', '.') ?>
                                            </div>
                                            <div style="font-size:.75rem;color:#FFC107">
                                                ★ <?= number_format($seller['rating_avg'] ?? 0, 1) ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
