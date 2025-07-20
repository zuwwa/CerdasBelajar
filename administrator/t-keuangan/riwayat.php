<?php
session_start();
include('../../koneksi.php');
function getSisaBayar($conn, $siswa_id, $tagihan_id, $total_tagihan)
{
    $query = mysqli_query($conn, "
        SELECT SUM(jumlah_bayar) AS total_bayar
        FROM pembayaran
        WHERE siswa_id = '$siswa_id' AND tagihan_id = '$tagihan_id' AND status = 'Lunas'
    ");

    $row = mysqli_fetch_assoc($query);
    $total_dibayar = $row['total_bayar'] ?? 0;

    return max(0, $total_tagihan - $total_dibayar);
}


// Cek login admin
if (!isset($_SESSION['email']) || $_SESSION['role'] != 1) {
    echo "<script>alert('⛔ Akses ditolak! Halaman ini hanya untuk admin.');window.location.href='../login.php';</script>";
    exit;
}

// Ambil daftar kelas untuk filter
$kelas_query = mysqli_query($conn, "SELECT * FROM kelas ORDER BY tingkat, kelas");
$kelas_terpilih = $_GET['kelas_id'] ?? '';
$siswa_list = [];

// Jika kelas terpilih, ambil daftar siswa di kelas tersebut
if ($kelas_terpilih) {
    $siswa_query = mysqli_query($conn, "SELECT * FROM siswa WHERE kelas_id = '$kelas_terpilih' ORDER BY nama ASC");
    while ($row = mysqli_fetch_assoc($siswa_query)) {
        $siswa_list[] = $row;
    }
}

// Ambil siswa_id dari URL jika ada
$siswa_id = $_GET['siswa_id'] ?? null;
$siswa = null;
$daftar_tagihan = [];

if ($siswa_id) {
    // Ambil data siswa
    $result = mysqli_query($conn, "SELECT * FROM siswa WHERE id = '$siswa_id'");
    $siswa = mysqli_fetch_assoc($result);

    if ($siswa) {
        // Ambil semua tagihan yang berlaku untuk siswa ini
        $kelas_id = $siswa['kelas_id'];

        $query_tagihan = mysqli_query($conn, "
            SELECT * FROM tagihan
            WHERE
                ditujukan_kepada = 'semua'
                OR (ditujukan_kepada = 'kelas' AND kelas_id = '$kelas_id')
                OR (ditujukan_kepada = 'siswa' AND siswa_id = '$siswa_id')
            ORDER BY tanggal_tagihan DESC
        ");

        while ($row = mysqli_fetch_assoc($query_tagihan)) {
            $daftar_tagihan[] = $row;
        }
    }
}

/**
 * Fungsi untuk menghasilkan nomor Virtual Account (VA).
 * @param int $tagihan_id ID Tagihan
 * @return string Nomor VA
 */
function generateVA($tagihan_id)
{
    return 'VA' . str_pad($tagihan_id, 8, '0', STR_PAD_LEFT);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Riwayat Pembayaran Siswa - SMAN 1 Kota Sukabumi</title>
    <link rel="stylesheet" href="../vendors/typicons.font/font/typicons.css" />
    <link rel="stylesheet" href="../css/vertical-layout-light/style.css" />
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="shortcut icon" href="../images/sma.png" />
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
            <?php include 'sidebar.php'; ?>
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
                            <?php mysqli_data_seek($kelas_query, 0); // Reset pointer for second loop 
                            ?>
                            <?php while ($kelas = mysqli_fetch_assoc($kelas_query)) : ?>
                                <a href="?kelas_id=<?= $kelas['id'] ?>" class="btn btn-outline-primary <?= ($kelas_terpilih == $kelas['id']) ? 'active' : '' ?>">
                                    <?= htmlspecialchars($kelas['tingkat'] . ' ' . $kelas['kelas']) ?>
                                </a>
                            <?php endwhile; ?>
                        </div>
                        <?php if ($kelas_terpilih || $siswa_id): ?>
                            <a href="riwayat.php" class="btn btn-outline-danger">
                                <span class="typcn typcn-times mr-2"></span> Reset Filter
                            </a>
                        <?php endif; ?>
                    </div>

                    <?php if ($kelas_terpilih): ?>
                        <div class="card p-4 mb-4">
                            <h5 class="mb-3">👥 Daftar Siswa di Kelas <?= htmlspecialchars(mysqli_fetch_assoc(mysqli_query($conn, "SELECT CONCAT(tingkat, ' ', kelas) as nama_kelas FROM kelas WHERE id = '$kelas_terpilih'"))['nama_kelas'] ?? '') ?></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>No</th>
                                            <th>NISN</th>
                                            <th>Nama</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($siswa_list): $no = 1;
                                            foreach ($siswa_list as $s): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= htmlspecialchars($s['nisn']) ?></td>
                                                    <td><?= htmlspecialchars($s['nama']) ?></td>
                                                    <td><a href="?siswa_id=<?= $s['id'] ?>&kelas_id=<?= $kelas_terpilih ?>" class="btn btn-sm btn-info">Lihat Pembayaran</a></td>
                                                </tr>
                                            <?php endforeach;
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
                                    <p class="mb-0">NISN: <?= htmlspecialchars($siswa['nisn']) ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="card p-4 mb-4">
                            <h5 class="mb-3">📌 Tagihan Aktif</h5>
                            <?php
                            $tagihan_aktif = [];
                            foreach ($daftar_tagihan as $tagihan) {
                                $tagihan_id = $tagihan['id'];
                                $cek_pembayaran_query = mysqli_query($conn, "SELECT SUM(jumlah_bayar) as total_bayar FROM pembayaran WHERE tagihan_id = '$tagihan_id' AND status = 'Lunas'");
                                $cek = mysqli_fetch_assoc($cek_pembayaran_query);
                                $total_dibayar = $cek['total_bayar'] ?? 0;

                                if ($total_dibayar < $tagihan['total']) {
                                    $tagihan_aktif[] = $tagihan;
                                }
                            }
                            ?>
                            <?php if ($tagihan_aktif): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Tanggal Tagihan</th>
                                                <th>Nama Tagihan</th>
                                                <th>Total Tagihan</th>
                                                <th>Sisa Bayar</th>
                                                <th>VA Number</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($tagihan_aktif as $row):
                                                $tagihan_id = $row['id'];
                                                $cek_pembayaran_query_detail = mysqli_query($conn, "SELECT SUM(jumlah_bayar) as total_bayar FROM pembayaran WHERE tagihan_id = '$tagihan_id' AND status = 'Lunas'");
                                                $cek_detail = mysqli_fetch_assoc($cek_pembayaran_query_detail);
                                                $total_dibayar_detail = $cek_detail['total_bayar'] ?? 0;
                                                $sisa_bayar = $row['total'] - $total_dibayar_detail;
                                            ?>
                                                <tr>
                                                    <td><?= date('d/m/Y', strtotime($row['tanggal_tagihan'])) ?></td>
                                                    <td><?= htmlspecialchars($row['nama_tagihan']) ?></td>
                                                    <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
                                                    <td>Rp<?= number_format($sisa_bayar, 0, ',', '.') ?></td>
                                                    <td><?= generateVA($row['id']) ?></td>
                                                    <td>
                                                        <a href="tambah_pembayaran.php?siswa_id=<?= $siswa['id'] ?>&tagihan_id=<?= $row['id'] ?>&kelas_id=<?= $kelas_terpilih ?>" class="btn btn-primary btn-sm">Tambahkan Pembayaran</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-success">🎉 Tidak ada tagihan aktif untuk siswa ini.</div>
                            <?php endif; ?>
                        </div>

                        <div class="card p-4 mb-4">
                            <h5 class="mb-3">💰 Riwayat Pembayaran</h5>
                            <?php
                            $pembayaran_query = mysqli_query($conn, "
                                    SELECT p.*, t.nama_tagihan, t.total
                                    FROM pembayaran p
                                    LEFT JOIN tagihan t ON p.tagihan_id = t.id
                                    WHERE p.siswa_id = '$siswa_id'
                                    ORDER BY p.tanggal DESC
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
                                                <th>Nama Siswa</th>
                                                <th>Tagihan</th>
                                                <th>Dibayar</th>
                                                <th><span class="text-danger">Sisa</span></th>
                                                <th>Status</th>
                                                <th>Metode</th>
                                                <th>Bukti</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php foreach ($riwayat as $row): ?>
                                                <tr>
                                                    <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                                    <td><?= htmlspecialchars($row['nama_tagihan']) ?></td>
                                                    <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
                                                    <td>Rp<?= number_format($row['jumlah_bayar'], 0, ',', '.') ?></td>

                                                    <td>
                                                        <?php
                                                        $sisa = getSisaBayar($conn, $row['siswa_id'], $row['tagihan_id'], $row['total']);
                                                        echo '<span class="badge badge-' . ($sisa == 0 ? 'success' : 'warning') . '">Rp' . number_format($sisa, 0, ',', '.') . '</span>';

                                                        ?>
                                                    </td>

                                                    <td>
                                                        <?php if (strtolower($row['status']) === 'lunas'): ?>
                                                            <span class="badge badge-success">Terkonfirmasi</span>
                                                        <?php elseif (strtolower($row['status']) === 'menunggu'): ?>
                                                            <span class="badge badge-warning">Menunggu</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-danger"><?= ucfirst($row['status']) ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($row['metode']) ?: '-' ?></td>
                                                    <td>
                                                        <?php if (!empty($row['keterangan'])): ?>
                                                            <a href="../../uploads/bukti_pembayaran/<?= $row['keterangan'] ?>" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if (strtolower($row['status']) !== 'lunas'): ?>
                                                            <a href="batal_pembayaran.php?id=<?= $row['id'] ?>&siswa_id=<?= $siswa['id'] ?>&kelas_id=<?= $kelas_terpilih ?>" class="btn btn-danger btn-sm" onclick="return confirm('Batalkan pembayaran ini?')">Batalkan</a>
                                                        <?php else: ?>
                                                            <span class="text-muted">Tidak ada aksi</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">Belum ada riwayat pembayaran untuk siswa ini.</div>
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