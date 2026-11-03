<?php
// Tugas 1 - Analisis access modifier
class Produk {
    public string $nama;
    protected string $kategori;
    private int $harga;

    public function __construct(string $nama, string $kategori, int $harga) {
        $this->nama = $nama;
        $this->kategori = $kategori;
        $this->harga = $harga;
    }
    public function getHarga(): int { return $this->harga; }
}

class ProdukDigital extends Produk {
    public function info(): string {
        // protected: BOLEH dari class turunan
        $r = "Kategori (protected, dari subclass): {$this->kategori}<br>";
        // private milik parent: TIDAK dapat diakses langsung dari subclass
        // echo $this->harga;  // -> Warning: Undefined property (private milik Produk)
        $r .= "Harga lewat getter: " . $this->getHarga() . "<br>";
        return $r;
    }
}

$p = new ProdukDigital("E-Book PBO", "Buku Digital", 50000);

echo "<h3>Uji akses dari luar class</h3>";
echo "1. public nama: " . $p->nama . " -> BERHASIL<br>";

try { echo $p->kategori; }
catch (Error $e) { echo "2. protected kategori -> DITOLAK: " . $e->getMessage() . "<br>"; }

try { echo $p->harga; }
catch (Error $e) { echo "3. private harga -> DITOLAK: " . $e->getMessage() . "<br>"; }

echo "<h3>Uji dari class turunan (ProdukDigital)</h3>";
echo $p->info();
