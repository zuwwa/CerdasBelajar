<?php
session_start();
include('../../koneksi.php');

// Cek login admin
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('⛔ Akses ditolak! Halaman ini hanya untuk admin.'); window.location='../logout.php';</script>";
    exit;
}


// Ambil data admin
$email = $_SESSION['email'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' AND type = 'admin'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Administrator tidak ditemukan.'); window.location='../logout.php';</script>";
    exit;
}

// Notifikasi
$jumlah_notif = 0;
$daftar_notif = [];
$notif_query = mysqli_query($conn, "SELECT * FROM notifikasi ORDER BY waktu DESC LIMIT 5");
$jumlah_notif = mysqli_num_rows($notif_query);
while ($row = mysqli_fetch_assoc($notif_query)) {
    $daftar_notif[] = $row;
}

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
        :root {
            --primary-blue: #004080; /* Dark blue for navbar */
            --gradient-start: rgb(2, 40, 122);
            --gradient-end: rgb(27, 127, 219);
        }

        .content-wrapper {
            padding: 2rem !important; /* Ensure consistent padding */
            background-color: #f8f9fa; /* Light background for content area */
        }

        .bg-gradient-primary-custom {
            background: linear-gradient(90deg, var(--gradient-start), var(--gradient-end));
            color: white;
        }

        .navbar-brand-wrapper {
            background-color: var(--primary-blue) !important;
            border-bottom: 1px solid #003366 !important;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding-left: 1.5rem;
        }

        .navbar-brand img {
            height: 50px; /* Adjust logo size */
            object-fit: contain;
            margin-right: 10px;
        }

        .navbar-menu-wrapper {
            background-color: var(--primary-blue) !important;
            border-bottom: 1px solid #003366 !important;
        }

        .navbar .nav-item .nav-link {
            color: white !important;
        }

        .navbar .nav-item .nav-link .typcn {
            color: white !important;
        }

        .notification-icon {
            position: relative;
        }

        .notification-icon img, .nav-profile-icon img {
            width: 24px;
            height: 24px;
            object-fit: contain;
            filter: brightness(0) invert(1); /* Makes icons white */
        }
        
        .notification-icon .badge {
            position: absolute;
            top: 0px;
            right: -8px;
            font-size: 0.7rem;
            padding: .3em .6em;
        }

        .btn-kelas {
            margin: 0.25rem;
            border-radius: 0.5rem; /* Slightly rounded buttons */
            transition: all 0.3s ease;
        }

        .btn-kelas:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .table-siswa th, .table-siswa td {
            vertical-align: middle;
            text-align: center;
        }
        
        .table-siswa thead th {
            background-color: var(--primary-blue);
            color: white;
            border-color: var(--primary-blue);
        }

        .table-siswa tbody tr:nth-child(even) {
            background-color: #f2f2f2; /* Zebra striping */
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem;
            font-size: 1.25rem;
            font-weight: bold;
            color: var(--primary-blue);
        }

        .form-inline .form-control {
            border-radius: 0.5rem;
        }

        .form-inline .btn {
            border-radius: 0.5rem;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 1.5rem;
            text-align: center;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="navbar-brand-wrapper d-flex align-items-center justify-content-start pl-3">
                <a class="navbar-brand brand-logo d-flex align-items-center" href="#">
                    <img src="../images/sma.png" alt="logo" />
                    <span class="text-white font-weight-bold h5 mb-0 d-none d-sm-inline-block">SMAN 1 Kota Sukabumi</span>
                </a>
                <a class="navbar-brand brand-logo-mini d-none" href="#">
                    <img src="../images/sma.png" alt="logo mini" />
                </a>
                <button class="navbar-toggler navbar-toggler align-self-center d-none d-lg-flex ml-3" type="button" data-toggle="minimize">
                    <span class="typcn typcn-th-menu text-white"></span>
                </button>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
                <ul class="navbar-nav navbar-nav-right d-flex align-items-center">
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link notification-icon" href="#">
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
                        <a class="nav-link nav-profile-icon" href="../logout.php" onclick="return confirm('Yakin ingin logout?')">
                            <img src="../images/logout.png" alt="Logout">
                        </a>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="typcn typcn-th-menu text-white"></span>
                </button>
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