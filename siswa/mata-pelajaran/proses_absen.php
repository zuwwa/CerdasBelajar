<?php
session_start();
include('../../koneksi.php');

// Validasi login siswa
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../logout.php");
    exit;
}

$email = $_SESSION['email'];
$mapel_id  = $_POST['mapel_id'] ?? null;
$materi_id = $_POST['materi_id'] ?? null;
$kode_mapel = $_GET['kode'] ?? '';

// Validasi input
if (!$mapel_id || !$materi_id || !$kode_mapel) {
    die("❌ Data tidak lengkap.");
}

// Ambil data siswa
$querySiswa = mysqli_query($conn, "
    SELECT s.nisn, s.nama, ts.id AS id_ts, ts.kelas
    FROM siswa s
    JOIN t_siswa ts ON s.nisn = ts.nis
    WHERE s.email = '$email'
");
$siswa = mysqli_fetch_assoc($querySiswa);
if (!$siswa) {
    die("❌ Data siswa tidak ditemukan.");
}

$siswa_id = $siswa['id_ts'];
$nama     = $siswa['nama'];
$kelas    = $siswa['kelas'];

// Ambil data mapel untuk mendapatkan id_kelas dan id_sekolah
$queryMapel = mysqli_query($conn, "SELECT id_kelas, id_sekolah FROM t_mapel WHERE id = '$mapel_id'");
$mapel = mysqli_fetch_assoc($queryMapel);
if (!$mapel) {
    die("❌ Data mapel tidak ditemukan.");
}

$id_kelas   = $mapel['id_kelas'];
$id_sekolah = $mapel['id_sekolah'];

// Cek apakah sudah absen hari ini
$cekAbsen = mysqli_query($conn, "
    SELECT id FROM t_absensi 
    WHERE id_siswa = '$siswa_id' 
      AND id_mapel = '$mapel_id' 
      AND materi_id = '$materi_id'
      AND DATE(waktu_kehadiran) = CURDATE()
");
if (mysqli_num_rows($cekAbsen) > 0) {
    header("Location: materi.php?kode=" . urlencode($kode_mapel));
    exit;
}


// Simpan ke t_absensi
$insert1 = mysqli_query($conn, "
    INSERT INTO t_absensi 
        (nama, kelas, waktu_kehadiran, id_siswa, id_mapel, materi_id, id_kelas, id_sekolah)
    VALUES 
        ('$nama', '$kelas', NOW(), '$siswa_id', '$mapel_id', '$materi_id', '$id_kelas', '$id_sekolah')
");

// Simpan ke t_siswa_absensi
$insert2 = mysqli_query($conn, "
    INSERT INTO t_siswa_absensi 
        (id_siswa, waktu_kehadiran, kehadiran)
    VALUES 
        ('$siswa_id', NOW(), 'H')
");

if ($insert1 && $insert2) {
    header("Location: materi.php?kode=" . urlencode($kode_mapel));
    exit;
} else {
    echo "❌ Gagal menyimpan absensi. Periksa koneksi atau struktur tabel.";
}

?>
