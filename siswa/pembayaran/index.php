<?php
session_start();
include '../../koneksi.php';

// Cek login siswa
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'siswa') {
  header("Location: ../logout.php");
  exit;
}

// Ambil data siswa berdasar email login
$email = $_SESSION['email'];
$siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa WHERE email = '$email'"));
if (!$siswa) {
  echo "<script>alert('❌ Siswa tidak ditemukan.'); window.location='../../logout.php';</script>";
  exit;
}
$nisn = $siswa['nisn'];

// Fungsi untuk mendapatkan sisa pembayaran berdasarkan nis
function getSisaBayar($conn, $nis, $jenis_tagihan) {
  // Ambil total tagihan dari t_keuangan_daftar
  $tagihan = mysqli_fetch_assoc(mysqli_query(
      $conn, 
      "SELECT total_tagihan FROM t_keuangan_daftar WHERE nama_tagihan = '$jenis_tagihan'"
  ));

  $total_tagihan = 0;
  if ($tagihan) {
      $total_tagihan = (int) preg_replace('/[^0-9]/', '', $tagihan['total_tagihan']); // Hilangkan Rp. dan titik
  }

  // Ambil total bayar dari t_keuangan_pembayaran
  $query = mysqli_query($conn, "
      SELECT SUM(CAST(REPLACE(REPLACE(jml_bayar, 'Rp. ', ''), '.', '') AS UNSIGNED)) AS total_bayar
      FROM t_keuangan_pembayaran
      WHERE nis = '$nis' AND jenis_tagihan = '$jenis_tagihan' AND status = 'lunas'
  ");
  $row = mysqli_fetch_assoc($query);
  $total_dibayar = $row['total_bayar'] ?? 0;

  return max(0, $total_tagihan - $total_dibayar);
}



// Ambil notifikasi siswa berdasarkan nisn
$jumlah_notif = 0;
$daftar_notif = [];
$notif_query = mysqli_query($conn, "SELECT * FROM notifikasi WHERE siswa_id = '$nisn' ORDER BY waktu DESC LIMIT 5");
$jumlah_notif = mysqli_num_rows($notif_query);
while ($row = mysqli_fetch_assoc($notif_query)) {
  $daftar_notif[] = $row;
}

// Ambil tagihan siswa
$tagihan_query = mysqli_query($conn, "
  SELECT 
    d.id_tagihan,
    d.nama_tagihan,
    REPLACE(REPLACE(d.total_tagihan, 'Rp. ', ''), '.', '') AS total_tagihan,
    IFNULL(SUM(CAST(REPLACE(REPLACE(p.jml_bayar, 'Rp. ', ''), '.', '') AS UNSIGNED)), 0) AS total_bayar
  FROM t_keuangan_daftar d
  LEFT JOIN t_keuangan_pembayaran p 
    ON p.nis = '$nisn' AND p.jenis_tagihan = d.nama_tagihan AND p.status = 'lunas'
  GROUP BY d.id_tagihan, d.nama_tagihan, d.total_tagihan
  ORDER BY d.id_tagihan ASC
");

if (!$tagihan_query) {
  die("Query error: " . mysqli_error($conn));
}

$daftar_tagihan = [];
while ($row = mysqli_fetch_assoc($tagihan_query)) {
  $daftar_tagihan[] = $row;
}

// Ambil riwayat pembayaran siswa
$pembayaran_query = mysqli_query($conn, "
    SELECT kp.*, kp.jenis_tagihan,
           (SELECT total_tagihan FROM t_keuangan_daftar WHERE nama_tagihan = kp.jenis_tagihan) AS total_tagihan
    FROM t_keuangan_pembayaran kp
    WHERE kp.nis = '$nisn'
    ORDER BY kp.id DESC
");

$riwayat = [];
while ($row = mysqli_fetch_assoc($pembayaran_query)) {
  $riwayat[] = $row;
}

// Fungsi generate Virtual Account Number (VA)
function generateVA($id_tagihan)
{
    return "888201" . str_pad((string)$id_tagihan, 8, "0", STR_PAD_LEFT);
}

?>


<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Tagihan Siswa - CerdasBelajar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../vendors/typicons.font/font/typicons.css">
  <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="../../images/sma.png">
  <style>
    .bg-gradient-primary {
      background: linear-gradient(90deg, rgb(2, 40, 122), rgb(27, 127, 219));
    }

    .notification-icon {
      width: 30px;
      height: 30px;
      cursor: pointer;
      position: relative;
    }

    .notification-icon img {
      width: 90%;
    }

    .notification-badge {
      position: absolute;
      top: -6px;
      right: -6px;
      background: red;
      color: white;
      font-size: 10px;
      padding: 2px 6px;
      border-radius: 50%;
    }

    .notification-dropdown {
      position: absolute;
      top: 38px;
      right: 0;
      background: #fff;
      width: 280px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 6px;
      display: none;
      z-index: 999;
    }

    .notification-dropdown.d-block {
      display: block;
    }

    .notification-dropdown h6,
    .notif-item,
    .notif-footer {
      padding: 10px 15px;
      font-size: 14px;
    }

    .notif-footer {
      text-align: center;
      font-weight: bold;
      color: crimson;
    }

    .nav-profile-icon img {
      width: 23px;
      margin-right: 15px;
    }

    .navbar-menu-wrapper {
      background-color: #004080 !important;
      border-bottom: 1px solid #003366 !important;
    }

    .table th,
    .table td {
      vertical-align: middle !important;
    }

    .sidebar .nav,
    .sidebar .nav-item,
    .sidebar .nav-link {
      background-color: transparent !important;
      color: white !important;
    }

    .sidebar .nav-item:hover .nav-link {
      background-color: rgba(255, 255, 255, 0.15) !important;
    }

    .sidebar .nav-item a.nav-link.text-danger,
    .sidebar .nav-item a.nav-link.text-danger .menu-icon {
      color: #ff4d4d !important;
    }
  </style>
</head>

<body>
  <div class="container-scroller">

    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex align-items-center justify-content-start pl-3" style="background-color: #004080;">
        <a class="navbar-brand brand-logo d-flex align-items-center" href="#">
          <span class="text-white font-weight-bold h5 mb-0">SMAN 1 Kota Sukabumi</span>
        </a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav navbar-nav-right d-flex align-items-center">
          <li class="nav-item d-flex align-items-center position-relative">
            <div class="notification-icon" onclick="toggleDropdown()">
              <img src="../../images/bell-icon.png" alt="Notifikasi">
              <?php if ($jumlah_notif > 0): ?>
                <div class="notification-badge"><?= $jumlah_notif ?></div>
              <?php endif; ?>
            </div>
            <div class="notification-dropdown" id="notifDropdown">
              <h6>Notifikasi</h6>
              <?php if ($daftar_notif): ?>
                <?php foreach ($daftar_notif as $notif): ?>
                  <div class="notif-item"><?= htmlspecialchars($notif['judul']) ?></div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="notif-item text-muted">Belum ada notifikasi</div>
              <?php endif; ?>
              <a href="../../notifikasi.php" class="notif-footer">Lihat Semua</a>
            </div>
          </li>
          <li class="nav-item d-flex align-items-center">
            <a class="nav-link nav-profile-icon" href="../../profil/index.php">
              <img src="../../images/profile.png?v=2" alt="Profil">
            </a>
          </li>
          <li class="nav-item d-flex align-items-center">
            <a class="nav-link nav-profile-icon" href="../../logout.php" onclick="return confirm('Yakin ingin logout?')">
              <img src="../../images/logout.png" alt="Logout">
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <div class="container-fluid page-body-wrapper">
      <?php include '../sidebar.php'; ?>

      <div class="main-panel">
  <div class="content-wrapper">
    <div class="row mb-4">
      <div class="col-md-12">
        <div class="bg-gradient-primary text-white p-4 rounded shadow">
          <h4>Tagihan & Riwayat Pembayaran</h4>
          <h2 class="mb-0"><?= $siswa['nama'] ?></h2>
        </div>
      </div>
    </div>

    <div class="card p-4 mb-4">
      <h5 class="mb-3">📌 Tagihan Aktif</h5>
      <?php
      $tagihan_aktif_siswa = [];
      foreach ($daftar_tagihan as $tagihan) {
        $sisa = getSisaBayar($conn, $nisn, $tagihan['nama_tagihan'], $tagihan['total_tagihan']);
     
        if ($sisa > 0) {
          $tagihan['sisa_bayar'] = $sisa;
          $tagihan_aktif_siswa[] = $tagihan;
        }
      }
      ?>
      <?php if ($tagihan_aktif_siswa): ?>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="thead-light">
              <tr>
                <th>Nama Tagihan</th>
                <th>Total Tagihan</th>
                <th>Sisa Bayar</th>
                <th>VA Number</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($tagihan_aktif_siswa as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['nama_tagihan']) ?></td>
                  <td>Rp<?= number_format($row['total_tagihan'], 0, ',', '.') ?></td>
                  <td>Rp<?= number_format($row['sisa_bayar'], 0, ',', '.') ?></td>
                  <td><?= generateVA($row['id_tagihan']) ?></td>
                  <td><a href="konfirmasi.php?id_tagihan=<?= $row['id_tagihan'] ?>" class="btn btn-primary btn-sm">Bayar Sekarang</a></td>

                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-success">🎉 Tidak ada tagihan aktif.</div>
      <?php endif; ?>
    </div>

    <div class="card p-4 mb-4">
      <h5 class="mb-3">💰 Riwayat Pembayaran</h5>
      <?php
$pembayaran_query = mysqli_query($conn, "
SELECT p.*, d.total_tagihan
FROM t_keuangan_pembayaran p
LEFT JOIN t_keuangan_daftar d ON p.jenis_tagihan = d.nama_tagihan
WHERE p.nis = '$nisn'
ORDER BY p.id DESC
");

      $riwayat = [];
      while ($row = mysqli_fetch_assoc($pembayaran_query)) {
        $riwayat[] = $row;
      }
      ?>
      <?php if ($riwayat): ?>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="thead-light">
              <tr>
                <th>Tanggal Pembayaran</th>
                <th>Nama Tagihan</th>
                <th>Total Tagihan</th>
                <th>Jumlah Dibayar</th>
                <th>Sisa Pembayaran</th>
                <th>Status</th>
                <th>Metode</th>
                <th>Bukti Pembayaran</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($riwayat as $row): 
    $sisa_pembayaran_histori = getSisaBayar($conn, $nisn, $row['jenis_tagihan']);
    $status_badge_class = '';
    $status_display_text = '';

    if (strtolower($row['status']) === 'lunas') {
        $status_badge_class = 'badge-success';
        $status_display_text = 'Terkonfirmasi';
    } elseif (strtolower($row['status']) === 'menunggu') {
        $status_badge_class = 'badge-warning';
        $status_display_text = 'Menunggu Konfirmasi';
    } else {
        $status_badge_class = 'badge-danger';
        $status_display_text = ucfirst($row['status']);
    }
?>

                <tr>
                  <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                  <td><?= htmlspecialchars($row['nama_tagihan']) ?></td>
                  <td>Rp<?= number_format($row['total_tagihan'], 0, ',', '.') ?></td>
                  <td>Rp<?= number_format($row['jml_bayar'], 0, ',', '.') ?></td>
                  <td>
                    <span class="badge badge-<?= ($sisa_pembayaran_histori == 0 ? 'success' : 'warning') ?>">
                      Rp<?= number_format($sisa_pembayaran_histori, 0, ',', '.') ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge <?= $status_badge_class ?>">
                      <?= $status_display_text ?>
                    </span>
                  </td>
                  <td><?= htmlspecialchars($row['metode']) ?: '-' ?></td>
                  <td>
                    <?php if (!empty($row['bukti_pembayaran'])): ?>
                      <a href="../../uploads/<?= htmlspecialchars($row['bukti_pembayaran']) ?>" target="_blank" class="btn btn-info btn-sm">Lihat Bukti</a>
                    <?php else: ?>
                      -
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-info">Belum ada riwayat pembayaran.</div>
      <?php endif; ?>
    </div>

    <footer class="footer mt-4">
      <div class="text-center">© SMAN 1 Kota Sukabumi 2025</div>
    </footer>
  </div>
</div>

    </div>
  </div>

  <script src="../../vendors/js/vendor.bundle.base.js"></script>
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/template.js"></script>
  <script>
    function toggleDropdown() {
      document.getElementById('notifDropdown').classList.toggle('d-block');
    }
    window.addEventListener('click', function(e) {
      const dropdown = document.getElementById('notifDropdown');
      const icon = document.querySelector('.notification-icon');
      if (!icon.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('d-block');
      }
    });
  </script>
</body>

</html>