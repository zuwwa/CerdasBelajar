<?php 
session_start();
include '../koneksi.php';

// Cek login orang tua
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'ortu') {
    echo "<script>alert('⛔ Akses ditolak! Halaman ini hanya untuk orang tua siswa.'); window.location='../logout.php';</script>";
    exit;
}

// Ambil data orang tua
$email = $_SESSION['email'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$data_ortu = mysqli_fetch_assoc($query);

if (!$data_ortu) {
    echo "<script>alert('Data orang tua tidak ditemukan.'); window.location='../logout.php';</script>";
    exit;
}

$ortu_id = $data_ortu['id'];

// Ambil data anak
$anak_query = mysqli_query($conn, "SELECT * FROM t_siswa WHERE id_ortu = '$ortu_id'");
$anak = mysqli_fetch_assoc($anak_query);

// Notifikasi untuk orang tua
$jumlah_notif = 0;
$daftar_notif = [];
$notif_query = mysqli_query($conn, "SELECT * FROM notifikasi WHERE untuk_role = 'ortu' ORDER BY waktu DESC LIMIT 5");
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
  <title>Dashboard Orang Tua - SMAN 1 Sukabumi</title>
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
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
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
                <h4>Selamat Datang Orang Tua 👨‍👩‍👧</h4>
                <h5 class="mb-0"><?= $_SESSION['username'] ?></h5>
              </div>
            </div>
          </div>

          <!-- INFO ANAK -->
          <?php if ($anak): ?>
            <div class="row">
              <div class="col-md-4 mb-3">
                <div class="card shadow-sm p-3">
                  <h6>👦 Nama Anak</h6>
                  <p class="mb-0"><?= $anak['nama'] ?></p>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="card shadow-sm p-3">
                  <h6>🏫 Kelas</h6>
                  <p class="mb-0"><?= $anak['kelas'] ?></p>
                </div>
              </div>
              <div class="col-md-4 mb-3">
                <div class="card shadow-sm p-3">
                  <h6>📊 Status Kehadiran</h6>
                  <p class="mb-0">Lihat detail di bawah</p>
                </div>
              </div>
            </div>

            <div class="row">
              <!-- Nilai -->
              <div class="col-md-4 mb-4">
                <a href="nilai.php?id=<?= $anak['id'] ?>" class="text-decoration-none text-dark">
                  <div class="card text-center p-3 shadow-sm">
                    <i class="typcn typcn-chart-bar display-4 text-success"></i>
                    <h6>Nilai Akademik</h6>
                  </div>
                </a>
              </div>

              <!-- Absensi -->
              <div class="col-md-4 mb-4">
                <a href="absensi.php?id=<?= $anak['id'] ?>" class="text-decoration-none text-dark">
                  <div class="card text-center p-3 shadow-sm">
                    <i class="typcn typcn-calendar display-4 text-warning"></i>
                    <h6>Kehadiran</h6>
                  </div>
                </a>
              </div>

              <!-- Detail Anak -->
              <div class="col-md-4 mb-4">
                <a href="profil-anak.php?id=<?= $anak['id'] ?>" class="text-decoration-none text-dark">
                  <div class="card text-center p-3 shadow-sm">
                    <i class="typcn typcn-user display-4 text-primary"></i>
                    <h6>Profil Anak</h6>
                  </div>
                </a>
              </div>
            </div>
          <?php else: ?>
            <div class="alert alert-warning">Belum ada data anak terhubung dengan akun ini.</div>
          <?php endif; ?>
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
