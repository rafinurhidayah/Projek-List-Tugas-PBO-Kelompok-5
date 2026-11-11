<?php
require_once __DIR__ . '/helpers.php';
$aktif = $aktif ?? '';
?>
<header class="nav">
    <a class="logo" href="index.php" aria-label="UMKT, beranda">UMKT</a>
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="menu-nav" aria-label="Buka menu"><span></span></button>
    <nav id="menu-nav" class="nav-menu" aria-label="Utama">
        <a href="index.php"<?= $aktif === 'beranda' ? ' aria-current="page"' : '' ?>>Beranda</a>
<?php if (sudah_login()): ?>
        <a href="halaman_tugas.php"<?= $aktif === 'tugas' ? ' aria-current="page"' : '' ?>>Tugas Saya</a>
        <a class="nav-cta" href="halaman_tugas.php#buat">+ Buat Tugas</a>
        <form class="nav-keluar" method="post" action="actions/auth.php">
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="aksi" value="keluar">
            <span class="nav-user"><?= e(user_nama()) ?></span>
            <button type="submit">Keluar</button>
        </form>
<?php else: ?>
        <a href="login.php"<?= $aktif === 'masuk' ? ' aria-current="page"' : '' ?>>Masuk</a>
        <a class="nav-cta" href="register.php">Daftar</a>
<?php endif; ?>
    </nav>
</header>
