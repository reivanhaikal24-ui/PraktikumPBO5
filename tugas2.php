<?php
declare(strict_types=1);

class Buku {
    private string $kodeBuku;
    private string $judul;
    private int $stok;

    public function __construct(string $kodeBuku, string $judul, int $stok) {
        $kodeBuku = trim($kodeBuku);
        if ($kodeBuku === "") throw new InvalidArgumentException("Kode buku wajib diisi");
        $this->kodeBuku = $kodeBuku;      // read-only: tidak ada setKodeBuku()
        $this->setJudul($judul);
        if ($stok < 0) throw new InvalidArgumentException("Stok awal tidak boleh negatif");
        $this->stok = $stok;
    }
    public function getKodeBuku(): string { return $this->kodeBuku; }
    public function getJudul(): string { return $this->judul; }
    public function getStok(): int { return $this->stok; }

    public function setJudul(string $judul): void {
        $judul = trim($judul);
        if ($judul === "") throw new InvalidArgumentException("Judul wajib diisi");
        $this->judul = $judul;
    }
    public function tambahStok(int $jumlah): void {
        if ($jumlah <= 0) throw new InvalidArgumentException("Jumlah tambah stok harus positif");
        $this->stok += $jumlah;
    }
    public function kurangiStok(int $jumlah): void {
        if ($jumlah <= 0) throw new InvalidArgumentException("Jumlah pengurangan harus positif");
        if ($jumlah > $this->stok) throw new InvalidArgumentException("Stok tidak mencukupi (sisa {$this->stok})");
        $this->stok -= $jumlah;
    }
}

function uji(string $label, callable $aksi): void {
    try { $aksi(); echo "[VALID]   $label -> berhasil<br>"; }
    catch (Throwable $e) { echo "[TIDAK VALID] $label -> ditolak: " . $e->getMessage() . "<br>"; }
}

$b = new Buku("BK001", "Pemrograman PHP", 10);

echo "<h3>Input valid</h3>";
uji("tambahStok(5)", fn() => $b->tambahStok(5));
uji("setJudul('PBO dengan PHP 8')", fn() => $b->setJudul("PBO dengan PHP 8"));
echo "Stok sekarang: {$b->getStok()}, judul: {$b->getJudul()}<br>";

echo "<h3>Input tidak valid</h3>";
uji("setJudul('   ')", fn() => $b->setJudul("   "));
uji("kurangiStok(100)", fn() => $b->kurangiStok(100));
uji("tambahStok(-3)", fn() => $b->tambahStok(-3));
uji("new Buku('B2','X',-1)", fn() => new Buku("B2", "X", -1));
uji("akses \$b->stok langsung", fn() => $b->stok = -99);
echo "Stok akhir: {$b->getStok()}<br>";
