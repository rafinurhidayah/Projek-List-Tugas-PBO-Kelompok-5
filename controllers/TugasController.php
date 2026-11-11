<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/Tugas.php';

/** Semua query dibatasi user_id: pengguna hanya bisa melihat dan mengubah tugasnya sendiri. */
class TugasController
{
    public function __construct(private mysqli $db, private int $userId) {}

    /** READ: belum selesai dulu, lalu yang penting, lalu terbaru. */
    public function semua(): array
    {
        $uid  = $this->userId;
        $stmt = $this->db->prepare(
            "SELECT id, judul, jenis, detail_tambahan, prioritas, status FROM tugas
             WHERE user_id = ?
             ORDER BY status ASC, FIELD(prioritas, 'penting', 'normal'), id DESC"
        );
        $stmt->bind_param('i', $uid);
        $stmt->execute();
        return array_map(
            fn(array $r) => Tugas::buat(
                $r['jenis'], $r['judul'], $r['detail_tambahan'], (string)$r['prioritas'],
                (bool)$r['status'], (int)$r['id']
            ),
            $stmt->get_result()->fetch_all(MYSQLI_ASSOC)
        );
    }

    /** CREATE: mengembalikan id baru. */
    public function tambah(Tugas $t): int
    {
        $uid   = $this->userId;
        $judul = $t->getJudul(); $jenis = $t->getJenis(); $detail = $t->getDetail();
        $prio  = $t->getPrioritas(); $status = $t->isSelesai() ? 1 : 0;
        $stmt = $this->db->prepare(
            'INSERT INTO tugas (user_id, judul, jenis, detail_tambahan, prioritas, status) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('issssi', $uid, $judul, $jenis, $detail, $prio, $status);
        $stmt->execute();
        return $this->db->insert_id;
    }

    /** UPDATE: tandai selesai / belum. false bila tugas tidak ada atau bukan milik pengguna. */
    public function ubahStatus(int $id, bool $selesai): bool
    {
        $uid = $this->userId;
        $status = $selesai ? 1 : 0;
        $stmt = $this->db->prepare('UPDATE tugas SET status = ? WHERE id = ? AND user_id = ?');
        $stmt->bind_param('iii', $status, $id, $uid);
        $stmt->execute();
        // affected_rows = 0 juga terjadi bila nilainya memang sudah sama, jadi cek keberadaan baris.
        return $stmt->affected_rows > 0 || $this->milikSaya($id);
    }

    /** DELETE */
    public function hapus(int $id): bool
    {
        $uid  = $this->userId;
        $stmt = $this->db->prepare('DELETE FROM tugas WHERE id = ? AND user_id = ?');
        $stmt->bind_param('ii', $id, $uid);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    private function milikSaya(int $id): bool
    {
        $uid  = $this->userId;
        $stmt = $this->db->prepare('SELECT 1 FROM tugas WHERE id = ? AND user_id = ?');
        $stmt->bind_param('ii', $id, $uid);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
}
