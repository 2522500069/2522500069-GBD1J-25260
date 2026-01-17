<?php
  session_start();
  require 'koneksi.php';
  require 'fungsi.php';

  $sql = "SELECT * FROM tbl_biodata_mahasiswa_sederhana ORDER BY cid DESC";
  $q = mysqli_query($conn, $sql);
  if (!$q) {
    die("Query error: " . mysqli_error($conn));
  }
?>

<?php
  $flash_sukses = $_SESSION['flash_sukses'] ?? ''; #jika query sukses
  $flash_error  = $_SESSION['flash_error'] ?? ''; #jika ada error
  #bersihkan session ini
  unset($_SESSION['flash_sukses'], $_SESSION['flash_error']); 
?>

<?php if (!empty($flash_sukses)): ?>
        <div style="padding:10px; margin-bottom:10px; 
          background:#d4edda; color:#155724; border-radius:6px;">
          <?= $flash_sukses; ?>
        </div>
<?php endif; ?>

<?php if (!empty($flash_error)): ?>
        <div style="padding:10px; margin-bottom:10px; 
          background:#f8d7da; color:#721c24; border-radius:6px;">
          <?= $flash_error; ?>
        </div>
<?php endif; ?>

<table border="1" cellpadding="8" cellspacing="0">
  <tr>
    <th>No</th>
    <th>Aksi</th>
    <th>ID</th>
    <th>Nim</th>
    <th>Nama lengkap</th>
    <th>Tempat lahir</th>
    <th>Tanggal lahir</th>
    <th>hobi</th>
    <th>pasangan</th>
    <th>pekerjaan</th>
    <th>Nama orang tua</th>
    <th>Nama kakak</th>
    <th>Nama adik</th>
  </tr>
  <?php $i = 1; ?>
  <?php while ($row = mysqli_fetch_assoc($q)): ?>
    <tr>
      <td><?= $i++ ?></td>
      <td>
        <a href="edit_biodata.php?cid=<?= (int)$row['cid']; ?>">Edit</a>
        <a href="proses_delete_biodata.php?cid=<?= (int)$row['cid']; ?>">Hapus</a>
      </td>
      <td><?= $row['cid']; ?></td>
      <td><?= htmlspecialchars($row['cnim']); ?></td>
      <td><?= htmlspecialchars($row['cnama_lengkap']); ?></td>
      <td><?= htmlspecialchars($row['ctempat_lahir']); ?></td>
      <td><?= htmlspecialchars($row['ctanggal_lahir']); ?></td>
      <td><?= htmlspecialchars($row['chobi']); ?></td>
      <td><?= htmlspecialchars($row['cpasangan']); ?></td>
      <td><?= htmlspecialchars($row['cpekerjaan']); ?></td>
      <td><?= htmlspecialchars($row['cnama_orang_tua']); ?></td>
      <td><?= htmlspecialchars($row['cnama_kakak']); ?></td>
      <td><?= htmlspecialchars($row['cnama_adik']); ?></td>
    </tr>
  <?php endwhile; ?>
</table>