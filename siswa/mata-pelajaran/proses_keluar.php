<?php
session_start();
include('../../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kode_mapel'])) {
  $kode_mapel = $_POST['kode_mapel'];
  $username = $_SESSION['username'];

  // Ambil id siswa
  $siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM siswa WHERE username = '$username'"));
  $siswa_id = $siswa['id'];

  // Ambil id mapel
  $mapel = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM mapel WHERE kode_mapel = '$kode_mapel'"));
  $mapel_id = $mapel['id'];

  // Hapus relasi dari siswa_mapel
  mysqli_query($conn, "DELETE FROM siswa_mapel WHERE siswa_id = '$siswa_id' AND mapel_id = '$mapel_id'");

  echo "<script>alert('Berhasil keluar dari mapel.'); window.location='index.php';</script>";
  exit();
} else {
  echo "<script>alert('Permintaan tidak valid.'); window.location='index.php';</script>";
  exit();
}
?>
