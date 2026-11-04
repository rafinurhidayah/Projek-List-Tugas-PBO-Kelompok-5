<?php
declare(strict_types=1);

/**
 * ABSTRAKSI   : kerangka dasar tugas, tidak bisa di-instansiasi langsung (new Tugas()).
 * ENKAPSULASI : data disimpan di properti protected, dibaca lewat getter.
 * Model hanya mengurus DATA dan ATURAN; HTML dibuat di halaman (view).
 */
abstract class Tugas
{
    public const PRIORITAS    = ['penting', 'normal'];
    public const MAKS_PANJANG = 255; // sesuai varchar(255) di database

    public function __construct(
        protected string $judul,
        protected string $detailTambahan = '',
        protected string $prioritas = 'normal',
        protected bool $selesai = false,
        protected ?int $id = null,
    ) {}

    // POLIMORFISME: tiap jenis tugas menjawab sendiri.
    abstract public function getJenis(): string;       // nilai kolom `jenis`
    abstract public function getJenisLabel(): string;  // teks untuk pengguna

    /** Factory tunggal: satu-satunya tempat yang memilih subclass. Dipakai juga saat membaca dari DB. */
    public static function buat(
        string $jenis, string $judul, string $detail = '', string $prioritas = 'normal',
        bool $selesai = false, ?int $id = null
    ): self {
        return match ($jenis) {
            'kuliah'  => new TugasKuliah($judul, $detail, $prioritas, $selesai, $id),
            'pribadi' => new TugasPribadi($judul, $detail, $prioritas, $selesai, $id),
            default   => throw new InvalidArgumentException('Jenis tugas tidak valid.'),
        };
    }

    /** Validasi data dari form, lalu bentuk objeknya. */
    public static function dariInput(array $in): self
    {
        $judul     = trim((string)($in['judul'] ?? ''));
        $detail    = trim((string)($in['detail_tambahan'] ?? ''));
        $prioritas = (string)($in['prioritas'] ?? 'normal');

        if ($judul === '') {
            throw new InvalidArgumentException('Judul tidak boleh kosong.');
        }
        if (mb_strlen($judul) > self::MAKS_PANJANG || mb_strlen($detail) > self::MAKS_PANJANG) {
            throw new InvalidArgumentException('Judul dan detail maksimal ' . self::MAKS_PANJANG . ' karakter.');
        }
        if (!in_array($prioritas, self::PRIORITAS, true)) {
            throw new InvalidArgumentException('Prioritas tidak valid.');
        }
        return self::buat((string)($in['jenis'] ?? ''), $judul, $detail, $prioritas, ($in['status'] ?? '0') === '1');
    }

    public function getId(): ?int          { return $this->id; }
    public function getJudul(): string     { return $this->judul; }
    public function getDetail(): string    { return $this->detailTambahan; }
    public function getPrioritas(): string { return $this->prioritas; }
    public function isPenting(): bool      { return $this->prioritas === 'penting'; }
    public function isSelesai(): bool      { return $this->selesai; }
    public function getKode(): string      { return $this->id === null ? 'TGS-baru' : sprintf('TGS-%03d', $this->id); }
}

// PEWARISAN: TugasKuliah dan TugasPribadi mewarisi semua sifat Tugas.
class TugasKuliah extends Tugas
{
    public function getJenis(): string      { return 'kuliah'; }
    public function getJenisLabel(): string { return 'Tugas Kuliah'; }
}

class TugasPribadi extends Tugas
{
    public function getJenis(): string      { return 'pribadi'; }
    public function getJenisLabel(): string { return 'Tugas Pribadi'; }
}
