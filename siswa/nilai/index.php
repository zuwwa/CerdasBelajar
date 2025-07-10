<?php 
session_start();
include '../../koneksi.php';

// Cek login siswa
if (!isset($_SESSION['email']) || $_SESSION['role'] != 2) {
    header("location:../index.php");
    exit;
}

// Ambil data siswa
$email = $_SESSION['email'];
$siswa_query = mysqli_query($conn, "SELECT * FROM siswa WHERE email = '$email'");
$siswa = mysqli_fetch_assoc($siswa_query);

if (!$siswa) {
  echo "<script>alert('Siswa tidak ditemukan.'); window.location='../../logout.php';</script>";
  exit;
}

$siswa_id = $siswa['id'];

// Ambil notifikasi
$jumlah_notif = 0;
$daftar_notif = [];
$notif_query = mysqli_query($conn, "SELECT * FROM notifikasi WHERE siswa_id = '$siswa_id' ORDER BY waktu DESC LIMIT 5");
$jumlah_notif = mysqli_num_rows($notif_query);
while ($row = mysqli_fetch_assoc($notif_query)) {
  $daftar_notif[] = $row;
}

// Ambil nilai dari tabel penilaian + mapel
$nilai_query = mysqli_query($conn, "
  SELECT p.*, m.nama_mapel
  FROM penilaian p
  LEFT JOIN mapel m ON p.id_mapel = m.id
  WHERE p.id_siswa = '$siswa_id'
  ORDER BY p.semester ASC
");
$daftar_nilai = [];
while ($n = mysqli_fetch_assoc($nilai_query)) {
  $daftar_nilai[] = $n;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Nilai Akademik - CerdasBelajar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../vendors/typicons.font/font/typicons.css" />
  <link rel="stylesheet" href="../css/vertical-layout-light/style.css" />
  <link rel="shortcut icon" href="../images/sma.png" />
  <style>
    .notification-icon {
  width: 30px;
  height: 30px;
  cursor: pointer;
  position: relative;
}
.notification-icon img {
  width: 90%;
  display: block;
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
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  border-radius: 6px;
  display: none;
  z-index: 999;
}
.notification-dropdown.d-block {
  display: block;
}
.notification-dropdown h6 {
  padding: 10px 15px;
  border-bottom: 1px solid #ddd;
  margin: 0;
  font-weight: bold;
}
.notification-dropdown .notif-item {
  padding: 10px 15px;
  font-size: 14px;
  border-bottom: 1px solid #eee;
  color: #333;
}
.notification-dropdown .notif-item:hover {
  background-color: #f5f5f5;
}
.notification-dropdown .notif-footer {
  display: block;
  text-align: center;
  padding: 10px;
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

    .bg-gradient-primary {
      background: linear-gradient(90deg, rgb(2, 40, 122), rgb(27, 127, 219));
    }
    .table th, .table td {
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

  <!-- ✅ NAVBAR -->
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="navbar-brand-wrapper d-flex align-items-center justify-content-start pl-3" style="background-color: #004080;">
    <a class="navbar-brand brand-logo d-flex align-items-center" href="#">
      <span class="text-white font-weight-bold h5 mb-0">SMAN 1 Kota Sukabumi</span>
    </a>
  </div>
  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
    <ul class="navbar-nav navbar-nav-right d-flex align-items-center">

      <!-- 🔔 NOTIFIKASI -->
      <li class="nav-item d-flex align-items-center position-relative">
        <div class="notification-icon" onclick="toggleDropdown()">
          <img src="../images/bell-icon.png" alt="Notifikasi">
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
          <a href="../notifikasi.php" class="notif-footer">Lihat Semua</a>
        </div>
      </li>

      <!-- 👤 PROFIL -->
      <li class="nav-item d-flex align-items-center">
        <a class="nav-link nav-profile-icon" href="../profil/index.php">
          <img src="../images/profile.png?v=2" alt="Profile">
        </a>
      </li>

      <!-- 🚪 LOGOUT -->
      <li class="nav-item d-flex align-items-center">
        <a class="nav-link nav-profile-icon" href="../../logout.php" onclick="return confirm('Yakin ingin logout?')">
          <img src="../images/logout.png" alt="Logout">
        </a>
      </li>

    </ul>
  </div>
</nav>


  <div class="container-fluid page-body-wrapper">
    <!-- SIDEBAR -->
    <?php include '../sidebar.php'; ?>

    <!-- MAIN -->
    <div class="main-panel">
      <div class="content-wrapper">

        <!-- Header -->
        <div class="row mb-4">
          <div class="col-md-12">
            <div class="bg-gradient-primary text-white p-4 rounded shadow">
              <h4>Nilai Akademik</h4>
              <h2 class="mb-0"><?= $siswa['nama'] ?></h2>
            </div>
          </div>
        </div>

        <!-- Tabel Nilai -->
        <div class="card p-4">
          <h5 class="mb-3">📊 Daftar Nilai</h5>
          <?php if (count($daftar_nilai) > 0): ?>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead class="thead-light">
                  <tr>
                    <th>No</th>
                    <th>Mata Pelajaran</th>
                    <th>Semester</th>
                    <th>Disiplin</th>
                    <th>Kehadiran</th>
                    <th>Sikap</th>
                    <th>Tugas</th>
                    <th>UTS</th>
                    <th>UAS</th>
                    <th>Evaluasi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $no = 1; foreach ($daftar_nilai as $n): ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= htmlspecialchars($n['nama_mapel']) ?></td>
                      <td><?= htmlspecialchars($n['semester']) ?></td>
                      <td><?= $n['kedisiplinan'] ?></td>
                      <td><?= $n['kehadiran'] ?></td>
                      <td><?= $n['sikap'] ?></td>
                      <td><?= $n['tugas'] ?></td>
                      <td><?= $n['uts'] ?></td>
                      <td><?= $n['uas'] ?></td>
                      <td><?= $n['evaluasi'] ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="alert alert-info">Belum ada nilai yang tersedia.</div>
          <?php endif; ?>
        </div>

        <!-- Footer -->
        <footer class="footer mt-4">
          <div class="text-center">Copyright © SMAN 1 Kota Sukabumi 2025</div>
        </footer>
      </div>
    </div>
  </div>
</div>

<!-- JS -->
<script src="../vendors/js/vendor.bundle.base.js"></script>
<script src="../js/off-canvas.js"></script>
<script src="../js/template.js"></script>
<script>
function toggleDropdown() {
  document.getElementById('notifDropdown').classList.toggle('active');
}
window.addEventListener('click', function(e) {
  const dropdown = document.getElementById('notifDropdown');
  const icon = document.querySelector('.notification-icon');
  if (!icon.contains(e.target) && !dropdown.contains(e.target)) {
    dropdown.classList.remove('active');
  }
});
</script>
</body>
</html>
