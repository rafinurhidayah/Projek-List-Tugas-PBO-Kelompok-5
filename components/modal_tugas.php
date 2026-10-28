<!-- Overlay background -->
<div id="modalOverlay" class="modal-overlay" style="display: none;">
    <!-- Kotak Form -->
    <div class="modal-box">
        
        <div class="modal-header">
            <h2>Buat Tugas</h2>
            <button class="btn-close" id="btnTutupModal">X</button>
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
                    <label>
                        <input type="radio" name="jenis" value="kuliah" checked>
                        Tugas Kuliah
                    </label>
                    <label>
                        <input type="radio" name="jenis" value="pribadi">
                        Tugas Pribadi
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Detail Tambahan</label>
                <textarea name="detail_tambahan" rows="2" placeholder="Catatan, tautan, atau tenggat..."></textarea>
            </div>

            <div class="form-group">
                <label>Prioritas</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="prioritas" value="penting">
                        Penting
                    </label>
                    <label>
                        <input type="radio" name="prioritas" value="normal" checked>
                        Biasa
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-cancel" id="btnBatalModal">Batal</button>
                <button type="submit" name="tambah" class="btn-submit">Simpan Tugas</button>
            </div>

        </form>
    </div>
</div>