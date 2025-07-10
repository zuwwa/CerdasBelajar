<?php
session_start();
include('../../koneksi.php');

// Pastikan user adalah siswa dan login
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'siswa') {
    echo "<script>alert('⛔ Akses ditolak.'); window.location='../../logout.php';</script>";
    exit();
}

$email = strtolower(trim($_SESSION['email']));

// Ambil data siswa
$query = mysqli_query($conn, "SELECT foto FROM siswa WHERE email = '$email'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('❌ Data siswa tidak ditemukan.'); window.location='index.php';</script>";
    exit();
}

// Cek dan hapus file jika ada
$foto = $data['foto'];
$folder = '../uploads/';
$path = $folder . $foto;

if (!empty($foto) && file_exists($path)) {
    unlink($path); // Hapus file
}

// Update database: kosongkan kolom foto
$update = mysqli_query($conn, "UPDATE siswa SET foto = NULL WHERE email = '$email'");

if ($update) {
    echo "<script>alert('✅ Foto berhasil dihapus.'); window.location='index.php';</script>";
} else {
    echo "<script>alert('❌ Gagal menghapus foto di database.'); window.location='index.php';</script>";
}
?>
