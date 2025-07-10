<?php
session_start();
include('../../koneksi.php');

// Validasi login siswa
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
  echo "<script>alert('Akses ditolak!'); window.location='../../logout.php';</script>";
  exit;
}

$email = $_SESSION['email'] ?? '';
$siswaQuery = mysqli_query($conn, "SELECT id FROM siswa WHERE email = '$email'");
$siswa = mysqli_fetch_assoc($siswaQuery);

if (!$siswa) {
  echo "<script>alert('Data siswa tidak ditemukan.'); window.location='../../logout.php';</script>";
  exit;
}

$siswa_id = $siswa['id'];
$mapel_id = $_POST['mapel_id'] ?? null;
$materi_id = $_POST['materi_id'] ?? null;

if (!$mapel_id || !$materi_id) {
  echo "<script>alert('❌ Data tidak lengkap.'); window.history.back();</script>";
  exit;
}

// Ambil tanggal hari ini
$tanggal = date('Y-m-d');

// Cek apakah siswa sudah absen hari ini untuk mapel dan materi tersebut
$cek = mysqli_query($conn, "
  SELECT * FROM absensi 
  WHERE siswa_id = $siswa_id 
    AND mapel_id = $mapel_id 
    AND tanggal = '$tanggal'
");

if (mysqli_num_rows($cek) > 0) {
  echo "<script>alert('⚠️ Kamu sudah absen hari ini.'); window.history.back();</script>";
  exit;
}

// Masukkan absensi
$insert = mysqli_query($conn, "
  INSERT INTO absensi (siswa_id, mapel_id, tanggal, status)
  VALUES ($siswa_id, $mapel_id, '$tanggal', 'Hadir')
");

if ($insert) {
  echo "<script>alert('✅ Absensi berhasil dicatat.'); window.history.back();</script>";
} else {
  echo "<script>alert('❌ Gagal menyimpan absensi.'); window.history.back();</script>";
}
?>
