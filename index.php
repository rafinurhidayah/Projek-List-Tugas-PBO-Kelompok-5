<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/helpers.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . '/includes/head.php'; ?>
    <meta name="description" content="Kelola tugas kuliah dan pribadi dengan lebih terstruktur.">
    <title>Manajemen Tugas PBO</title>
</head>
<body>
<section class="hero">
    <img class="hero-foto" src="assets/img/hero-buku.jpg" alt="" fetchpriority="high">
    <?php $aktif = 'beranda'; include __DIR__ . '/includes/nav.php'; ?>
    <div class="hero-isi">
        <p class="eyebrow">Manajemen Tugas PBO</p>
        <h1 class="headline">Kelola Tugas Kuliah Dan Pribadi Lebih Terstruktur</h1>
        <div class="aksi-hero">
<?php if (sudah_login()): ?>
            <a class="tombol tombol-emas" href="halaman_tugas.php">Lihat Tugas Saya</a>
            <a class="tombol tombol-garis" href="halaman_tugas.php#buat">Buat Tugas</a>
<?php else: ?>
            <a class="tombol tombol-emas" href="login.php">Masuk</a>
            <a class="tombol tombol-garis" href="register.php">Daftar</a>
<?php endif; ?>
        </div>
    </div>
</section>
<script src="assets/js/script.js" defer></script>
</body>
</html>
