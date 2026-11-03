<?php
// Skrip pengujian (jalankan: php uji.php) - memetakan 10 kasus uji wajib
declare(strict_types=1);
require_once __DIR__ . '/DataMahasiswa.php';
session_start();
$_SESSION = [];
$repo = new DataMahasiswa();
function t(string $no, string $nama, callable $f): void {
    try { $r = $f(); echo "$no | $nama | $r\n"; }
    catch (Throwable $e) { echo "$no | $nama | DITOLAK: " . get_class($e) . " - " . $e->getMessage() . "\n"; }
}
$mk = fn($nim="23001",$nama="Siti",$sem=4) => new Mahasiswa($nim,$nama,"Sains dan Teknologi","Informatika","SMAN 1",$sem,"Membaca","Islam");
t("01","Valid sem 4", function() use($repo,$mk){ $repo->tambah($mk()); return "tersimpan (".count($repo->semua()).")"; });
t("02","NIM kosong", fn()=>$mk("   "));
t("03","Semester -1", fn()=>$mk("1","Budi",-1));
t("04","Semester 15", fn()=>$mk("2","Budi",15));
t("05","Nama spasi", fn()=>$mk("3","   "));
t("06","Akses \$m->semester", fn()=>$mk()->semester);
t("07","Edit nama valid", function() use($repo){ $repo->ubah("23001","Siti Aisyah","Sains dan Teknologi","Informatika","SMAN 1",4,"Membaca","Islam"); return "nama kini: ".$repo->cari("23001")->getNama(); });
t("08","NIM duplikat", fn()=>$repo->tambah($mk()));
t("09","Hapus", function() use($repo,$mk){ $repo->tambah($mk("23002","Ani")); $repo->hapus("23001"); return "sisa: ".implode(",",array_keys($repo->semua())); });
t("--","Agama tidak valid", fn()=>new Mahasiswa("9","X","F","P","S",1,"","Alien"));
