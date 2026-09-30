<?php
class Database {
    // Menambahkan tipe data 'string' untuk teks
    private string $host = "localhost";
    private string $user = "root";
    private string $pass = "";
    private string $db_name = "list_tugas_pbo";
    
    // Menambahkan tipe data '?mysqli'
    public ?mysqli $conn = null;

    // Menambahkan ': ?mysqli' sebagai tipe data kembalian (return type)
    public function getConnection(): ?mysqli {
        try {
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db_name);
        } catch(Exception $e) {
            echo "Koneksi Error: " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>