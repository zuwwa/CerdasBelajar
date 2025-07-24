<?php
session_start();
include '../../koneksi.php';

// Cek login role kepsek
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'kepsek') {
    echo "<script>alert('⛔ Akses ditolak!'); window.location='../../logout.php';</script>";
    exit;
}

// Ambil data mapel dan kelas
$mapel_result = mysqli_query($conn, "SELECT id, Kode, Nama FROM mata_pelajaran ORDER BY Nama ASC");
$kelas_result = mysqli_query($conn, "SELECT id, kelas FROM t_kelas ORDER BY kelas ASC");

// Proses simpan jadwal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_mapel    = $_POST['id_mapel'];
    $id_kelas    = $_POST['id_kelas'];
    $hari        = $_POST['hari'];
    $jam_mulai   = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $ruang       = $_POST['ruang'];

    $stmt = $conn->prepare("INSERT INTO jadwal (id_mapel, id_kelas, hari, jam_mulai, jam_selesai, ruang) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $id_mapel, $id_kelas, $hari, $jam_mulai, $jam_selesai, $ruang);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Jadwal berhasil ditambahkan'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal menambahkan jadwal');</script>";
    }
    exit;
}
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
    <meta charset="UTF-8">
    <title>Agenda Kegiatan Sekolah - CerdasBelajar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../vendors/typicons.font/font/typicons.css">
    <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
    <link rel="shortcut icon" href="../../images/sma.png">
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

        .table th,
        .table td {
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

        .table-responsive {
            margin-top: 20px;
        }

        .card-title {
            color: #333;
            font-weight: 600;
        }

        .card-description {
            color: #666;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.03);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .btn-group-custom {
            margin-bottom: 15px;
            /* Jarak antara tombol dan judul tabel */
        }

        .btn-group-custom .btn {
            margin-right: 10px;
            /* Jarak antar tombol */
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
            <!-- MAIN PANEL -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-8 mx-auto grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title text-center mb-4">Tambah Jadwal Pelajaran</h4>

                                    <form method="POST">
                                        <div class="form-group">
                                            <label>Mata Pelajaran</label>
                                            <select name="id_mapel" class="form-control" required>
                                                <option value="">-- Pilih Mapel --</option>
                                                <?php while ($m = mysqli_fetch_assoc($mapel_result)) : ?>
                                                    <option value="<?= $m['id'] ?>"><?= $m['Kode'] ?> - <?= $m['Nama'] ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Kelas</label>
                                            <select name="id_kelas" class="form-control" required>
                                                <option value="">-- Pilih Kelas --</option>
                                                <?php while ($k = mysqli_fetch_assoc($kelas_result)) : ?>
                                                    <option value="<?= $k['id'] ?>"><?= $k['kelas'] ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Hari</label>
                                            <select name="hari" class="form-control" required>
                                                <option value="">-- Pilih Hari --</option>
                                                <?php foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $h) : ?>
                                                    <option value="<?= $h ?>"><?= $h ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Jam Mulai</label>
                                            <input type="time" name="jam_mulai" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Jam Selesai</label>
                                            <input type="time" name="jam_selesai" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Ruang</label>
                                            <input type="text" name="ruang" class="form-control" placeholder="Contoh: R.3A" required>
                                        </div>

                                        <div class="mt-4 d-flex justify-content-between">
                                            <a href="index.php" class="btn btn-secondary">Kembali</a>
                                            <button type="submit" class="btn btn-success">Simpan Jadwal</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- content-wrapper ends -->
            </div> <!-- main-panel ends -->
        </div>

        <script src="../../vendors/js/vendor.bundle.base.js"></script>
        <script src="../../js/off-canvas.js"></script>
        <script src="../../js/template.js"></script>
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