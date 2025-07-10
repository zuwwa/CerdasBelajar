<?php
include('../../koneksi.php');

if (!isset($_GET['nisn'])) {
    echo "NISN tidak ditemukan!";
    exit();
}

$nisn = $_GET['nisn'];

// Hapus data siswa
$hapus = mysqli_query($conn, "DELETE FROM siswa WHERE nisn = '$nisn'");

if ($hapus) {
    echo "<script>alert('Data berhasil dihapus.'); window.location='index.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data.'); window.location='index.php';</script>";
}
?>
