<?php
  session_start();
  require __DIR__ . '/koneksi.php';
  require_once __DIR__ . '/fungsi.php';

  #cek method form, hanya izinkan POST
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_error'] = 'Akses tidak valid.';
    redirect_ke('read.php');
  }

  #validasi cid wajib angka dan > 0
  $cid = filter_input(INPUT_POST, 'cid', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
  ]);

  if (!$cid) {
    $_SESSION['flash_error'] = 'CID Tidak Valid.';
    redirect_ke('edit.php?cid='. (int)$cid);
  }

  #ambil dan bersihkan (sanitasi) nilai dari form
  $nim = bersihkan($_POST['txtNim']  ?? '');
  $nama = bersihkan($_POST['txtNmlengkap'] ?? '');
  $tempat = bersihkan($_POST['txtT4lhr'] ?? '');
  $tanggal = bersihkan($_POST['txtTglhr'] ?? '');
  $hobi = bersihkan($_POST['txtHobi'] ?? '');
  $pasangan = bersihkan($_POST['txtpasangan'] ?? '');
  $pekerjaan= bersihkan($_POST['txtKerja'] ?? '');
  $ortu = bersihkan($_POST['txtNmOrtu'] ?? '');
  $kakak = bersihkan($_POST['txtNmkakak'] ?? '');
  $adik = bersihkan($_POST['txtNmadik'] ?? '');

  #Validasi sederhana
  $errors = []; #ini array untuk menampung semua error yang ada

  if ($nim === '') {
    $errors[] = 'Nim wajib diisi.';
  }
  if ($nama === '') {
    $errors[] = 'Nama wajib diisi.';
  } 
  if ($tempat=== '') {
    $errors[] = 'Tempat wajib diisi.';
  }
  if ($tanggal === '') {
    $errors[] = 'Tanggal wajib diisi.';
  }
  if ($hobi === '') {
    $errors[] = 'Hobi wajib diisi.';
  }
  if ($pasangan === '') {
    $errors[] = 'Pasangan wajib diisi.';
  }
  if ($pekerjaan === '') {
    $errors[] = 'Pekerjaan wajib diisi.';
  }
  if ($ortu === '') {
    $errors[] = 'Ortu wajib diisi.';
  }
  if ($kakak === '') {
    $errors[] = 'kakak wajib diisi.';
  }
  if ($adik === '') {
    $errors[] = 'adik wajib diisi.';
  }

  if (mb_strlen($nama) < 3) {
    $errors[] = 'Nama minimal 3 karakter.';
  }

  if (mb_strlen($pesan) < 10) {
    $errors[] = 'Pesan minimal 10 karakter.';
  }

  if ($captcha!=="6") {
    $errors[] = 'Jawaban '. $captcha.' captcha salah.';
  }

  /*
  kondisi di bawah ini hanya dikerjakan jika ada error, 
  simpan nilai lama dan pesan error, lalu redirect (konsep PRG)
  */

  if (!empty($errors)) {
    $_SESSION['old_biodata'] = [
    "txtNim" => $nim,
    "txtNamalengkap" => $nama,
    "txtT4lahr"=> $tempat,
    "txtTanggal"=>$hobi,
    "txtpasangan"=>$pasangan,
    "txtKerja"=>$prkrjaan,
    "txtOrtu"=>$ortu,
    "txtKakak"=>$kakak,
    "txtadik"=>$adik,

    ];

    $_SESSION['flash_error'] = implode('<br>', $errors);
    redirect_ke('edit.php?cid='. (int)$cid);
  }

  /*
    Prepared statement untuk anti SQL injection.
    menyiapkan query UPDATE dengan prepared statement 
    (WAJIB WHERE cid = ?)
  */
  $stmt = mysqli_prepare($conn, "UPDATE tbl_tamu 
                                SET cnama = ?, cemail = ?, cpesan = ? 
                                WHERE cid = ?");
  if (!$stmt) {
    #jika gagal prepare, kirim pesan error (tanpa detail sensitif)
    $_SESSION['flash_error'] = 'Terjadi kesalahan sistem (prepare gagal).';
    redirect_ke('edit.php?cid='. (int)$cid);
  }

  #bind parameter dan eksekusi (s = string, i = integer)
  mysqli_stmt_bind_param($stmt, "sssi", $nama, $email, $pesan, $cid);

  if (mysqli_stmt_execute($stmt)) { #jika berhasil, kosongkan old value
    unset($_SESSION['old']);
    /*
      Redirect balik ke read.php dan tampilkan info sukses.
    */
    $_SESSION['flash_sukses'] = 'Terima kasih, data Anda sudah diperbaharui.';
    redirect_ke('read.php'); #pola PRG: kembali ke data dan exit()
  } else { #jika gagal, simpan kembali old value dan tampilkan error umum
    $_SESSION['old'] = [
      'nama'  => $nama,
      'email' => $email,
      'pesan' => $pesan,
    ];
    $_SESSION['flash_error'] = 'Data gagal diperbaharui. Silakan coba lagi.';
    redirect_ke('edit.php?cid='. (int)$cid);
  }
  #tutup statement
  mysqli_stmt_close($stmt);

  redirect_ke('edit.php?cid='. (int)$cid);