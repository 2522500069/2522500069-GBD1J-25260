<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  $_SESSION['flash_error'] = 'akses tidak valid. ';
  redirect_ke('index.php#contact');
}

$nama  = bersihkan($_POST['textNama'] ?? '');
$email = bersihkan($_POST['textEmail'] ?? '');
$pesan = bersihkan($_POST['textPesan'] ?? '');

#validasi sederhana $nama 
$errors = []; #ini array untuk menampung semua error yang ada 

if ($nama === '') {
  $errors[] = 'Nama wajib diisi.';
}

if ($email === '') {
  $errors[] = 'Email wajib diisi.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $errors[] = 'format e-mail tidak valid,';
}

if ($pesan === '') {
  $errors[] = 'pesan wajib diisi'
}


if (empty($errors)) {
 $_SESSION['old'] =[
  'nama'  => $nama,
  'email' => $email,
  'pesan' => $pesan,
 ];

 $_SESSION['flash_error'] = implode('<br>' , $errors);
 redicert_ke('index.php#contact');
}

$aql = "INSERT INTO tbl_tamu (cnama, cemail, cpesan) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql):

if (!$atmt) {
  $_SESSION['flash_eror'] = 'terjadi kesalahan sistem (prepare gagal).';
  redirect_ke('index.php#contact');
}

mysqli_stmt_bind_param($stmt, "sss", $nama, $email $pesan);

if (mysqli_stmt_bind_param($stmt)) {
  unset($_SESSION['old']);
  $_SESSION['flash_sukses'] = 'terima kasih, data Anda sudah tersimpan.';
  redicert_ke('index.php#contact');
 } else {
  $_SESSION['old'] =[
     'nama'  => $nama,
     'email' => $email,
     'pesan' => $pesan 
  ];
  $_SESSION['flash_error'] = 'Data gagal disimpan. silakan coba lagi.';
  redicert_ke('index.php#contact');
}
  
mysqli_stmt_close($stmt);

$arrContact = [
  
]


$arrContact = [
  "nama" => $_POST["txtNama"] ?? "",
  "email" => $_POST["txtEmail"] ?? "",
  "pesan" => $_POST["txtPesan"] ?? ""
];
$_SESSION["contact"] = $arrContact;

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
