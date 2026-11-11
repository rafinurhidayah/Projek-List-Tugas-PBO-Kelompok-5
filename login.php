<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/helpers.php';

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
    <title>Masuk - Manajemen Tugas PBO</title>
</head>
<body>
<?php $aktif = 'masuk'; include __DIR__ . '/includes/nav.php'; ?>

<main class="auth">
    <form class="form auth-kartu" method="post" action="actions/auth.php">
        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
        <input type="hidden" name="aksi" value="masuk">

        <div>
            <p class="eyebrow">Selamat datang</p>
            <h1>Masuk</h1>
        </div>

<?php if ($flash): ?>
        <p class="auth-pesan <?= ($flash['tipe'] ?? '') === 'sukses' ? 'sukses' : 'galat' ?>" role="alert"><?= e($flash['pesan'] ?? '') ?></p>
<?php endif; ?>

        <label class="bidang"><span>Username atau email</span>
            <input class="input" type="text" name="identitas" required maxlength="100" autocomplete="username"
                   value="<?= e($lama['identitas'] ?? '') ?>" autofocus>
        </label>
        <label class="bidang"><span>Password</span>
            <input class="input" type="password" name="password" required autocomplete="current-password">
        </label>

        <button type="submit" class="tombol tombol-emas">Masuk</button>
        <p class="auth-alt">Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
    </form>
</main>
<script src="assets/js/script.js" defer></script>
</body>
</html>
