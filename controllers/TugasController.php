<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/Tugas.php';

class TugasController
{
    public function __construct(private mysqli $db) {}

    /** READ: belum selesai dulu, lalu yang penting, lalu terbaru. */
    public function semua(): array
    {
        $hasil = $this->db->query(
            "SELECT id, judul, jenis, detail_tambahan, prioritas, status FROM tugas
             ORDER BY status ASC, FIELD(prioritas, 'penting', 'normal'), id DESC"
        );
        return array_map(
            fn(array $r) => Tugas::buat(
                $r['jenis'], $r['judul'], $r['detail_tambahan'], (string)$r['prioritas'],
                (bool)$r['status'], (int)$r['id']
            ),
            $hasil->fetch_all(MYSQLI_ASSOC)
        );
    }

    /** CREATE: mengembalikan id baru. */
    public function tambah(Tugas $t): int
    {
        $judul = $t->getJudul(); $jenis = $t->getJenis(); $detail = $t->getDetail();
        $prio  = $t->getPrioritas(); $status = $t->isSelesai() ? 1 : 0;
        $stmt = $this->db->prepare(
            'INSERT INTO tugas (judul, jenis, detail_tambahan, prioritas, status) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('ssssi', $judul, $jenis, $detail, $prio, $status);
        $stmt->execute();
        return $this->db->insert_id;
    }

    /** UPDATE: tandai selesai atau kembalikan ke belum. */
    public function ubahStatus(int $id, bool $selesai): bool
    {
        $status = $selesai ? 1 : 0;
        $stmt = $this->db->prepare('UPDATE tugas SET status = ? WHERE id = ?');
        $stmt->bind_param('ii', $status, $id);
        return $stmt->execute();
    }

    /** DELETE */
    public function hapus(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM tugas WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
}
