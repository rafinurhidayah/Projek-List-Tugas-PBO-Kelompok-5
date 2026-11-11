<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/User.php';

class UserController
{
    /** Hash palsu: password_verify tetap dijalankan walau akun tidak ada (waktu respons sama). */
    private const HASH_PALSU = '$2y$07$BCryptRequires22Chrcte/VlQH0piJtjXl.0t1XkA8pw9dMXTpOq';

    public function __construct(private mysqli $db) {}

    /** CREATE: simpan akun baru, mengembalikan id. Password disimpan sebagai hash. */
    public function daftar(User $u, string $password): int
    {
        $username = $u->getUsername();
        $email    = $u->getEmail();
        $hash     = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $this->db->prepare('INSERT INTO `user` (username, email, password) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $username, $email, $hash);
            $stmt->execute();
            return $this->db->insert_id;
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) { // duplicate entry (username/email unik)
                throw new InvalidArgumentException('Username atau email sudah terdaftar.');
            }
            throw $e;
        }
    }

    /** Login dengan username ATAU email. Pesan gagal sengaja sama agar akun tidak bisa ditebak. */
    public function masuk(string $identitas, string $password): User
    {
        $stmt = $this->db->prepare('SELECT id, username, email, password FROM `user` WHERE username = ? OR email = ? LIMIT 1');
        $stmt->bind_param('ss', $identitas, $identitas);
        $stmt->execute();
        $r = $stmt->get_result()->fetch_assoc();

        $cocok = password_verify($password, $r['password'] ?? self::HASH_PALSU);
        if ($r === null || !$cocok) {
            throw new InvalidArgumentException('Username/email atau password salah.');
        }

        if (password_needs_rehash($r['password'], PASSWORD_DEFAULT)) {
            $baru = password_hash($password, PASSWORD_DEFAULT);
            $id   = (int)$r['id'];
            $up   = $this->db->prepare('UPDATE `user` SET password = ? WHERE id = ?');
            $up->bind_param('si', $baru, $id);
            $up->execute();
        }
        return new User($r['username'], $r['email'], (int)$r['id']);
    }
}
