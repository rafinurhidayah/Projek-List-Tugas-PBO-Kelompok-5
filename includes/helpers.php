<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    // Nama sesi sendiri agar tidak bentrok dengan proyek PHP lain di localhost.
    session_name('tugas_pbo');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

/** Escape output HTML (cegah XSS). Pakai di SETIAP teks dari pengguna/database. */
function e(?string $teks): string
{
    return htmlspecialchars((string)$teks, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/* ---------- CSRF ---------- */

function csrf_token(): string
{
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}

/** Token kosong atau sesi tanpa token selalu ditolak (hash_equals('', '') bernilai true). */
function csrf_valid(mixed $token): bool
{
    $asli = $_SESSION['csrf'] ?? '';
    return is_string($token) && $token !== '' && is_string($asli) && $asli !== '' && hash_equals($asli, $token);
}

/* ---------- Flash message & input lama ---------- */

function flash(string $tipe, string $pesan): void
{
    $_SESSION['flash'] = ['tipe' => $tipe, 'pesan' => $pesan];
}

function ambil_flash(): ?array
{
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

/** Simpan isian form (tanpa password) agar tidak hilang saat validasi gagal. */
function ingat_input(array $data): void
{
    $_SESSION['lama'] = $data;
}

function ambil_input(): array
{
    $d = $_SESSION['lama'] ?? [];
    unset($_SESSION['lama']);
    return is_array($d) ? $d : [];
}

/* ---------- Autentikasi ---------- */

function sudah_login(): bool
{
    return isset($_SESSION['user']['id']);
}

function user_id(): ?int
{
    return sudah_login() ? (int)$_SESSION['user']['id'] : null;
}

function user_nama(): string
{
    return (string)($_SESSION['user']['username'] ?? '');
}

/** Dipanggil di halaman yang butuh login (dari folder root proyek). */
function wajib_login(): void
{
    if (!sudah_login()) {
        flash('error', 'Silakan masuk terlebih dahulu.');
        header('Location: login.php');
        exit;
    }
}

/** Simpan pengguna ke sesi. ID sesi diganti untuk mencegah session fixation. */
function login_sesi(User $user): void
{
    session_regenerate_id(true);
    $_SESSION['user'] = ['id' => $user->getId(), 'username' => $user->getUsername()];
}

function keluar_sesi(): void
{
    $_SESSION = [];
    session_regenerate_id(true);
}
