<?php
// Memanggil file koneksi database dan logika controller
require_once '../config/Database.php';
require_once '../controllers/TugasController.php';

// Inisialisasi Database dan Controller
$database = new Database();
$db = $database->getConnection();
$controller = new TugasController($db);

// ==========================================
// 1. PROSES TAMBAH TUGAS (CREATE)
// ==========================================
if (isset($_POST['tambah'])) {
    // Menangkap data dari form input HTML
    $judul = $_POST['judul'];
    $jenis = $_POST['jenis'];
    $prioritas = $_POST['prioritas'];
    $detail_tambahan = $_POST['detail_tambahan'];

    // Polimorfisme: Membentuk objek yang berbeda berdasarkan pilihan 'jenis' di form
    if ($jenis == 'kuliah') {
        $tugasBaru = new TugasKuliah($judul, $detail_tambahan, $prioritas);
    } else {
        $tugasBaru = new TugasPribadi($judul, $detail_tambahan, $prioritas);
    }

    // Mengirim objek tersebut ke Controller untuk disimpan ke database
    $controller->tambahTugas($tugasBaru, $jenis, $detail_tambahan);
    
    // Redirect kembali ke halaman utama
    header("Location: ../index.php");
    exit();
}

// ==========================================
// 2. PROSES SELESAIKAN TUGAS (UPDATE)
// ==========================================
if (isset($_GET['selesai'])) {
    $id_tugas = (int)$_GET['selesai'];
    
    $controller->selesaikanTugas($id_tugas);
    header("Location: ../index.php");
    exit();
}

// ==========================================
// 3. PROSES HAPUS TUGAS (DELETE)
// ==========================================
if (isset($_GET['hapus'])) {
    $id_tugas = (int)$_GET['hapus'];
    
    $controller->hapusTugas($id_tugas);
    header("Location: ../index.php");
    exit();
}
?>