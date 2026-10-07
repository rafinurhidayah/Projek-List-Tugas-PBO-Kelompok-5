<?php
// =================================================================
// 1. ABSTRAKSI: Class induk sebagai kerangka dasar yang tidak bisa 
// diinstansiasi langsung menggunakan 'new Tugas()'
// =================================================================
abstract class Tugas {
    
    // 2. ENKAPSULASI: Menyembunyikan data menggunakan modifier 'protected' 
    // agar hanya bisa diakses oleh class ini dan class anaknya.
    protected ?int $id;
    protected string $judul;
    protected string $prioritas;
    protected int $status;

    public function __construct(string $judul, string $prioritas = 'normal', int $status = 0, ?int $id = null) {
        $this->judul = $judul;
        $this->prioritas = $prioritas;
        $this->status = $status;
        $this->id = $id;
    }

    // Abstract method: Memaksa semua class anak untuk membuat fungsi ini
    abstract public function getDetailTugas(): string;
    
    // Getter untuk mengambil data yang di-enkapsulasi
    public function getJudul(): string { return $this->judul; }
    public function getPrioritas(): string { return $this->prioritas; }
    public function getStatus(): int { return $this->status; }
    public function getId(): ?int { return $this->id; }

    // Helper method untuk mempercantik tampilan UI (Badge Prioritas)
    public function getBadgePrioritas(): string {
        if ($this->prioritas === 'penting') {
            return "<span class='badge bg-danger'>Penting 🚨</span>";
        }
        return "<span class='badge bg-secondary'>Normal</span>";
    }
}

// =================================================================
// 3. INHERITANSI (Pewarisan): TugasKuliah mewarisi sifat dari Tugas
// =================================================================
class TugasKuliah extends Tugas {
    private string $mataKuliah; // Properti khusus untuk tugas kuliah

    public function __construct(string $judul, string $mataKuliah, string $prioritas = 'normal', int $status = 0, ?int $id = null) {
        // Memanggil constructor dari class induk (Tugas)
        parent::__construct($judul, $prioritas, $status, $id); 
        $this->mataKuliah = $mataKuliah;
    }

    // 4. POLIMORFISME: Implementasi fungsi getDetailTugas dengan cara khusus
    public function getDetailTugas(): string {
        return "<span class='badge bg-primary'>Tugas Kuliah</span> " . $this->mataKuliah;
    }
}

// =================================================================
// 3. INHERITANSI: TugasPribadi mewarisi sifat dari Tugas
// =================================================================
class TugasPribadi extends Tugas {
    private string $kategori; // Properti khusus untuk tugas pribadi

    public function __construct(string $judul, string $kategori, string $prioritas = 'normal', int $status = 0, ?int $id = null) {
        parent::__construct($judul, $prioritas, $status, $id);
        $this->kategori = $kategori;
    }

    // 4. POLIMORFISME: Implementasi yang berbeda dari TugasKuliah
    public function getDetailTugas(): string {
        return "<span class='badge bg-success'>Tugas Pribadi</span> " . $this->kategori;
    }
}
?>