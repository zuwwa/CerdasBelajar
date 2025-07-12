<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
include('../../koneksi.php');

// Validasi login siswa
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'siswa') {
    header("Location: ../logout.php");
    exit;
}

$email = $_SESSION['email'];
$kode = $_GET['kode'] ?? '';

// Ambil data siswa
$querySiswa = mysqli_query($conn, "
    SELECT s.nisn, s.nama, ts.id AS id_ts, ts.kelas
    FROM siswa s
    JOIN t_siswa ts ON s.nisn = ts.nis
    WHERE s.email = '$email'
");
$siswa = mysqli_fetch_assoc($querySiswa);
$siswa_id = $siswa['id_ts'] ?? 0;

// Ambil data mapel
$mapelQuery = mysqli_query($conn, "
    SELECT m.*, k.kelas AS nama_kelas
    FROM t_mapel m
    LEFT JOIN t_kelas k ON m.id_kelas = k.id
    WHERE m.kode = '$kode'
");
$mapel = mysqli_fetch_assoc($mapelQuery);
$mapel_id = $mapel['id'] ?? 0;

// Ambil semua materi mapel ini
$materiQuery = mysqli_query($conn, "
    SELECT * FROM t_materi
    WHERE mapel_id = $mapel_id
    ORDER BY tanggal_upload DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Materi - <?= htmlspecialchars($mapel['nama_mapel'] ?? 'Tidak Ditemukan') ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="shortcut icon" href="../images/sma.png" />
  <style>
    body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
    .container { max-width: 900px; }
    .card { border-radius: 16px; }
    .btn-download {
      background-color: #2e86de;
      color: white;
      border-radius: 20px;
      padding: 6px 18px;
      font-weight: 500;
    }
    .btn-download:hover { background-color: #1c5fac; }
    .btn-absen {
      background-color: #28a745;
      color: white;
      border-radius: 20px;
      padding: 6px 18px;
      font-weight: 500;
    }
    .btn-absen[disabled] {
      background-color: #6c757d;
      cursor: not-allowed;
    }
  </style>
</head>
<body>
<div class="container mt-5 mb-5">
  <div class="card shadow p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="text-primary m-0">📚 Materi: <?= htmlspecialchars($mapel['nama_mapel'] ?? '-') ?></h3>
      <a href="../index.php" class="btn btn-dark btn-sm">🏠 Beranda</a>
    </div>
    <p><strong>Guru Pengampu:</strong> <?= htmlspecialchars($mapel['nama_guru'] ?? '-') ?></p>
    <p><strong>Kelas:</strong> <?= htmlspecialchars($mapel['nama_kelas'] ?? '-') ?></p>
    <hr>

    <?php if (mysqli_num_rows($materiQuery) < 1): ?>
      <div class="alert alert-info">Belum ada materi untuk mata pelajaran ini.</div>
    <?php else: ?>
      <ul class="list-group">
        <?php while ($materi = mysqli_fetch_assoc($materiQuery)) :
          $materi_id = $materi['id'];
          $hari_ini = date('Y-m-d');

          $cekAbsen = mysqli_query($conn, "
            SELECT id FROM t_absensi 
            WHERE id_siswa = '$siswa_id' 
              AND id_mapel = '$mapel_id' 
              AND materi_id = '$materi_id'
              AND DATE(waktu_kehadiran) = '$hari_ini'
          ");
          $sudahAbsen = mysqli_num_rows($cekAbsen) > 0;
        
        ?>
        
        <li class="list-group-item">
          <h5><?= htmlspecialchars($materi['judul']) ?></h5>
          <p><?= nl2br(htmlspecialchars($materi['deskripsi'])) ?></p>
          <small class="text-muted">Diunggah: <?= date('d M Y', strtotime($materi['tanggal_upload'])) ?></small><br>

          <?php if (!empty($materi['file'])): ?>
            <a href="../uploads/<?= urlencode($materi['file']) ?>" class="btn btn-download mt-2" download>📥 Unduh</a>
          <?php endif; ?>

          <?php if (!$sudahAbsen): ?>
          <form method="post" action="proses_absen.php?kode=<?= urlencode($kode); ?>" class="d-inline">
            <input type="hidden" name="mapel_id" value="<?= $mapel_id ?>">
            <input type="hidden" name="materi_id" value="<?= $materi_id ?>">
            <button type="submit" class="btn btn-absen mt-2">📅 Absen Hari Ini</button>
          </form>
          <?php else: ?>
          <button class="btn btn-success mt-2" disabled>✅ Anda telah absen hari ini</button>
          <?php endif; ?>

        </li>
        <?php endwhile; ?>
      </ul>
    <?php endif; ?>

    <a href="mapel.php?kode=<?= urlencode($kode) ?>" class="btn btn-secondary mt-4">← Kembali ke Mapel</a>
  </div>
</div>
</body>
</html>