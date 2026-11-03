<?php
declare(strict_types=1);
require_once __DIR__ . '/Mahasiswa.php';

class DataMahasiswa {
    private array $data; // [nim => Mahasiswa], disimpan di $_SESSION

    public function __construct() {
        $_SESSION['mahasiswa'] ??= [];
        $this->data = &$_SESSION['mahasiswa'];
    }
    public function tambah(Mahasiswa $m): void {
        if (isset($this->data[$m->getNim()])) {
            throw new InvalidArgumentException("NIM {$m->getNim()} sudah terdaftar");
        }
        $this->data[$m->getNim()] = $m;
    }
    public function semua(): array { return $this->data; }
    public function cari(string $nim): ?Mahasiswa { return $this->data[$nim] ?? null; }

    /** NIM tidak diubah; objek baru dibangun (divalidasi) lalu ditimpa pada key yang sama. */
    public function ubah(string $nim, string $nama, string $fakultas, string $prodi,
                         string $asalSekolah, int $semester, string $hobi, string $agama): void {
        if (!isset($this->data[$nim])) throw new InvalidArgumentException("Data tidak ditemukan");
        $baru = new Mahasiswa($nim, $nama, $fakultas, $prodi, $asalSekolah, $semester, $hobi, $agama);
        $this->data[$nim] = $baru;
    }
    public function hapus(string $nim): void { unset($this->data[$nim]); }
}
