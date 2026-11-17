<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 p-0">
            <div class="sidebar">
                <div class="px-3 mb-3">
                    <span class="badge" style="background:var(--ki-blue)">ADMIN PANEL</span>
                    <div class="mt-2 small fw-600"><?= session('name') ?></div>
                </div>
                <hr class="my-2">
                <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="<?= base_url('admin/users') ?>"     class="sidebar-link active"><i class="bi bi-people"></i> Kelola User</a>
                <a href="<?= base_url('admin/jasa') ?>"      class="sidebar-link"><i class="bi bi-briefcase"></i> Kelola Jasa</a>
                <a href="<?= base_url('admin/kategori') ?>"  class="sidebar-link"><i class="bi bi-tags"></i> Kategori</a>
                <a href="<?= base_url('admin/transaksi') ?>" class="sidebar-link"><i class="bi bi-credit-card"></i> Transaksi</a>
                <a href="<?= base_url('admin/laporan') ?>"   class="sidebar-link"><i class="bi bi-bar-chart"></i> Laporan</a>
                <hr class="my-2">
                <a href="<?= base_url('auth/logout') ?>" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>

        <!-- Main -->
        <div class="col-md-10 py-4 px-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-800 mb-0">Kelola User</h4>
            </div>

            <!-- Filter -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--ki-radius)">
                <div class="card-body p-3">
                    <form action="<?= base_url('admin/users') ?>" method="GET" class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-600">Filter Role</label>
                            <select name="role" class="form-select form-select-sm">
                                <option value="">Semua Role</option>
                                <option value="buyer"  <?= request()->getGet('role') === 'buyer'  ? 'selected' : '' ?>>Buyer</option>
                                <option value="seller" <?= request()->getGet('role') === 'seller' ? 'selected' : '' ?>>Seller</option>
                                <option value="admin"  <?= request()->getGet('role') === 'admin'  ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-600">Cari</label>
                            <input type="text" name="search" class="form-control form-control-sm"
                                   placeholder="Nama atau email..."
                                   value="<?= esc(request()->getGet('search')) ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                <i class="bi bi-search me-1"></i>Cari
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="<?= base_url('admin/users') ?>" class="btn btn-sm btn-light w-100">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabel User -->
            <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                <div class="table-responsive">
                    <table class="table table-ki align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Bergabung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        Tidak ada user ditemukan
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $i => $user): ?>
                                    <tr>
                                        <td class="text-muted small"><?= $i + 1 ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="seller-avatar" style="width:32px;height:32px;font-size:.85rem">
                                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                                </div>
                                                <span class="fw-600 small"><?= esc($user['name']) ?></span>
                                            </div>
                                        </td>
                                        <td class="text-muted small"><?= esc($user['email']) ?></td>
                                        <td>
                                            <?php
                                                $roleColor = match($user['role']) {
                                                    'admin'  => 'danger',
                                                    'seller' => 'success',
                                                    default  => 'primary'
                                                };
                                            ?>
                                            <span class="badge bg-<?= $roleColor ?>">
                                                <?= ucfirst($user['role']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($user['is_active']): ?>
                                                <span class="status-badge badge-active">Aktif</span>
                                            <?php else: ?>
                                                <span class="status-badge badge-cancelled">Nonaktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted small">
                                            <?= date('d M Y', strtotime($user['created_at'])) ?>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <!-- Toggle aktif/nonaktif -->
                                                <form action="<?= base_url('admin/users/toggle/' . $user['id']) ?>"
                                                      method="POST">
                                                    <?= csrf_field() ?>
                                                    <button class="btn btn-sm <?= $user['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>"
                                                            title="<?= $user['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                                        <i class="bi <?= $user['is_active'] ? 'bi-pause-circle' : 'bi-play-circle' ?>"></i>
                                                    </button>
                                                </form>
                                                <!-- Hapus -->
                                                <?php if ($user['role'] !== 'admin'): ?>
                                                    <form action="<?= base_url('admin/users/hapus/' . $user['id']) ?>"
                                                          method="POST"
                                                          onsubmit="return confirm('Yakin hapus user ini?')">
                                                        <?= csrf_field() ?>
                                                        <button class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginasi -->
                <?php if (isset($pager)): ?>
                    <div class="p-3 d-flex justify-content-center">
                        <?= $pager->links() ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
