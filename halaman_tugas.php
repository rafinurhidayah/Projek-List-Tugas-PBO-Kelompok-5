<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/TugasController.php';

wajib_login();

$daftar = [];
$galat  = null;
try {
    $daftar = (new TugasController((new Database())->getConnection(), (int)user_id()))->semua();
} catch (Throwable $e) {
    error_log($e->getMessage());
    $galat = 'Tugas belum bisa dimuat. Pastikan MySQL di XAMPP menyala dan database "list_tugas_pbo" sudah diimpor.';
}

$total = count($daftar);
$belum = count(array_filter($daftar, fn(Tugas $t) => !$t->isSelesai()));
$hitung = [
    'semua'   => $total,
    'kuliah'  => count(array_filter($daftar, fn(Tugas $t) => $t->getJenis() === 'kuliah')),
    'pribadi' => count(array_filter($daftar, fn(Tugas $t) => $t->getJenis() === 'pribadi')),
];
$csrf  = csrf_token();
$flash = ambil_flash();

const IKON_CEK   = '<svg viewBox="0 0 16 16" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 8.5l3.2 3.2L13 4.8"/></svg>';
const IKON_HAPUS = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M10 11v6M14 11v6M6 7l1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-12M9 7V4h6v3"/></svg>';
const IKON_X     = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . '/includes/head.php'; ?>
    <title>Daftar Tugas Saya - PBO</title>
</head>
<body class="halaman-tugas" data-flash-tipe="<?= e($flash['tipe'] ?? '') ?>" data-flash-pesan="<?= e($flash['pesan'] ?? '') ?>">

<?php $aktif = 'tugas'; include __DIR__ . '/includes/nav.php'; ?>

<main class="wadah">
    <div class="kepala-daftar">
        <div>
            <p class="eyebrow">Tugas saya</p>
            <h1>Daftar Tugas</h1>
        </div>
        <button type="button" class="tombol tombol-emas" data-buka-modal>+ Buat Tugas</button>
    </div>

<?php if ($galat): ?>
    <p class="kosong galat" role="alert"><?= e($galat) ?></p>
<?php else: ?>
    <div class="toolbar">
        <div class="chips" role="group" aria-label="Filter jenis tugas">
            <button type="button" class="chip" data-filter="semua" aria-pressed="true">Semua <b><?= $hitung['semua'] ?></b></button>
            <button type="button" class="chip" data-filter="kuliah" aria-pressed="false">Tugas Kuliah <b><?= $hitung['kuliah'] ?></b></button>
            <button type="button" class="chip" data-filter="pribadi" aria-pressed="false">Tugas Pribadi <b><?= $hitung['pribadi'] ?></b></button>
        </div>
        <input type="search" id="cari" class="input cari" placeholder="Cari tugas…  (tekan /)" aria-label="Cari tugas" autocomplete="off">
    </div>
    <p id="ringkasan" class="ringkasan" aria-live="polite"><?= $total ? "$belum dari $total tugas belum selesai" : '' ?></p>

    <div id="daftar" class="daftar">
    <?php foreach ($daftar as $t): $id = (int)$t->getId(); ?>
        <article class="kartu<?= $t->isPenting() ? ' is-penting' : '' ?><?= $t->isSelesai() ? ' is-selesai' : '' ?>"
                 data-jenis="<?= e($t->getJenis()) ?>" data-selesai="<?= $t->isSelesai() ? '1' : '0' ?>">
            <form class="kartu-cek" method="post" action="actions/proses.php" data-ajax="status">
                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                <input type="hidden" name="aksi" value="status">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="status" value="<?= $t->isSelesai() ? '0' : '1' ?>">
                <button type="submit" class="cek" aria-pressed="<?= $t->isSelesai() ? 'true' : 'false' ?>"
                        aria-label="Tandai selesai: <?= e($t->getJudul()) ?>"><span class="kotak"><?= IKON_CEK ?></span></button>
            </form>
            <div class="kartu-isi">
                <p class="kode"><?= e($t->getKode()) ?></p>
                <h3><?= e($t->getJudul()) ?></h3>
                <?php if ($t->getDetail() !== ''): ?><p class="detail"><?= e($t->getDetail()) ?></p><?php endif; ?>
            </div>
            <div class="kartu-meta">
                <span class="lencana"><?= e($t->getJenisLabel()) ?></span>
                <span class="lencana<?= $t->isPenting() ? ' lencana-penting' : '' ?>"><?= $t->isPenting() ? 'Penting' : 'Biasa' ?></span>
                <span class="lencana lencana-status <?= $t->isSelesai() ? 'lencana-sudah' : 'lencana-belum' ?>"><?= $t->isSelesai() ? 'Sudah' : 'Belum' ?></span>
            </div>
            <form class="kartu-hapus" method="post" action="actions/proses.php" data-ajax="hapus"
                  data-konfirmasi="Hapus tugas “<?= e($t->getJudul()) ?>”? Tindakan ini tidak bisa dibatalkan.">
                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                <input type="hidden" name="aksi" value="hapus">
                <input type="hidden" name="id" value="<?= $id ?>">
                <button type="submit" class="ikon-hapus" aria-label="Hapus: <?= e($t->getJudul()) ?>"><?= IKON_HAPUS ?></button>
            </form>
        </article>
    <?php endforeach; ?>
    </div>

    <p id="kosong" class="kosong"<?= $total ? ' hidden' : '' ?>>Belum ada tugas. Tekan <strong>+ Buat Tugas</strong> untuk memulai.</p>
    <p id="kosong-filter" class="kosong" hidden>Tidak ada tugas yang cocok dengan filter atau pencarian.</p>
