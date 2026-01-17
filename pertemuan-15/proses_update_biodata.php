<?php
  session_start();
  require __DIR__ . '/koneksi.php';
  require_once __DIR__ . '/fungsi.php';

  #cek method form, hanya izinkan POST
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_error'] = 'Akses tidak valid.';
    redirect_ke('read_biodata.php');
  }

  #validasi cid wajib angka dan > 0
  $cid = filter_input(INPUT_POST, 'cid', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
  ]);

  if (!$cid) {
    $_SESSION['flash_error'] = 'CID Tidak Valid.';
    redirect_ke('read_biodata.php?cid='. (int)$cid);
  }

  #ambil dan bersihkan (sanitasi) nilai dari form
  $nim  = bersihkan($_POST['txtNim']  ?? '');
  $nama  = bersihkan($_POST['txtNmLengkap']  ?? '');
  $tempat = bersihkan($_POST['txtT4Lhr'] ?? '');
  $tanggal = bersihkan($_POST['txtTglLhr'] ?? '');
  $hobi = bersihkan($_POST['txtHobi'] ?? '');
  $pasangan = bersihkan($_POST['txtPasangan'] ?? '');
  $pekerjaan = bersihkan($_POST['txtKerja'] ?? '');
  $ortu = bersihkan($_POST['txtNmOrtu'] ?? ''); 
  $kakak = bersihkan($_POST['txtNmKakak'] ?? '');
  $adik = bersihkan($_POST['txtNmAdik'] ?? '');

  #Validasi sederhana
  $errors = []; #ini array untuk menampung semua error yang ada

  if ($nim === '') {
    $errors[] = 'NIM wajib diisi.';
  }
  if ($nama === '') {
    $errors[] = 'Nama wajib diisi.';
  }

  if ($tempat === '') {
    $errors[] = 'Tempat lahir wajib diisi.';
  }

  if ($tanggal === '') {
    $errors[] = 'Tanggal lahir wajib diisi.';
  }
  
  if ($hobi === '') {
    $errors[] = 'Hobi wajib diisi.';
  }
    if ($pasangan === '') {
        $errors[] = 'Nama pasangan wajib diisi.';
    }
    if ($pekerjaan === '') {
        $errors[] = 'Pekerjaan wajib diisi.';
    }
    if ($ortu === '') {
        $errors[] = 'Nama orang tua wajib diisi.';
    }
    if ($kakak === '') {
        $errors[] = 'Nama kakak wajib diisi.';
    }
    if ($adik === '') {
        $errors[] = 'Nama adik wajib diisi.';
    }

  if (mb_strlen($nama) < 3) {
    $errors[] = 'Nama minimal 3 karakter.';
  }

  /*
  kondisi di bawah ini hanya dikerjakan jika ada error, 
  simpan nilai lama dan pesan error, lalu redirect (konsep PRG)
  */
  if (!empty($errors)) {
    $_SESSION['old_biodata'] = [
      'txtNim' => $nim,
      'txtNmLengkap'  => $nama,
      'txtT4Lhr' => $tempat,
      'txtTglLhr' => $tanggal,
      'txtHobi' => $hobi,
      'txtPasangan' => $pasangan,
      'txtKerja' => $pekerjaan,
      'txtNmOrtu' => $ortu,
      'txtNmKakak' => $kakak,
      'txtNmAdik' => $adik
    ];

    $_SESSION['flash_error'] = implode('<br>', $errors);
    redirect_ke('edit_biodata.php?cid='. (int)$cid);
  }

  /*
    Prepared statement untuk anti SQL injection.
    menyiapkan query UPDATE dengan prepared statement 
    (WAJIB WHERE cid = ?)
  */
  $stmt = mysqli_prepare($conn, "UPDATE tbl_biodata_mahasiswa_sederhana 
                                SET cnim = ?, cnama_lengkap = ?, ctempat_lahir = ?, ctanggal_lahir = ?, chobi = ?, cpasangan = ?, cpekerjaan = ?, cnama_orang_tua = ?, cnama_kakak = ?, cnama_adik = ? 
                                WHERE cid = ?");
  if (!$stmt) {
    #jika gagal prepare, kirim pesan error (tanpa detail sensitif)
    $_SESSION['flash_error'] = 'Terjadi kesalahan sistem (prepare gagal).';
    redirect_ke('edit_biodata.php?cid='. (int)$cid);
  }

  #bind parameter dan eksekusi (s = string, i = integer)
  mysqli_stmt_bind_param($stmt, "ssssssssssi", $nim, $nama, $tempat, $tanggal, $hobi, $pasangan, $pekerjaan, $ortu, $kakak, $adik, $cid);

  if (mysqli_stmt_execute($stmt)) { #jika berhasil, kosongkan old value
    unset($_SESSION['old_biodata']);
    /*
      Redirect balik ke read.php dan tampilkan info sukses.
    */
    $_SESSION['flash_sukses'] = 'Terima kasih, data Anda sudah diperbaharui.';
    redirect_ke('read_biodata.php'); #pola PRG: kembali ke data dan exit()
  } else { #jika gagal, simpan kembali old value dan tampilkan error umum
    $_SESSION['old_biodata'] = [
      'txtNim' => $nim,
      'txtNmLengkap'  => $nama,
      'txtT4Lhr' => $tempat,
      'txtTglLhr' => $tanggal,
      'txtHobi' => $hobi,
      'txtPasangan' => $pasangan,
      'txtKerja' => $pekerjaan,
      'txtNmOrtu' => $ortu,
      'txtNmKakak' => $kakak,
      'txtNmAdik' => $adik
    ];
    $_SESSION['flash_error'] = 'Data gagal diperbaharui. Silakan coba lagi.';
    redirect_ke('edit_biodata.php?cid='. (int)$cid);
  }
  #tutup statement
  mysqli_stmt_close($stmt);