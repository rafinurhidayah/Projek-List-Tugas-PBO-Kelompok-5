<div class="dashboard-header">
    <div class="dashboard-title-area">
        <span class="section-subtitle">—— TUGAS SAYA</span>
        <h2 class="section-title">Daftar Tugas</h2>
    </div>
    
    <!-- BERUBAH: Ini sekarang menjadi link ke halaman form_tugas.php -->
    <a href="form_tugas.php" class="btn-tambah">+ Buat Tugas</a>
</div>

<!-- ... (Kode filter dan list tugas di bawahnya tetap sama) ... -->

<div class="task-filters">
    <div class="filter-badges">
        <span class="badge active">Semua : 4</span>
        <span class="badge">Tugas Kuliah : 2</span>
        <span class="badge">Tugas Pribadi : 2</span>
    </div>
    <span class="filter-summary">3 dari 4 tugas belum selesai</span>
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
                    
                    <!-- Tombol Aksi (Bisa disembunyikan/ditampilkan dengan CSS hover nantinya) -->
                    <div class="task-actions">
                        <?php if(!$tugas->getStatus()): ?>
                            <a href="actions/proses.php?selesai=<?php echo $tugas->getId(); ?>" class="btn-selesai">Selesai</a>
                        <?php endif; ?>
                        <a href="actions/proses.php?hapus=<?php echo $tugas->getId(); ?>" class="btn-hapus">Hapus</a>
                    </div>
                </div>
            </article>

        <?php endforeach; ?>
    <?php else: ?>
        <p class="empty-state">Belum ada tugas yang ditambahkan.</p>
    <?php endif; ?>
</div>