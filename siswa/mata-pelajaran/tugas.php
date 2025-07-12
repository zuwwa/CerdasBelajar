<?php
session_start();
include('../../koneksi.php');

// Validasi sesi login dan role siswa (id_role = 2)
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../logout.php");
    exit;
}

// Ambil ID siswa dari session
$siswa_id = $_SESSION['id_user'];

$kode = $_GET['kode'] ?? '';
if (empty($kode)) {
    echo "<script>alert('Kode mapel tidak ditemukan.'); window.location='index.php';</script>";
    exit;
}

// Ambil data mapel
$mapelQuery = mysqli_query($conn, "
  SELECT m.*, m.nama_guru AS nama_guru, k.kelas AS nama_kelas 
  FROM t_mapel m
  LEFT JOIN t_kelas k ON m.id_kelas = k.id
  WHERE m.kode = '$kode'
");
$mapel = mysqli_fetch_assoc($mapelQuery);
if (!$mapel) {
    echo "<script>alert('Mapel tidak ditemukan.'); window.location='index.php';</script>";
    exit;
}
// Ambil daftar tugas dari mapel ini
$tugasQuery = mysqli_query($conn, "
  SELECT * FROM tugas
  WHERE mapel_id = {$mapel['id']}
  ORDER BY deadline ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tugas - <?= htmlspecialchars($mapel['nama_mapel']) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="shortcut icon" href="../images/sma.png" />
  <style>
    body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
    .container { max-width: 960px; }
    .card { border-radius: 16px; }
    .badge-danger { background-color: #dc3545; }
    .badge-warning { background-color: #ffc107; color: #212529; }
    .badge-success { background-color: #28a745; }
    .btn-download { padding: 4px 12px; font-size: 14px; border-radius: 20px; }
  </style>
</head>
<body>
<div class="container mt-5 mb-5">
  <div class="card shadow p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="text-primary m-0">📘 Tugas: <?= htmlspecialchars($mapel['nama_mapel']) ?></h3>
      <a href="../index.php" class="btn btn-dark btn-sm">🏠 Beranda</a>
    </div>
    <p><strong>Guru Pengampu:</strong> <?= htmlspecialchars($mapel['nama_guru']) ?></p>
    <p><strong>Kelas:</strong> <?= htmlspecialchars($mapel['nama_kelas']) ?></p>
    <hr>

    <?php if (mysqli_num_rows($tugasQuery) < 1): ?>
      <div class="alert alert-info">Belum ada tugas untuk mata pelajaran ini.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="thead-light">
            <tr>
              <th>#</th>
              <th>Judul</th>
              <th>Deskripsi</th>
              <th>Deadline</th>
              <th>File Tugas</th>
              <th>File Jawaban</th>
              <th>Status</th>
              <th>Nilai</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
          <?php $no = 1; while ($tugas = mysqli_fetch_assoc($tugasQuery)) : ?>
            <?php
              $tugas_id = $tugas['id'];

              // Ambil nilai siswa
              $nilai_query = mysqli_query($conn, "SELECT nilai FROM pengumpulan_tugas WHERE tugas_id = $tugas_id AND siswa_id = $siswa_id");
              $nilai_data = mysqli_fetch_assoc($nilai_query);
              $nilai = $nilai_data ? $nilai_data['nilai'] : null;

              // Ambil file jawaban siswa
              $jawaban_query = mysqli_query($conn, "SELECT file_jawaban FROM pengumpulan_tugas WHERE tugas_id = $tugas_id AND siswa_id = $siswa_id");
              $jawaban_data = mysqli_fetch_assoc($jawaban_query);
              $file_jawaban = $jawaban_data ? $jawaban_data['file_jawaban'] : null;

              $now = date('Y-m-d');
            ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><strong><?= htmlspecialchars($tugas['judul']); ?></strong></td>
              <td><?= nl2br(htmlspecialchars($tugas['deskripsi'])); ?></td>
              <td><?= date('d M Y', strtotime($tugas['deadline'])); ?></td>
              <td class="text-center">
                <?php if (!empty($tugas['file_tugas'])): ?>
                  <a class="btn btn-sm btn-primary btn-download" href="../../uploads/tugas/<?= htmlspecialchars($tugas['file_tugas']); ?>" download>📥 Unduh</a>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <?php if ($file_jawaban): ?>
                  <a class="btn btn-sm btn-success btn-download" href="../../uploads/jawaban/<?= htmlspecialchars($file_jawaban); ?>" target="_blank" download>📥 Jawaban</a>
                <?php else: ?>
                  <span class="text-muted">Belum upload</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <?php
                  if ($tugas['deadline'] < $now) {
                    echo '<span class="badge badge-danger">Terlambat</span>';
                  } elseif ($tugas['deadline'] == $now) {
                    echo '<span class="badge badge-warning">Hari Ini</span>';
                  } else {
                    echo '<span class="badge badge-success">Aktif</span>';
                  }
                ?>
              </td>
              <td class="text-center">
                <?= is_numeric($nilai) ? $nilai : '<span class="text-muted">Belum dinilai</span>'; ?>
              </td>
              <td class="text-center">
                <a href="kumpul_tugas.php?id=<?= $tugas['id']; ?>" class="btn btn-sm btn-success">Kumpulkan</a>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

    <a href="mapel.php?kode=<?= urlencode($mapel['kode']); ?>" class="btn btn-secondary mt-3">← Kembali ke Mapel</a>
  </div>
</div>
</body>
</html>
