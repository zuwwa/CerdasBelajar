<?php 
session_start();
include '../koneksi.php';

// Cek login admin
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('⛔ Akses ditolak! Halaman ini hanya untuk admin.'); window.location='../logout.php';</script>";
    exit;
}


// Ambil data admin
$email = $_SESSION['email'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' AND type = 'admin'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Administrator tidak ditemukan.'); window.location='../logout.php';</script>";
    exit;
}

// Notifikasi
$jumlah_notif = 0;
$daftar_notif = [];
$notif_query = mysqli_query($conn, "SELECT * FROM notifikasi ORDER BY waktu DESC LIMIT 5");
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
  <title>Dashboard Admin - SMAN 1 Sukabumi</title>
  <link rel="stylesheet" href="../vendors/typicons.font/font/typicons.css" />
  <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css" />
  <link rel="stylesheet" href="../css/vertical-layout-light/style.css" />
  <link rel="shortcut icon" href="../images/sma.png" />
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
    <!-- NAVBAR ADMIN RAPAT -->
<nav class="navbar fixed-top d-flex justify-content-between align-items-center px-3" style="background-color: #004080; height: 60px;">
  <!-- Kiri: Logo + Nama Sekolah -->
  <div class="d-flex align-items-center">
    <span class="text-white font-weight-bold" style="font-size: 16px;">SMAN 1 Kota Sukabumi</span>
  </div>

  <!-- Kanan: Notifikasi, Profil, Logout -->
  <ul class="navbar-nav d-flex flex-row align-items-center m-0">
    <!-- Notifikasi -->
    <li class="nav-item position-relative mx-2" style="cursor: pointer;" onclick="toggleDropdown()">
      <img src="../images/bell-icon.png" alt="Notifikasi" style="width: 24px;">
      <?php if ($jumlah_notif > 0): ?>
        <span class="notification-badge"><?= $jumlah_notif ?></span>
      <?php endif; ?>
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

    <!-- Profil -->
    <li class="nav-item mx-2">
      <a class="nav-link p-0" href="profil/index.php" title="Profil">
        <img src="../images/profile.png?v=2" alt="Profil" style="width: 24px; height: 24px;">
      </a>
    </li>

    <!-- Logout -->
    <li class="nav-item mx-2">
      <a class="nav-link p-0" href="logout.php" title="Logout" onclick="return confirm('Yakin ingin logout?')">
        <img src="../images/logout.png" alt="Logout" style="width: 24px; height: 24px;">
      </a>
    </li>
  </ul>
</nav>


    <div class="container-fluid page-body-wrapper">
      <!-- SIDEBAR -->
      <?php include "sidesbar.php"; ?>


      <!-- MAIN PANEL -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row mb-4">
            <div class="col-md-12">
              <div class="text-white p-4 rounded shadow" style="background: linear-gradient(90deg, rgb(2, 40, 122), rgb(27, 127, 219));">
                <h4>Selamat Datang 👋</h4>
                <h2 class="mb-0"><?= isset($_SESSION['username']) ? $_SESSION['username'] : 'Administrator'; ?></h2>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- KARTU MENU ADMIN -->
            <div class="col-md-3 mb-4">
              <a href="users/" class="text-decoration-none text-dark">
                <div class="card text-center p-3 shadow-sm">
                  <i class="typcn typcn-group display-4 text-primary"></i>
                  <h6>Manajemen Pengguna</h6>
                </div>
              </a>
            </div>

            <div class="col-md-3 mb-4">
              <a href="mata-pelajaran/" class="text-decoration-none text-dark">
                <div class="card text-center p-3 shadow-sm">
                  <i class="typcn typcn-book display-4 text-success"></i>
                  <h6>Mata Pelajaran</h6>
                </div>
              </a>
            </div>

            <div class="col-md-3 mb-4">
              <a href="kelas/" class="text-decoration-none text-dark">
                <div class="card text-center p-3 shadow-sm">
                  <i class="typcn typcn-home display-4 text-warning"></i>
                  <h6>Manajemen Kelas</h6>
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
