<?php
session_start();
include('../../koneksi.php');

// Cek login admin
if (!isset($_SESSION['email']) || $_SESSION['role'] != 1) {
    echo "<script>alert('⛔ Akses ditolak!');window.location.href='../login.php';</script>";
    exit;
}

if (!isset($_GET['id']) || !isset($_GET['aksi'])) {
    echo "<script>alert('❌ Parameter tidak lengkap!');window.location.href='index.php';</script>";
    exit;
}

$id = intval($_GET['id']);
$aksi = $_GET['aksi'];

// Ambil data pembayaran
$pembayaran = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT p.*, t.total 
    FROM pembayaran p
    JOIN tagihan t ON p.tagihan_id = t.id
    WHERE p.id = '$id'
"));

if (!$pembayaran) {
    echo "<script>alert('❌ Pembayaran tidak ditemukan!');window.location.href='index.php';</script>";
    exit;
}

if ($aksi == 'terima') {
    // Cek apakah sudah lunas
    $total_tagihan = $pembayaran['total'];
    $jumlah_bayar = $pembayaran['jumlah_bayar'];

    // Hitung total pembayaran sebelumnya
    $siswa_id = $pembayaran['siswa_id'];
    $tagihan_id = $pembayaran['tagihan_id'];

    $total_bayar_sebelumnya = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT SUM(jumlah_bayar) as total_bayar 
        FROM pembayaran 
        WHERE tagihan_id = '$tagihan_id' 
        AND siswa_id = '$siswa_id' 
        AND status = 'Lunas'
    "))['total_bayar'] ?? 0;

    $total_terbaru = $total_bayar_sebelumnya + $jumlah_bayar;

    $status = ($total_terbaru >= $total_tagihan) ? 'Lunas' : 'Lunas'; // Atur sesuai logikamu

    // Update pembayaran ini menjadi status Lunas
    $update = mysqli_query($conn, "
        UPDATE pembayaran 
        SET status = '$status' 
        WHERE id = '$id'
    ");

    if ($update) {
        echo "<script>alert('✅ Pembayaran telah diterima dan diperbarui.');window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal mengupdate status pembayaran.');window.location.href='index.php';</script>";
    }

} elseif ($aksi == 'tolak') {
    // Hapus bukti pembayaran dari folder
    $bukti_path = '../../uploads/bukti_pembayaran/' . $pembayaran['keterangan'];
    if (file_exists($bukti_path)) {
        unlink($bukti_path);
    }

    // Hapus data pembayaran
    $delete = mysqli_query($conn, "DELETE FROM pembayaran WHERE id = '$id'");

    if ($delete) {
        echo "<script>alert('⚠️ Pembayaran ditolak dan dihapus.');window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal menolak pembayaran.');window.location.href='index.php';</script>";
    }

} else {
    echo "<script>alert('❌ Aksi tidak valid!');window.location.href='index.php';</script>";
}
?>
