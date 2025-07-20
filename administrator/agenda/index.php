<?php
session_start();
include('../../koneksi.php');

// Cek login admin
if (!isset($_SESSION['email']) || $_SESSION['role'] != 1) {
    echo "<script>alert('⛔ Akses ditolak! Halaman ini hanya untuk admin.');window.location.href='../login.php';</script>";
    exit;
}

// Query untuk pembayaran yang menunggu konfirmasi
$konfirmasi_query = mysqli_query($conn, "
    SELECT p.*, s.nama as nama_siswa, t.nama_tagihan, t.total
    FROM pembayaran p
    JOIN siswa s ON p.siswa_id = s.id
    JOIN tagihan t ON p.tagihan_id = t.id
    WHERE p.status = 'menunggu'
    ORDER BY p.tanggal DESC
");

/**
 * Fungsi untuk menghasilkan nomor Virtual Account (VA).
 * @param int $tagihan_id ID Tagihan
 * @return string Nomor VA
 */
function generateVA($tagihan_id) {
    return 'VA' . str_pad($tagihan_id, 8, '0', STR_PAD_LEFT);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Keuangan - SMAN 1 Kota Sukabumi</title>
    <link rel="stylesheet" href="../vendors/typicons.font/font/typicons.css" />
    <link rel="stylesheet" href="../css/vertical-layout-light/style.css" />
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="shortcut icon" href="../images/sma.png" />
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
            <?php include 'sidebar.php'; ?>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="p-4 rounded-lg bg-gradient-primary-custom text-white shadow-sm">
                                <h1 class="mb-0">Keuangan</h1>
                                <p class="lead mb-0">Kelola keuangan sekolah.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="bg-gradient-primary-custom text-white p-4 rounded shadow">
                            <h4>✅ Konfirmasi Pembayaran Siswa</h4>
                            <p class="mb-0">Admin dapat melihat dan memverifikasi pembayaran yang dikirim siswa.</p>
                        </div>
                    </div>

                    <div class="card p-4 mb-4">
                        <h5 class="mb-3">🕒 Menunggu Konfirmasi</h5>
                        <?php if (mysqli_num_rows($konfirmasi_query) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Nama Siswa</th>
                                            <th>Tagihan</th>
                                            <th>Total Tagihan</th>
                                            <th>Dibayar</th>
                                            <th>Metode</th>
                                            <th>Bukti</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($konfirmasi_query)): ?>
                                            <tr>
                                                <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                                <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                                                <td><?= htmlspecialchars($row['nama_tagihan']) ?></td>
                                                <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
                                                <td>Rp<?= number_format($row['jumlah_bayar'], 0, ',', '.') ?></td>
                                                <td><?= htmlspecialchars($row['metode']) ?></td>
                                                <td>
                                                    <?php if (!empty($row['keterangan'])): ?>
                                                        <a href="../../uploads/bukti_pembayaran/<?= $row['keterangan'] ?>" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="verifikasi.php?id=<?= $row['id'] ?>&aksi=terima" class="btn btn-success btn-sm" onclick="return confirm('Terima pembayaran ini?')">Terima</a>
                                                    <a href="verifikasi.php?id=<?= $row['id'] ?>&aksi=tolak" class="btn btn-danger btn-sm" onclick="return confirm('Tolak pembayaran ini?')">Tolak</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">Belum ada pembayaran yang menunggu konfirmasi.</div>
                        <?php endif; ?>
                    </div>

                    <div class="card p-4 mb-4 text-center">
                        <h5 class="mb-3">Lihat Riwayat Pembayaran Siswa</h5>
                        <p class="mb-3">Klik tombol di bawah untuk melihat tagihan aktif dan riwayat pembayaran per kelas dan siswa.</p>
                        <a href="riwayat.php" class="btn btn-primary btn-lg">
                            <span class="typcn typcn-arrow-right mr-2"></span> Buka Riwayat Pembayaran
                        </a>
                    </div>

                </div>

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
    <script src="../js/hoverable-collapse.js"></script>
    <script src="../js/template.js"></script>
</body>
</html>