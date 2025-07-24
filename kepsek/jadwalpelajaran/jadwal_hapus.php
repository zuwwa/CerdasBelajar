<?php
include '../../koneksi.php';
if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  mysqli_query($conn, "DELETE FROM jadwal WHERE id = $id");
}
header("Location: jadwalpelajaran.php");
exit;
?>
