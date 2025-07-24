<?php 
session_start();
include '../koneksi.php';

// Cek login kepala sekolah
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'kepsek') {
    echo "<script>alert('⛔ Akses ditolak! Halaman ini hanya untuk kepsek'); window.location='../logout.php';</script>";
    exit;
}

// Ambil data kepala sekolah
$email = $_SESSION['email'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data kepala sekolah tidak ditemukan.'); window.location='../logout.php';</script>";
    exit;
}

$kepsek_id = $data['id'];

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
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>SMAN 1 SUKABUMI</title>
  <link rel="stylesheet" href="../vendors/typicons.font/font/typicons.css" />
  <link rel="stylesheet" href="../vendors/css/vendor.bundle.base.css" />
  <link rel="stylesheet" href="../css/vertical-layout-light/style.css" />
  <link rel="shortcut icon" href="images/sma.png" />
  <style>
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }

    .container-scroller {
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    .card {
    pointer-events: auto; /* <-- jangan none */
    cursor: pointer;
  }


    .page-body-wrapper {
      height: 100%;
      display: flex;
    }

    .main-panel {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .content-wrapper {
      flex-grow: 1;
      overflow-y: auto;
      padding: 2rem;
    }

    .navbar-menu-wrapper {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      background-color: #004080 !important;
      padding: 0 20px;
      height: 70px;
    }

    .navbar-nav {
      display: flex;
      align-items: center;
      gap: 20px;
      margin: 0;
      padding: 0;
      list-style: none;
    }

    .notification-wrapper {
      position: relative;
    }

    .notification-icon {
      width: 30px;
      height: 30px;
      cursor: pointer;
      position: relative;
    }

    .notification-icon img {
      width: 90%;
      height: auto;
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
      background-color: white;
      width: 280px;
      box-shadow: 0 4px 8px rgba(199, 10, 10, 0.1);
      border-radius: 6px;
      display: none;
      z-index: 1000;
    }

    .notification-dropdown.active {
      display: block;
    }

    .notification-dropdown h6 {
      padding: 10px 15px;
      margin: 0;
      border-bottom: 1px solid #ddd;
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
      text-align: center;
      padding: 10px;
      font-weight: bold;
      color: crimson;
      cursor: pointer;
    }

    .nav-profile-icon img {
      width: 24px;
      height: 24px;
    }

    .nav-profile-icon {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .sidebar .nav,
    .sidebar .nav-item,
    .sidebar .nav-link {
      background-color: transparent !important;
      color: white !important;
    }

    .sidebar .nav-item:hover .nav-link {
      background-color: rgba(255, 255, 255, 0.15) !important;
      color: white !important;
    }
    .sidebar .nav-item a.nav-link.text-danger,
    .sidebar .nav-item a.nav-link.text-danger .menu-icon {
      color: #ff4d4d !important;
    }
  </style>
</head>
<body>
  <div class="container-scroller">
    <!-- NAVBAR -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex align-items-center justify-content-start pl-3" style="background-color: #004080;">
        <a class="navbar-brand brand-logo d-flex align-items-center" href="#">
          <span class="text-white font-weight-bold h5 mb-0">SMAN 1 Kota Sukabumi</span>
        </a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav">
          <!-- NOTIFIKASI -->
          <li class="notification-wrapper">
            <div class="notification-icon" onclick="toggleDropdown()">
              <img src="../images/bell-icon.png" alt="Notifikasi">
              <?php if ($jumlah_notif > 0): ?>
                <div class="notification-badge"><?= $jumlah_notif ?></div>
              <?php endif; ?>
            </div>
            <div class="notification-dropdown" id="notifDropdown">
              <h6>Notifikasi</h6>
              <?php if (count($daftar_notif) > 0): ?>
                <?php foreach ($daftar_notif as $notif): ?>
                  <div class="notif-item">
                    <?= htmlspecialchars($notif['judul']) ?>
                  </div>
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
              <img src="../images/profile.png?v=2" alt="Profil">
            </a>
          </li>

          <!-- LOGOUT -->
          <li class="nav-item nav-profile-icon">
            <a class="nav-link" href="logout.php" onclick="return confirm('Yakin ingin logout?')">
              <img src="../images/logout.png" alt="Logout">
            </a>
          </li>
        </ul>
      </div>
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
                <h4>Selamat Datang Kepala Sekolah 👋</h4>
                <h2 class="mb-0"><?= isset($_SESSION['username']) ? $_SESSION['username'] : 'Kepala Sekolah'; ?></h2>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- DATA GURU -->
            <div class="col-md-3 mb-4">
              <a href="guru/" class="text-decoration-none text-dark">
                <div class="card text-center p-3 shadow-sm">
                  <i class="typcn typcn-user display-4 text-primary"></i>
                  <h6>Data Guru</h6>
                </div>
              </a>
            </div>

            <!-- DATA SISWA -->
            <div class="col-md-3 mb-4">
              <a href="siswa/" class="text-decoration-none text-dark">
                <div class="card text-center p-3 shadow-sm">
                  <i class="typcn typcn-group display-4 text-success"></i>
                  <h6>Data Siswa</h6>
                </div>
              </a>
            </div>

            <!-- LAPORAN NILAI -->
            <div class="col-md-3 mb-4">
              <a href="nilai/" class="text-decoration-none text-dark">
                <div class="card text-center p-3 shadow-sm">
                  <i class="typcn typcn-chart-bar display-4 text-warning"></i>
                  <h6>Grafik Nilai</h6>
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
