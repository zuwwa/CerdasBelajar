<?php
session_start();
include('../../koneksi.php');

if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('⛔ Akses ditolak!'); window.location='../logout.php';</script>";
    exit;
}

$id = intval($_GET['id']);
$aksi = $_GET['aksi'] ?? '';

// Ambil data pembayaran
$pembayaran = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT p.*, td.total_tagihan
    FROM t_keuangan_pembayaran p
    JOIN t_keuangan_daftar td ON p.jenis_tagihan = td.nama_tagihan
    WHERE p.id = '$id'
"));


if (!$pembayaran) {
    echo "<script>alert('❌ Pembayaran tidak ditemukan!');window.location.href='index.php';</script>";
    exit;
}

if ($aksi == 'terima') {
    $nis = $pembayaran['nis'];
    $tagihan = $pembayaran['jenis_tagihan'];

    // Total bayar sebelumnya
    $result = mysqli_query($conn, "
        SELECT SUM(jml_bayar) AS total_bayar 
        FROM t_keuangan_pembayaran
        WHERE nis = '$nis' AND jenis_tagihan = '$tagihan' AND status = 'lunas'
    ");
    $total_bayar_sebelumnya = mysqli_fetch_assoc($result)['total_bayar'] ?? 0;

    // Hitung total terbaru
    $total_tagihan = (int) str_replace(['Rp. ', '.'], '', $pembayaran['total_tagihan']);
    $total_terbaru = $total_bayar_sebelumnya + $pembayaran['jml_bayar'];

    // Update status pembayaran ini
    $status = ($total_terbaru >= $total_tagihan) ? 'lunas' : 'lunas';
    mysqli_query($conn, "UPDATE t_keuangan_pembayaran SET status = '$status' WHERE id = '$id'");

    echo "<script>alert('✅ Pembayaran telah diterima.');window.location.href='index.php';</script>";
}
elseif ($aksi == 'tolak') {
    // Hapus bukti pembayaran
    $bukti_path = '../../uploads/bukti_pembayaran/' . $pembayaran['bukti_pembayaran'];
    if (!empty($pembayaran['bukti_pembayaran']) && file_exists($bukti_path)) {
        unlink($bukti_path);
    }

    mysqli_query($conn, "DELETE FROM t_keuangan_pembayaran WHERE id = '$id'");
    echo "<script>alert('⚠️ Pembayaran ditolak dan dihapus.');window.location.href='index.php';</script>";
}

?>
