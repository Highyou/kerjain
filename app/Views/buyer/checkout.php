<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="<?= base_url('buyer/cart') ?>" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="fw-800 mb-0">Checkout</h4>
            </div>

            <form action="<?= base_url('buyer/checkout/process') ?>" method="POST">
                <?= csrf_field() ?>

                <div class="row g-4">
                    <!-- Daftar Item -->
                    <div class="col-md-7">
                        <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--ki-radius)">
                            <div class="card-body p-4">
                                <h6 class="fw-700 mb-3">
                                    <i class="bi bi-bag me-2"></i>Item Pesanan (<?= count($items) ?>)
                                </h6>

                                <?php foreach ($items as $item): ?>
                                    <div class="d-flex gap-3 align-items-center mb-3 pb-3 border-bottom">
                                        <img src="<?= $item['thumbnail']
                                            ? base_url('uploads/thumbnails/' . $item['thumbnail'])
                                            : 'https://placehold.co/65x52/E8F0FE/1A73E8?text=Jasa' ?>"
                                             style="width:65px;height:52px;object-fit:cover;border-radius:8px;flex-shrink:0">
                                        <div class="flex-grow-1">
                                            <div class="fw-700 small"><?= esc($item['title']) ?></div>
                                            <div class="text-muted" style="font-size:.75rem">
                                                Seller: <?= esc($item['seller_name']) ?> •
                                                Selesai: <?= $item['delivery_days'] ?> hari
                                            </div>
                                            <?php if (!empty($item['custom_request'])): ?>
                                                <div class="text-muted" style="font-size:.75rem">
                                                    Request: <?= esc(substr($item['custom_request'], 0, 60)) ?>...
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="fw-800 text-end" style="color:var(--ki-blue);white-space:nowrap">
                                            Rp <?= number_format($item['price'], 0, ',', '.') ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                            <div class="card-body p-4">
                                <h6 class="fw-700 mb-3">
                                    <i class="bi bi-chat-text me-2"></i>Catatan (opsional)
                                </h6>
                                <textarea name="note" class="form-control" rows="3"
                                          placeholder="Tambahkan catatan untuk pesanan ini..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan & Pembayaran -->
                    <div class="col-md-5">
                        <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--ki-radius)">
                            <div class="card-body p-4">
                                <h6 class="fw-700 mb-3">
                                    <i class="bi bi-receipt me-2"></i>Ringkasan Pembayaran
                                </h6>

                                <?php foreach ($items as $item): ?>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted small" style="max-width:160px">
                                            <?= esc(substr($item['title'], 0, 30)) ?>...
                                        </span>
                                        <span class="fw-600 small">
                                            Rp <?= number_format($item['price'], 0, ',', '.') ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>

                                <hr>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-700">Total</span>
                                    <span class="fw-800 fs-5" style="color:var(--ki-blue)">
                                        Rp <?= number_format($total, 0, ',', '.') ?>
                                    </span>
                                </div>
                                <div class="text-muted small"><?= count($items) ?> jasa dari berbagai seller</div>
                            </div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <div class="card border-0 shadow-sm mb-4" style="border-radius:var(--ki-radius)">
                            <div class="card-body p-4">
                                <h6 class="fw-700 mb-3">
                                    <i class="bi bi-credit-card me-2"></i>Metode Pembayaran
                                </h6>

                                <?php
                                $methods = [
                                    'transfer_bank' => ['icon'=>'bi-bank', 'label'=>'Transfer Bank'],
                                    'gopay'         => ['icon'=>'bi-phone', 'label'=>'GoPay'],
                                    'ovo'           => ['icon'=>'bi-phone-fill', 'label'=>'OVO'],
                                    'dana'          => ['icon'=>'bi-wallet2', 'label'=>'DANA'],
                                    'cod'           => ['icon'=>'bi-cash', 'label'=>'Bayar Manual'],
                                ];
                                ?>
                                <?php foreach ($methods as $val => $m): ?>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio"
                                               name="payment_method" id="pm_<?= $val ?>"
                                               value="<?= $val ?>" <?= $val === 'transfer_bank' ? 'checked' : '' ?>>
                                        <label class="form-check-label d-flex align-items-center gap-2"
                                               for="pm_<?= $val ?>">
                                            <i class="bi <?= $m['icon'] ?>" style="color:var(--ki-blue)"></i>
                                            <?= $m['label'] ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <button type="submit" class="btn-ki-primary btn w-100 py-3 fs-6">
                            <i class="bi bi-lock-fill me-2"></i>
                            Konfirmasi Pesanan
                        </button>
                        <div class="text-center text-muted small mt-2">
                            <i class="bi bi-shield-check me-1"></i>
                            Transaksi aman & terlindungi
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>