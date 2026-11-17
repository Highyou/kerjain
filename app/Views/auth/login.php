<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
 
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <a href="/" class="text-decoration-none">
                    <h1 style="font-weight:800;color:var(--ki-blue);font-size:2rem">KerjaIn<span style="color:var(--ki-green)">.</span></h1>
                </a>
                <p class="text-muted">Masuk ke akun Anda</p>
            </div>
 
            <div class="card border-0 shadow-sm" style="border-radius:var(--ki-radius)">
                <div class="card-body p-4">
                    <form action="<?= base_url('auth/login') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?= old('email') ?>" placeholder="email@kamu.com" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="pwdInput" class="form-control" placeholder="Min. 8 karakter" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePwd()">
                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn-ki-primary btn w-100 py-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                        </button>
                    </form>
 
                    <hr class="my-3">
                    <p class="text-center text-muted mb-0" style="font-size:.9rem">
                        Belum punya akun? <a href="<?= base_url('auth/register') ?>" style="color:var(--ki-blue);font-weight:600">Daftar Gratis</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
 
<?= $this->section('scripts') ?>
<script>
function togglePwd() {
    const inp = document.getElementById('pwdInput');
    const ico = document.getElementById('eyeIcon');
    inp.type = inp.type === 'password' ? 'text' : 'password';
    ico.className = inp.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>