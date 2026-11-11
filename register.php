<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/models/User.php';

if (sudah_login()) {
    header('Location: halaman_tugas.php');
    exit;
}
$csrf  = csrf_token();
$flash = ambil_flash();
$lama  = ambil_input();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . '/includes/head.php'; ?>
    <title>Daftar - Manajemen Tugas PBO</title>
</head>
<body>
<?php $aktif = 'daftar'; include __DIR__ . '/includes/nav.php'; ?>

<main class="auth">
    <form class="form auth-kartu" method="post" action="actions/auth.php">
        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
        <input type="hidden" name="aksi" value="daftar">

        <div>
            <p class="eyebrow">Akun baru</p>
            <h1>Daftar</h1>
        </div>

<?php if ($flash): ?>
        <p class="auth-pesan <?= ($flash['tipe'] ?? '') === 'sukses' ? 'sukses' : 'galat' ?>" role="alert"><?= e($flash['pesan'] ?? '') ?></p>
<?php endif; ?>

        <label class="bidang"><span>Username <small>3-30 karakter</small></span>
            <input class="input" type="text" name="username" required minlength="3" maxlength="30"
                   pattern="[A-Za-z0-9_.]{3,30}" title="Huruf, angka, titik, atau garis bawah" autocomplete="username"
                   value="<?= e($lama['username'] ?? '') ?>" autofocus>
        </label>
        <label class="bidang"><span>Email</span>
            <input class="input" type="email" name="email" required maxlength="100" autocomplete="email"
                   value="<?= e($lama['email'] ?? '') ?>">
        </label>
        <label class="bidang"><span>Password <small>minimal <?= User::MIN_PASSWORD ?> karakter</small></span>
            <input class="input" type="password" name="password" required minlength="<?= User::MIN_PASSWORD ?>" maxlength="72" autocomplete="new-password">
        </label>
        <label class="bidang"><span>Ulangi password</span>
            <input class="input" type="password" name="password_ulang" required minlength="<?= User::MIN_PASSWORD ?>" maxlength="72" autocomplete="new-password">
        </label>

        <button type="submit" class="tombol tombol-emas">Buat Akun</button>
        <p class="auth-alt">Sudah punya akun? <a href="login.php">Masuk</a></p>
    </form>
</main>
<script src="assets/js/script.js" defer></script>
</body>
</html>
