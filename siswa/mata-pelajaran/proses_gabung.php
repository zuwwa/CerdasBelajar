<?php
session_start();
include('../../koneksi.php');

// Pastikan hanya siswa yang bisa gabung
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'siswa') {
    echo "<script>alert('⛔ Akses ditolak!'); window.location='../logout.php';</script>";
    exit;
}

// Ambil kode mapel dari form
$kode = $_POST['kode_mapel'] ?? '';

if (empty($kode)) {
    echo "<script>alert('⚠️ Kode mapel tidak boleh kosong.'); window.location='../index.php';</script>";
    exit;
}

// Cek apakah kode mapel ada di database
$q = mysqli_query($conn, "SELECT * FROM t_mapel WHERE kode = '$kode'");
$mapel = mysqli_fetch_assoc($q);

if (!$mapel) {
    echo "<script>alert('❌ Kode mapel tidak ditemukan.'); window.location='../index.php';</script>";
    exit;
}

// Simpan kode mapel ke dalam sesi (kalau belum ada)
if (!isset($_SESSION['mapel_gabung'])) {
    $_SESSION['mapel_gabung'] = [];
}

// Cek apakah kode sudah pernah digabung
if (!in_array($kode, $_SESSION['mapel_gabung'])) {
    $_SESSION['mapel_gabung'][] = $kode;
    echo "<script>alert('✅ Berhasil gabung ke mapel \"{$mapel['nama_mapel']}\".'); window.location='../index.php';</script>";
} else {
    echo "<script>alert('⚠️ Kamu sudah tergabung di mapel ini sebelumnya.'); window.location='../index.php';</script>";
}
