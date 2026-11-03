<?php
declare(strict_types=1);
require_once __DIR__ . '/DataMahasiswa.php'; // class dimuat sebelum session_start
session_start();

function aman(mixed $s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function input(string $k): string { return trim((string)($_POST[$k] ?? '')); }

$repo = new DataMahasiswa();
$error = $_SESSION['flash_error'] ?? null;
$info  = $_SESSION['flash_info'] ?? null;
unset($_SESSION['flash_error'], $_SESSION['flash_info']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = input('aksi');
    try {
        if ($aksi === 'hapus') {
            $repo->hapus(input('nim'));
            $_SESSION['flash_info'] = "Data dihapus";
        } else {
            $sem = filter_var(input('semester'), FILTER_VALIDATE_INT);
            if ($sem === false) throw new InvalidArgumentException("Semester harus berupa angka bulat");
            $args = [input('nim'), input('nama'), input('fakultas'), input('prodi'),
                     input('asalSekolah'), $sem, input('hobi'), input('agama')];
            if ($aksi === 'tambah') {
                $repo->tambah(new Mahasiswa(...$args));
                $_SESSION['flash_info'] = "Data berhasil ditambahkan";
            } elseif ($aksi === 'ubah') {
                $repo->ubah(...$args);
                $_SESSION['flash_info'] = "Data berhasil diubah";
            }
        }
    } catch (InvalidArgumentException $e) {
        $_SESSION['flash_error'] = $e->getMessage();
        if ($aksi === 'ubah') { header('Location: index.php?edit=' . urlencode(input('nim'))); exit; }
    }
    header('Location: index.php'); exit;
}

$edit = isset($_GET['edit']) ? $repo->cari((string)$_GET['edit']) : null;
?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><title>CRUD Mahasiswa - Enkapsulasi</title>
<style>
body{font-family:sans-serif;max-width:960px;margin:20px auto;padding:0 12px}
table{border-collapse:collapse;width:100%}td,th{border:1px solid #ccc;padding:6px}
label{display:block;margin:6px 0 2px}input,select{width:100%;padding:6px;box-sizing:border-box}
.err{background:#fdd;padding:8px}.ok{background:#dfd;padding:8px}
</style></head><body>
<h2>CRUD Data Mahasiswa (Enkapsulasi)</h2>
<?php if ($error): ?><p class="err"><?= aman($error) ?></p><?php endif; ?>
<?php if ($info): ?><p class="ok"><?= aman($info) ?></p><?php endif; ?>

<h3><?= $edit ? 'Edit Mahasiswa' : 'Tambah Mahasiswa' ?></h3>
<form method="post">
  <input type="hidden" name="aksi" value="<?= $edit ? 'ubah' : 'tambah' ?>">
  <label>NIM</label>
  <?php if ($edit): ?>
    <input name="nim" value="<?= aman($edit->getNim()) ?>" readonly>
  <?php else: ?>
    <input name="nim" required>
  <?php endif; ?>
  <label>Nama</label><input name="nama" value="<?= aman($edit?->getNama() ?? '') ?>">
  <label>Fakultas</label><input name="fakultas" value="<?= aman($edit?->getFakultas() ?? '') ?>">
  <label>Prodi</label><input name="prodi" value="<?= aman($edit?->getProdi() ?? '') ?>">
  <label>Asal Sekolah</label><input name="asalSekolah" value="<?= aman($edit?->getAsalSekolah() ?? '') ?>">
  <label>Semester (1–14)</label><input name="semester" type="number" value="<?= aman($edit?->getSemester() ?? '') ?>">
  <label>Hobi</label><input name="hobi" value="<?= aman($edit?->getHobi() ?? '') ?>">
  <label>Agama</label>
  <select name="agama">
    <?php foreach (Mahasiswa::AGAMA as $a): ?>
      <option <?= ($edit?->getAgama() === $a) ? 'selected' : '' ?>><?= aman($a) ?></option>
    <?php endforeach; ?>
  </select>
  <p><button type="submit"><?= $edit ? 'Simpan' : 'Tambah' ?></button>
  <?php if ($edit): ?><a href="index.php">Batal</a><?php endif; ?></p>
</form>

<h3>Daftar Mahasiswa</h3>
<table>
<tr><th>NIM</th><th>Nama</th><th>Fakultas</th><th>Prodi</th><th>Asal Sekolah</th><th>Sem</th><th>Hobi</th><th>Agama</th><th>Aksi</th></tr>
<?php foreach ($repo->semua() as $m): ?>
<tr>
  <td><?= aman($m->getNim()) ?></td><td><?= aman($m->getNama()) ?></td>
  <td><?= aman($m->getFakultas()) ?></td><td><?= aman($m->getProdi()) ?></td>
  <td><?= aman($m->getAsalSekolah()) ?></td><td><?= aman($m->getSemester()) ?></td>
  <td><?= aman($m->getHobi()) ?></td><td><?= aman($m->getAgama()) ?></td>
  <td><a href="?edit=<?= urlencode($m->getNim()) ?>">Edit</a>
    <form method="post" style="display:inline" onsubmit="return confirm('Hapus?')">
      <input type="hidden" name="aksi" value="hapus"><input type="hidden" name="nim" value="<?= aman($m->getNim()) ?>">
      <button>Hapus</button></form></td>
</tr>
<?php endforeach; ?>
</table>
</body></html>
