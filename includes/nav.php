<?php $aktif = $aktif ?? ''; ?>
<header class="nav">
    <a class="logo" href="index.php" aria-label="MNTN, beranda">UMKT</a>
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="menu-nav" aria-label="Buka menu"><span></span></button>
    <nav id="menu-nav" class="nav-menu" aria-label="Utama">
        <a href="index.php"<?= $aktif === 'beranda' ? ' aria-current="page"' : '' ?>>Beranda</a>
        <a href="halaman_tugas.php"<?= $aktif === 'tugas' ? ' aria-current="page"' : '' ?>>Tugas Saya</a>
        <a class="nav-cta" href="halaman_tugas.php#buat">+ Buat Tugas</a>
    </nav>
</header>
