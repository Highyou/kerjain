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
                    <a href="<?= base_url('seller/jasa/tambah') ?>" class="sidebar-link active"><i class="bi bi-plus-circle"></i> Tambah Jasa</a>
                    <a href="<?= base_url('seller/pesanan') ?>"     class="sidebar-link"><i class="bi bi-bag-check"></i> Pesanan Masuk</a>
                    <a href="<?= base_url('seller/earnings') ?>"    class="sidebar-link"><i class="bi bi-cash-stack"></i> Pendapatan</a>
                    <a href="<?= base_url('seller/profile') ?>"     class="sidebar-link"><i class="bi bi-person"></i> Profil</a>
                    <hr class="my-2">
                    <a href="<?= base_url('auth/logout') ?>" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="col-md-9">
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="<?= base_url('seller/jasa') ?>" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="fw-800 mb-0"><?= $title ?></h4>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                <div class="card-body p-4">
                    <form action="<?= $service
                        ? base_url('seller/jasa/update/' . $service['id'])
                        : base_url('seller/jasa/simpan') ?>"
                          method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">Judul Jasa <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control"
                                   placeholder="Contoh: Jasa Desain Logo Profesional"
                                   value="<?= old('title', $service['title'] ?? '') ?>" required>
                            <div class="form-text">Minimal 10 karakter, maksimal 200 karakter</div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"
                                            <?= old('category_id', $service['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                            <?= esc($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="price" class="form-control"
                                           placeholder="50000"
                                           value="<?= old('price', $service['price'] ?? '') ?>"
                                           min="1000" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi Jasa <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="6"
                                      placeholder="Jelaskan detail jasa yang kamu tawarkan, termasuk apa yang buyer dapatkan..."
                                      required><?= old('description', $service['description'] ?? '') ?></textarea>
                            <div class="form-text">Minimal 50 karakter</div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Waktu Pengerjaan (hari) <span class="text-danger">*</span></label>
                                <input type="number" name="delivery_days" class="form-control"
                                       placeholder="3"
                                       value="<?= old('delivery_days', $service['delivery_days'] ?? 3) ?>"
                                       min="1" max="90" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jumlah Revisi</label>
                                <select name="revision_count" class="form-select">
                                    <?php for ($i = 0; $i <= 5; $i++): ?>
                                        <option value="<?= $i ?>"
                                            <?= old('revision_count', $service['revision_count'] ?? 1) == $i ? 'selected' : '' ?>>
                                            <?= $i == 0 ? 'Tidak ada revisi' : "$i kali revisi" ?>
                                        </option>
                                    <?php endfor; ?>
                                    <option value="99" <?= old('revision_count', $service['revision_count'] ?? 1) == 99 ? 'selected' : '' ?>>
                                        Revisi unlimited
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tags</label>
                            <input type="text" name="tags" class="form-control"
                                   placeholder="logo desain branding (pisahkan dengan spasi)"
                                   value="<?= old('tags', $service['tags'] ?? '') ?>">
                            <div class="form-text">Kata kunci untuk memudahkan pencarian</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Thumbnail Jasa</label>
                            <?php if (!empty($service['thumbnail'])): ?>
                                <div class="mb-2">
                                    <img src="<?= base_url('uploads/thumbnails/' . $service['thumbnail']) ?>"
                                         style="width:120px;height:90px;object-fit:cover;border-radius:8px">
                                    <div class="form-text">Thumbnail saat ini. Upload baru untuk mengganti.</div>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="thumbnail" class="form-control" accept="image/*">
                            <div class="form-text">Format: JPG, PNG, WEBP. Ukuran maks: 2MB</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-ki-primary btn px-4">
                                <i class="bi bi-check-circle me-2"></i>
                                <?= $service ? 'Simpan Perubahan' : 'Tambah Jasa' ?>
                            </button>
                            <a href="<?= base_url('seller/jasa') ?>" class="btn btn-light px-4">Batal</a>
                        </div>

                        <?php if (!$service): ?>
                            <div class="alert alert-info mt-3 mb-0" style="border-radius:var(--ki-radius-sm)">
                                <i class="bi bi-info-circle me-2"></i>
                                Jasa baru akan menunggu <strong>persetujuan admin</strong> sebelum tampil ke publik.
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>