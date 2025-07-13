<?php
session_start();
include('../../koneksi.php');

// Cek login siswa
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'siswa') {
    echo "<script>alert('⛔ Akses ditolak.'); window.location='../../logout.php';</script>";
    exit();
}

$email = strtolower(trim($_SESSION['email']));

// Ambil NISN siswa dari tabel siswa (yang berelasi dengan email)
$getNISN = mysqli_query($conn, "SELECT nisn FROM siswa WHERE email = '$email'");
$dataSiswa = mysqli_fetch_assoc($getNISN);

if (!$dataSiswa) {
    echo "<script>alert('❌ Data siswa tidak ditemukan.'); window.location='index.php';</script>";
    exit();
}

$nisn = $dataSiswa['nisn'];

// Ambil foto dari tabel t_siswa
$getFoto = mysqli_query($conn, "SELECT foto FROM t_siswa WHERE nis = '$nisn'");
$dataFoto = mysqli_fetch_assoc($getFoto);
$foto = $dataFoto['foto'] ?? null;

// Hapus file jika ada
$folder = '../../uploads/';
$path = $folder . $foto;

if (!empty($foto) && file_exists($path)) {
    unlink($path);
}

// Kosongkan kolom foto di t_siswa
$update = mysqli_query($conn, "UPDATE t_siswa SET foto = 'default.png' WHERE nis = '$nisn'");

if ($update) {
    echo "<script>alert('✅ Foto berhasil dihapus.'); window.location='index.php';</script>";
} else {
    echo "<script>alert('❌ Gagal menghapus foto di database.'); window.location='index.php';</script>";
}
?>
