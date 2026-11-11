<?php
declare(strict_types=1);

class Database
{
    private const HOST = 'localhost';
    private const USER = 'root';
    private const PASS = '';
    private const NAMA = 'list_tugas_pbo';

    /** Membuka koneksi; gagal => RuntimeException dengan pesan aman (detail masuk error_log). */
    public function getConnection(): mysqli
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $conn = new mysqli(self::HOST, self::USER, self::PASS, self::NAMA);
            $conn->set_charset('utf8mb4');
            return $conn;
        } catch (mysqli_sql_exception $e) {
            error_log('Koneksi database gagal: ' . $e->getMessage());
            throw new RuntimeException('Tidak dapat terhubung ke database.');
        }
    }
}