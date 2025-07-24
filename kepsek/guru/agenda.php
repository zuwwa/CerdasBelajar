<?php
session_start();
include '../../koneksi.php';

// Cek login kepala sekolah
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'kepsek') {
  echo "<script>alert('⛔ Akses ditolak! Halaman ini hanya untuk Kepala Sekolah.'); window.location='../../logout.php';</script>";
  exit;
}

// Ambil data agenda guru dari tabel agenda_guru langsung
$data_agenda = [];
$query = "SELECT * FROM agenda_guru ORDER BY tanggal DESC";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $data_agenda[] = $row;
  }
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
  <meta charset="UTF-8">
  <title>Agenda Kegiatan Sekolah - CerdasBelajar</title>
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

    .table-responsive {
      margin-top: 20px;
    }

    .card-title {
      color: #333;
      font-weight: 600;
    }

    .card-description {
      color: #666;
      margin-bottom: 20px;
    }

    .table th,
    .table td {
      vertical-align: middle;
    }

    .table-striped tbody tr:nth-of-type(odd) {
      background-color: rgba(0, 0, 0, 0.03);
    }

    .table-hover tbody tr:hover {
      background-color: rgba(0, 0, 0, 0.075);
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
              <a href="../notifikasi.php" class="notif-footer">Lihat Semua</a>
            </div>
          </li>
          <li class="nav-item d-flex align-items-center">
            <a class="nav-link nav-profile-icon" href="../profil/index.php">
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
      <?php include '../sidesbar.php'; ?>
      <!-- MAIN PANEL -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Data Agenda Guru</h4>
                  <p class="card-description">
                    Daftar Agenda guru yang terdaftar di SMAN 1 Sukabumi.
                  </p>
                  <div class="d-flex justify-content-start align-items-center mb-4">
                    <a href="index.php" class="btn btn-primary mr-2">
                      <i class="typcn typcn-calendar-outline"></i> Data Guru
                    </a>
                    <a href="penilaian.php" class="btn btn-success">
                      <i class="typcn typcn-clipboard"></i> Kelola Penilaian
                    </a>
                  </div>
                  <div class="table-responsive">
                  <table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>No</th>
      <th>Tanggal</th>
      <th>Pertemuan</th>
      <th>Materi</th>
      <th>Sub Materi</th>
      <th>Nama Guru</th>
      <th>Kelas</th>
      <th>Mata Pelajaran</th>
      <th>NUPTK</th>
      <th>Jam</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($data_agenda)) : ?>
      <?php $no = 1; foreach ($data_agenda as $row) : ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($row['tanggal']) ?></td>
          <td><?= $row['pertemuan'] ?></td>
          <td><?= htmlspecialchars($row['materi']) ?></td>
          <td><?= nl2br(htmlspecialchars($row['sub_materi'])) ?></td>
          <td><?= htmlspecialchars($row['nama_guru']) ?></td>
          <td><?= htmlspecialchars($row['kelas']) ?></td>
          <td><?= htmlspecialchars($row['mata_pelajaran']) ?></td>
          <td><?= htmlspecialchars($row['nuptk']) ?></td>
          <td><?= htmlspecialchars($row['jam']) ?></td>
        </tr>
      <?php endforeach; ?>
    <?php else : ?>
      <tr>
        <td colspan="10" class="text-center text-muted">Belum ada agenda guru.</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
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