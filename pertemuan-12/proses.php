<?php
session_start();
require_once "koneksi.php";
require_once "fungsi.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  $_SESSION["error"] = "Akses tidak valid!";
  redirect_ke('index.php#contact');
  exit;
}

$nama  = bersihkan($_POST['txtNama'] ?? '');
$email = bersihkan($_POST['txtEmail'] ?? '');
$pesan = bersihkan($_POST['txtPesan'] ?? '');
$captcha = bersihkan($_POST['captcha'] ?? '');
$eror = [];

if ($captcha === "") {
  $eror[] = 'Captcha wajib diisi!';
} elseif ($captcha != 5) {
  $eror[] = "Jawaban captcha salah!";
}

if ($nama === "") {
  $eror[] = 'Nama wajib diisi!';
} elseif (strlen($nama) < 3) {
  $eror[] = "Nama minimal 3 karakter!";
}

if ($email === "") {
  $eror[] = 'Email wajib diisi!';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $eror[] = 'Format email tidak valid!';
}
if ($pesan === "") {
  $eror[] = 'Pesan wajib diisi!';
} elseif (strlen($pesan) < 10) {
  $eror[] = "Pesan minimal 10 karakter!";
}

if (!empty($eror)) {
  $_SESSION["flash_error"] = implode('<br>', $eror);
  redirect_ke('index.php#contact');
}


$sql = "INSERT INTO tbl_tamu (cnama, cemail, cpesan) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
  $_SESSION['flash_error'] = 'Terjadi kesalahan sistem (prepare gagal).';
  redirect_ke('index.php#contact');
}

mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $pesan);

if (mysqli_stmt_execute($stmt)) {
  unset($_SESSION["old"]);
  $_SESSION['flash_sukses'] = 'Terima kasih, data Anda sudah diterima.';
  redirect_ke("index.php#contact");
} else {
  $_SESSION['old'] = [
    'nama' => $nama,
    'email' => $email,
    'pesan' => $pesan
  ];
  $_SESSION['flash_error'] = 'Data gagal disimpan. Silakan coba lagi.';
  redirect_ke("index.php#contact");
}
mysqli_stmt_close($stmt);
 
$arrBiodata = [
  "nim" => $_POST["txtNim"] ?? "",
  "nama" => $_POST["txtNmLengkap"] ?? "",
  "tempat" => $_POST["txtT4Lhr"] ?? "",
  "tanggal" => $_POST["txtTglLhr"] ?? "",
  "hobi" => $_POST["txtHobi"] ?? "",
  "pasangan" => $_POST["txtPasangan"] ?? "",
  "pekerjaan" => $_POST["txtKerja"] ?? "",
  "ortu" => $_POST["txtNmOrtu"] ?? "",
  "kakak" => $_POST["txtNmKakak"] ?? "",
  "adik" => $_POST["txtNmAdik"] ?? ""
];
$_SESSION["biodata"] = $arrBiodata;

header("location: index.php#about");