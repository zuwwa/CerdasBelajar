<?php
session_start();
include('../../koneksi.php');

// Validasi sesi
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../logout.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kode_mapel'])) {
    $kode_mapel = $_POST['kode_mapel'];
    $email = $_SESSION['email'];

    // Ambil data siswa
    $siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa WHERE email = '$email'"));
    if (!$siswa) {
        echo "<script>alert('Siswa tidak ditemukan.'); window.location='index.php';</script>";
        exit;
    }

    $siswa_id = $siswa['nisn']; // gunakan NISN

    // Ambil ID mapel
    $mapel = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM t_mapel WHERE kode = '$kode_mapel'"));
    if (!$mapel) {
        echo "<script>alert('Mapel tidak ditemukan.'); window.location='index.php';</script>";
        exit;
    }

    $mapel_id = $mapel['id'];

    // Hapus data dari anggota_mapel
    $hapus = mysqli_query($conn, "DELETE FROM anggota_mapel WHERE siswa_id = '$siswa_id' AND mapel_id = '$mapel_id'");

    if ($hapus) {
        echo "<script>alert('✅ Kamu berhasil keluar dari mapel.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal keluar dari mapel.'); window.location='index.php';</script>";
    }
    exit;
} else {
    echo "<script>alert('Permintaan tidak valid.'); window.location='index.php';</script>";
    exit;
}
