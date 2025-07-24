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

// Notifikasi
$jumlah_notif = 0;
$daftar_notif = [];
$notif_query = mysqli_query($conn, "SELECT * FROM notifikasi ORDER BY waktu DESC LIMIT 5");
$jumlah_notif = mysqli_num_rows($notif_query);
while ($row = mysqli_fetch_assoc($notif_query)) {
    $daftar_notif[] = $row;
}

$kelas_id = isset($_GET['kelas_id']) ? intval($_GET['kelas_id']) : null; // Gunakan intval untuk keamanan
if (!$kelas_id) {
    echo "<script>alert('Kelas belum dipilih. Anda akan dialihkan ke halaman manajemen siswa.'); window.location='index.php';</script>";
    exit;
}

$kelas_id = isset($_GET['kelas_id']) ? intval($_GET['kelas_id']) : null;
if (!$kelas_id) {
    echo "<script>alert('Kelas belum dipilih. Anda akan dialihkan ke halaman manajemen siswa.'); window.location='index.php';</script>";
    exit;
}

// Ambil kelas
$query_kelas_info = "SELECT * FROM t_kelas WHERE id = '$kelas_id'";
$result_kelas_info = mysqli_query($conn, $query_kelas_info);
$kelas = mysqli_fetch_assoc($result_kelas_info);
if (!$kelas) {
    echo "<script>alert('Kelas tidak ditemukan.'); window.location='index.php';</script>";
    exit;
}

// Proses tambah
if (isset($_POST['submit'])) {
    $nisn = mysqli_real_escape_string($conn, $_POST['nisn']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $agama = mysqli_real_escape_string($conn, $_POST['agama']);
    $tempat_lahir = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_telepon = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Hash password
    $password_hash = md5($password);

    // 1. Insert ke tabel siswa
    $insert_siswa = "INSERT INTO siswa (nisn, nama, email, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, alamat, no_telepon)
 VALUES (
        '$nisn', '$nama', '$email', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', '$agama', '$alamat', '$no_telepon'
    )";
    $result_siswa = mysqli_query($conn, $insert_siswa);

    // 2. Insert ke tabel t_siswa
    $insert_tsiswa = "INSERT INTO t_siswa (nis, nama, jenis_kelamin, tempat_lahir, tanggal_lahir, kelas, id_sekolah, password, foto) VALUES (
        '$nisn', '$nama', LEFT('$jenis_kelamin', 1), '$tempat_lahir', '$tanggal_lahir', '$kelas_id', 1, '$password_hash', ''
    )";
    $result_tsiswa = mysqli_query($conn, $insert_tsiswa);

    // 3. Insert ke tabel users (type siswa)
    $insert_user = "INSERT INTO users (fullname, email, password, type, picture) VALUES (
        '$nama', '$email', '$password_hash', 'siswa', 'default.png'
    )";
    $result_user = mysqli_query($conn, $insert_user);

    if ($result_siswa && $result_tsiswa && $result_user) {
        echo "<script>alert('✅ Siswa berhasil ditambahkan.'); window.location='index.php?kelas_id=$kelas_id';</script>";
    } else {
        echo "<script>alert('❌ Gagal menambahkan siswa. Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Siswa - SMAN 1 Kota Sukabumi</title>
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

        .form-control {
            border-radius: 0.5rem;
            border-color: #ced4da;
        }

        .form-control:focus {
            border-color: var(--gradient-end);
            box-shadow: 0 0 0 0.2rem rgba(27, 127, 219, 0.25);
        }

        .btn-primary {
            background-color: var(--gradient-end);
            border-color: var(--gradient-end);
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--gradient-start);
            border-color: var(--gradient-start);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
            transform: translateY(-1px);
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
                                <h1 class="mb-0">Tambah Siswa Baru</h1>
                                <p class="lead mb-0">Formulir untuk menambahkan siswa ke kelas <?= htmlspecialchars($kelas['kelas']) ?>.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card p-4">
                        <div class="card-header">
                            <h2 class="h4 mb-0">➕ Tambah Siswa untuk Kelas <span class="text-primary"><?= htmlspecialchars($kelas['kelas']) ?></span></h2>
                        </div>
                        <div class="card-body">
                            <form method="post" class="mt-4">
                                <div class="form-group">
                                    <label for="nisn">NISN:</label>
                                    <input type="text" name="nisn" id="nisn" class="form-control" required placeholder="Masukkan NISN siswa">
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama Lengkap:</label>
                                    <input type="text" name="nama" id="nama" class="form-control" required placeholder="Masukkan nama lengkap siswa">
                                </div>
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin:</label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="agama">Agama:</label>
                                    <select name="agama" id="agama" class="form-control">
                                        <option value="">-- Pilih --</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen Protestan">Kristen Protestan</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Konghucu">Konghucu</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tempat_lahir">Tempat Lahir:</label>
                                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" placeholder="Masukkan tempat lahir">
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Lahir:</label>
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat:</label>
                                    <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="no_telepon">No. Telepon:</label>
                                    <input type="text" name="no_telepon" id="no_telepon" class="form-control" placeholder="Masukkan nomor telepon">
                                </div>

                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="text" name="email" id="email" class="form-control" placeholder="Masukkan Email">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password Login:</label>
                                    <input type="password" name="password" id="password" class="form-control" required placeholder="Masukkan password untuk login siswa">
                                </div>


                                <button type="submit" name="submit" class="btn btn-primary mt-3 mr-2">Simpan Data Siswa</button>
                                <a href="index.php?kelas_id=<?= $kelas_id ?>" class="btn btn-secondary mt-3">Batal dan Kembali</a>
                            </form>
                        </div>
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