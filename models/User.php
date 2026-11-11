<?php
declare(strict_types=1);

/**
 * ENKAPSULASI : data pengguna di properti protected, dibaca lewat getter.
 * Hash password TIDAK disimpan di objek; verifikasi dilakukan UserController.
 */
class User
{
    public const MIN_PASSWORD = 8;

    public function __construct(
        protected string $username,
        protected string $email,
        protected ?int $id = null,
    ) {}

    /**
     * Validasi isian form daftar. Mengembalikan [objek User, password mentah].
     * @return array{0: User, 1: string}
     */
    public static function dariInputDaftar(array $in): array
    {
        $username = trim((string)($in['username'] ?? ''));
        $email    = mb_strtolower(trim((string)($in['email'] ?? '')));
        $password = (string)($in['password'] ?? '');
        $ulang    = (string)($in['password_ulang'] ?? '');

        if (!preg_match('/^[A-Za-z0-9_.]{3,30}$/', $username)) {
            throw new InvalidArgumentException('Username 3-30 karakter: huruf, angka, titik, atau garis bawah.');
        }
        if (mb_strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Format email tidak valid.');
        }
        if (mb_strlen($password) < self::MIN_PASSWORD) {
            throw new InvalidArgumentException('Password minimal ' . self::MIN_PASSWORD . ' karakter.');
        }
        if (strlen($password) > 72) { // batas bcrypt: lebih dari 72 byte dipotong diam-diam
            throw new InvalidArgumentException('Password maksimal 72 karakter.');
        }
        if (!hash_equals($password, $ulang)) {
            throw new InvalidArgumentException('Konfirmasi password tidak sama.');
        }
        return [new self($username, $email), $password];
    }

    public function getId(): ?int       { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function getEmail(): string  { return $this->email; }
}
