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
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="<?= base_url('seller/pesanan') ?>" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="fw-800 mb-0">Detail Pesanan</h4>
            </div>

            <div class="row g-4">
                <!-- Info Pesanan -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--ki-radius)">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="fw-700 mb-1">Pesanan #<?= esc($item['order_code']) ?></h6>
                                    <small class="text-muted">
                                        <?= date('d F Y, H:i', strtotime($item['created_at'])) ?>
                                    </small>
                                </div>
                                <span class="status-badge badge-<?= $item['status'] ?>" style="font-size:.85rem;padding:6px 14px">
                                    <?= ucfirst(str_replace('_', ' ', $item['status'])) ?>
                                </span>
                            </div>

                            <hr>

                            <!-- Detail Jasa -->
                            <div class="d-flex gap-3 mb-4">
                                <img src="<?= $item['thumbnail']
                                    ? base_url('uploads/thumbnails/' . $item['thumbnail'])
                                    : 'https://placehold.co/80x65/E8F0FE/1A73E8?text=Jasa' ?>"
                                     style="width:80px;height:65px;object-fit:cover;border-radius:8px">
                                <div>
                                    <div class="fw-700"><?= esc($item['title']) ?></div>
                                    <div class="fw-800 mt-1" style="color:var(--ki-blue)">
                                        Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Request -->
                            <?php if (!empty($item['custom_request'])): ?>
                                <div class="p-3 rounded-3 mb-3" style="background:var(--ki-blue-light)">
                                    <div class="fw-700 small mb-1" style="color:var(--ki-blue)">
                                        <i class="bi bi-chat-text me-1"></i>Permintaan Khusus Buyer:
                                    </div>
                                    <p class="mb-0 small"><?= nl2br(esc($item['custom_request'])) ?></p>
                                </div>
                            <?php endif; ?>

                            <!-- File Hasil -->
                            <?php if (!empty($item['delivery_file'])): ?>
                                <div class="p-3 rounded-3" style="background:var(--ki-green-light)">
                                    <div class="fw-700 small mb-1" style="color:var(--ki-green)">
                                        <i class="bi bi-file-earmark-check me-1"></i>File Hasil Kerja:
                                    </div>
                                    <a href="<?= base_url('uploads/deliveries/' . $item['delivery_file']) ?>"
                                       class="btn btn-sm btn-success" download>
                                        <i class="bi bi-download me-1"></i>Download File
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Update Status -->
                    <?php if (!in_array($item['status'], ['completed', 'cancelled'])): ?>
                        <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--ki-radius)">
                            <div class="card-body p-4">
                                <h6 class="fw-700 mb-3">Update Status Pesanan</h6>
                                <form action="<?= base_url('seller/pesanan/update-status') ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="order_item_id" value="<?= $item['id'] ?>">

                                    <div class="mb-3">
                                        <label class="form-label">Status Baru</label>
                                        <select name="status" class="form-select">
                                            <?php if ($item['status'] === 'pending'): ?>
                                                <option value="confirmed">✅ Konfirmasi Pesanan</option>
                                            <?php elseif ($item['status'] === 'confirmed'): ?>
                                                <option value="in_progress">🔧 Mulai Pengerjaan</option>
                                            <?php elseif ($item['status'] === 'in_progress'): ?>
                                                <option value="delivered">📦 Kirim Hasil</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Catatan untuk Buyer (opsional)</label>
                                        <textarea name="note_seller" class="form-control" rows="3"
                                                  placeholder="Pesan untuk buyer..."><?= esc($item['note_seller'] ?? '') ?></textarea>
                                    </div>

                                    <button type="submit" class="btn-ki-primary btn">
                                        <i class="bi bi-arrow-repeat me-2"></i>Update Status
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Upload File Hasil -->
                        <?php if ($item['status'] === 'in_progress'): ?>
                            <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                                <div class="card-body p-4">
                                    <h6 class="fw-700 mb-3">Upload File Hasil Kerja</h6>
                                    <form action="<?= base_url('seller/pesanan/upload-file') ?>" method="POST"
                                          enctype="multipart/form-data">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="order_item_id" value="<?= $item['id'] ?>">
                                        <div class="mb-3">
                                            <input type="file" name="delivery_file" class="form-control" required>
                                            <div class="form-text">Upload file hasil kerja kamu (ZIP, PDF, PNG, dll)</div>
                                        </div>
                                        <button type="submit" class="btn-ki-green btn">
                                            <i class="bi bi-upload me-2"></i>Upload & Kirim ke Buyer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Info Buyer -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                        <div class="card-body p-4">
                            <h6 class="fw-700 mb-3">Info Buyer</h6>
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="seller-avatar">
                                    <?= strtoupper(substr($item['buyer_name'] ?? 'B', 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="fw-700 small"><?= esc($item['buyer_name'] ?? '-') ?></div>
                                    <?php if (!empty($item['buyer_phone'])): ?>
                                        <div class="text-muted" style="font-size:.75rem">
                                            <?= esc($item['buyer_phone']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Subtotal</span>
                                <span class="fw-700">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Qty</span>
                                <span class="fw-600"><?= $item['quantity'] ?>x</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Status</span>
                                <span class="status-badge badge-<?= $item['status'] ?>">
                                    <?= ucfirst(str_replace('_', ' ', $item['status'])) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>