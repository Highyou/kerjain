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
                <a href="<?= base_url('admin/users') ?>"     class="sidebar-link"><i class="bi bi-people"></i> Kelola User</a>
                <a href="<?= base_url('admin/jasa') ?>"      class="sidebar-link active"><i class="bi bi-briefcase"></i> Kelola Jasa</a>
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
                <h4 class="fw-800 mb-0">Kelola Jasa</h4>
            </div>

            <!-- Filter Status -->
            <div class="d-flex gap-2 mb-4 flex-wrap">
                <?php
                $statuses = [''=> 'Semua', 'pending'=>'Pending', 'active'=>'Aktif', 'inactive'=>'Nonaktif'];
                foreach ($statuses as $key => $label):
                    $current = request()->getGet('status') ?? '';
                ?>
                    <a href="<?= base_url('admin/jasa?status=' . $key) ?>"
                       class="category-pill <?= $current === $key ? 'active' : '' ?>">
                        <?= $label ?>
                        <?php if ($key === 'pending'): ?>
                            <span class="badge bg-danger ms-1" style="font-size:.65rem">!</span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                <div class="table-responsive">
                    <table class="table table-ki align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Jasa</th>
                                <th>Seller</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Rating</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($services)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        Tidak ada jasa ditemukan
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($services as $svc): ?>
                                    <tr>
                                        <td style="max-width:220px">
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="<?= $svc['thumbnail']
                                                    ? base_url('uploads/thumbnails/' . $svc['thumbnail'])
                                                    : 'https://placehold.co/45x36/E8F0FE/1A73E8?text=J' ?>"
                                                     style="width:45px;height:36px;object-fit:cover;border-radius:6px;flex-shrink:0">
                                                <span class="fw-600 small" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                                                    <?= esc($svc['title']) ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="small text-muted"><?= esc($svc['seller_name'] ?? '-') ?></td>
                                        <td><small class="text-muted"><?= esc($svc['category_name'] ?? '-') ?></small></td>
                                        <td class="fw-700 small" style="color:var(--ki-blue)">
                                            Rp <?= number_format($svc['price'], 0, ',', '.') ?>
                                        </td>
                                        <td>
                                            <span class="status-badge badge-<?= $svc['status'] ?>">
                                                <?= ucfirst($svc['status']) ?>
                                            </span>
                                        </td>
                                        <td class="small">
                                            <span class="text-warning">★</span>
                                            <?= number_format($svc['rating_avg'], 1) ?>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 flex-wrap">
                                                <?php if ($svc['status'] === 'pending'): ?>
                                                    <form action="<?= base_url('admin/jasa/approve/' . $svc['id']) ?>" method="POST">
                                                        <?= csrf_field() ?>
                                                        <button class="btn btn-sm btn-success" title="Setujui">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    </form>
                                                    <form action="<?= base_url('admin/jasa/reject/' . $svc['id']) ?>" method="POST">
                                                        <?= csrf_field() ?>
                                                        <button class="btn btn-sm btn-warning" title="Tolak">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>
                                                    </form>
                                                <?php elseif ($svc['status'] === 'active'): ?>
                                                    <form action="<?= base_url('admin/jasa/reject/' . $svc['id']) ?>" method="POST">
                                                        <?= csrf_field() ?>
                                                        <button class="btn btn-sm btn-outline-warning" title="Nonaktifkan">
                                                            <i class="bi bi-pause-circle"></i>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <form action="<?= base_url('admin/jasa/approve/' . $svc['id']) ?>" method="POST">
                                                        <?= csrf_field() ?>
                                                        <button class="btn btn-sm btn-outline-success" title="Aktifkan">
                                                            <i class="bi bi-play-circle"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <form action="<?= base_url('admin/jasa/hapus/' . $svc['id']) ?>" method="POST"
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
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (isset($pager)): ?>
                    <div class="p-3 d-flex justify-content-center"><?= $pager->links() ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
