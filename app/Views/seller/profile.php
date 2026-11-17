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
                    <a href="<?= base_url('seller/pesanan') ?>"     class="sidebar-link"><i class="bi bi-bag-check"></i> Pesanan Masuk</a>
                    <a href="<?= base_url('seller/earnings') ?>"    class="sidebar-link"><i class="bi bi-cash-stack"></i> Pendapatan</a>
                    <a href="<?= base_url('seller/profile') ?>"     class="sidebar-link active"><i class="bi bi-person"></i> Profil</a>
                    <hr class="my-2">
                    <a href="<?= base_url('auth/logout') ?>" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>

        <!-- Main -->
        <div class="col-md-9">
            <h4 class="fw-800 mb-4">Profil Seller</h4>

            <div class="row g-4">
                <!-- Avatar & Info Singkat -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center" style="border-radius:var(--ki-radius)">
                        <div class="card-body p-4">
                            <div class="seller-avatar mx-auto mb-3"
                                 style="width:80px;height:80px;font-size:2rem;border-radius:50%">
                                <?= strtoupper(substr(session('name'), 0, 1)) ?>
                            </div>
                            <h6 class="fw-800 mb-1"><?= session('name') ?></h6>
                            <div class="text-muted small mb-2"><?= session('email') ?></div>

                            <?php if (!empty($seller['is_verified'])): ?>
                                <span class="badge" style="background:var(--ki-green)">
                                    <i class="bi bi-patch-check me-1"></i>Terverifikasi
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Belum Terverifikasi</span>
                            <?php endif; ?>

                            <hr>
                            <div class="d-flex justify-content-around text-center">
                                <div>
                                    <div class="fw-800" style="color:var(--ki-blue)">
                                        <?= number_format($seller['rating_avg'] ?? 0, 1) ?>★
                                    </div>
                                    <div class="text-muted" style="font-size:.75rem">Rating</div>
                                </div>
                                <div>
                                    <div class="fw-800" style="color:var(--ki-green)">
                                        <?= $seller['total_orders'] ?? 0 ?>
                                    </div>
                                    <div class="text-muted" style="font-size:.75rem">Order</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Edit Profil -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                        <div class="card-body p-4">
                            <h6 class="fw-700 mb-4">Edit Profil Seller</h6>
                            <form action="<?= base_url('seller/profile/update') ?>" method="POST">
                                <?= csrf_field() ?>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Universitas</label>
                                        <input type="text" name="university" class="form-control"
                                               placeholder="Universitas Gadjah Mada"
                                               value="<?= esc($seller['university'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Jurusan</label>
                                        <input type="text" name="major" class="form-control"
                                               placeholder="Teknik Informatika"
                                               value="<?= esc($seller['major'] ?? '') ?>">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Bio / Tentang Kamu</label>
                                    <textarea name="bio" class="form-control" rows="4"
                                              placeholder="Ceritakan keahlian dan pengalaman kamu..."><?= esc($seller['bio'] ?? '') ?></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Link Portfolio</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                                        <input type="url" name="portfolio_url" class="form-control"
                                               placeholder="https://portofolio-kamu.com"
                                               value="<?= esc($seller['portfolio_url'] ?? '') ?>">
                                    </div>
                                </div>

                                <button type="submit" class="btn-ki-primary btn px-4">
                                    <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>