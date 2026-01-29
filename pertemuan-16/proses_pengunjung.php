<?php
session_start();
require __DIR__ . '/koneksi.php';
require_once __DIR__ . '/fungsi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_error'] = 'Akses tidak valid.';
    redirect_ke('index.php#biodata');
}

$kode       = bersihkan($_POST['txtKdpengunjung'] ?? '');
$nama       = bersihkan($_POST['txtNmpengunjung'] ?? '');
$alamat     = bersihkan($_POST['txtalamatrumah'] ?? '');
$tanggal    = bersihkan($_POST['txtTglkunjungan'] ?? '');
$hobi       = bersihkan($_POST['txthobi'] ?? '');
$asal       = bersihkan($_POST['txtAsalslta'] ?? '');
$pekerjaan  = bersihkan($_POST['txtpekerjaan'] ?? '');
$ortu       = bersihkan($_POST['txtNmortu'] ?? '');
$pacar      = bersihkan($_POST['txtNmpacar'] ?? '');
$mantan     = bersihkan($_POST['txtNmmantan'] ?? '');


$errors = [];

if ($kode === '')        { $errors[] = 'kode pengunjung wajib diisi.'; }
if ($nama === '')       { $errors[] = 'Nama pengunjung wajib diisi.'; }
if ($alamat === '')     { $errors[] = 'Alamat wajib diisi.'; }
if ($tanggal === '')    { $errors[] = 'Tanggal kunjungan wajib diisi.'; }
if ($hobi === '')       { $errors[] = 'Hobi wajib diisi.'; }
if ($asal === '')       { $errors[] = 'Asal SLTA wajib diisi.'; }
if ($pekerjaan === '')  { $errors[] = 'pekerjaan wajib diisi.'; }
if ($ortu === '')       { $errors[] = 'Nama orang tua wajib diisi.'; }
if ($pacar === '')       { $errors[] = 'Nama pacar wajib diisi.'; }
if ($mantan === '')       { $errors[] = 'Nama mantan wajib diisi.'; }

if (mb_strlen($nim) < 5)  { $errors[] = 'NIM minimal 5 karakter.'; }
if (mb_strlen($nama) < 3) { $errors[] = 'Nama minimal 3 karakter.'; }

if (!empty($errors)) {
    $_SESSION['old_biodata'] = [
        'kode'        => $kode,
        'nama'       => $nama,
        'tempat'     => $alamat,
        'tanggal'    => $tanggal,
        'hobi'       => $hobi,
        'asal'       => $asal,
        'pekerjaan'  => $pekerjaan,
        'ortu'       => $ortu,
        'pacar'      => $pacar,
        'mantan'     => $mantan,
    ];

    $_SESSION['flash_error'] = implode('<br>', $errors);
    redirect_ke('index.php#biodata');
}


$sql = "INSERT INTO tbl_biodata_mahasiswa_sederhana
        (cnim, cnama_lengkap, ctempat_lahir, ctanggal_lahir, chobi,
 cpasangan, cpekerjaan, cnama_orang_tua, cnama_kakak, cnama_adik)
        VALUES (?,?,?,?,?,?,?,?,?,?)";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    $_SESSION['flash_error'] = 'Kesalahan sistem (prepare gagal).';
    redirect_ke('index.php#biodata');
}

mysqli_stmt_bind_param(
    $stmt,
    "ssssssssss",
    $kode,
    $nama,
    $alamat,
    $tanggal,
    $hobi,
    $asal,
    $pekerjaan,
    $ortu,
    $pacar,
    $mantan
);


if (mysqli_stmt_execute($stmt)) {
    unset($_SESSION['old_biodata']);
    $_SESSION['flash_sukses'] = 'Biodata berhasil disimpan.';
} else {
    $_SESSION['flash_error'] = 'Data gagal disimpan.' . mysqli_stmt_error($stmt);
}

mysqli_stmt_close($stmt);
redirect_ke('index.php#biodata');
