<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/TugasController.php';

const HALAMAN = '../halaman_tugas.php';
$ajax = str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');

/** JSON untuk fetch(); tanpa JavaScript => flash message + redirect biasa. */
function balas(bool $ok, string $pesan, bool $ajax, int $kode = 200): never
{
    if ($ajax) {
        http_response_code($kode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => $ok, 'pesan' => $pesan], JSON_UNESCAPED_UNICODE);
        exit;
    }
    flash($ok ? 'sukses' : 'error', $pesan);
    header('Location: ' . HALAMAN);
    exit;
}

// Semua aksi hanya lewat POST + login + token CSRF.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . HALAMAN);
    exit;
}
if (!sudah_login()) {
    balas(false, 'Sesi berakhir. Silakan masuk lagi.', $ajax, 401);
}
if (!csrf_valid($_POST['csrf'] ?? null)) {
    balas(false, 'Sesi kedaluwarsa. Muat ulang halaman lalu coba lagi.', $ajax, 403);
}

try {
    $kontrol = new TugasController((new Database())->getConnection(), (int)user_id());
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

    switch ($_POST['aksi'] ?? '') {
        case 'tambah':
            $kontrol->tambah(Tugas::dariInput($_POST));
            balas(true, 'Tugas berhasil disimpan.', $ajax);

        case 'status':
            if ($id === false) { throw new InvalidArgumentException('ID tugas tidak valid.'); }
            $kontrol->ubahStatus($id, ($_POST['status'] ?? '0') === '1')
                ? balas(true, 'Status diperbarui.', $ajax)
                : balas(false, 'Tugas tidak ditemukan.', $ajax, 404);

        case 'hapus':
            if ($id === false) { throw new InvalidArgumentException('ID tugas tidak valid.'); }
            $kontrol->hapus($id)
                ? balas(true, 'Tugas dihapus.', $ajax)
                : balas(false, 'Tugas tidak ditemukan.', $ajax, 404);

        default:
            balas(false, 'Aksi tidak dikenal.', $ajax, 400);
    }
} catch (InvalidArgumentException $e) {
    balas(false, $e->getMessage(), $ajax, 422);
} catch (Throwable $e) {
    error_log($e->getMessage());
    balas(false, 'Terjadi kesalahan pada server.', $ajax, 500);
}
