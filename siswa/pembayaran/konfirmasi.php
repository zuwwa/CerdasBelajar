<?php
session_start();
include('../../koneksi.php');

// Cek login & role siswa
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../logout.php");
    exit;
}

// Ambil data siswa
$email = $_SESSION['email'];
$siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa WHERE email = '$email'"));
if (!$siswa) {
    echo "<script>alert('❌ Siswa tidak ditemukan.'); window.location='../../logout.php';</script>";
    exit;
}
$nis = $siswa['nisn'];
$nama = $siswa['nama'];
$kelas = $siswa['kelas_id'];
$alamat = $siswa['alamat'] ?? '-';

// Ambil notifikasi (Diambil dari index.php untuk konsistensi)
$jumlah_notif = 0;
$daftar_notif = [];
$notif_query = mysqli_query($conn, "SELECT * FROM notifikasi WHERE siswa_id = '$siswa_id' ORDER BY waktu DESC LIMIT 5");
$jumlah_notif = mysqli_num_rows($notif_query);
while ($row = mysqli_fetch_assoc($notif_query)) {
    $daftar_notif[] = $row;
}

// Proses form submit
if (isset($_POST['submit'])) {
    $tagihan_id = $_POST['tagihan_id'];
    $jumlah_bayar = $_POST['jumlah_bayar'];
    $metode = $_POST['metode'];
    $tanggal = date('Y-m-d');

    // Upload bukti pembayaran
    $bukti = $_FILES['bukti']['name'];
    $tmp = $_FILES['bukti']['tmp_name'];
    $folder = '../../uploads/bukti_pembayaran/'; // Path relatif dari konfirmasi.php ke folder uploads
    $filename = uniqid() . '_' . $bukti;

    if (move_uploaded_file($tmp, $folder . $filename)) {
        // Simpan ke database
        $query = mysqli_query($conn, "
            INSERT INTO pembayaran (siswa_id, tagihan_id, jumlah_bayar, tanggal, status, metode, keterangan)
            VALUES ('$siswa_id', '$tagihan_id', '$jumlah_bayar', '$tanggal', 'menunggu', '$metode', '$filename')
        ");
        if ($query) {
            echo "<script>alert('✅ Bukti pembayaran berhasil dikirim. Tunggu verifikasi.'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('❌ Gagal menyimpan ke database.');</script>";
        }
    } else {
        echo "<script>alert('❌ Gagal upload bukti pembayaran.');</script>";
    }
}

// Ambil daftar tagihan aktif siswa (opsional, jika Anda ingin mengisi dropdown berdasarkan tagihan yang belum lunas)
$tagihan_query = mysqli_query($conn, "
    SELECT * FROM tagihan
    WHERE siswa_id = '$siswa_id' AND id NOT IN (
        SELECT tagihan_id FROM pembayaran WHERE siswa_id = '$siswa_id' AND status = 'lunas'
    )
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pembayaran - CerdasBelajar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../vendors/typicons.font/font/typicons.css">
    <link rel="stylesheet" href="../css/vertical-layout-light/style.css">
    <link rel="shortcut icon" href="../images/sma.png">
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
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
        .table th, .table td {
            vertical-align: middle !important;
        }
        /* Style untuk Sidebar - disamakan dengan index.php */
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
                        <img src="../images/bell-icon.png" alt="Notifikasi">
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
                        <img src="../images/profile.png?v=2" alt="Profil">
                    </a>
                </li>
                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link nav-profile-icon" href="../../logout.php" onclick="return confirm('Yakin ingin logout?')">
                        <img src="../images/logout.png" alt="Logout">
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container-fluid page-body-wrapper">
        <?php include 'sidebar.php'; ?>
        <div class="main-panel">
            <div class="content-wrapper">

                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="bg-gradient-primary text-white p-4 rounded shadow">
                            <h4>Konfirmasi Pembayaran</h4>
                            <h2 class="mb-0"><?= $siswa['nama'] ?></h2>
                        </div>
                    </div>
                </div>

                <div class="card p-4">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="tagihan_id">Pilih Tagihan</label>
                            <select name="tagihan_id" class="form-control" required>
                                <option value="">-- Pilih Tagihan --</option>
                                <?php while ($tagihan = mysqli_fetch_assoc($tagihan_query)): ?>
                                    <option value="<?= $tagihan['id'] ?>"><?= $tagihan['nama_tagihan'] ?> - Rp<?= number_format($tagihan['total'], 0, ',', '.') ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="jumlah_bayar">Jumlah Bayar (Rp)</label>
                            <input type="number" name="jumlah_bayar" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="metode">Metode Pembayaran</label>
                            <select name="metode" class="form-control" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="VA BNI">Virtual Account BNI</option>
                                <option value="VA BSI">Virtual Account BSI</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="Tunai">Tunai</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="bukti">Upload Bukti Pembayaran</label>
                            <input type="file" name="bukti" class="form-control-file" accept=".jpg,.jpeg,.png,.pdf" required>
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary">Kirim Konfirmasi</button>
                        <a href="index.php" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>

                <footer class="footer mt-4">
                    <div class="text-center">© SMAN 1 Kota Sukabumi 2025</div>
                </footer>
            </div>
        </div>
    </div>
</div>

<script src="../vendors/js/vendor.bundle.base.js"></script>
<script src="../js/off-canvas.js"></script>
<script src="../js/template.js"></script>
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