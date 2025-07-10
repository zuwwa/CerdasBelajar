<?php
session_start();
include('../../koneksi.php');

// Validasi login siswa
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
  echo "<script>alert('Akses ditolak!'); window.location='../../logout.php';</script>";
  exit;
}

$siswa_id = $_SESSION['id_user'];
$kode = $_GET['kode'] ?? '';
if (empty($kode)) {
  echo "<script>alert('Kode mapel tidak ditemukan.'); window.location='index.php';</script>";
  exit;
}

// Ambil data mapel
$mapelQuery = mysqli_query($conn, "
  SELECT m.*, g.nama AS nama_guru, k.kelas AS nama_kelas 
  FROM mapel m
  LEFT JOIN guru g ON m.guru_id = g.id
  LEFT JOIN kelas k ON m.kelas_id = k.id
  WHERE m.kode_mapel = '$kode'
");
$mapel = mysqli_fetch_assoc($mapelQuery);
if (!$mapel) {
  echo "<script>alert('Mapel tidak ditemukan.'); window.location='index.php';</script>";
  exit;
}

// Ambil daftar tugas
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
    <h3 class="mb-3 text-primary">📘 Tugas: <?= htmlspecialchars($mapel['nama_mapel']) ?></h3>
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
              <th>File</th>
              <th>Status</th>
              <th>Nilai</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
          <?php $no = 1; while ($tugas = mysqli_fetch_assoc($tugasQuery)) : ?>
            <?php
              $tugas_id = $tugas['id'];
              $nilai_query = mysqli_query($conn, "
                SELECT nilai FROM pengumpulan_tugas
                WHERE tugas_id = $tugas_id AND siswa_id = $siswa_id
              ");
              $nilai_data = mysqli_fetch_assoc($nilai_query);
              $nilai = $nilai_data ? $nilai_data['nilai'] : null;
            ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><strong><?= htmlspecialchars($tugas['judul']); ?></strong></td>
              <td><?= nl2br(htmlspecialchars($tugas['deskripsi'])); ?></td>
              <td><?= date('d M Y', strtotime($tugas['deadline'])); ?></td>
              <td>
                <?php if (!empty($tugas['file_tugas'])): ?>
                  <a class="btn btn-sm btn-primary btn-download" href="../../uploads/tugas/<?= $tugas['file_tugas']; ?>" download>📥 Unduh</a>
                <?php else: ?>
                  <span class="text-muted">-</span>
                <?php endif; ?>
              </td>
              <td>
                <?php
                  $now = date('Y-m-d');
                  if ($tugas['deadline'] < $now) {
                    echo '<span class="badge badge-danger">Terlambat</span>';
                  } elseif ($tugas['deadline'] == $now) {
                    echo '<span class="badge badge-warning">Hari Ini</span>';
                  } else {
                    echo '<span class="badge badge-success">Aktif</span>';
                  }
                ?>
              </td>
              <td>
                <?= is_numeric($nilai) ? $nilai : '<span class="text-muted">Belum dinilai</span>'; ?>
              </td>
              <td>
                <a href="kumpul_tugas.php?id=<?= $tugas['id']; ?>" class="btn btn-sm btn-success">Kumpulkan</a>
              </td>

            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

    <a href="mapel.php?kode=<?= urlencode($mapel['kode_mapel']); ?>" class="btn btn-secondary mt-3">← Kembali ke Mapel</a>
  </div>
</div>
</body>
</html>
