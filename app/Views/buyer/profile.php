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
                    <a href="<?= base_url('buyer/orders') ?>"    class="sidebar-link"><i class="bi bi-bag-check"></i> Pesanan Saya</a>
                    <a href="<?= base_url('jasa') ?>"            class="sidebar-link"><i class="bi bi-grid"></i> Jelajahi Jasa</a>
                    <a href="<?= base_url('buyer/profile') ?>"   class="sidebar-link active"><i class="bi bi-person"></i> Profil</a>
                    <hr class="my-2">
                    <a href="<?= base_url('auth/logout') ?>" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>

        <!-- Main -->
        <div class="col-md-9">
            <h4 class="fw-800 mb-4">Profil Saya</h4>

            <div class="row g-4">
                <!-- Avatar -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center" style="border-radius:var(--ki-radius)">
                        <div class="card-body p-4">
                            <?php if (!empty(session('avatar'))): ?>
                                <img src="<?= base_url('uploads/avatars/' . session('avatar')) ?>"
                                     class="rounded-circle mb-3"
                                     style="width:80px;height:80px;object-fit:cover">
                            <?php else: ?>
                                <div class="seller-avatar mx-auto mb-3"
                                     style="width:80px;height:80px;font-size:2rem;border-radius:50%">
                                    <?= strtoupper(substr(session('name'), 0, 1)) ?>
                                </div>
                            <?php endif; ?>

                            <h6 class="fw-800 mb-1"><?= session('name') ?></h6>
                            <div class="text-muted small mb-3"><?= session('email') ?></div>
                            <span class="badge" style="background:var(--ki-blue)">Buyer</span>
                        </div>
                    </div>
                </div>

                <!-- Form Edit -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                        <div class="card-body p-4">
                            <h6 class="fw-700 mb-4">Edit Profil</h6>
                            <form action="<?= base_url('buyer/profile/update') ?>" method="POST"
                                  enctype="multipart/form-data">
                                <?= csrf_field() ?>

                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control"
                                           value="<?= esc(session('name')) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control"
                                           value="<?= esc(session('email')) ?>" disabled>
                                    <div class="form-text">Email tidak dapat diubah</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">No. HP</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                        <input type="text" name="phone" class="form-control"
                                               placeholder="08xxxxxxxxxx">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Foto Profil</label>
                                    <input type="file" name="avatar" class="form-control" accept="image/*">
                                    <div class="form-text">Format JPG/PNG, maks 1MB</div>
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