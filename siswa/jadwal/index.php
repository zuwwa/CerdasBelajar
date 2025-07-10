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
$query = mysqli_query($conn, "SELECT * FROM siswa WHERE email = '$email'");
$siswa = mysqli_fetch_assoc($query);

if (!$siswa) {
  echo "<script>alert('❌ Siswa tidak ditemukan.'); window.location='../../logout.php';</script>";
  exit;
}

$siswa_id = $siswa['id'];
$kelas_id = $siswa['kelas_id'];

// Ambil notifikasi
$jumlah_notif = 0;
$daftar_notif = [];
$notif_query = mysqli_query($conn, "SELECT * FROM notifikasi WHERE siswa_id = '$siswa_id' ORDER BY waktu DESC LIMIT 5");
$jumlah_notif = mysqli_num_rows($notif_query);
while ($row = mysqli_fetch_assoc($notif_query)) {
  $daftar_notif[] = $row;
}

// Ambil jadwal pelajaran berdasarkan kelas
$jadwal_query = mysqli_query($conn, "
  SELECT j.*, m.nama_mapel, g.nama AS nama_guru
  FROM jadwal j
  LEFT JOIN mapel m ON j.mapel_id = m.id
  LEFT JOIN guru g ON m.guru_id = g.id
  WHERE j.kelas_id = '$kelas_id'
  ORDER BY FIELD(j.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), j.jam_mulai
");

$jadwal_list = [];
while ($row = mysqli_fetch_assoc($jadwal_query)) {
  $jadwal_list[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Jadwal Pelajaran - CerdasBelajar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../vendors/typicons.font/font/typicons.css">
  <link rel="stylesheet" href="../css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="../images/sma.png">
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
      margin: 0;
      font-weight: bold;
      border-bottom: 1px solid #ddd;
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
    .table th, .table td {
      vertical-align: middle !important;
    }
    .nav-profile-icon img {
      width: 23px;
      margin-right: 15px;
    }
    .navbar-menu-wrapper {
      background-color: #004080 !important;
      border-bottom: 1px solid #003366 !important;
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
  <!-- Navbar -->
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
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link nav-profile-icon" href="../profil/index.php">
            <img src="../images/profile.png?v=2" alt="Profil">
          </a>
        </li>
        <li class="nav-item d-flex align-items-center">
          <a class="nav-link nav-profile-icon" href="../../logout.php" onclick="return confirm('Yakin ingin logout?')">
            <img src="../images/logout.png" alt="Logout">
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
              <h4>Jadwal Pelajaran</h4>
              <h2 class="mb-0"><?= $siswa['nama'] ?></h2>
            </div>
          </div>
        </div>

        <!-- Tabel Jadwal -->
        <div class="card p-4">
          <h5 class="mb-3">📅 Jadwal Mingguan</h5>
          <?php if ($jadwal_list): ?>
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Ruang</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($jadwal_list as $j): ?>
                    <tr>
                      <td><?= $j['hari'] ?></td>
                      <td><?= substr($j['jam_mulai'], 0, 5) ?> - <?= substr($j['jam_selesai'], 0, 5) ?></td>
                      <td><?= $j['nama_mapel'] ?></td>
                      <td><?= $j['nama_guru'] ?></td>
                      <td><?= $j['ruang'] ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="alert alert-info">Jadwal belum tersedia.</div>
          <?php endif; ?>
        </div>

        <footer class="footer mt-4">
          <div class="text-center">© SMAN 1 Kota Sukabumi 2025</div>
        </footer>
      </div>
    </div>
  </div>
</div>

<script src="../vendors/js/vendor.bundle.base.js"></script>
<script src="../js/off-canvas.js"></script>
<script src="../js/template.js"></script>
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
