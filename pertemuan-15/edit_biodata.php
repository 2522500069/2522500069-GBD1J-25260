<?php
  session_start();
  require 'koneksi.php';
  require 'fungsi.php';

  /*
    Ambil nilai cid dari GET dan lakukan validasi untuk 
    mengecek cid harus angka dan lebih besar dari 0 (> 0).
    'options' => ['min_range' => 1] artinya cid harus ≥ 1 
    (bukan 0, bahkan bukan negatif, bukan huruf, bukan HTML).
  */
  $cid = filter_input(INPUT_GET, 'cid', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
  ]);
  /*
    Skrip di atas cara penulisan lamanya adalah:
    $cid = $_GET['cid'] ?? '';
    $cid = (int)$cid;

    Cara lama seperti di atas akan mengambil data mentah 
    kemudian validasi dilakukan secara terpisah, sehingga 
    rawan lupa validasi. Untuk input dari GET atau POST, 
    filter_input() lebih disarankan daripada $_GET atau $_POST.
  */

  /*
    Cek apakah $cid bernilai valid:
    Kalau $cid tidak valid, maka jangan lanjutkan proses, 
    kembalikan pengguna ke halaman awal (read.php) sembari 
    mengirim penanda error.
  */
  if (!$cid) {
    $_SESSION['flash_error'] = 'Akses tidak valid.';
    redirect_ke('read_biodata.php');
  }

  /*
    Ambil data lama dari DB menggunakan prepared statement, 
    jika ada kesalahan, tampilkan penanda error.
  */
  $stmt = mysqli_prepare($conn, "SELECT cid, cnim, cnama_lengkap,	ctempat_lahir,	ctanggal_lahir,chobi,	cpasangan,	cpekerjaan,	cnama_orang_tua,	cnama_kakak,	cnama_adik	

                                    FROM tbl_biodata_mahasiswa_sederhana WHERE cid = ? LIMIT 1");
  if (!$stmt) {
    $_SESSION['flash_error'] = 'Query tidak benar.';
    redirect_ke('read_biodata.php');
  }

  mysqli_stmt_bind_param($stmt, "i", $cid);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  $row = mysqli_fetch_assoc($res);
  mysqli_stmt_close($stmt);

  if (!$row) {
    $_SESSION['flash_error'] = 'Record tidak ditemukan.';
    redirect_ke('read_biodata.php');
  }

  #Nilai awal (prefill form)
  $nim  = $row['cnim'] ?? '';
  $nama = $row['cnama_lengkap'] ?? '';
  $tempat = $row['ctempat_lahir'] ?? '';
  $tanggal = $row['ctanggal_lahir'] ?? '';
  $hobi = $row['chobi'] ?? '';
  $pasangan = $row['cpasangan'] ?? '';
  $pekerjaan = $row['cpekerjaan'] ?? '';
  $ortu = $row['cnama_orang_tua'] ?? '';
  $kakak = $row['cnama_kakak'] ?? '';
  $adik = $row['cnama_adik'] ?? '';

  #Ambil error dan nilai old input kalau ada
  $flash_error = $_SESSION['flash_error'] ?? '';
  $old_biodata = $_SESSION['old_biodata'] ?? [];
  unset($_SESSION['flash_error'], $_SESSION['old_biodata']);
  if (!empty($old_biodata)) {

    $nim = $old_biodata['txtNim']?? $txtNim;
    $nama = $old_biodata['txtNmLengkap']?? $txtNmLengkap;
    $tempat = $old_biodata['txtT4Lhr']?? $txtT4Lhr;
    $tanggal = $old_biodata['txtTglLhr']?? $txtTglLhr;
    $hobi = $old_biodata['txtHobi']?? $txtHobi;
    $pasangan = $old_biodata['txtPasangan']?? $txtPasangan;
    $pekerjaan = $old_biodata['txtKerja']?? $txtKerja;
    $ortu = $old_biodata['txtNmOrtu']?? $txtNmOrtu;
    $kakak = $old_biodata['txtNmKakak']?? $txtNmKakak;
    $adik = $old_biodata['txtNmAdik']?? $txtNmAdik;
  }
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judul Halaman</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <header>
      <h1>Ini Header</h1>
      <button class="menu-toggle" id="menuToggle" aria-label="Toggle Navigation">
        &#9776;
      </button>
      <nav>
        <ul>
          <li><a href="#home">Beranda</a></li>
          <li><a href="#about">Tentang</a></li>
          <li><a href="#contact">Kontak</a></li>
        </ul>
      </nav>
    </header>

    <main>
      <section id="biodata">
        <h2>Edit Buku Tamu</h2>
        <?php if (!empty($flash_error)): ?>
          <div style="padding:10px; margin-bottom:10px; 
            background:#f8d7da; color:#721c24; border-radius:6px;">
            <?= $flash_error; ?>
          </div>
        <?php endif; ?>
        <form action="proses_update_biodata.php" method="POST">

          <input type="hidden" name="cid" value="<?= (int)$cid; ?>">

          <label for="txtNim"><span>NIM:</span>
            <input type="text" id="txtNim" name="txtNim" 
              placeholder="Masukkan nim" required autocomplete="nim"
              value="<?= !empty($nim) ? $nim : '' ?>">
          </label>

          <label for="txtNmLengkap"><span>Nama Lengkap:</span>
            <input type="text" id="txtNmLengkap" name="txtNmLengkap" 
              placeholder="Masukkan nama lengkap anda" required autocomplete="nama"
              value="<?= !empty($nama) ? $nama : '' ?>">
          </label>
          <label for="txtT4Lhr"><span>Tempat Lahir:</span>
            <input type="text" id="txtT4Lhr" name="txtT4Lhr" 
              placeholder="Masukkan tempat lahir" required autocomplete="tempat"
              value="<?= !empty($tempat) ? $tempat : '' ?>">
          </label>
          <label for="txtTglLhr"><span>Tanggal Lahir:</span>
            <input type="text" id="txtTglLhr" name="txtTglLhr" 
              placeholder="Masukkan tanggal lahir" required autocomplete="tanggal"
              value="<?= !empty($tanggal) ? $tanggal : '' ?>">
          </label>

          <label for="txtHobi"><span>Hobi:</span>
            <input type="text" id="txtHobi" name="txtHobi" 
              placeholder="Masukkan hobi anda ya" required autocomplete="hobi"
              value="<?= !empty($hobi) ? $hobi : '' ?>">
        </label>

          <label for="txtKerja"><span>Pekerjaan:</span>
            <input type="text" id="txtKerja" name="txtKerja" 
              placeholder="Masukkan Pekerjaan" required autocomplete="pekerjaan"
              value="<?= !empty($pekerjaan) ? $pekerjaan : '' ?>">
          </label>

          <label for="txtPasangan"><span>Nama Lengkap:</span>
            <input type="text" id="txtPasangan" name="txtPasangan" 
              placeholder="Masukkan pasangan" required autocomplete="pasangan"
              value="<?= !empty($pasangan) ? $pasangan : '' ?>">
          </label>
          
          <label for="txtNmOrtu"><span>Nama orang tua:</span>
            <input type="text" id="txtNmOrtu" name="txtNmOrtu" 
              placeholder="Masukkan orang tua" required autocomplete="ortu"
              value="<?= !empty($ortu) ? $ortu : '' ?>">
          </label>

          <label for="txtNmKakak"><span>Nama kakak:</span>
            <input type="text" id="txtNmKakak" name="txtNmKakak" 
              placeholder="Masukkan nama kakak" required autocomplete="kakak"
              value="<?= !empty($kakak) ? $kakak : '' ?>">
          </label>

          <label for="txtNmAdik"><span>Nama Adik:</span>
            <input type="text" name="txtNmAdik" 
              placeholder="Masukkan nama adik" required autocomplete="adik"
              value="<?= !empty($adik) ? $adik : '' ?>">
          </label>

          <button type="submit">Kirim</button>
          <button type="reset">Batal</button>
          <a href="read_biodata.php" class="reset">Kembali</a>
        </form>
      </section>
    </main>

    <script src="script.js"></script>
  </body>
</html>