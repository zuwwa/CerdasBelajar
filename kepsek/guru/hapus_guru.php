<?php
session_start();
include '../../koneksi.php';

// Fungsi untuk redirect dan menampilkan pesan error
function redirectWithError($message, $location = '../../logout.php')
{
    echo "<script>alert('" . htmlspecialchars($message) . "'); window.location='" . htmlspecialchars($location) . "';</script>";
    exit;
}

// Fungsi untuk redirect dan menampilkan pesan sukses
function redirectWithSuccess($message, $location)
{
    echo "<script>alert('" . htmlspecialchars($message) . "'); window.location='" . htmlspecialchars($location) . "';</script>";
    exit;
}

// Cek login kepala sekolah
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'kepsek') {
    redirectWithError('⛔ Akses ditolak! Halaman ini hanya untuk Kepala Sekolah.');
}

$guru_id = $_GET['id'] ?? null;
if (!$guru_id) {
    redirectWithError('ID Guru tidak ditemukan.', 'index.php');
}

// Ambil NIP guru untuk menghapus dari tabel users
$nip_guru = null;
$query_get_nip = "SELECT nip FROM t_guru WHERE id = ?";
$stmt_get_nip = mysqli_prepare($conn, $query_get_nip);
if ($stmt_get_nip === false) {
    error_log("Error preparing query get NIP: " . mysqli_error($conn));
    redirectWithError('Terjadi kesalahan sistem saat mengambil NIP guru. Silakan coba lagi nanti.');
}
mysqli_stmt_bind_param($stmt_get_nip, "i", $guru_id);
mysqli_stmt_execute($stmt_get_nip);
$result_get_nip = mysqli_stmt_get_result($stmt_get_nip);

if ($result_get_nip && mysqli_num_rows($result_get_nip) > 0) {
    $row_nip = mysqli_fetch_assoc($result_get_nip);
    $nip_guru = $row_nip['nip'];
} else {
    redirectWithError('NIP guru tidak ditemukan untuk ID ini.', 'index.php');
}
mysqli_stmt_close($stmt_get_nip);

// Mulai transaksi
mysqli_begin_transaction($conn);

try {
    // 1. Hapus dari tabel t_guru
    $query_delete_guru = "DELETE FROM t_guru WHERE id = ?";
    $stmt_delete_guru = mysqli_prepare($conn, $query_delete_guru);
    if ($stmt_delete_guru === false) {
        throw new Exception("Gagal menyiapkan query hapus guru: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt_delete_guru, "i", $guru_id);
    if (!mysqli_stmt_execute($stmt_delete_guru)) {
        throw new Exception("Gagal menghapus data guru: " . mysqli_stmt_error($stmt_delete_guru));
    }
    mysqli_stmt_close($stmt_delete_guru);

    // 2. Hapus dari tabel users
    // Email guru dibuat dari NIP
    $email_guru_to_delete = $nip_guru . '@sman1sukabumi.sch.id';
    $query_delete_user = "DELETE FROM users WHERE email = ? AND type = 'guru'"; // Pastikan hanya menghapus user guru
    $stmt_delete_user = mysqli_prepare($conn, $query_delete_user);
    if ($stmt_delete_user === false) {
        throw new Exception("Gagal menyiapkan query hapus user: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt_delete_user, "s", $email_guru_to_delete);
    if (!mysqli_stmt_execute($stmt_delete_user)) {
        throw new Exception("Gagal menghapus data user: " . mysqli_stmt_error($stmt_delete_user));
    }
    mysqli_stmt_close($stmt_delete_user);

    // Commit transaksi jika semua berhasil
    mysqli_commit($conn);
    redirectWithSuccess('Data guru dan akun user berhasil dihapus!', 'index.php');

} catch (Exception $e) {
    // Rollback transaksi jika ada error
    mysqli_rollback($conn);
    error_log("Error deleting teacher: " . $e->getMessage());
    redirectWithError('Terjadi kesalahan: ' . $e->getMessage());
}
?>
