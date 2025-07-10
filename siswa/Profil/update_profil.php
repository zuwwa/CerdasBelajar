<?php
session_start();
include('../../koneksi.php');

// Validasi login
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
  echo "<script>alert('Akses ditolak!'); window.location='../../logout.php';</script>";
  exit();
}

$email = strtolower(trim($_SESSION['email']));
$nis = $_POST['nis'];
$nama = $_POST['nama'];
$tempat_lahir = $_POST['tempat_lahir'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$agama = $_POST['agama'];
$alamat = $_POST['alamat'];
$no_telepon = $_POST['no_telepon'];

// Update data
$query = "UPDATE siswa SET 
            nama='$nama',
            tempat_lahir='$tempat_lahir',
            tanggal_lahir='$tanggal_lahir',
            jenis_kelamin='$jenis_kelamin',
            agama='$agama',
            alamat='$alamat',
            no_telepon='$no_telepon'
          WHERE email='$email'";

if (mysqli_query($conn, $query)) {
  echo "<script>alert('✅ Data profil berhasil diperbarui.'); window.location='index.php';</script>";
} else {
  echo "<script>alert('❌ Gagal memperbarui data.'); window.location='index.php';</script>";
}
?>
