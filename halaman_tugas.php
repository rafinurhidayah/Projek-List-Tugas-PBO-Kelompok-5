<?php
// Wajib memanggil database dan controller di file yang menampilkan data
require_once 'config/Database.php';
require_once 'controllers/TugasController.php';

$database = new Database();
$db = $database->getConnection();
$controller = new TugasController($db);
$daftarTugas = $controller->getSemuaTugas();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas Saya - PBO</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="task-page-body">

    <!-- AREA DAFTAR TUGAS -->
    <main class="task-container">
        
        <div class="task-header">
            <div class="task-title-area">
                <span class="section-subtitle">—— TUGAS SAYA</span>
                <h2 class="section-title">Daftar Tugas</h2>
            </div>
            <!-- Tombol ini akan ditangkap oleh JavaScript untuk memunculkan Floating Card -->
            <button id="btnBukaModal" class="btn-tambah">+ Buat Tugas</button>
        </div>

        <div class="task-filters">
            <div class="filter-badges">
                <span class="badge active">Semua</span>
                <span class="badge">Tugas Kuliah</span>
                <span class="badge">Tugas Pribadi</span>
            </div>
        </div>

        <div class="task-list">
            <?php if (!empty($daftarTugas)): ?>
                <?php foreach($daftarTugas as $tugas): ?>
                    <article class="task-card">
                        <div class="task-info">
                            <input type="checkbox" class="task-checkbox" <?php echo $tugas->getStatus() ? 'checked' : ''; ?>>
                            <div class="task-text">
                                <h3 class="task-title"><?php echo $tugas->getJudul(); ?></h3>
                                <p class="task-detail"><?php echo $tugas->getDetailTugas(); ?></p>
                            </div>
                        </div>
                        <div class="task-meta">
                            <span class="badge-priority"><?php echo $tugas->getPrioritas(); ?></span>
                            <span class="badge-status"><?php echo $tugas->getStatus() ? 'Sudah' : 'Belum'; ?></span>
                            
                            <div class="task-actions">
                                <?php if(!$tugas->getStatus()): ?>
                                    <a href="actions/proses.php?selesai=<?php echo $tugas->getId(); ?>" class="btn-selesai">✔</a>
                                <?php endif; ?>
                                <a href="actions/proses.php?hapus=<?php echo $tugas->getId(); ?>" class="btn-hapus">✖</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty-state">Belum ada tugas yang ditambahkan.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- AREA FLOATING CARD FORM (Sembunyi secara default) -->
    <div id="modalOverlay" class="modal-overlay" style="display: none;">
        <div class="modal-box">
            <div class="modal-header">
                <h2>Buat Tugas</h2>
                <button id="btnTutupModal" class="btn-close-modal">X</button>
            </div>
            
            <form action="actions/proses.php" method="POST" class="form-tugas">
                <div class="form-group">
                    <label>ID</label>
                    <input type="text" value="Otomatis" disabled>
                </div>
                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" name="judul" required placeholder="Contoh: Laporan Praktikum PBO">
                </div>
                <div class="form-group">
                    <label>Jenis</label>
                    <div class="radio-group">
                        <label><input type="radio" name="jenis" value="kuliah" checked> Tugas Kuliah</label>
                        <label><input type="radio" name="jenis" value="pribadi"> Tugas Pribadi</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Detail Tambahan</label>
                    <textarea name="detail_tambahan" rows="2" placeholder="Catatan, tautan, atau tenggat..."></textarea>
                </div>
                <div class="form-group">
                    <label>Prioritas</label>
                    <div class="radio-group">
                        <label><input type="radio" name="prioritas" value="penting"> Penting</label>
                        <label><input type="radio" name="prioritas" value="normal" checked> Biasa</label>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" id="btnBatalModal" class="btn-cancel">Batal</button>
                    <button type="submit" name="tambah" class="btn-submit">Simpan Tugas</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Panggil JavaScript untuk mengatur Floating Card -->
    <script src="assets/js/script.js"></script>
</body>
</html>