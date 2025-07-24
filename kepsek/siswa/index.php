<?php
session_start();
include('../../koneksi.php');

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



// Ambil semua kelas
$query_kelas = mysqli_query($conn, "SELECT * FROM t_kelas ORDER BY angkatan, kelas");

// Ambil id kelas yang diklik
$kelas_terpilih = isset($_GET['kelas_id']) ? $_GET['kelas_id'] : null;
$nama_kelas_terpilih = "";
$data_siswa = [];
$search = $_GET['search'] ?? '';

if ($kelas_terpilih) {
    $kelas_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM t_kelas WHERE id = '$kelas_terpilih'"));
    $nama_kelas_terpilih = $kelas_info['kelas'];

    // Ambil data siswa sesuai kelas di t_siswa
    $kelas_nama = mysqli_real_escape_string($conn, $kelas_info['kelas']);
    $query_siswa = "
    SELECT ts.id, ts.nis, ts.nama, ts.jenis_kelamin, 
           s.tempat_lahir, s.tanggal_lahir, s.alamat, s.no_telepon
    FROM t_siswa ts
    JOIN siswa s ON ts.nis = s.nisn
    JOIN t_kelas k ON ts.kelas = k.id
    WHERE k.id = '$kelas_terpilih'
";

if ($search) {
    $search_safe = mysqli_real_escape_string($conn, $search);
    $query_siswa .= " AND (ts.nama LIKE '%$search_safe%' OR ts.nis LIKE '%$search_safe%')";
}
$data_siswa = mysqli_query($conn, $query_siswa);
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Siswa - SMAN 1 Kota Sukabumi</title>
    <link rel="stylesheet" href="../../vendors/typicons.font/font/typicons.css" />
    <link rel="stylesheet" href="../../css/vertical-layout-light/style.css" />
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="shortcut icon" href="../../images/sma.png" />
    <style>
       /* General Body and Container Styles */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f6f9; /* A light grey background for the overall page */
}

.container-scroller {
    overflow: hidden; /* Prevent horizontal scroll issues */
}

