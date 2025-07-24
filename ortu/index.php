<?php 
include 'includes/sidebar.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portal Orang Tua - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --primary-color: rgb(2, 40, 122);
      --secondary-color: rgb(27, 127, 219);
      --navbar-height: 70px;
      --sidebar-width: 250px;
    }

    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f5f5;
      overflow-x: hidden;
      padding-top: var(--navbar-height);
    }

    .sidebar {
      width: var(--sidebar-width);
      height: 100vh;
      position: fixed;
      left: 0;
      top: var(--navbar-height);
      background-color: #252531;
      color: white;
      z-index: 1000;
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }

    .main-content {
      margin-left: var(--sidebar-width);
      padding: 20px;
      padding-top: calc(var(--navbar-height) + 20px);
    }

    .header {
      background-color: white;
      padding: 15px 20px;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
      transition: transform 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-header {
      background-color: var(--primary-color);
      color: white;
      border-radius: 10px 10px 0 0 !important;
      padding: 15px;
      font-weight: 600;
    }

    footer.footer {
      margin-left: var(--sidebar-width);
      background-color: #252531;
      color: white;
      padding: 20px;
      text-align: center;
    }

    .bg-pink {
      background-color: #ff66b2 !important;
    }

    @keyframes pulse {
      0% { background-color: rgba(231, 76, 60, 0.1); }
      50% { background-color: rgba(231, 76, 60, 0.2); }
      100% { background-color: rgba(231, 76, 60, 0.1); }
    }

    @media (max-width: 768px) {
      .sidebar {
        position: static;
        width: 100%;
        height: auto;
      }

      .main-content,
      footer.footer {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>

  <!-- Sidebar dan Navbar di sini (placeholder) -->
  <?php include 'includes/navbar.php';?>

  <main>
    <div class="main-content pt-5 ps-5">
      <div class="header">
        <h4>Selamat Datang<br><span>Nama Orang Tua</span></h4>
      </div>

      <div class="row mb-4">
        <div class="col-md-4 mb-3">
          <div class="card bg-dark text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h5 class="card-title">Jumlah Anak</h5>
                  <h2 class="mb-0">3</h2>
                </div>
                <i class="fas fa-child fa-3x opacity-50"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-3">
          <div class="card bg-success text-white">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h5 class="card-title">Tagihan Lunas</h5>
                  <h2 class="mb-0">5</h2>
                </div>
                <i class="fas fa-check-circle fa-3x opacity-50"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card bg-warning text-dark">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h5 class="card-title">Tagihan Belum Lunas</h5>
                  <h2 class="mb-0">2</h2>
                </div>
                <i class="fas fa-exclamation-circle fa-3x opacity-50"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Data Anak -->
      <section class="mb-5">
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0"><i class="fas fa-users me-2"></i>Data Anak</h3>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Jenis Kelamin</th>
                    <th>Umur</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>12345</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="me-3">
                          <img src="assets/img/default-avatar.png" class="rounded-circle" width="40" height="40" alt="Foto siswa">
                        </div>
                        <div>Rama Putra</div>
                      </div>
                    </td>
                    <td>X IPA 1</td>
                    <td><span class="badge bg-primary">Laki-laki</span></td>
                    <td>16 tahun</td>
                    <td>
                      <div class="d-flex gap-2">
                        <a href="#" class="btn btn-sm btn-success"><i class="fas fa-book-open me-1"></i>Nilai</a>
                        <a href="#" class="btn btn-sm btn-warning"><i class="fas fa-calendar-check me-1"></i>Absensi</a>
                      </div>
                    </td>
                  </tr>
                  <!-- Tambahkan lebih banyak siswa di sini -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>

      <!-- Tagihan Terbaru -->
      <section>
        <div class="card">
          <div class="card-header bg-danger">
            <h3 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Tagihan Terbaru</h3>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Anak</th>
                    <th>Jenis Tagihan</th>
                    <th>Jumlah</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="table-danger">
                    <td>Rama Putra</td>
                    <td>SPP Juli</td>
                    <td>Rp 150.000</td>
                    <td>10 Jul 2025 <span class="badge bg-danger ms-2">Terlambat</span></td>
                    <td><span class="badge bg-warning text-dark">Belum Lunas</span></td>
                    <td><a href="#" class="btn btn-sm btn-primary"><i class="fas fa-money-bill-wave me-1"></i>Bayar</a></td>
                  </tr>
                  <!-- Tambahkan tagihan lainnya di sini -->
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer text-end">
            <a href="#" class="btn btn-outline-danger">
              <i class="fas fa-list me-1"></i> Lihat Semua Tagihan
            </a>
          </div>
        </div>
      </section>
    </div>
  </main>

  <footer class="footer">
    <div class="footer-content">
      <div class="copyright">
        &copy; 2025 SMAN 1 Kota Sukabumi. All rights reserved.
      </div>
      <div class="footer-links">
        <a href="#"><i class="fas fa-info-circle"></i> About</a>
        <a href="#"><i class="fas fa-envelope"></i> Contact</a>
        <a href="#"><i class="fas fa-shield-alt"></i> Privacy Policy</a>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.table-danger').forEach(function(row) {
        row.style.animation = 'pulse 2s infinite';
      });
    });
  </script>
</body>
</html>
