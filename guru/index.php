<?php 
session_start();
include '../koneksi.php';

// Cek login guru
if (!isset($_SESSION['email']) || $_SESSION['role'] != 3) {
    header("location:../index.php");
    exit;
}

// Ambil data guru
$email = $_SESSION['email'];
$query = mysqli_query($conn, "SELECT * FROM t_guru WHERE email = '$email'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Guru tidak ditemukan.'); window.location='../logout.php';</script>";
    exit;
}

$guru_id = $data['id'];

// Ambil notifikasi untuk guru
$jumlah_notif = 0;
$daftar_notif = [];
$notif_query = mysqli_query($conn, "SELECT * FROM notifikasi WHERE guru_id = '$guru_id' ORDER BY waktu DESC LIMIT 5");
$jumlah_notif = mysqli_num_rows($notif_query);
while ($row = mysqli_fetch_assoc($notif_query)) {
    $daftar_notif[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Guru - SMAN 1 Sukabumi</title>
  <link rel="stylesheet" href="vendors/typicons.font/font/typicons.css" />
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css" />
  <link rel="stylesheet" href="css/vertical-layout-light/style.css" />
  <link rel="shortcut icon" href="images/sma.png" />
  <style>
    .navbar-menu-wrapper { background-color: #004080 !important; }
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
      background-color: white;
      width: 280px;
      box-shadow: 0 4px 8px rgba(199, 10, 10, 0.1);
      border-radius: 6px;
      display: none;
      z-index: 1000;
    }
    .notification-dropdown.active { display: block; }
    .notification-dropdown h6, .notif-item, .notif-footer {
      padding: 10px 15px;
    }
    .notif-footer {
      text-align: center;
      font-weight: bold;
      color: crimson;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="container-scroller">
    <!-- NAVBAR -->
    <nav class="navbar fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex align-items-center justify-content-start" style="background-color: #004080;">
        <a class="navbar-brand brand-logo text-white font-weight-bold h5 mb-0" href="#">SMAN 1 Kota Sukabumi</a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav">
          <!-- NOTIFIKASI -->
          <li class="notification-wrapper position-relative">
            <div class="notification-icon" onclick="toggleDropdown()">
              <img src="images/bell-icon.png" alt="Notifikasi" style="width:28px">
              <?php if ($jumlah_notif > 0): ?>
                <div class="notification-badge"><?= $jumlah_notif ?></div>
              <?php endif; ?>
            </div>
            <div class="notification-dropdown" id="notifDropdown">
              <h6>Notifikasi</h6>
              <?php if (count($daftar_notif) > 0): ?>
                <?php foreach ($daftar_notif as $notif): ?>
                  <div class="notif-item"><?= htmlspecialchars($notif['judul']) ?></div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="notif-item text-muted">Belum ada notifikasi</div>
              <?php endif; ?>
              <a href="notifikasi.php" class="notif-footer">Lihat Semua</a>
            </div>
          </li>

          <!-- PROFIL -->
          <li class="nav-item nav-profile-icon">
            <a class="nav-link" href="profil/index.php">
              <img src="images/profile.png?v=2" alt="Profil" style="width:24px;height:24px">
            </a>
          </li>

          <!-- LOGOUT -->
          <li class="nav-item nav-profile-icon">
            <a class="nav-link" href="logout.php" onclick="return confirm('Yakin ingin logout?')">
              <img src="images/logout.png" alt="Logout" style="width:24px;height:24px">
            </a>
          </li>
        </ul>
      </div>
    </nav>

    <div class="container-fluid page-body-wrapper">
      <!-- SIDEBAR -->
      <?php include "sidebar.php"; ?>

      <!-- MAIN PANEL -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row mb-4">
            <div class="col-md-12">
              <div class="text-white p-4 rounded shadow" style="background: linear-gradient(90deg, rgb(2, 40, 122), rgb(27, 127, 219));">
                <h4>Selamat Datang 👋</h4>
                <h2 class="mb-0"><?= isset($_SESSION['username']) ? $_SESSION['username'] : 'Guru'; ?></h2>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- MANAJEMEN MAPEL -->
            <div class="col-md-3 mb-4">
              <a href="mapel/" class="text-decoration-none text-dark">
                <div class="card text-center p-3 shadow-sm">
                  <i class="typcn typcn-book display-4 text-primary"></i>
                  <h6>Daftar Mapel Saya</h6>
                </div>
              </a>
            </div>

            <!-- PENILAIAN -->
            <div class="col-md-3 mb-4">
              <a href="penilaian/" class="text-decoration-none text-dark">
                <div class="card text-center p-3 shadow-sm">
                  <i class="typcn typcn-edit display-4 text-success"></i>
                  <h6>Input Nilai</h6>
                </div>
              </a>
            </div>

            <!-- AGENDA -->
            <div class="col-md-3 mb-4">
              <a href="agenda/" class="text-decoration-none text-dark">
                <div class="card text-center p-3 shadow-sm">
                  <i class="typcn typcn-calendar display-4 text-warning"></i>
                  <h6>Agenda Guru</h6>
                </div>
              </a>
            </div>
          </div>
        </div>

        <!-- FOOTER -->
        <footer class="footer mt-4">
          <div class="text-center">Copyright © SMAN 1 Kota Sukabumi 2025</div>
        </footer>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="js/off-canvas.js"></script>
  <script src="js/template.js"></script>
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
