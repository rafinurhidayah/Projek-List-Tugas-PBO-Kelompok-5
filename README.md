# Manajemen Tugas PBO

Aplikasi daftar tugas (PHP + MySQL) dengan penerapan OOP: abstraksi, enkapsulasi, pewarisan, dan polimorfisme.

## Menjalankan (XAMPP)
1. Salin folder ini ke `C:\xampp\htdocs\projek_list_tugas_pbo`.
2. Nyalakan **Apache** dan **MySQL** di XAMPP Control Panel.
3. Buka `http://localhost/phpmyadmin` → tab **Import** → pilih `database/list_tugas_pbo.sql` → **Import**
   (file ini sudah membuat database `list_tugas_pbo` sendiri).
4. Buka `http://localhost/projek_list_tugas_pbo/`.

Pengaturan koneksi ada di `config/Database.php` (bawaan XAMPP: user `root`, tanpa password).

## Struktur
| Folder / file | Fungsi |
|---|---|
| `models/Tugas.php` | `Tugas` (abstract), `TugasKuliah`, `TugasPribadi`, factory + validasi |
| `controllers/TugasController.php` | CRUD ke database (prepared statement) |
| `actions/proses.php` | Penerima form (hanya POST + token CSRF) |
| `includes/` | `helpers.php` (escape, CSRF, flash), `nav.php`, `head.php` |
| `index.php`, `halaman_tugas.php` | Halaman beranda dan daftar tugas |
| `assets/css/style.css`, `assets/js/script.js` | Tampilan responsif dan perilaku |
| `database/list_tugas_pbo.sql` | Struktur + contoh data |

## Perilaku di halaman tugas
- Tombol **+ Buat Tugas** (atau tekan `n`) membuka form; `Esc`, tombol X, Batal, atau klik di luar menutupnya.
- Filter jenis + kolom cari (tekan `/`), jumlah di tiap filter ikut berubah.
- Centang tugas selesai dan hapus (dengan konfirmasi) tanpa reload halaman.
- Notifikasi (toast) setelah simpan, hapus, atau saat ada error.
- Tanpa JavaScript, semua tombol tetap berfungsi sebagai form biasa.
