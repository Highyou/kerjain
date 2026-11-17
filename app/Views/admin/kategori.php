<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php /* FILE: app/Views/admin/kategori.php */ ?>

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
                <a href="<?= base_url('admin/kategori') ?>"  class="sidebar-link active"><i class="bi bi-tags"></i> Kategori</a>
                <a href="<?= base_url('admin/transaksi') ?>" class="sidebar-link"><i class="bi bi-credit-card"></i> Transaksi</a>
                <a href="<?= base_url('admin/laporan') ?>"   class="sidebar-link"><i class="bi bi-bar-chart"></i> Laporan</a>
                <hr class="my-2">
                <a href="<?= base_url('auth/logout') ?>" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>

        <div class="col-md-10 py-4 px-4">
            <h4 class="fw-800 mb-4">Kelola Kategori</h4>

            <div class="row g-4">
                <!-- Form Tambah -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                        <div class="card-body p-4">
                            <h6 class="fw-700 mb-3">Tambah Kategori Baru</h6>
                            <form action="<?= base_url('admin/kategori/simpan') ?>" method="POST">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <label class="form-label">Nama Kategori</label>
                                    <input type="text" name="name" class="form-control"
                                           placeholder="Desain Grafis" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Icon Bootstrap</label>
                                    <input type="text" name="icon" class="form-control"
                                           placeholder="bi-palette" value="bi-briefcase">
                                    <div class="form-text">
                                        Cari icon di <a href="https://icons.getbootstrap.com" target="_blank">icons.getbootstrap.com</a>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="description" class="form-control" rows="2"
                                              placeholder="Deskripsi singkat..."></textarea>
                                </div>
                                <button type="submit" class="btn-ki-primary btn w-100">
                                    <i class="bi bi-plus-circle me-2"></i>Tambah
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Daftar Kategori -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                        <div class="table-responsive">
                            <table class="table table-ki align-middle mb-0">
                                <thead>
                                    <tr><th>Icon</th><th>Nama</th><th>Slug</th><th>Status</th><th>Aksi</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categories as $cat): ?>
                                        <tr>
                                            <td>
                                                <i class="bi <?= esc($cat['icon']) ?> fs-5" style="color:var(--ki-blue)"></i>
                                            </td>
                                            <td class="fw-600"><?= esc($cat['name']) ?></td>
                                            <td><code class="small"><?= esc($cat['slug']) ?></code></td>
                                            <td>
                                                <?php if ($cat['is_active']): ?>
                                                    <span class="status-badge badge-active">Aktif</span>
                                                <?php else: ?>
                                                    <span class="status-badge badge-cancelled">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <button class="btn btn-sm btn-outline-primary"
                                                            onclick="editKategori(<?= htmlspecialchars(json_encode($cat)) ?>)">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <form action="<?= base_url('admin/kategori/hapus/' . $cat['id']) ?>"
                                                          method="POST"
                                                          onsubmit="return confirm('Yakin hapus kategori ini?')">
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
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:var(--ki-radius)">
            <div class="modal-header border-0">
                <h6 class="modal-title fw-700">Edit Kategori</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon</label>
                        <input type="text" name="icon" id="editIcon" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" id="editDesc" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="is_active" id="editStatus" class="form-select">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-ki-primary btn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
function editKategori(cat) {
    document.getElementById('editName').value   = cat.name;
    document.getElementById('editIcon').value   = cat.icon;
    document.getElementById('editDesc').value   = cat.description || '';
    document.getElementById('editStatus').value = cat.is_active;
    document.getElementById('editForm').action  = '<?= base_url('admin/kategori/update/') ?>' + cat.id;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>
