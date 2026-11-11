# Manajemen Tugas PBO

Aplikasi daftar tugas (PHP + MySQL) dengan penerapan OOP: abstraksi, enkapsulasi, pewarisan, dan polimorfisme.
Setiap pengguna punya akun sendiri (daftar/masuk) dan hanya melihat tugasnya sendiri.

## Menjalankan (XAMPP)
1. Salin folder ini ke `C:\xampp\htdocs\projek_list_tugas_pbo`.
2. Nyalakan **Apache** dan **MySQL** di XAMPP Control Panel.
3. Buka `http://localhost/phpmyadmin`. Jika database `list_tugas_pbo` versi lama sudah ada, **hapus dulu**.
   Lalu tab **Import** → pilih `database/list_tugas_pbo.sql` → **Import**
   (file ini sudah membuat database `list_tugas_pbo` sendiri).
4. Buka `http://localhost/projek_list_tugas_pbo/`, klik **Daftar** untuk membuat akun, lalu **Masuk**.

Pengaturan koneksi ada di `config/database.php` (bawaan XAMPP: user `root`, tanpa password).

## Struktur
| Folder / file | Fungsi |
|---|---|
| `models/Tugas.php` | `Tugas` (abstract), `TugasKuliah`, `TugasPribadi`, factory + validasi |
| `models/User.php` | `User` + validasi form daftar |
| `controllers/TugasController.php` | CRUD tugas milik satu pengguna (prepared statement, selalu dibatasi `user_id`) |
| `controllers/UserController.php` | Daftar akun (password di-hash) dan verifikasi login |
| `actions/proses.php` | Penerima form tugas (hanya POST + login + token CSRF) |
| `actions/auth.php` | Penerima form masuk, daftar, dan keluar |
| `includes/` | `helpers.php` (escape, CSRF, flash, sesi login), `nav.php`, `head.php` |
| `index.php`, `login.php`, `register.php`, `halaman_tugas.php` | Beranda, masuk, daftar, dan daftar tugas |
| `assets/css/style.css`, `assets/js/script.js` | Tampilan responsif dan perilaku |
| `database/list_tugas_pbo.sql` | Struktur tabel `user` dan `tugas` (relasi `tugas.user_id` → `user.id`) |

## Relasi database
`user` 1 — N `tugas`. Kolom `tugas.user_id` adalah foreign key ke `user.id` dengan `ON DELETE CASCADE`.

## Keamanan
- Password disimpan dengan `password_hash()` (bcrypt), diperiksa dengan `password_verify()`.
- ID sesi diganti saat login; cookie sesi `HttpOnly` + `SameSite=Lax`.
- Semua aksi lewat POST dengan token CSRF; query memakai prepared statement; output di-escape.
- Tugas hanya bisa diubah/dihapus oleh pemiliknya (`WHERE id = ? AND user_id = ?`).

## Perilaku di halaman tugas
- Tombol **+ Buat Tugas** (atau tekan `n`) membuka form; `Esc`, tombol X, Batal, atau klik di luar menutupnya.
- Filter jenis + kolom cari (tekan `/`), jumlah di tiap filter ikut berubah.
- Centang tugas selesai dan hapus (dengan konfirmasi) tanpa reload halaman.
- Notifikasi (toast) setelah simpan, hapus, atau saat ada error. Jika sesi habis, otomatis diarahkan ke halaman masuk.
- Tanpa JavaScript, semua tombol tetap berfungsi sebagai form biasa.