/* Primary Color Variables for Consistency */
:root {
    --primary-blue: #004080; /* Dark blue for main elements */
    --gradient-start: rgb(2, 40, 122); /* Start color for gradients */
    --gradient-end: rgb(27, 127, 219); /* End color for gradients */
    --text-light: white; /* For text on dark backgrounds */
    --text-dark: #333; /* For general text */
    --border-light: #e0e0e0; /* Light border color */
    --card-shadow: 0 4px 15px rgba(0,0,0,0.08); /* Consistent card shadow */
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

/* Main Content Area */
.main-panel {
    background-color: #f8f9fa; /* Light background for main content */
}

.content-wrapper {
    padding: 2.5rem !important; /* Generous padding for content */
    background-color: #f8f9fa;
    min-height: calc(100vh - 120px); /* Adjust based on header/footer height */
}

/* Header Banner (e.g., "Manajemen Siswa" banner) */
.bg-gradient-primary-custom {
    background: linear-gradient(90deg, var(--gradient-start), var(--gradient-end));
    color: var(--text-light);
    padding: 2.5rem; /* Larger padding for a more prominent banner */
    border-radius: 10px;
    box-shadow: var(--card-shadow);
    text-align: center;
}

.bg-gradient-primary-custom h1 {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.bg-gradient-primary-custom p.lead {
    font-size: 1.15rem;
    opacity: 0.9;
}

/* Card Styles (used for content blocks like "Pilih Kelas" and "Daftar Siswa") */
.card {
    border: none;
    border-radius: 10px;
    box-shadow: var(--card-shadow);
    margin-bottom: 1.5rem;
    background-color: var(--text-light);
}

.card-header {
    background-color: var(--text-light);
    border-bottom: 1px solid var(--border-light);
    padding: 1.5rem;
    font-size: 1.25rem;
    font-weight: bold;
    color: var(--primary-blue);
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.card-body {
    padding: 2rem;
}

/* Class Selection Buttons */
.btn-kelas {
    margin: 0.35rem; /* Slightly more margin for buttons */
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    padding: 0.75rem 1.25rem; /* More comfortable button size */
    font-weight: 600;
}

.btn-kelas:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.btn-outline-primary {
    color: var(--primary-blue);
    border-color: var(--primary-blue);
}

.btn-outline-primary:hover {
    background-color: var(--primary-blue);
    color: var(--text-light);
}

/* Table Styling */
.table-responsive {
    overflow-x: auto; /* Ensures table is scrollable on smaller screens */
}

.table-siswa th, .table-siswa td {
    vertical-align: middle;
    text-align: center;
    padding: 12px 10px; /* Adjust cell padding */
    font-size: 0.95rem;
}

.table-siswa thead th {
    background-color: var(--primary-blue);
    color: var(--text-light);
    border-color: var(--primary-blue);
    font-weight: 600;
}

.table-siswa tbody tr:nth-child(even) {
    background-color: #f9f9f9; /* Lighter zebra striping */
}

.table-siswa tbody tr:hover {
    background-color: #f0f0f0; /* Hover effect */
}

/* Action Buttons in Table */
.table-siswa .btn-sm {
    padding: 0.35rem 0.75rem;
    font-size: 0.85rem;
    border-radius: 0.3rem;
    margin: 0 3px;
}

/* Search Form */
.form-inline .form-control {
    border-radius: 0.5rem;
    padding: 0.65rem 1rem;
    border: 1px solid var(--border-light);
}

.form-inline .btn {
    border-radius: 0.5rem;
    padding: 0.65rem 1.2rem;
}

.input-group .btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: var(--text-light);
}

.input-group .btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

/* Alert Messages */
.alert {
    border-radius: 8px;
    padding: 1.25rem;
    font-size: 1rem;
}

.alert-info {
    background-color: #e2f2ff;
    color: #0056b3;
    border-color: #b3d7ff;
}

/* Footer Styling */
.footer {
    background-color: #f8f9fa;
    padding: 1.5rem;
    text-align: center;
    border-top: 1px solid var(--border-light);
    color: #6c757d;
    font-size: 0.9rem;
    margin-top: auto; /* Push footer to the bottom */
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
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="p-4 rounded-lg bg-gradient-primary-custom text-white shadow-sm">
                                <h1 class="mb-0">Manajemen Siswa</h1>
                                <p class="lead mb-0">Kelola data siswa berdasarkan kelas dengan mudah.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card p-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h2 class="h4 mb-0">📚 Manajemen Siswa Berdasarkan Kelas</h2>
                        </div>
                        <div class="card-body">
                            <h5 class="mb-3">Pilih Kelas:</h5>
                            <div class="d-flex flex-wrap mb-4">
                                <?php mysqli_data_seek($query_kelas, 0); // Reset pointer for second loop if needed ?>
                                <?php while ($k = mysqli_fetch_assoc($query_kelas)) : ?>
                                    <a href="?kelas_id=<?= $k['id'] ?>" class="btn <?= ($kelas_terpilih == $k['id']) ? 'btn-primary' : 'btn-outline-primary' ?> btn-kelas">
                                        <?= $k['angkatan'] . ' ' . $k['kelas'] ?>
                                    </a>
                                <?php endwhile; ?>
                            </div>

                            <?php if ($kelas_terpilih): ?>
    <hr class="my-4">
    <h4 class="mb-3">👨‍🏫 Daftar Siswa: <span class="text-primary"><?= $nama_kelas_terpilih ?></span></h4>

    <form method="GET" class="form-inline mb-4 justify-content-between align-items-center">
        <input type="hidden" name="kelas_id" value="<?= $kelas_terpilih ?>">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari nama/NIS..." value="<?= htmlspecialchars($search) ?>">
            <div class="input-group-append">
                <button class="btn btn-secondary" type="submit">🔍 Cari</button>
            </div>
        </div>
        <a href="siswa_tambah.php?kelas_id=<?= $kelas_terpilih ?>" class="btn btn-success ml-auto mt-2 mt-md-0">➕ Tambah Siswa</a>
    </form>

    <?php if (mysqli_num_rows($data_siswa) > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-siswa">
        <thead class="thead-dark">
    <tr>
        <th>NIS</th>
        <th>Nama</th>
        <th>Jenis Kelamin</th>
        <th>Tempat Lahir</th>
        <th>Tanggal Lahir</th>
        <th>Alamat</th>
        <th>No Telepon</th>
        <th>Aksi</th>
    </tr>
</thead>
<tbody>
<?php while ($s = mysqli_fetch_assoc($data_siswa)) : ?>
<tr>
    <td><?= htmlspecialchars($s['nis']) ?></td>
    <td><?= htmlspecialchars($s['nama']) ?></td>
    <td><?= htmlspecialchars($s['jenis_kelamin']) ?></td>
    <td><?= htmlspecialchars($s['tempat_lahir']) ?></td>
    <td><?= htmlspecialchars($s['tanggal_lahir']) ?></td>
    <td><?= htmlspecialchars($s['alamat']) ?></td>
    <td><?= htmlspecialchars($s['no_telepon']) ?></td>
    <td>
        <a href="siswa_edit.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-warning">✏️ Edit</a>
        <a href="siswa_hapus.php?id=<?= $s['id'] ?>&kelas_id=<?= $kelas_terpilih ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus siswa ini?')">🗑️ Hapus</a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>

        </table>
    </div>
    <?php else: ?>
        <div class="alert alert-info text-center" role="alert">
            Tidak ada siswa ditemukan di kelas ini atau dengan kriteria pencarian tersebut.
        </div>
    <?php endif; ?>
<?php else: ?>
    <div class="alert alert-info text-center mt-4" role="alert">
        Silakan pilih salah satu kelas di atas untuk melihat daftar siswa.
    </div>
<?php endif; ?>


                <footer class="footer mt-5">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-center text-sm-left d-block d-sm-inline-block">
                            Copyright © <a href="https://www.sman1sukabumi.sch.id/" target="_blank" rel="noopener noreferrer">SMAN 1 Kota Sukabumi</a> 2025
                        </span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <script src="../vendors/js/vendor.bundle.base.js"></script>
    <script src="../js/off-canvas.js"></script>
    <script src="../js/hoverable-collapse.js"></script> <script src="../js/template.js"></script>
</body>
</html>