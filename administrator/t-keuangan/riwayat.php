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

// Ambil daftar kelas dari t_siswa
$kelas_query = mysqli_query($conn, "
    SELECT tk.id, tk.kelas, tk.angkatan
    FROM t_kelas tk
    ORDER BY tk.angkatan DESC, tk.kelas ASC
");


$kelas_terpilih = $_GET['kelas_id'] ?? '';
$angkatan_terpilih = $_GET['angkatan'] ?? '';
$siswa_list = [];

if ($kelas_terpilih) {
    $kelas_info = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM t_kelas WHERE id = '$kelas_terpilih'"));
    $siswa_query = mysqli_query($conn, "
        SELECT ts.*
        FROM t_siswa ts
        WHERE ts.kelas = '{$kelas_info['kelas']}'
        ORDER BY ts.nama ASC
    ");


    while ($row = mysqli_fetch_assoc($siswa_query)) {
        $siswa_list[] = $row;
    }
}

// Ambil NIS dari URL
$nis = $_GET['nis'] ?? null;
$siswa = null;
$daftar_tagihan = [];

if ($nis) {
    $result = mysqli_query($conn, "SELECT * FROM t_siswa WHERE nis = '$nis'");
    $siswa = mysqli_fetch_assoc($result);

    if ($siswa) {
        // Ambil tagihan siswa ini
        $query_tagihan = mysqli_query($conn, "
            SELECT * FROM t_keuangan_tagihan
            WHERE nis = '$nis'
            ORDER BY id DESC
        ");
        while ($row = mysqli_fetch_assoc($query_tagihan)) {
            $daftar_tagihan[] = $row;
        }
    }
}

$tagihan_query = mysqli_query($conn, "
    SELECT 
        td.id_tagihan,
        td.nama_tagihan,
        td.total_tagihan,
        IFNULL(SUM(tp.jml_bayar), 0) AS total_bayar
    FROM t_keuangan_daftar td
    LEFT JOIN t_keuangan_pembayaran tp
      ON td.nama_tagihan = tp.jenis_tagihan
    GROUP BY td.id_tagihan, td.nama_tagihan, td.total_tagihan
    ORDER BY td.id_tagihan ASC
");


$tagihan_aktif = [];
while ($row = mysqli_fetch_assoc($tagihan_query)) {
    $total_tagihan = (int) str_replace(['Rp. ', '.'], '', $row['total_tagihan']);
    $total_bayar = (int) $row['total_bayar'];

    $sisa_bayar = max(0, $total_tagihan - $total_bayar);
    $row['sisa_bayar'] = $sisa_bayar;
    $row['status'] = ($sisa_bayar == 0) ? 'Lunas' : 'Belum Lunas';
    $tagihan_aktif[] = $row;
}

$riwayat_query = mysqli_query($conn, "
    SELECT p.id, p.nis, p.jml_bayar, p.metode, p.bukti_pembayaran, p.status, p.tanggal_bayar,
           d.nama_tagihan, d.total_tagihan AS jml_tagihan
    FROM t_keuangan_pembayaran p
    LEFT JOIN t_keuangan_daftar d 
        ON p.jenis_tagihan = d.nama_tagihan
    ORDER BY p.tanggal_bayar DESC
");


/**
 * Fungsi untuk menghasilkan nomor Virtual Account (VA).
 */
function generateVA($jenis_tagihan)
{
    return 'VA' . str_pad($jenis_tagihan, 8, '0', STR_PAD_LEFT);
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Riwayat Pembayaran Siswa - SMAN 1 Kota Sukabumi</title>
    <link rel="stylesheet" href="../../vendors/typicons.font/font/typicons.css" />
    <link rel="stylesheet" href="../../css/vertical-layout-light/style.css" />
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="shortcut icon" href="../../images/sma.png" />
    <style>
        :root {
            --primary-blue: #004080;
            /* Dark blue for navbar */
            --gradient-start: rgb(2, 40, 122);
            --gradient-end: rgb(27, 127, 219);
        }

        .content-wrapper {
            padding: 2rem !important;
            /* Ensure consistent padding */
            background-color: #f8f9fa;
            /* Light background for content area */
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
            height: 50px;
            /* Adjust logo size */
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

        .notification-icon img,
        .nav-profile-icon img {
            width: 24px;
            height: 24px;
            object-fit: contain;
            filter: brightness(0) invert(1);
            /* Makes icons white */
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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
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

        /* Styles for neatly arranged filter buttons */
        .filter-buttons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            /* Adjust minmax as needed */
            gap: 10px;
            /* Space between buttons */
        }

        .filter-buttons-grid .btn {
            width: 100%;
            /* Make buttons fill their grid cell */
            white-space: nowrap;
            /* Prevent text wrapping */
            overflow: hidden;
            text-overflow: ellipsis;
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
                                <h1 class="mb-0">Riwayat Pembayaran Siswa</h1>
                                <p class="lead mb-0">Lihat dan kelola tagihan serta riwayat pembayaran siswa.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <a href="index.php" class="btn btn-secondary">
                            <span class="typcn typcn-arrow-left mr-2"></span> Kembali ke Dashboard Keuangan
                        </a>
                    </div>

                    <div class="card p-4 mb-4">
                        <h5 class="mb-3">🎓 Pilih Kelas</h5>
                        <div class="filter-buttons-grid mb-3">
                            <?php while ($kelas = mysqli_fetch_assoc($kelas_query)) :
                                $kelas_label = $kelas['kelas'] . ' - ' . $kelas['angkatan'];
                            ?>
                                <a href="?kelas_id=<?= $kelas['id'] ?>"
                                    class="btn btn-outline-primary <?= ($kelas_terpilih == $kelas['id']) ? 'active' : '' ?>">
                                    <?= htmlspecialchars($kelas_label) ?>
                                </a>
                            <?php endwhile; ?>
                        </div>


                        <?php if ($kelas_terpilih || $nis): ?>
                            <a href="riwayat.php" class="btn btn-outline-danger">
                                <span class="typcn typcn-times mr-2"></span> Reset Filter
                            </a>
                        <?php endif; ?>
                    </div>


                    <?php if ($kelas_terpilih): ?>
                        <div class="card p-4 mb-4">
                            <h5 class="mb-3">👥 Daftar Siswa di Kelas <?= htmlspecialchars($kelas_terpilih) ?></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>NIS</th>
                                            <th>Nama</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $siswa_query = mysqli_query($conn, "SELECT * FROM t_siswa WHERE kelas = '$kelas_terpilih' ORDER BY nama ASC");
                                        if (mysqli_num_rows($siswa_query) > 0):
                                            $no = 1;
                                            while ($s = mysqli_fetch_assoc($siswa_query)): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= htmlspecialchars($s['nis']) ?></td>
                                                    <td><?= htmlspecialchars($s['nama']) ?></td>
                                                    <td><a href="?nis=<?= $s['nis'] ?>&kelas=<?= urlencode($kelas_terpilih) ?>" class="btn btn-sm btn-info">Lihat Pembayaran</a></td>
                                                </tr>
                                            <?php endwhile;
                                        else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center">Tidak ada siswa di kelas ini.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>


                    <?php if (isset($siswa)): ?>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="bg-gradient-primary-custom text-white p-4 rounded shadow">
                                    <h4>Tagihan & Riwayat Pembayaran</h4>
                                    <h2 class="mb-0"><?= htmlspecialchars($siswa['nama']) ?></h2>
                                    <p class="mb-0">NISN: <?= htmlspecialchars($siswa['nis']) ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="card p-4 mb-4">
                            <h5 class="mb-3">📌 Daftar Tagihan</h5>
                            <?php if ($tagihan_aktif): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>ID Tagihan</th>
                                                <th>Nama Tagihan</th>
                                                <th>Total Tagihan</th>
                                                <th>Dibayar</th>
                                                <th>Sisa Bayar</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($tagihan_aktif as $row): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($row['id_tagihan']) ?></td>
                                                    <td><?= htmlspecialchars($row['nama_tagihan'] ?? '') ?> </td>
                                                    <td><?= htmlspecialchars($row['total_tagihan']) ?></td>
                                                    <td>Rp<?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                                                    <td>Rp<?= number_format($row['sisa_bayar'], 0, ',', '.') ?></td>
                                                    <td>
                                                        <span class="badge <?= ($row['status'] == 'Lunas') ? 'badge-success' : 'badge-warning' ?>">
                                                            <?= $row['status'] ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if ($row['sisa_bayar'] > 0): ?>
                                                            <a href="tambah_pembayaran.php?tagihan=<?= urlencode($row['nama_tagihan']) ?>" class="btn btn-primary btn-sm">Bayar</a>
                                                        <?php else: ?>
                                                            <span class="text-muted">Lunas</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success">🎉 Tidak ada tagihan aktif.</div>
                            <?php endif; ?>
                        </div>




                        <div class="card p-4 mb-4">
                            <h5 class="mb-3">💰 Riwayat Pembayaran</h5>
                            <?php
                            $pembayaran_query = mysqli_query($conn, "
    SELECT p.*, t.jenis_tagihan, t.jml_tagihan
    FROM t_keuangan_pembayaran p
    LEFT JOIN t_keuangan_tagihan t ON p.nis = t.nis AND p.jenis_tagihan = t.jenis_tagihan
    WHERE p.nis = '$nis'
    ORDER BY p.tanggal_bayar DESC
");
                            $riwayat = [];
                            while ($row = mysqli_fetch_assoc($pembayaran_query)) {
                                $riwayat[] = $row;
                            }
                            ?>
                            <?php if ($riwayat): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Tagihan</th>
                                                <th>Total Tagihan</th>
                                                <th>Dibayar</th>
                                                <th>Status</th>
                                                <th>Metode</th>
                                                <th>Bukti</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($riwayat as $row): ?>
                                                <tr>
                                                    <td><?= !empty($row['tanggal_bayar']) ? date('d/m/Y', strtotime($row['tanggal_bayar'])) : '-' ?></td>
                                                    <td><?= htmlspecialchars($row['nama_tagihan'] ?? '-') ?></td>
                                                    <td>Rp<?= number_format((int) filter_var($row['jml_tagihan'] ?? 0, FILTER_SANITIZE_NUMBER_INT), 0, ',', '.') ?></td>
                                                    <td>Rp<?= number_format((int) filter_var($row['jml_bayar'] ?? 0, FILTER_SANITIZE_NUMBER_INT), 0, ',', '.') ?></td>
                                                    <td>
                                                        <span class="badge <?= ($row['status'] ?? '') == 'lunas' ? 'badge-success' : (($row['status'] ?? '') == 'menunggu' ? 'badge-warning' : 'badge-danger') ?>">
                                                            <?= ucfirst($row['status'] ?? '-') ?>
                                                        </span>
                                                    </td>
                                                    <td><?= htmlspecialchars($row['metode'] ?? '-') ?></td>
                                                    <td>
                                                        <?php if (!empty($row['bukti_pembayaran'])): ?>
                                                            <a href="../../uploads/bukti_pembayaran/<?= htmlspecialchars($row['bukti_pembayaran']) ?>" target="_blank" class="btn btn-info btn-sm">Lihat</a>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">Belum ada riwayat pembayaran.</div>
                            <?php endif; ?>

                        </div>
                    <?php endif; ?>
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