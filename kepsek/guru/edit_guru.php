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

$guru_id = $_GET['id'] ?? null;
if (!$guru_id) {
    redirectWithError('ID Guru tidak ditemukan.', 'index.php');
}

$data_guru = null;
$data_user = null;

// Ambil data guru dari t_guru dan users
// Tidak perlu mengambil password dari t_guru karena tidak akan digunakan
// PERBAIKAN: Menambahkan COLLATE untuk mengatasi masalah kolasi
$query = "SELECT tg.id, tg.nip, tg.nama, tg.jenis_kelamin, tg.tempat_lahir, tg.tanggal_lahir, tg.kelas, u.email FROM t_guru tg JOIN users u ON tg.nip COLLATE utf8mb4_general_ci = SUBSTRING_INDEX(u.email, '@', 1) COLLATE utf8mb4_general_ci WHERE tg.id = ?";
$stmt = mysqli_prepare($conn, $query);
if ($stmt === false) {
    error_log("Error preparing guru data query: " . mysqli_error($conn));
    redirectWithError('Terjadi kesalahan sistem saat mengambil data guru. Silakan coba lagi nanti.');
}
mysqli_stmt_bind_param($stmt, "i", $guru_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $data_guru = mysqli_fetch_assoc($result);
} else {
    redirectWithError('Data guru tidak ditemukan.', 'index.php');
}
mysqli_stmt_close($stmt);

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
    $id = $_POST['id'] ?? '';
    $nip = $_POST['nip'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $tempat_lahir = $_POST['tempat_lahir'] ?? '';
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $kelas = $_POST['kelas'] ?? ''; // Sekarang akan menjadi nilai dari dropdown
    $password_baru = $_POST['password'] ?? ''; // Password ini hanya untuk tabel users

    // Validasi input
    if (empty($id) || empty($nip) || empty($nama) || empty($jenis_kelamin) || empty($tempat_lahir) || empty($tanggal_lahir) || empty($kelas)) {
        redirectWithError('Semua kolom wajib diisi kecuali password jika tidak ingin diubah!');
    }

    // Mulai transaksi
    mysqli_begin_transaction($conn);

    try {
        // 1. Update tabel t_guru (kolom password tidak diubah)
        $query_guru = "UPDATE t_guru SET nip = ?, nama = ?, jenis_kelamin = ?, tempat_lahir = ?, tanggal_lahir = ?, kelas = ?, jk = ? WHERE id = ?";
        $stmt_guru = mysqli_prepare($conn, $query_guru);
        if ($stmt_guru === false) {
            throw new Exception("Gagal menyiapkan query update guru: " . mysqli_error($conn));
        }
        // Perhatikan parameter binding: sssssssi (tanpa parameter password)
        mysqli_stmt_bind_param($stmt_guru, "sssssssi", $nip, $nama, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $kelas, $jenis_kelamin, $id);
        if (!mysqli_stmt_execute($stmt_guru)) {
            throw new Exception("Gagal memperbarui data guru: " . mysqli_stmt_error($stmt_guru));
        }
        mysqli_stmt_close($stmt_guru);

        // 2. Update tabel users (password di-hash dan disimpan di sini jika ada perubahan)
        $email_guru_baru = $nip . '@sman1sukabumi.sch.id';
        $query_users = "UPDATE users SET fullname = ?, email = ?";
        if (!empty($password_baru)) {
            $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
            $query_users .= ", password = ?";
        }
        $query_users .= " WHERE email = ?"; // Update user berdasarkan email lama atau NIP lama

        $stmt_users = mysqli_prepare($conn, $query_users);
        if ($stmt_users === false) {
            throw new Exception("Gagal menyiapkan query update user: " . mysqli_error($conn));
        }

        // Dapatkan email lama guru untuk kondisi WHERE
        // Ini penting karena NIP mungkin berubah, jadi kita perlu email lama untuk menemukan record user
        $email_lama = $data_guru['nip'] . '@sman1sukabumi.sch.id';

        if (!empty($password_baru)) {
            mysqli_stmt_bind_param($stmt_users, "ssss", $nama, $email_guru_baru, $hashed_password, $email_lama);
        } else {
            mysqli_stmt_bind_param($stmt_users, "sss", $nama, $email_guru_baru, $email_lama);
        }

        if (!mysqli_stmt_execute($stmt_users)) {
            throw new Exception("Gagal memperbarui data user: " . mysqli_stmt_error($stmt_users));
        }
        mysqli_stmt_close($stmt_users);

        // Commit transaksi jika semua berhasil
        mysqli_commit($conn);
        redirectWithSuccess('Data guru dan akun user berhasil diperbarui!', 'index.php');

    } catch (Exception $e) {
        // Rollback transaksi jika ada error
        mysqli_rollback($conn);
        error_log("Error editing teacher: " . $e->getMessage());
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
    <title>Edit Guru - SMAN 1 Kota Sukabumi</title>
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
        </nav>

        <div class="container-fluid page-body-wrapper">
            <?php include '../sidesbar.php'; ?>
            <!-- MAIN PANEL -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Edit Data Guru</h4>
                                    <p class="card-description">
                                        Perbarui informasi guru di bawah ini.
                                    </p>
                                    <form class="forms-sample" method="POST">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($data_guru['id']); ?>">
                                        <div class="form-group">
                                            <label for="nip">NIP</label>
                                            <input type="text" class="form-control" id="nip" name="nip" placeholder="Nomor Induk Pegawai" value="<?= htmlspecialchars($data_guru['nip']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama">Nama Guru</label>
                                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap Guru" value="<?= htmlspecialchars($data_guru['nama']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="jenis_kelamin">Jenis Kelamin</label>
                                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="L" <?= ($data_guru['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                                <option value="P" <?= ($data_guru['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="tempat_lahir">Tempat Lahir</label>
                                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir" value="<?= htmlspecialchars($data_guru['tempat_lahir']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="tanggal_lahir">Tanggal Lahir</label>
                                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?= htmlspecialchars($data_guru['tanggal_lahir']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="kelas">Kelas Mengajar</label>
                                            <select class="form-control" id="kelas" name="kelas" required>
                                                <option value="">Pilih Kelas</option>
                                                <?php foreach ($data_kelas as $kelas_option) : ?>
                                                    <option value="<?= htmlspecialchars($kelas_option); ?>" <?= ($data_guru['kelas'] == $kelas_option) ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($kelas_option); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password Baru (kosongkan jika tidak ingin diubah)</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Isi untuk mengubah password">
                                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary mr-2">Perbarui</button>
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