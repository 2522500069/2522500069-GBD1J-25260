<?php
session_start();

require __DIR__ . '/koneksi.php';
require_once __DIR__ . '/fungsi.php';


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
  $_SESSION['sinar_error'] = 'Akses tidak valid.';
  redirect_ke('read.php');
}


$cid = filter_input(INPUT_POST, 'cid', FILTER_VALIDATE_INT, [
  'options' => ['min_range' => 1]
]);

if (!$cid) {
  $_SESSION['sinar_error'] = 'CID tidak valid.';
  redirect_ke('edit.php?cid=' . (int)$cid);
}


$nama    = bersihkan($_POST['txtNama'] ?? '');
$email   = bersihkan($_POST['txtEmail'] ?? '');
$pesan   = bersihkan($_POST['txtPesan'] ?? '');
$captcha = bersihkan($_POST['captcha'] ?? '');


$errors = [];

if ($nama === '') {
  $errors[] = 'Nama wajib diisi.';
}

if ($email === '') {
  $errors[] = 'Email wajib diisi.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $errors[] = 'Format e-mail tidak valid.';
}

if ($pesan === '') {
  $errors[] = 'Pesan wajib diisi.';
}

if ($captcha === '') {
  $errors[] = 'Pertanyaan wajib diisi.';
} 
if (mb_strlen($nama) < 3) {
  $errors[] = 'Nama minimal 3 karakter.';
}

if (mb_strlen($pesan) < 10) {
  $errors[] = 'Pesan minimal 10 karakter.';
}

if ($captcha != "6") {
  $errors[] = 'Jawaban captcha salah.';
}



if (!empty($errors)) {
  $_SESSION['old'] = [
    'nama'  => $nama,
    'email' => $email,
    'pesan' => $pesan
  ];

  $_SESSION['sinar_error'] = implode('<br>', $errors);
  redirect_ke('edit.php?cid=' . (int)$cid);
}


$stmt = mysqli_prepare(
  $conn,
  "UPDATE tbl_tamu
   SET cnama = ?, cemail = ?, cpesan = ?
   WHERE cid = ?"
);

if (!$stmt) {

  $_SESSION['sinar_error'] = 'Terjadi kesalahan sistem (prepare gagal).';
  redirect_ke('edit.php?cid=' . (int)$cid);
}


mysqli_stmt_bind_param($stmt, "sssi", $nama, $email, $pesan, $cid);

if (mysqli_stmt_execute($stmt)) {

  unset($_SESSION['old']);


  $_SESSION['sinar_sukses'] = 'Terima kasih, data Anda sudah diperbaharui.';
  redirect_ke('read.php');
} else {

  $_SESSION['old'] = [
    'nama'  => $nama,
    'email' => $email,
    'pesan' => $pesan
  ];

  $_SESSION['sinar_error'] = 'Data gagal diperbaharui. Silakan coba lagi.';
  redirect_ke('edit.php?cid=' . (int)$cid);
}


mysqli_stmt_close($stmt);

redirect_ke('edit.php?cid=' . (int)$cid);