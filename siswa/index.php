<?php 
session_start();
include '../koneksi.php';

// Cek login siswa
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../logout.php");
    exit;
}

// Ambil data siswa dari tabel `siswa`
$email = $_SESSION['email'];
$query = mysqli_query($conn, "SELECT * FROM siswa WHERE email = '$email'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Siswa tidak ditemukan.'); window.location='../logout.php';</script>";
    exit;
}

$nisn = $data['nisn']; // Gunakan nisn sebagai ID siswa

// Ambil data lengkap dari `t_siswa` + nama kelas langsung dari `t_kelas`
$t_siswa_query = mysqli_query($conn, "
    SELECT t_siswa.*, t_kelas.kelas AS nama_kelas, t_kelas.id AS kelas_id
    FROM t_siswa
    LEFT JOIN t_kelas ON t_siswa.kelas = t_kelas.id
    WHERE t_siswa.nis = '$nisn'
");
$t_siswa = mysqli_fetch_assoc($t_siswa_query);

if (!$t_siswa || !$t_siswa['kelas_id']) {
    echo "<script>alert('Data siswa atau kelas tidak valid.'); window.location='../logout.php';</script>";
    exit;
}

$kelas_id = $t_siswa['kelas_id']; // id kelas untuk mapel
$nama_kelas = $t_siswa['nama_kelas']; // nama kelas untuk ditampilkan

// Ambil notifikasi siswa berdasarkan nisn
$jumlah_notif = 0;
$daftar_notif = [];
$notif_query = mysqli_query($conn, "SELECT * FROM notifikasi WHERE siswa_id = '$nisn' ORDER BY waktu DESC LIMIT 5");
$jumlah_notif = mysqli_num_rows($notif_query);
while ($row = mysqli_fetch_assoc($notif_query)) {
    $daftar_notif[] = $row;
}

// Ambil daftar mapel berdasarkan kelas_id
$daftar_mapel = [];
$mapel_result = mysqli_query($conn, "
    SELECT t_mapel.*
    FROM anggota_mapel
    JOIN t_mapel ON anggota_mapel.mapel_id = t_mapel.id
    WHERE anggota_mapel.siswa_id = '$nisn'
");
while ($m = mysqli_fetch_assoc($mapel_result)) {
    $daftar_mapel[] = $m;
}


// Tambahkan mapel dari session gabung jika ada
if (isset($_SESSION['mapel_gabung']) && is_array($_SESSION['mapel_gabung'])) {
    foreach ($_SESSION['mapel_gabung'] as $kode_mapel) {
        $cek = mysqli_query($conn, "SELECT * FROM t_mapel WHERE kode = '$kode_mapel' LIMIT 1");
        if ($row = mysqli_fetch_assoc($cek)) {
            $sudah_ada = false;
            foreach ($daftar_mapel as $mapel) {
                if ($mapel['id'] == $row['id']) {
                    $sudah_ada = true;
                    break;
                }
            }
            if (!$sudah_ada) {
                $daftar_mapel[] = $row;
            }
        }
    }
}

// Ambil tugas terbaru berdasarkan kelas
$tugas_result = mysqli_query($conn, "
    SELECT tugas.*, t_mapel.nama_mapel, t_mapel.kode AS kode_mapel
    FROM tugas
    INNER JOIN t_mapel ON tugas.mapel_id = t_mapel.id
    INNER JOIN anggota_mapel ON t_mapel.id = anggota_mapel.mapel_id
    WHERE anggota_mapel.siswa_id = '$nisn'
      AND tugas.deadline >= NOW()
    ORDER BY tugas.deadline ASC
    LIMIT 5
");

$daftar_tugas = [];
while ($row = mysqli_fetch_assoc($tugas_result)) {
    $daftar_tugas[] = $row;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>SMAN 1 SUKABUMI</title>
  <link rel="stylesheet" href="vendors/typicons.font/font/typicons.css" />
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css" />
  <link rel="stylesheet" href="css/vertical-layout-light/style.css" />
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
              <img src="images/bell-icon.png" alt="Notifikasi">
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
              <img src="images/profile.png?v=2" alt="Profil">
            </a>
          </li>

          <!-- LOGOUT -->
          <li class="nav-item nav-profile-icon">
            <a class="nav-link" href="logout.php" onclick="return confirm('Yakin ingin logout?')">
              <img src="images/logout.png" alt="Logout">
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
          
          <!-- HEADER HALAMAN -->
          <div class="row mb-4">
            <div class="col-md-12">
              <div class="text-white p-4 rounded shadow" style="background: linear-gradient(90deg, rgb(2, 40, 122), rgb(27, 127, 219));">
                <h4>Selamat Datang 👋</h4>
                <h2 class="mb-0"><?= htmlspecialchars($data['nama']) ?></h2>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- MATA PELAJARAN -->
            <div class="col-md-3 mb-4">
              <a href="/CerdasBelajar/siswa/mata-pelajaran/index.php" class="text-decoration-none text-dark" style="display:block;">
                <div class="card text-center p-3 shadow-sm" style="cursor:pointer;">
                  <i class="typcn typcn-book display-4 text-primary"></i>
                  <h6>Mata Pelajaran</h6>
                </div>
              </a>
            </div>

            <!-- GABUNG MAPEL -->
            <div class="col-md-3 mb-4">
              <a href="#" class="text-decoration-none text-dark" data-toggle="modal" data-target="#modalGabungMapel">
                <div class="card text-center p-3 shadow-sm">
                  <i class="typcn typcn-plus display-4 text-warning"></i>
                  <h6>Gabung Kelas</h6>
                </div>
              </a>
            </div>
          </div>

        <?php if (!empty($daftar_tugas)): ?>
        <?php foreach ($daftar_tugas as $tugas): ?>
          <a href="mata-pelajaran/tugas.php?kode=<?= urlencode($tugas['kode_mapel']) ?>">
            <div class="card mb-2 p-3">
              <strong><?= htmlspecialchars($tugas['judul']) ?></strong>
              <div class="small text-muted">
                Mapel: <?= htmlspecialchars($tugas['nama_mapel']) ?> |
                Deadline: <?= date('d-m-Y H:i', strtotime($tugas['deadline'])) ?>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="text-muted">Tidak ada tugas aktif saat ini.</div>
      <?php endif; ?>


          <!-- MATA PELAJARAN KELAS -->
          <?php if (!empty($kelas_id)): ?>
          <div class="row mt-4">
            <div class="col-md-12">
              <h5 class="mb-3">📚 Mata Pelajaran Kelasmu</h5>
              <div class="row">
                <?php foreach ($daftar_mapel as $mapel): ?>
                  <div class="col-md-3 mb-3">
                    <a href="mata-pelajaran/mapel.php?kode=<?= urlencode($mapel['kode']) ?>" class="text-decoration-none text-dark">
                      <div class="card p-3 shadow-sm h-100">
                        <h6 class="mb-1"><?= htmlspecialchars($mapel['nama_mapel']) ?></h6>
                        <div class="text-muted small">Kode: <?= $mapel['kode'] ?></div>
                      </div>
                    </a>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        <?php endif; ?>


          <!-- FOOTER -->
          <footer class="footer mt-5">
            <div class="text-center">
              Copyright © SMAN 1 Kota Sukabumi 2025
            </div>
          </footer>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL GABUNG MAPEL -->
  <div class="modal fade" id="modalGabungMapel" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form action="mata-pelajaran/proses_gabung.php" method="POST" class="modal-content">
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title">Gabung Mata Pelajaran</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <label>Kode Mata Pelajaran:</label>
          <input type="text" name="kode_mapel" class="form-control" required />
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-warning">Gabung</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <!-- JS -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <script src="js/off-canvas.js"></script>
  <script src="js/template.js"></script>
  <script>
    function toggleDropdown() {
      const dropdown = document.getElementById('notifDropdown');
      dropdown.classList.toggle('active');
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