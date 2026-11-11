<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../models/User.php';

function alihkan(string $url): never
{
    header('Location: ' . $url);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    alihkan('../login.php');
}

$aksi  = (string)($_POST['aksi'] ?? '');
$balik = $aksi === 'daftar' ? '../register.php' : '../login.php';

if (!csrf_valid($_POST['csrf'] ?? null)) {
    flash('error', 'Sesi kedaluwarsa. Muat ulang halaman lalu coba lagi.');
    alihkan($balik);
}

try {
    switch ($aksi) {
        case 'masuk':
            $identitas = trim((string)($_POST['identitas'] ?? ''));
            $password  = (string)($_POST['password'] ?? '');
            if ($identitas === '' || $password === '') {
                throw new InvalidArgumentException('Isi username/email dan password.');
            }
            $user = (new UserController((new Database())->getConnection()))->masuk($identitas, $password);
            login_sesi($user);
            alihkan('../halaman_tugas.php');

        case 'daftar':
            [$user, $password] = User::dariInputDaftar($_POST);
            (new UserController((new Database())->getConnection()))->daftar($user, $password);
            flash('sukses', 'Akun berhasil dibuat. Silakan masuk.');
            alihkan('../login.php');

        case 'keluar':
            keluar_sesi();
            flash('sukses', 'Anda sudah keluar.');
            alihkan('../login.php');

        default:
            alihkan('../login.php');
    }
} catch (InvalidArgumentException $e) {
    flash('error', $e->getMessage());
    ingat_input($aksi === 'daftar'
        ? ['username' => (string)($_POST['username'] ?? ''), 'email' => (string)($_POST['email'] ?? '')]
        : ['identitas' => (string)($_POST['identitas'] ?? '')]);
    alihkan($balik);
} catch (Throwable $e) {
    error_log($e->getMessage());
    flash('error', 'Terjadi kesalahan pada server. Pastikan MySQL menyala dan database sudah diimpor.');
    alihkan($balik);
}

catch (Throwable $e) {
    // Tampilkan error asli ke layar sementara waktu untuk mencari tahu masalahnya
    flash('error', 'Error Sistem: ' . $e->getMessage()); 
    alihkan($balik);
}