<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php /* FILE: app/Views/buyer/review_form.php */ ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="<?= base_url('buyer/orders') ?>" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="fw-800 mb-0">Beri Ulasan</h4>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                <div class="card-body p-4">
                    <!-- Info Jasa -->
                    <div class="d-flex gap-3 align-items-center mb-4 p-3 rounded-3"
                         style="background:var(--ki-gray-50)">
                        <img src="<?= $item['thumbnail']
                            ? base_url('uploads/thumbnails/' . $item['thumbnail'])
                            : 'https://placehold.co/70x56/E8F0FE/1A73E8?text=Jasa' ?>"
                             style="width:70px;height:56px;object-fit:cover;border-radius:8px">
                        <div>
                            <div class="fw-700"><?= esc($item['title']) ?></div>
                            <div class="text-muted small">
                                Seller: <?= esc($item['seller_name'] ?? '-') ?>
                            </div>
                        </div>
                    </div>

                    <form action="<?= base_url('buyer/review/submit') ?>" method="POST">
                        <?= csrf_field() ?>
                        <input type="hidden" name="order_item_id" value="<?= $item['id'] ?>">

                        <!-- Rating Bintang -->
                        <div class="mb-4">
                            <label class="form-label">Rating <span class="text-danger">*</span></label>
                            <div class="d-flex gap-2" id="starRating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <button type="button" class="btn btn-light star-btn"
                                            data-value="<?= $i ?>"
                                            style="font-size:2rem;line-height:1;padding:4px 8px;border-radius:8px">
                                        ☆
                                    </button>
                                <?php endfor; ?>
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" required>
                            <div class="form-text" id="ratingText">Klik bintang untuk memberi rating</div>
                        </div>

                        <!-- Komentar -->
                        <div class="mb-4">
                            <label class="form-label">Komentar (opsional)</label>
                            <textarea name="comment" class="form-control" rows="4"
                                      placeholder="Ceritakan pengalamanmu menggunakan jasa ini..."></textarea>
                        </div>

                        <button type="submit" class="btn-ki-primary btn w-100 py-2">
                            <i class="bi bi-star-fill me-2"></i>Kirim Ulasan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
const stars = document.querySelectorAll('.star-btn');
const ratingInput = document.getElementById('ratingInput');
const ratingText = document.getElementById('ratingText');
const labels = ['', 'Sangat Buruk 😞', 'Kurang Memuaskan 😕', 'Cukup 😐', 'Bagus 😊', 'Luar Biasa! 🌟'];

stars.forEach(btn => {
    btn.addEventListener('click', function() {
        const val = parseInt(this.dataset.value);
        ratingInput.value = val;
        ratingText.textContent = labels[val];
        stars.forEach((s, i) => {
            s.textContent = i < val ? '★' : '☆';
            s.style.color = i < val ? '#FFC107' : '';
        });
    });

    btn.addEventListener('mouseover', function() {
        const val = parseInt(this.dataset.value);
        stars.forEach((s, i) => {
            s.textContent = i < val ? '★' : '☆';
            s.style.color = i < val ? '#FFC107' : '';
        });
    });

    btn.addEventListener('mouseout', function() {
        const current = parseInt(ratingInput.value) || 0;
        stars.forEach((s, i) => {
            s.textContent = i < current ? '★' : '☆';
            s.style.color = i < current ? '#FFC107' : '';
        });
    });
});
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>