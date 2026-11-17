<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php /* ====================================================
   app/Views/seller/earnings.php — Halaman Pendapatan
   ==================================================== */ ?>

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
                    <a href="<?= base_url('seller/pesanan') ?>"     class="sidebar-link"><i class="bi bi-bag-check"></i> Pesanan Masuk</a>
                    <a href="<?= base_url('seller/earnings') ?>"    class="sidebar-link active"><i class="bi bi-cash-stack"></i> Pendapatan</a>
                    <a href="<?= base_url('seller/profile') ?>"     class="sidebar-link"><i class="bi bi-person"></i> Profil</a>
                    <hr class="my-2">
                    <a href="<?= base_url('auth/logout') ?>" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>

        <!-- Main -->
        <div class="col-md-9">
            <h4 class="fw-800 mb-4">Pendapatan Saya</h4>

            <!-- Total Revenue Card -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-number" style="font-size:1.5rem">
                            Rp <?= number_format($total_revenue, 0, ',', '.') ?>
                        </div>
                        <div class="stat-label">Total Pendapatan</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card green">
                        <div class="stat-number"><?= count($monthly_data) ?></div>
                        <div class="stat-label">Bulan Aktif</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card orange">
                        <?php
                            $avgMonthly = count($monthly_data) > 0
                                ? $total_revenue / count($monthly_data) : 0;
                        ?>
                        <div class="stat-number" style="font-size:1.3rem">
                            Rp <?= number_format($avgMonthly, 0, ',', '.') ?>
                        </div>
                        <div class="stat-label">Rata-rata/Bulan</div>
                    </div>
                </div>
            </div>

            <!-- Tabel Bulanan -->
            <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                <div class="card-body p-4">
                    <h6 class="fw-700 mb-3">Pendapatan per Bulan (6 Bulan Terakhir)</h6>

                    <?php if (empty($monthly_data)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-bar-chart fs-1 d-block mb-2"></i>
                            Belum ada data pendapatan.
                        </div>
                    <?php else: ?>
                        <!-- Bar chart sederhana -->
                        <?php
                            $maxVal = max(array_column($monthly_data, 'total'));
                        ?>
                        <div class="mb-4">
                            <?php foreach ($monthly_data as $row): ?>
                                <?php $pct = $maxVal > 0 ? ($row['total'] / $maxVal) * 100 : 0; ?>
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <div style="width:70px;font-size:.8rem;color:var(--ki-gray-600);text-align:right">
                                        <?= date('M Y', strtotime($row['bulan'] . '-01')) ?>
                                    </div>
                                    <div class="flex-grow-1" style="background:var(--ki-gray-100);border-radius:50px;height:22px;overflow:hidden">
                                        <div style="width:<?= $pct ?>%;background:var(--ki-blue);height:100%;border-radius:50px;transition:.5s"></div>
                                    </div>
                                    <div style="width:110px;font-size:.82rem;font-weight:700;color:var(--ki-blue)">
                                        Rp <?= number_format($row['total'], 0, ',', '.') ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <table class="table table-ki mb-0">
                            <thead>
                                <tr><th>Bulan</th><th>Total Pendapatan</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_reverse($monthly_data) as $row): ?>
                                    <tr>
                                        <td><?= date('F Y', strtotime($row['bulan'] . '-01')) ?></td>
                                        <td class="fw-700" style="color:var(--ki-green)">
                                            Rp <?= number_format($row['total'], 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>