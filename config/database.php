<?php
// Mengambil nilai dari Env Vars EthioDeploy
$host = getenv('DB_HOST');
$user = getenv('DB_USERNAME');
$pass = getenv('DB_PASSWORD');
$db   = getenv('DB_DATABASE');
$port = getenv('DB_PORT');

// Membuat koneksi menggunakan OOP mysqli
$koneksi = new mysqli($host, $user, $pass, $db, $port);

// Mengecek koneksi
if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}
?>