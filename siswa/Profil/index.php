<?php 
session_start();
include '../../koneksi.php';

// Cek login siswa
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../logout.php");
    exit;
}

// ✅ Ambil data siswa
$email = strtolower(trim($_SESSION['email']));
$query = mysqli_query($conn, "SELECT * FROM siswa WHERE email = '$email'");
$data = mysqli_fetch_assoc($query);
if (!$data) {
  echo "<script>alert('❌ Data siswa tidak ditemukan.'); window.location='../../logout.php';</script>";
  exit();
}

$nisn = $data['nisn'];
$query_foto = mysqli_query($conn, "SELECT foto FROM t_siswa WHERE nis = '$nisn' LIMIT 1");
$data_foto = mysqli_fetch_assoc($query_foto);
$foto = $data_foto['foto'] ?? '';


// ✅ Ambil notifikasi terbaru
$siswa_id = $_SESSION['id_user'] ?? null;
$jumlah_notif = 0;
$daftar_notif = [];

if ($siswa_id) {
  $notif_query = mysqli_query($conn, "SELECT * FROM notifikasi WHERE siswa_id = '$siswa_id' ORDER BY waktu DESC LIMIT 5");
  $jumlah_notif = mysqli_num_rows($notif_query);
  while ($row = mysqli_fetch_assoc($notif_query)) {
    $daftar_notif[] = $row;
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profil Siswa - SMAN 1 Kota Sukabumi</title>
  <link rel="stylesheet" href="../../vendors/typicons.font/font/typicons.css" />
  <link rel="stylesheet" href="../../css/vertical-layout-light/style.css" />
  <link rel="shortcut icon" href="../../images/sma.png" />
  <style>
    html, body {
      height: 100%;
      overflow: hidden; /* ini kunci utama */
    }

    .main-panel {
      height: calc(100vh - 60px);
      overflow-y: auto;
      padding-bottom: 30px;
    }

    .foto-siswa {
    width: auto;
    height: 250px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 15px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  }

    .content-wrapper { padding: 2rem; }

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

    .notification-dropdown.d-block { display: block; }

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

    .navbar-brand img { height: 100px; object-fit: contain; }

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
          <a class="nav-link nav-profile-icon" href="#">
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
    <!-- 📂 SIDEBAR -->
    <?php include '../sidebar.php'; ?>

    <!-- 📄 MAIN PANEL -->
    <div class="main-panel">
      <div class="content-wrapper">

        <!-- 👋 Header -->
        <div class="row mb-4">
          <div class="col-md-12">
            <div class="bg-gradient-primary text-white p-4 rounded shadow">
              <h4>Profil Siswa</h4>
              <h2 class="font-weight-bold mb-0"><?= $data['nama']; ?></h2>
            </div>
          </div>
        </div>

        <!-- 🧾 DATA PROFIL -->
        <div class="row">
          <div class="col-md-8 mb-4">
            <div class="bg-white p-4 rounded shadow-sm">
              <h4>Data Profil</h4>
              <form action="update_profil.php" method="POST">
                <?php
                  $fields = [
                    'NISN' => ['nis', $data['nisn'], true],
                    'Nama' => ['nama', $data['nama'], false],
                    'Tempat Lahir' => ['tempat_lahir', $data['tempat_lahir'], false],
                    'Tanggal Lahir' => ['tanggal_lahir', $data['tanggal_lahir'], false],
                    'Agama' => ['agama', $data['agama'], false],
                    'Alamat' => ['alamat', $data['alamat'], false],
                    'No Telepon' => ['no_telepon', $data['no_telepon'] ?? '', false],
                    'Email' => ['email', $data['email'], true]
                  ];
                  foreach ($fields as $label => [$name, $value, $readonly]) {
                    echo "<div class='form-group'><label>$label</label>";
                    if ($name == 'alamat') {
                      echo "<textarea class='form-control' name='$name' rows='3' " . ($readonly ? 'readonly' : '') . ">$value</textarea>";
                    } else {
                      $type = ($name === 'email') ? 'email' : 'text';
                      if ($name === 'tanggal_lahir') $type = 'date';
                      echo "<input type='$type' class='form-control' name='$name' value='$value' " . ($readonly ? 'readonly' : 'required') . ">";
                    }
                    echo "</div>";
                  }
                ?>
                <div class="form-group"><label>Jenis Kelamin</label>
                  <select name="jenis_kelamin" class="form-control" required>
                    <option value="Laki-laki" <?= $data['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="Perempuan" <?= $data['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                  </select>
                </div>
                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
              </form>
            </div>
          </div>

          <!-- 📷 FOTO PROFIL -->
          <div class="col-md-4 mb-4">
            <div class="bg-white p-4 rounded shadow-sm text-center">
              <img src="../../uploads/<?= $foto ?: 'default.png'; ?>?v=<?= time(); ?>" alt="Foto Siswa" class="foto-siswa">
              <div>
                <?php if (empty($foto) || $foto === 'default.png'): ?>
                  <a href="edit_foto.php" class="btn btn-sm btn-primary mb-2" style="width: 80%;">Tambah Foto</a><br>
                <?php endif; ?>
                <a href="edit_foto.php" class="btn btn-sm btn-warning mb-2" style="width: 80%;">Edit Foto</a><br>
                <a href="hapus_foto.php" class="btn btn-sm btn-danger" style="width: 80%;" onclick="return confirm('Yakin ingin menghapus foto ini?')">Hapus Foto</a>
              </div>
            </div>
          </div>
        </div>

        <!-- 📌 FOOTER -->
        <footer class="footer mt-5">
          <div class="text-center">
            Copyright © <a href="#">SMAN 1 Kota Sukabumi</a> 2025
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
<script>
  function toggleDropdown() {
    const dropdown = document.getElementById('notifDropdown');
    dropdown.classList.toggle('d-block');
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
