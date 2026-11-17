<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
 
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-4">
                <a href="/" class="text-decoration-none">
                    <h1 style="font-weight:800;color:var(--ki-blue);font-size:2rem">KerjaIn<span style="color:var(--ki-green)">.</span></h1>
                </a>
                <p class="text-muted">Buat akun baru — Gratis!</p>
            </div>
 
            <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                <div class="card-body p-4">
                    <form action="<?= base_url('auth/register') ?>" method="POST">
                        <?= csrf_field() ?>
 
                        <!-- Pilih Role -->
                        <div class="mb-4">
                            <label class="form-label">Saya ingin bergabung sebagai:</label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="role" id="roleBuyer" value="buyer" checked>
                                    <label class="btn btn-outline-primary w-100 d-flex flex-column align-items-center py-3" for="roleBuyer">
                                        <i class="bi bi-person-circle fs-3 mb-1"></i>
                                        <span class="fw-700">Buyer</span>
                                        <small class="text-muted">Beli jasa</small>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <input type="radio" class="btn-check" name="role" id="roleSeller" value="seller">
                                    <label class="btn btn-outline-success w-100 d-flex flex-column align-items-center py-3" for="roleSeller">
                                        <i class="bi bi-briefcase fs-3 mb-1"></i>
                                        <span class="fw-700">Seller</span>
                                        <small class="text-muted">Jual jasa</small>
                                    </label>
                                </div>
                            </div>
                        </div>
 
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="<?= old('name') ?>" placeholder="Nama Anda" required>
                        </div>
 
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= old('email') ?>" placeholder="email@kamu.com" required>
                        </div>
 
                        <!-- Field khusus seller -->
                        <div id="sellerFields" style="display:none">
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label">Universitas</label>
                                    <input type="text" name="university" class="form-control" placeholder="UGM, ITB, UI...">
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Jurusan</label>
                                    <input type="text" name="major" class="form-control" placeholder="Teknik Informatika...">
                                </div>
                            </div>
                        </div>
 
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required>
                        </div>
 
                        <div class="mb-4">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="confirm" class="form-control" placeholder="Ulangi password" required>
                        </div>
 
                        <button type="submit" class="btn-ki-primary btn w-100 py-2">
                            <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                        </button>
                    </form>
 
                    <hr class="my-3">
                    <p class="text-center text-muted mb-0" style="font-size:.9rem">
                        Sudah punya akun? <a href="<?= base_url('auth/login') ?>" style="color:var(--ki-blue);font-weight:600">Login di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
 
<?= $this->section('scripts') ?>
<script>
// Tampil/sembunyikan field seller
document.querySelectorAll('input[name="role"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('sellerFields').style.display =
            this.value === 'seller' ? 'block' : 'none';
    });
});
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>