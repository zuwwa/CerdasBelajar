<?php
session_start();
include('../../koneksi.php');

// Validasi login siswa
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../logout.php");
    exit;
}

// Ambil kode mapel
$kode = $_GET['kode'] ?? '';
if (empty($kode)) {
  echo "<script>alert('Kode mapel tidak ditemukan.'); window.location='index.php';</script>";
  exit();
}

// Ambil data mapel + guru + kelas
$query = mysqli_query($conn, "
  SELECT m.*, m.nama_guru AS nama_guru, k.kelas AS nama_kelas 
  FROM t_mapel m
  LEFT JOIN t_kelas k ON m.id_kelas = k.id
  WHERE m.kode = '$kode'
");

$mapel = mysqli_fetch_assoc($query);

if (!$mapel) {
  echo "<script>alert('Mata pelajaran tidak ditemukan.'); window.location='index.php';</script>";
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($mapel['nama_mapel']); ?> - SMA</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="shortcut icon" href="../images/sma.png" />
  <style>
    body {
      background-color: #f4f6f9;
      font-family: 'Segoe UI', sans-serif;
    }
    .banner {
      background-color: #1b3e72;
      color: white;
      padding: 40px 20px;
      text-align: center;
      position: relative;
    }
    .banner h2 {
      font-weight: bold;
      margin-bottom: 10px;
    }
    .btn-beranda {
      position: absolute;
      top: 20px;
      background: white;
      color: #1b3e72;
      font-weight: 500;
      padding: 6px 15px;
      border-radius: 20px;
      border: none;
    }
    .btn-beranda:hover {
      background: #f0f0f0;
    }
    .btn-left {
      left: 20px;
    }
    .btn-right {
      right: 20px;
    }
    .info-box {
      background: #ffffff;
      padding: 30px;
      margin-top: 0;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    .btn-action {
      border-radius: 20px;
      padding: 10px 20px;
      font-weight: 500;
      margin: 5px;
    }
    .topik-list li {
      list-style: none;
      padding: 8px 0;
    }
    .topik-list li::before {
      content: "✔";
      color: green;
      margin-right: 10px;
    }
  </style>
</head>
<body>

  <!-- HEADER MAPEL -->
  <div class="banner">
    <a href="index.php" class="btn btn-light btn-sm btn-beranda btn-left">← Kembali</a>

    <!-- Tombol Pengaturan -->
    <button class="btn btn-light btn-sm btn-beranda btn-right" data-toggle="modal" data-target="#modalPengaturan">
      <i class="bi bi-gear-fill"></i> Pengaturan
    </button>

    <h2><?= strtoupper(htmlspecialchars($mapel['nama_mapel'])); ?></h2>
    <p>Oleh <?= htmlspecialchars($mapel['nama_guru']); ?></p>
    <small>Kode Mapel: <?= htmlspecialchars($mapel['kode']); ?></small>
  </div>

  <!-- TOMBOL FITUR MAPEL -->
  <div class="text-center mt-4">
    <a href="../index.php" class="btn btn-dark btn-action">🏠 Beranda</a> <!-- ✅ Tambahan tombol beranda -->
    <a href="absensi.php?kode=<?= urlencode($mapel['kode']); ?>" class="btn btn-info btn-action">📅 Absensi</a>
    <a href="tugas.php?kode=<?= urlencode($mapel['kode']); ?>" class="btn btn-primary btn-action">📘 Tugas</a>
    <a href="materi.php?kode=<?= urlencode($mapel['kode']); ?>" class="btn btn-success btn-action">📚 Materi</a>
  </div>

  <!-- KONTEN MATERI -->
  <div class="container mt-4 mb-5">
    <div class="info-box">
      <h5>Deskripsi Mapel</h5>
      <p><?= !empty($mapel['deskripsi']) ? nl2br(htmlspecialchars($mapel['deskripsi'])) : 'Mata pelajaran ini disiapkan untuk mendukung kurikulum SMA berbasis Merdeka Belajar.'; ?></p>

      <h5 class="mt-4">Apa Saja yang Akan Dipelajari?</h5>
      <ul class="topik-list">
        <li>Pengantar Materi</li>
        <li>Kompetensi Dasar</li>
        <li>Penilaian Harian</li>
        <li>Tugas & Proyek</li>
        <li>UTS & UAS</li>
      </ul>

      <div class="mt-4">
        <div class="alert alert-secondary">
          ❗ Ingin keluar dari kelas ini? Silakan hubungi guru pengampu atau admin sekolah untuk proses penghapusan.
        </div>
        <a href="index.php" class="btn btn-secondary mt-2">← Kembali ke Daftar Mapel</a>
      </div>
    </div>
  </div>

  <!-- MODAL PENGATURAN: Keluar Mapel -->
  <div class="modal fade" id="modalPengaturan" tabindex="-1" role="dialog" aria-labelledby="pengaturanLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form action="proses_keluar.php" method="POST" class="modal-content">
        <input type="hidden" name="kode_mapel" value="<?= htmlspecialchars($mapel['kode']) ?>">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="pengaturanLabel">Keluar dari Mata Pelajaran</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin keluar dari mata pelajaran <strong><?= htmlspecialchars($mapel['nama_mapel']); ?></strong>?</p>
          <p class="text-danger small mb-0">Tindakan ini tidak dapat dibatalkan. Silakan lanjutkan hanya jika Anda yakin.</p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Ya, Keluar</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <!-- JS Bootstrap -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
