<?php
// Memanggil class Model agar bisa digunakan untuk membentuk objek
require_once __DIR__ . '/../models/Tugas.php';

class TugasController {
    private mysqli $conn;
    private string $table = "tugas";

    public function __construct(mysqli $db) {
        $this->conn = $db;
    }

    // CREATE: Menyimpan data ke database
    public function tambahTugas(Tugas $tugas, string $jenis, string $detail_tambahan): bool {
        // Mengambil data dari dalam objek menggunakan metode Getter (Enkapsulasi)
        $judul = $tugas->getJudul();
        $prioritas = $tugas->getPrioritas(); 
        
        $stmt = $this->conn->prepare("INSERT INTO " . $this->table . " (judul, jenis, detail_tambahan, prioritas) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $judul, $jenis, $detail_tambahan, $prioritas);
        
        return $stmt->execute();
    }

    // READ: Menarik data dari database dan mengubahnya menjadi array berisi objek
    public function getSemuaTugas(): array {
        // Query diurutkan berdasarkan prioritas 'penting' terlebih dahulu, baru ID terbaru
        $query = "SELECT * FROM " . $this->table . " ORDER BY FIELD(prioritas, 'penting', 'normal'), id DESC";
        $result = $this->conn->query($query);
        
        $daftarTugas = [];

        while($row = $result->fetch_assoc()) {
            // Polimorfisme: Mencetak objek yang berbeda berdasarkan isi kolom 'jenis'
            if($row['jenis'] == 'kuliah') {
                $daftarTugas[] = new TugasKuliah(
                    $row['judul'], 
                    $row['detail_tambahan'], 
                    $row['prioritas'], 
                    $row['status'], 
                    $row['id']
                );
            } else {
                $daftarTugas[] = new TugasPribadi(
                    $row['judul'], 
                    $row['detail_tambahan'], 
                    $row['prioritas'], 
                    $row['status'], 
                    $row['id']
                );
            }
        }
        return $daftarTugas;
    }

    // UPDATE: Mengubah status tugas menjadi selesai (1)
    public function selesaikanTugas(int $id): bool {
        $stmt = $this->conn->prepare("UPDATE " . $this->table . " SET status = 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }

    // DELETE: Menghapus tugas dari database
    public function hapusTugas(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM " . $this->table . " WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
}
?>