<?php
session_start();
include('../../koneksi.php');

// Validasi login siswa
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
  echo "<script>alert('Akses ditolak!'); window.location='../../logout.php';</script>";
  exit();
}

// Ambil kode mapel dari URL
$kode = $_GET['kode'] ?? '';
if (empty($kode)) {
  echo "<script>alert('Kode mapel tidak ditemukan.'); window.location='index.php';</script>";
  exit();
}

// Ambil data mapel
$query = mysqli_query($conn, "
  SELECT m.*, g.nama AS nama_guru, k.kelas AS nama_kelas 
  FROM mapel m
  LEFT JOIN guru g ON m.guru_id = g.id
  LEFT JOIN kelas k ON m.kelas_id = k.id
  WHERE m.kode_mapel = '$kode'
");
$mapel = mysqli_fetch_assoc($query);
if (!$mapel) {
  echo "<script>alert('Mata pelajaran tidak ditemukan.'); window.location='index.php';</script>";
  exit();
}

// Ambil ID siswa berdasarkan email
$email = $_SESSION['email'];
$siswaQuery = mysqli_query($conn, "SELECT id FROM siswa WHERE email = '$email'");
$siswa = mysqli_fetch_assoc($siswaQuery);
$id_siswa = $siswa['id'];

// Ambil data absensi
$absensiQuery = mysqli_query($conn, "
  SELECT * FROM absensi
  WHERE mapel_id = {$mapel['id']} AND siswa_id = $id_siswa
  ORDER BY tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Absensi - <?= htmlspecialchars($mapel['nama_mapel']); ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="shortcut icon" href="../images/sma.png" />
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .container {
      max-width: 900px;
    }
    .card {
      border-radius: 16px;
    }
    .badge {
      font-size: 90%;
      padding: 6px 10px;
      border-radius: 8px;
    }
    .badge.Hadir { background-color: #28a745; color: white; }
    .badge.Izin { background-color: #ffc107; color: black; }
    .badge.Sakit { background-color: #17a2b8; color: white; }
    .badge.Alfa { background-color: #dc3545; color: white; }
  </style>
</head>
<body>
<div class="container mt-5 mb-5">
  <div class="card shadow p-4">
    <h3 class="mb-3 text-primary">📅 Riwayat Absensi: <?= htmlspecialchars($mapel['nama_mapel']); ?></h3>
    <p><strong>Guru Pengampu:</strong> <?= htmlspecialchars($mapel['nama_guru']); ?></p>
    <p><strong>Kelas:</strong> <?= htmlspecialchars($mapel['nama_kelas']); ?></p>
    <hr>

    <?php if (mysqli_num_rows($absensiQuery) < 1): ?>
      <div class="alert alert-info">Belum ada data absensi untuk mata pelajaran ini.</div>
    <?php else: ?>
      <table class="table table-bordered table-hover">
        <thead class="thead-light">
          <tr>
            <th>#</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Jam Hadir</th>
          </tr>
        </thead>
        <tbody>
        <?php $no = 1; while ($a = mysqli_fetch_assoc($absensiQuery)) : ?>
          <tr>
            <td><?= $no++; ?></td>
            <td><?= date('d M Y', strtotime($a['tanggal'])); ?></td>
            <td><span class="badge <?= $a['status']; ?>"><?= $a['status']; ?></span></td>
            <td><?= date('H:i', strtotime($a['waktu_kehadiran'])); ?></td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <a href="mapel.php?kode=<?= urlencode($mapel['kode_mapel']); ?>" class="btn btn-secondary mt-3">← Kembali ke Mapel</a>
  </div>
</div>
</body>
</html>
