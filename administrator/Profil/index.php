<?php
session_start();
include('../../koneksi.php');

// Cek login dan role admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
  echo "<script>alert('⛔ Akses ditolak. Silakan login sebagai admin.'); window.location='../../logout.php';</script>";
  exit();
}

// Ambil data siswa berdasarkan email
$email = strtolower(trim($_SESSION['email']));
$query = mysqli_query($conn, "SELECT * FROM t_siswa WHERE LOWER(email) = '$email'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profil Siswa - SMAN 1 Kota Sukabumi</title>
  <link rel="stylesheet" href="../vendors/typicons.font/font/typicons.css" />
  <link rel="stylesheet" href="../css/vertical-layout-light/style.css" />
  <link rel="shortcut icon" href="../images/sma.png" />
  <style>
    .content-wrapper {
      padding: 2rem;
    }
    .bg-gradient-primary {
      background: linear-gradient(90deg,rgb(2, 40, 122),rgb(27, 127, 219));
    }
    .notification-icon {
      margin-right: 10px;
      position: relative;
    }
    .notification-icon img {
      width: 23px;
    }
    .notification-icon .badge {
      position: absolute;
      top: 5px;
      left: -8px;
    }
    .nav-profile-icon img {
      width: 23px;
      margin-right: 15px;
    }
    .navbar-brand img {
      height: 100px;
      object-fit: contain;
    }
    .navbar-menu-wrapper {
      background-color: #004080 !important; /* biru tua sesuai logo */
      border-bottom: 1px solid #003366 !important;
    }


  </style>
</head>
<body>
<div class="container-scroller">
   <!-- NAVBAR -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
  <div class="navbar-brand-wrapper d-flex align-items-center justify-content-start pl-3" style="background-color: #004080;">
    <a class="navbar-brand brand-logo d-flex align-items-center" href="#">
      <img src="../images/sma.png" alt="logo" style="height: 60px; object-fit: contain; margin-right: 10px;" />
      <span class="text-white font-weight-bold h5 mb-0">SMAN 1 Kota Sukabumi</span>
    </a>
    <a class="navbar-brand brand-logo-mini d-none" href="#">
      <img src="../images/sma.png" alt="logo mini" style="height: 30px;" />
    </a>
    <button class="navbar-toggler navbar-toggler align-self-center d-none d-lg-flex ml-3" type="button" data-toggle="minimize">
      <span class="typcn typcn-th-menu text-white"></span>
    </button>
  </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav navbar-nav-right d-flex align-items-center">
          <li class="nav-item d-flex align-items-center">
            <a class="nav-link notification-icon position-relative" href="#">
              <img src="../images/bell-icon.png" alt="Notifikasi">
              <span class="badge badge-pill badge-danger">3</span>
            </a>
          </li>
          <li class="nav-item d-flex align-items-center">
            <a class="nav-link nav-profile-icon" href="#">
              <img src="../images/profile.png?v=2" alt="Profile">
            </a>
          </li>
          <li class="nav-item d-flex align-items-center">
            <a class="nav-link nav-profile-icon" href="logout.php" onclick="return confirm('Yakin ingin logout?')">
              <img src="../images/logout.png" alt="Logout">
            </a>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="typcn typcn-th-menu"></span>
        </button>
      </div>
    </nav>


  <div class="container-fluid page-body-wrapper">
    <!-- SIDEBAR -->
   <?php include 'sidebar.php'; ?>
    <!-- MAIN -->
    <div class="main-panel">
      <div class="content-wrapper" style="padding: 2rem;">
        <div class="row mb-4">
          <div class="col-md-12">
            <div style="background: linear-gradient(90deg, rgb(2, 40, 122), rgb(27, 127, 219)); color: white; padding: 2rem; border-radius: 10px;">
              <h4>Profil Siswa</h4>
              <h2 class="font-weight-bold mb-0"><?= $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?></h2>
            </div>
          </div>
        </div>

        <!-- PROFIL -->
        <div class="row">
          <div class="col-md-8">
            <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 8px rgba(0,0,0,0.05);">
              <h4>Data Profil</h4>
              <table class="mt-3" style="width: 100%; border-collapse: collapse;">
                <tr><th style="text-align: left; padding: 8px;">NISN</th><td><?= $data['nisn']; ?></td></tr>
                <tr><th style="text-align: left; padding: 8px;">Nama</th><td><?= $data['nama']; ?></td></tr>
                <tr><th style="text-align: left; padding: 8px;">Tempat Lahir</th><td><?= $data['tempat_lahir']; ?></td></tr>
                <tr><th style="text-align: left; padding: 8px;">Tanggal Lahir</th><td><?= $data['tanggal_lahir']; ?></td></tr>
                <tr><th style="text-align: left; padding: 8px;">Jenis Kelamin</th><td><?= $data['jenis_kelamin']; ?></td></tr>
                <tr><th style="text-align: left; padding: 8px;">Agama</th><td><?= $data['agama']; ?></td></tr>
                <tr><th style="text-align: left; padding: 8px;">Alamat</th><td><?= $data['alamat']; ?></td></tr>
                <tr><th style="text-align: left; padding: 8px;">No Telepon</th><td><?= $data['no_telepon']; ?></td></tr>
                <tr><th style="text-align: left; padding: 8px;">Email</th><td><?= $data['email']; ?></td></tr>
              </table>
            </div>
          </div>
        </div>

        <footer class="footer mt-5">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-center text-sm-left d-block d-sm-inline-block">
              Copyright © <a href="#">SMAN 1 Kota Sukabumi</a> 2025
            </span>
          </div>
        </footer>
      </div>
    </div>
  </div>
</div>

<!-- JS -->
<script src="../../vendors/js/vendor.bundle.base.js"></script>
<script src="../../js/off-canvas.js"></script>
<script src="../../js/template.js"></script>
</body>
</html>
