<?php
session_start();
include('../../koneksi.php');

// Cek sesi dan role
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'siswa') {
  echo "<script>alert('Akses ditolak.'); window.location='../../logout.php';</script>";
  exit();
}

$email = $_SESSION['email'];
$folder = '../uploads/';
$maxSize = 2 * 1024 * 1024; // 2MB

// Debug: apakah file dikirim?
if (!isset($_FILES['foto'])) {
  echo "<script>alert('❌ Tidak ada file yang dikirim.'); history.back();</script>";
  exit();
}

// Cek error upload
if ($_FILES['foto']['error'] !== 0) {
  echo "<script>alert('❌ Terjadi kesalahan saat upload. Kode: " . $_FILES['foto']['error'] . "'); history.back();</script>";
  exit();
}

$tmp = $_FILES['foto']['tmp_name'];
$namaFile = basename($_FILES['foto']['name']);
$ekstensi = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
$namaBaru = uniqid('foto_', true) . '.' . $ekstensi;
$pathBaru = $folder . $namaBaru;

// Validasi ekstensi
if (!in_array($ekstensi, ['jpg', 'jpeg', 'png'])) {
  echo "<script>alert('❌ Format harus JPG atau PNG'); history.back();</script>";
  exit();
}

// Validasi ukuran
if ($_FILES['foto']['size'] > $maxSize) {
  echo "<script>alert('❌ Ukuran foto maksimal 2MB'); history.back();</script>";
  exit();
}

// Pindahkan file
if (!move_uploaded_file($tmp, $pathBaru)) {
  echo "<script>alert('❌ Gagal memindahkan file. Pastikan folder uploads bisa ditulis.'); history.back();</script>";
  exit();
}

// Update DB
$update = mysqli_query($conn, "UPDATE siswa SET foto = '$namaBaru' WHERE email = '$email'");

if ($update) {
  echo "<script>alert('✅ Foto berhasil diperbarui'); window.location='index.php';</script>";
} else {
  echo "<script>alert('❌ Gagal menyimpan ke database: " . mysqli_error($conn) . "'); history.back();</script>";
}
?>