<?php endif; ?>
</main>

<!-- Form: muncul dan hilang (elemen <dialog> bawaan browser: Esc menutup, fokus terkunci di dalam) -->
<dialog id="modal-tugas" class="modal" aria-labelledby="judul-modal">
    <form id="form-tugas" class="form" method="post" action="actions/proses.php">
        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
        <input type="hidden" name="aksi" value="tambah">
        <div class="form-kepala">
            <h2 id="judul-modal">Buat Tugas</h2>
            <button type="button" class="ikon-tutup" data-tutup aria-label="Tutup"><?= IKON_X ?></button>
        </div>

        <label class="bidang"><span>ID</span>
            <input class="input" type="text" value="Otomatis" disabled>
        </label>
        <label class="bidang"><span>Judul</span>
            <input class="input" type="text" name="judul" required maxlength="255" autocomplete="off" placeholder="Contoh: Laporan Praktikum PBO">
        </label>
        <fieldset class="bidang"><legend>Jenis</legend>
            <div class="pilihan">
                <label><input type="radio" name="jenis" value="kuliah" checked><span>Tugas Kuliah</span></label>
                <label><input type="radio" name="jenis" value="pribadi"><span>Tugas Pribadi</span></label>
            </div>
        </fieldset>
        <label class="bidang"><span>Detail Tambahan <small id="hitung">0/255</small></span>
            <textarea class="input" name="detail_tambahan" rows="3" maxlength="255" placeholder="Catatan, tautan, atau tenggat…"></textarea>
        </label>
        <fieldset class="bidang"><legend>Prioritas</legend>
            <div class="pilihan">
                <label><input type="radio" name="prioritas" value="penting"><span>Penting</span></label>
                <label><input type="radio" name="prioritas" value="normal" checked><span>Biasa</span></label>
            </div>
        </fieldset>
        <fieldset class="bidang"><legend>Status</legend>
            <div class="pilihan">
                <label><input type="radio" name="status" value="0" checked><span>Belum</span></label>
                <label><input type="radio" name="status" value="1"><span>Sudah</span></label>
            </div>
        </fieldset>

        <div class="aksi-form">
            <button type="button" class="tombol tombol-garis" data-tutup>Batal</button>
            <button type="submit" class="tombol tombol-emas">Simpan Tugas</button>
        </div>
    </form>
</dialog>

<dialog id="modal-konfirmasi" class="modal modal-kecil" aria-labelledby="judul-konfirmasi">
    <form method="dialog" class="form">
        <h2 id="judul-konfirmasi">Hapus tugas?</h2>
        <p id="teks-konfirmasi" class="detail"></p>
        <div class="aksi-form">
            <button value="batal" class="tombol tombol-garis">Batal</button>
            <button value="ya" class="tombol tombol-bahaya">Hapus</button>
        </div>
    </form>
</dialog>

<div id="toast" class="toast-area" role="status" aria-live="polite"></div>
<script src="assets/js/script.js" defer></script>
</body>
</html>
