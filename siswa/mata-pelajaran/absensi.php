<?php
session_start();
include('../../koneksi.php');

// Validasi login siswa
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../logout.php");
    exit;
}

$email = $_SESSION['email'];
$siswaQuery = mysqli_query($conn, "
    SELECT s.nisn, ts.id AS id_ts
    FROM siswa s
    JOIN t_siswa ts ON s.nisn = ts.nis
    WHERE s.email = '$email'
");


$siswa = mysqli_fetch_assoc($siswaQuery);
$siswa_id = $siswa['id_ts'] ?? null;

if (!$siswa_id) {
    die("❌ Data siswa tidak ditemukan.");
}

// Ambil kode mapel
$kode = $_GET['kode'] ?? '';
if (empty($kode)) {
    echo "<script>alert('Kode mapel tidak ditemukan.'); window.location='mapel.php';</script>";
    exit;
}

// Ambil data mapel
$mapelQuery = mysqli_query($conn, "
    SELECT m.*, k.kelas AS nama_kelas 
    FROM t_mapel m
    LEFT JOIN t_kelas k ON m.id_kelas = k.id
    WHERE m.kode = '$kode'
");
$mapel = mysqli_fetch_assoc($mapelQuery);
if (!$mapel) {
    echo "<script>alert('Mata pelajaran tidak ditemukan.'); window.location='mapel.php';</script>";
    exit;
}

// Ambil data absensi siswa berdasarkan mapel
$absensiQuery = mysqli_query($conn, "
    SELECT a.*, tm.judul, tm.tanggal_upload
    FROM t_absensi a
    LEFT JOIN t_materi tm ON a.materi_id = tm.id
    WHERE a.id_siswa = '$siswa_id' AND a.id_mapel = '{$mapel['id']}'
    ORDER BY tm.tanggal_upload DESC
");

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Absensi - <?= htmlspecialchars($mapel['nama_mapel']) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="shortcut icon" href="../images/sma.png" />
  <style>
    body {
      background-color: #f4f6f9;
      font-family: 'Segoe UI', sans-serif;
    }
    .container { max-width: 950px; }
    .card { border-radius: 16px; padding: 30px; }
    h3, h4 { font-weight: 600; }
    .badge { font-size: 0.85rem; padding: 6px 12px; border-radius: 10px; text-transform: uppercase; }
    .badge.H { background-color: #28a745; color: white; }
    .btn-secondary { border-radius: 10px; }
  </style>
</head>
<body>
<div class="container mt-5 mb-5">
  <div class="card shadow">
    <div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="text-primary m-0">📅 Riwayat Absensi: <?= htmlspecialchars($mapel['nama_mapel']) ?></h3>
  <a href="../index.php" class="btn btn-dark btn-sm">🏠 Beranda</a>
</div>
    <p><strong>Guru Pengampu:</strong> <?= htmlspecialchars($mapel['nama_guru']) ?></p>
    <p><strong>Kelas:</strong> <?= htmlspecialchars($mapel['nama_kelas']) ?></p>
    <p><strong>Tanggal Hari Ini:</strong> <?= date('d M Y') ?></p>
    <hr>

    <h4 class="mt-4">Riwayat Absensi</h4>
    <?php if (mysqli_num_rows($absensiQuery) < 1): ?>
      <div class="alert alert-info mt-3">Belum ada data absensi yang tercatat untuk mata pelajaran ini.</div>
    <?php else: ?>
      <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover">
          <thead class="thead-light text-center">
            <tr>
              <th>No</th>
              <th>Materi</th>
              <th>Tanggal</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
          <?php $no = 1; while ($a = mysqli_fetch_assoc($absensiQuery)) : ?>
            <tr>
              <td class="text-center"><?= $no++ ?></td>
              <td><?= htmlspecialchars($a['judul'] ?? '-') ?></td>
              <td class="text-center"><?= date('d M Y', strtotime($a['tanggal_upload'])) ?></td>
              <td class="text-center"><span class="badge H">H</span></td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

    <a href="mapel.php?kode=<?= urlencode($kode) ?>" class="btn btn-secondary mt-3">← Kembali ke Mapel</a>
  </div>
</div>
</body>
</html>
