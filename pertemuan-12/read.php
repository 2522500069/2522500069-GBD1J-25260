<?php
session_start();
require 'koneksi.php';
require 'fungsi.php';

$sql = "SELECT * FROM tbl_tamu ORDER BY cid DESC";
$q   = mysqli_query($conn, $sql);
$no = 1;
?>

<?php
$sinar_sukses = $_SESSION['sinar_sukses'] ?? '';
$sinar_error = $_SESSION['sinar_error'] ?? '';
unset($_SESSION['sinar_sukses'], $_SESSION['sinar_error']);
?>
  <?php if (!empty($sinar_sukses)): ?>
          <div style="padding:10px; margin-bottom:10px; background:#d4edda; color:#155724; border-radius:6px;">
            <?= $sinar_sukses; ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($sinar_error)): ?>
          <div style="padding:10px; margin-bottom:10px; background:#f8d7da; color:#721c24; border-radius:6px;">
            <?= $sinar_error; ?>
          </div>
        <?php endif; ?>



<table border="1" cellpadding="8" cellspacing="0">
    <tr>

        <th>No</th>
        <th>Aksi</th>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Pesan</th>
        <th>Created At</th>

    </tr>

    <?php while ($row = mysqli_fetch_assoc($q)): ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><a href="edit.php?cid=<?= (int)$row['cid']; ?>">Edit</a></td>
        <td><?= $row['cid']; ?></td>
        <td><?= htmlspecialchars($row['cnama']); ?></td>
        <td><?= htmlspecialchars($row['cemail']); ?></td>
        <td><?= nl2br(htmlspecialchars($row['cpesan'])); ?></td>
        <td><?= formatTanggal(htmlspecialchars($row['dcreated_at'])); ?></td>
            
    </tr>
    <?php endwhile; ?>
</table>