<?php
session_start();
include('../../koneksi.php');

// Validasi sesi admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('⛔ Akses ditolak! Anda bukan admin.'); window.location='../../logout.php';</script>";
    exit;
}

// Ambil semua data mapel
$query = "
    SELECT m.id, m.nama_mapel, m.nama_guru, m.kode, k.kelas, k.angkatan
    FROM t_mapel m
    JOIN t_kelas k ON m.id_kelas = k.id
    ORDER BY k.angkatan DESC, k.kelas ASC, m.nama_mapel ASC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mata Pelajaran - Admin</title>

  <!-- CSS -->
  <link rel="stylesheet" href="/CerdasBelajar/vendors/typicons.font/font/typicons.css" />
  <link rel="stylesheet" href="/CerdasBelajar/css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="/CerdasBelajar/images/sma.png" />

  <style>
    .navbar-menu-wrapper {
      background-color: #004080 !important;
      border-bottom: 1px solid #003366 !important;
    }
    .navbar-brand-wrapper {
      background-color: #004080;
    }
    .navbar-brand span,
    .navbar-brand {
      color: white !important;
    }
    .nav-profile-icon img,
    .notification-icon img {
      width: 23px;
    }
    .notification-icon .badge {
      position: absolute;
      top: 5px;
      left: -8px;
    }
    .content-wrapper {
      padding: 2rem;
      height: calc(100vh - 70px);
      overflow-y: auto;
    }
    .main-panel {
      width: 100%;
    }
    .table th {
      background-color: #004080;
      color: white;
    }
    .search-box {
      width: 250px;
      margin-bottom: 15px;
    }
    .btn-tambah {
      background-color: #198754;
      color: white;
    }
    
  </style>
</head>
<body>
  <div class="container-scroller">

    <!-- NAVBAR -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex align-items-center pl-3">
        <a class="navbar-brand font-weight-bold h5 mb-0" href="#">
          SMAN 1 Kota Sukabumi
        </a>
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
            <a class="nav-link nav-profile-icon" href="../../logout.php" onclick="return confirm('Yakin ingin logout?')">
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
      <?php include '../sidesbar.php'; ?>

      <!-- MAIN PANEL -->
      <div class="main-panel">
        <div class="content-wrapper">

          <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Data Mata Pelajaran</h3>
            <a href="tambah.php" class="btn btn-sm btn-tambah">+ Tambah Mapel</a>
          </div>

          <input type="text" class="form-control search-box" id="searchInput" placeholder="Cari mapel atau guru...">

          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="mapelTable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nama Mapel</th>
                  <th>Guru Pengampu</th>
                  <th>Kode</th>
                  <th>Kelas</th>
                  <th>Angkatan</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= htmlspecialchars($row['nama_mapel']) ?></td>
                  <td><?= htmlspecialchars($row['nama_guru']) ?></td>
                  <td><?= htmlspecialchars($row['kode']) ?></td>
                  <td><?= htmlspecialchars($row['kelas']) ?></td>
                  <td><?= htmlspecialchars($row['angkatan']) ?></td>
                  <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>

        </div> <!-- content-wrapper -->
      </div> <!-- main-panel -->

    </div> <!-- page-body-wrapper -->
  </div> <!-- container-scroller -->

  <!-- JS -->
  <script src="/CerdasBelajar/vendors/js/vendor.bundle.base.js"></script>
  <script src="/CerdasBelajar/js/off-canvas.js"></script>
  <script src="/CerdasBelajar/js/template.js"></script>
  <script>
    // Search filter
    document.getElementById("searchInput").addEventListener("keyup", function () {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll("#mapelTable tbody tr");

      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
      });
    });
  </script>
</body>
</html>
