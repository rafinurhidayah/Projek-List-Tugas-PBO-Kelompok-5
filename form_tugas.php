<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Tugas Baru - PBO</title>

</head>
<body class="form-page-body">

    <!-- Container khusus untuk halaman form -->
    <main class="form-page-container">
        
        <div class="form-box">
            <div class="form-header">
                <h2>Buat Tugas Baru</h2>
                <!-- Link X untuk kembali ke dashboard -->
                <a href="index.php" class="btn-close-link">X</a>
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
                    <!-- Tombol Batal mengarah kembali ke index.php -->
                    <a href="index.php" class="btn-cancel-link">Batal</a>
                    <button type="submit" name="tambah" class="btn-submit">Simpan Tugas</button>
                </div>

            </form>
        </div>

    </main>

</body>
</html>