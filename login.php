<?php
session_start();
include("koneksi.php");

$email = strtolower(trim($_SESSION['email']));

// Cek ke tabel users
$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$data  = mysqli_fetch_assoc($query);

if ($data) {
    $userId   = $data['id'];
    $userName = $data['fullname'];
    $userType = $data['id_role']; // ✅ INI yang benar

    // Simpan ke session
    $_SESSION['id_user'] = $userId;
    $_SESSION['nama']    = $userName;
    $_SESSION['type']    = $userType;

    // Arahkan sesuai role
    switch ($userType) {
        case 1:
            header("Location: administrator/index.php");
            break;
        case 2:
            // Cek apakah siswa sudah ada di t_siswa
            $cek = mysqli_query($conn, "SELECT * FROM t_siswa WHERE nis = '$userId'");
            if (mysqli_num_rows($cek) == 0) {
                mysqli_query($conn, "INSERT INTO t_siswa (nis, nama, id_sekolah, jurusan) VALUES ('$userId', '$userName', 1, 'IPA')");
            }
            header("Location: siswa/index.php?page=dashboard");
            break;
        case 3:
            header("Location: guru/index.php");
            break;
        case 4:
            header("Location: ortu/index.php");
            break;
        case 5:
            header("Location: kepsek/index.php");
            break;
        case 6:
            header("Location: perpus/index.php");
            break;
        default:
            echo "<script>alert('⚠️ Akses tidak dikenali'); window.location='logout.php';</script>";
    }
    exit;
} else {
    echo "<script>alert('⚠️ Akun tidak terdaftar.'); window.location='logout.php';</script>";
}
