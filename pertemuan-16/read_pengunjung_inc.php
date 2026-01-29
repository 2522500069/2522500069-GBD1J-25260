<?php
require 'koneksi.php';

$fieldConfig = [
      "kodepen" => ["label" => "Kode Pengunjung:", "suffix" => ""],
      "nama" => ["label" => "Nama Pengunjung:", "suffix" => " &#128526;"],
      "alamat" => ["label" => "Alamat Rumah:", "suffix" => ""],
      "tanggal" => ["label" => "Tanggal Kunjungan:", "suffix" => ""],
      "hobi" => ["label" => "Hobi:", "suffix" => " &#127926;"],
      "slta" => ["label" => "Asal SLTA:", "suffix" => " &hearts;"],
      "pekerjaan" => ["label" => "Pekerjaan:", "suffix" => " &copy; 2025"],
      "ortu" => ["label" => "Nama Orang Tua:", "suffix" => ""],
      "pacar" => ["label" => "Nama Pacar:", "suffix" => ""],
      "mantan" => ["label" => "Nama Mantan:", "suffix" => ""],
    ];

$sql = "SELECT * FROM tbl_biodata pengunjung ORDER BY cid DESC";
$q = mysqli_query($conn, $sql);
if (!$q) {
  echo "<p>Gagal membaca data tamu: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
} elseif (mysqli_num_rows($q) === 0) {
  echo "<p>Belum ada data tamu yang tersimpan.</p>";
} else {
  while ($row = mysqli_fetch_assoc($q)) {
    $arrContact = [
      "kode"  => $row["ckode_pengunjung"]  ?? "",
      "nama" => $row["cnama_pengunjung"] ?? "",
      "alamat" => $row["calamat_rumah"] ?? "",
      "tanggal" => $row["ctanggal_kunjungan"] ?? "",
      "hobi" => $row["chobi"] ?? "",
      "asal" => $row["casal_SLTA"] ?? "",
      "pekerjan" => $row["cpekerjaan"] ?? "",
      "ortu" => $row["cnama_orang_tua"] ?? "",
      "pacar" => $row["cnama_pacar"] ?? "",
      "mantan" => $row["cnama_mantan"] ?? "",





    ];
    echo tampilkanBiodata($fieldContact, $arrContact);
  }
}
?>
