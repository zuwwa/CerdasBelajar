<?php
session_start();
include '../../koneksi.php';

// Fungsi untuk redirect dan menampilkan pesan error
function redirectWithError($message, $location = '../../logout.php')
{
    echo "<script>alert('" . htmlspecialchars($message) . "'); window.location='" . htmlspecialchars($location) . "';</script>";
    exit;
}

// Fungsi untuk redirect dan menampilkan pesan sukses
function redirectWithSuccess($message, $location)
{
    echo "<script>alert('" . htmlspecialchars($message) . "'); window.location='" . htmlspecialchars($location) . "';</script>";
    exit;
}

// Cek login kepala sekolah
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'kepsek') {
    redirectWithError('⛔ Akses ditolak! Halaman ini hanya untuk Kepala Sekolah.');
}

// Ambil data kelas dari tabel t_kelas
$data_kelas = [];
$query_kelas = "SELECT kelas FROM t_kelas ORDER BY kelas ASC";
$stmt_kelas = mysqli_prepare($conn, $query_kelas);
if ($stmt_kelas === false) {
    error_log("Error preparing kelas query: " . mysqli_error($conn));
    redirectWithError('Terjadi kesalahan sistem saat mengambil data kelas. Silakan coba lagi nanti.');
}
mysqli_stmt_execute($stmt_kelas);
$result_kelas = mysqli_stmt_get_result($stmt_kelas);
if ($result_kelas) {
    while ($row_kelas = mysqli_fetch_assoc($result_kelas)) {
        $data_kelas[] = $row_kelas['kelas'];
    }
}
mysqli_stmt_close($stmt_kelas);


// Proses form jika ada POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = $_POST['nip'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $tempat_lahir = $_POST['tempat_lahir'] ?? '';
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $kelas = $_POST['kelas'] ?? ''; // Sekarang akan menjadi nilai dari dropdown
    $password = $_POST['password'] ?? ''; // Password ini hanya untuk tabel users

    // Validasi input
    if (empty($nip) || empty($nama) || empty($jenis_kelamin) || empty($tempat_lahir) || empty($tanggal_lahir) || empty($kelas) || empty($password)) {
        redirectWithError('Semua kolom wajib diisi!');
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    // Asumsi id_sekolah adalah 1
    $id_sekolah = 1;
    // Asumsi foto default
    $foto_default = 'default.jpg';
    // jk diambil dari jenis_kelamin
    $jk = $jenis_kelamin;

    // Mulai transaksi
    mysqli_begin_transaction($conn);

    try {
        // 1. Insert ke tabel t_guru (kolom password disertakan dengan nilai default '-')
        $default_guru_password = '-'; // Nilai default untuk t_guru.password
        $query_guru = "INSERT INTO t_guru (nip, nama, jenis_kelamin, tempat_lahir, tanggal_lahir, kelas, id_sekolah, password, foto, jk) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_guru = mysqli_prepare($conn, $query_guru);
        if ($stmt_guru === false) {
            throw new Exception("Gagal menyiapkan query guru: " . mysqli_error($conn));
        }
        // Perhatikan parameter binding: ssssssisss (10 parameter, termasuk password)
        mysqli_stmt_bind_param($stmt_guru, "ssssssisss", $nip, $nama, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $kelas, $id_sekolah, $default_guru_password, $foto_default, $jk);
        if (!mysqli_stmt_execute($stmt_guru)) {
            throw new Exception("Gagal menambahkan data guru: " . mysqli_stmt_error($stmt_guru));
        }
        mysqli_stmt_close($stmt_guru);

        // 2. Insert ke tabel users (password di-hash dan disimpan di sini)
        // Email guru bisa dibuat dari NIP
        $email_guru = $nip . '@sman1sukabumi.sch.id';
        $user_type = 'guru';
        $user_status = 1; // Aktif
        $user_picture = 'default_profile.png'; // Gambar profil default

        $query_users = "INSERT INTO users (fullname, email, password, status, picture, type) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_users = mysqli_prepare($conn, $query_users);
        if ($stmt_users === false) {
            throw new Exception("Gagal menyiapkan query users: " . mysqli_error($conn));
        }
        // Parameter binding: sssiss (6 parameter: string, string, string, int, string, string)
        mysqli_stmt_bind_param($stmt_users, "sssiss", $nama, $email_guru, $hashed_password, $user_status, $user_picture, $user_type);
        if (!mysqli_stmt_execute($stmt_users)) {
            throw new Exception("Gagal menambahkan data user: " . mysqli_stmt_error($stmt_users));
        }
        mysqli_stmt_close($stmt_users);

        // Commit transaksi jika semua berhasil
        mysqli_commit($conn);
        redirectWithSuccess('Data guru dan akun user berhasil ditambahkan!', 'index.php');

    } catch (Exception $e) {
        // Rollback transaksi jika ada error
        mysqli_rollback($conn);
        error_log("Error adding teacher: " . $e->getMessage());
        redirectWithError('Terjadi kesalahan: ' . $e->getMessage());
    }
}

// Notifikasi (sama seperti di index.php)
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
    <title>Tambah Guru Baru - SMAN 1 Kota Sukabumi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../vendors/typicons.font/font/typicons.css">
    <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
    <link rel="shortcut icon" href="../../images/sma.png">
    <style>
        /* Gaya CSS yang sama seperti di index.php */
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
        }

        .btn-group-custom .btn {
            margin-right: 10px;
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
                            <?php if ($jumlah_notif > 0) : ?>
                                <div class="notification-badge"><?= $jumlah_notif ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="notification-dropdown" id="notifDropdown">
                            <h6>Notifikasi</h6>
                            <?php if ($daftar_notif) : ?>
                                <?php foreach ($daftar_notif as $notif) : ?>
                                    <div class="notif-item"><?= htmlspecialchars($notif['judul']) ?></div>
                                <?php endforeach; ?>
                            <?php else : ?>
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
        </div>

        <div class="container-fluid page-body-wrapper">
            <?php include '../sidesbar.php'; ?>
            <!-- MAIN PANEL -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Tambah Guru Baru</h4>
                                    <p class="card-description">
                                        Isi formulir di bawah untuk menambahkan data guru baru.
                                    </p>
                                    <form class="forms-sample" method="POST">
                                        <div class="form-group">
                                            <label for="nip">NIP</label>
                                            <input type="text" class="form-control" id="nip" name="nip" placeholder="Nomor Induk Pegawai" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama">Nama Guru</label>
                                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap Guru" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="jenis_kelamin">Jenis Kelamin</label>
                                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="L">Laki-laki</option>
                                                <option value="P">Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="tempat_lahir">Tempat Lahir</label>
                                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="tanggal_lahir">Tanggal Lahir</label>
                                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="kelas">Kelas Mengajar</label>
                                            <select class="form-control" id="kelas" name="kelas" required>
                                                <option value="">Pilih Kelas</option>
                                                <?php foreach ($data_kelas as $kelas_option) : ?>
                                                    <option value="<?= htmlspecialchars($kelas_option); ?>"><?= htmlspecialchars($kelas_option); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password (untuk login)</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password untuk akun login guru" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary mr-2">Simpan</button>
                                        <a href="index.php" class="btn btn-light">Batal</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
            </div>
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