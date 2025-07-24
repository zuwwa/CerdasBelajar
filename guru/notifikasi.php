<?php
session_start();
include '../koneksi.php';

// Cek akses siswa
if (!isset($_SESSION['email']) || $_SESSION['role'] != 1) {
    echo "<script>alert('⛔ Akses ditolak sebagai admin'); window.location='../logout.php';</script>";
    exit;
}


$siswa_id = $_SESSION['id_user'] ?? null;
$notifikasi = [];

if ($siswa_id) {
    $query = mysqli_query($conn, "SELECT * FROM notifikasi WHERE siswa_id = '$siswa_id' ORDER BY waktu DESC");
    while ($row = mysqli_fetch_assoc($query)) {
        $notifikasi[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Notifikasi - CerdasBelajar</title>
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="images/sma.png" />
  <style>
    body {
      background-color: #f8f9fa;
    }
    .notifikasi-card {
      border-left: 5px solid #004080;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <h3 class="mb-4">📬 Daftar Notifikasi</h3>
    
    <?php if (count($notifikasi) === 0): ?>
      <div class="alert alert-info">Belum ada notifikasi.</div>
    <?php else: ?>
      <?php foreach ($notifikasi as $notif): ?>
        <div class="card mb-3 notifikasi-card">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($notif['judul']) ?></h5>
            <p class="card-text"><?= htmlspecialchars($notif['isi']) ?></p>
            <p class="card-text"><small class="text-muted"><?= date('d M Y, H:i', strtotime($notif['waktu'])) ?></small></p>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <a href="index.php" class="btn btn-secondary mt-4">⬅️ Kembali ke Dashboard</a>
  </div>

  <script src="vendors/js/vendor.bundle.base.js"></script>
</body>
</html>
