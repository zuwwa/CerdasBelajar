<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['email'])) {
    echo "<script>alert('⚠️ Sesi tidak valid. Silakan login ulang.'); window.location='index.php';</script>";
    exit;
}

$email = strtolower(trim($_SESSION['email']));

// Cek ke tabel users
// Cek ke tabel users
$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$data = mysqli_fetch_assoc($query);

if ($data) {
    $userId   = $data['id'];
    $userName = $data['fullname'];
    $userType = $data['type']; // Ganti id_role → type

    // Simpan ke session
    $_SESSION['id_user'] = $userId;
    $_SESSION['nama']    = $userName;
    $_SESSION['role']    = $userType; // String: siswa, admin, dll
    

    // Arahkan sesuai role
    switch ($userType) {
        case 'admin':
            header("Location: administrator/index.php");
            break;
        case 'siswa':
            // Tambah ke t_siswa kalau belum ada
            $cek = mysqli_query($conn, "SELECT * FROM t_siswa WHERE nis = '$userId'");
            if (mysqli_num_rows($cek) == 0) {
                mysqli_query($conn, "INSERT INTO t_siswa (nis, nama, id_sekolah, jurusan) VALUES ('$userId', '$userName', 1, 'IPA')");
            }
            header("Location: siswa/index.php?page=dashboard");
            break;
        case 'guru':
            header("Location: guru/index.php");
            break;
        case 'ortu':
            header("Location: ortu/index.php");
            break;
        case 'kepsek':
            header("Location: kepsek/index.php");
            break;
        case 'perpus':
            header("Location: perpus/index.php");
            break;
        default:
            echo "<script>alert('⚠️ Akses tidak dikenali'); window.location='logout.php';</script>";
    }
    exit;
}
