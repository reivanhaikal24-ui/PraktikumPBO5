<?php
declare(strict_types=1);

class Mahasiswa {
    public const AGAMA = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];

    private string $nim;
    private string $nama;
    private string $fakultas;
    private string $prodi;
    private string $asalSekolah;
    private int $semester;
    private string $hobi;
    private string $agama;

    public function __construct(
        string $nim, string $nama, string $fakultas, string $prodi,
        string $asalSekolah, int $semester, string $hobi, string $agama
    ) {
        $nim = trim($nim);
        if ($nim === "") throw new InvalidArgumentException("NIM wajib diisi");
        $this->nim = $nim; // identitas tetap: tidak ada setNim()
        $this->setNama($nama);
        $this->setFakultas($fakultas);
        $this->setProdi($prodi);
        $this->setAsalSekolah($asalSekolah);
        $this->setSemester($semester);
        $this->setHobi($hobi);
        $this->setAgama($agama);
    }

    public function getNim(): string { return $this->nim; }
    public function getNama(): string { return $this->nama; }
    public function getFakultas(): string { return $this->fakultas; }
    public function getProdi(): string { return $this->prodi; }
    public function getAsalSekolah(): string { return $this->asalSekolah; }
    public function getSemester(): int { return $this->semester; }
    public function getHobi(): string { return $this->hobi; }
    public function getAgama(): string { return $this->agama; }

    private static function wajib(string $nilai, string $label): string {
        $nilai = trim($nilai);
        if ($nilai === "") throw new InvalidArgumentException("$label wajib diisi");
        return $nilai;
    }
    public function setNama(string $v): void { $this->nama = self::wajib($v, "Nama"); }
    public function setFakultas(string $v): void { $this->fakultas = self::wajib($v, "Fakultas"); }
    public function setProdi(string $v): void { $this->prodi = self::wajib($v, "Prodi"); }
    public function setAsalSekolah(string $v): void { $this->asalSekolah = self::wajib($v, "Asal sekolah"); }
    public function setHobi(string $v): void { $this->hobi = trim($v); } // hobi boleh kosong
    public function setSemester(int $v): void {
        if ($v < 1 || $v > 14) throw new InvalidArgumentException("Semester harus 1–14");
        $this->semester = $v;
    }
    public function setAgama(string $v): void {
        $v = trim($v);
        if (!in_array($v, self::AGAMA, true)) throw new InvalidArgumentException("Agama tidak termasuk daftar yang diizinkan");
        $this->agama = $v;
    }
}
