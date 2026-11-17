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
                    <a href="<?= base_url('seller/jasa') ?>"        class="sidebar-link active"><i class="bi bi-briefcase"></i> Jasa Saya</a>
                    <a href="<?= base_url('seller/jasa/tambah') ?>" class="sidebar-link"><i class="bi bi-plus-circle"></i> Tambah Jasa</a>
                    <a href="<?= base_url('seller/pesanan') ?>"     class="sidebar-link"><i class="bi bi-bag-check"></i> Pesanan Masuk</a>
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
                <h4 class="fw-800 mb-0">Jasa Saya</h4>
                <a href="<?= base_url('seller/jasa/tambah') ?>" class="btn-ki-green btn">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Jasa Baru
                </a>
            </div>

            <?php if (empty($services)): ?>
                <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="bi bi-briefcase fs-1 d-block mb-3"></i>
                        <p class="mb-3">Kamu belum punya jasa. Yuk mulai jual!</p>
                        <a href="<?= base_url('seller/jasa/tambah') ?>" class="btn-ki-primary btn">Tambah Jasa Pertama</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                    <div class="table-responsive">
                        <table class="table table-ki align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Jasa</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Rating</th>
                                    <th>Order</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($services as $svc): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="<?= $svc['thumbnail']
                                                    ? base_url('uploads/thumbnails/' . $svc['thumbnail'])
                                                    : 'https://placehold.co/50x40/E8F0FE/1A73E8?text=Jasa' ?>"
                                                     style="width:50px;height:40px;object-fit:cover;border-radius:6px">
                                                <div>
                                                    <div class="fw-600 small"><?= esc($svc['title']) ?></div>
                                                    <div class="text-muted" style="font-size:.75rem">
                                                        <?= $svc['delivery_days'] ?> hari pengerjaan
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><small class="text-muted"><?= esc($svc['category_name'] ?? '-') ?></small></td>
                                        <td class="fw-700" style="color:var(--ki-blue)">
                                            Rp <?= number_format($svc['price'], 0, ',', '.') ?>
                                        </td>
                                        <td>
                                            <span class="status-badge badge-<?= $svc['status'] ?>">
                                                <?= ucfirst($svc['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-warning fw-600">★</span>
                                            <?= number_format($svc['rating_avg'], 1) ?>
                                            <small class="text-muted">(<?= $svc['total_reviews'] ?>)</small>
                                        </td>
                                        <td class="fw-600"><?= $svc['total_orders'] ?></td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="<?= base_url('seller/jasa/edit/' . $svc['id']) ?>"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="<?= base_url('seller/jasa/hapus/' . $svc['id']) ?>" method="POST"
                                                      onsubmit="return confirm('Yakin hapus jasa ini?')">
                                                    <?= csrf_field() ?>
                                                    <button class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
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