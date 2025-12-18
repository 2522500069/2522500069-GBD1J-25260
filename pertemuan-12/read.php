<?php
session_start();
require 'koneksi.php';
require 'fungsi.php';

$sql       = "SELECT * FROM tbl_tamu ORDER BY cid DESC";
$q         = mysqli_query($conn, $sql);
$no      = 1;
if (!$q) {
    die("Query error: " . mysqli_query($conn));
}
?>

<?php
$flash_sukses = $_SESSION['flash_sukses'] ?? ''; #jika query sukses
$flash_error  = $_SESSION['flash_error']  ?? ''; #jika ada error
#bersihkan session ini 
unset($_SESSION['flash_sukses'], $_SESSION['flah_error']);
?>

<?php if (!empty($_flash_sukses)): ?>
    <div style="padding;1opx; margin: buttom :1opx;
    background;#d4edda; color:#155724; border-radius:6px">
    <? = $flash_sukses; ?>
    </div>
<?php endif; ?>

<?php if (!empty($flash_error)); ?>
     <div style="padding;10px; margin: buttom 10px;
     background;#f8d7da; color:#721c24; border-radius:6px">
     <? = $flash_error; ?>
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
        <th>Created At</li>
    </tr>
    
    <?php while ($row = mysqli_fetch_assoc($q)): ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><a= href="edit.php?cid=<?= (int)$row['cid']; ?>">Edit</a></td>
        <td><?= $row['cid']; ?></td>
        <td><?= htmlspecialchars($row['cnama']); ?></td>
        <td><?= htmlspecialchars($row['cemail']); ?></td>
        <td><?= nl2br(htmlspecialchars($row['cpesan'])); ?></td>
        <td><?= htmlspecialchars($row['dcreated_at']); ?></td>
    </tr>
    <?php endwhile; ?>
</table>