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

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>alert('ID tidak ditemukan.'); window.location='index.php';</script>";
    exit;
}

// Ambil data siswa dan user
$query = mysqli_query($conn, "
    SELECT ts.*, s.tempat_lahir, s.tanggal_lahir, s.alamat, s.no_telepon, u.password AS password_user
    FROM t_siswa ts
    JOIN siswa s ON ts.nis = s.nisn
    LEFT JOIN users u ON u.email = ts.nis
    WHERE ts.id = '$id'
");

$siswa = mysqli_fetch_assoc($query);
if (!$siswa) {
    echo "<script>alert('Data siswa tidak ditemukan.'); window.location='index.php';</script>";
    exit;
}

// Proses update
if (isset($_POST['update'])) {
    $nama           = mysqli_real_escape_string($conn, $_POST['nama']);
    $jenis_kelamin  = $_POST['jenis_kelamin'];
    $tempat_lahir   = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
    $tanggal_lahir  = $_POST['tanggal_lahir'];
    $alamat         = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon        = mysqli_real_escape_string($conn, $_POST['no_telepon']);
    $password_baru  = $_POST['password'];

    // Update ke dua tabel
    mysqli_query($conn, "UPDATE t_siswa SET nama='$nama', jenis_kelamin='$jenis_kelamin' WHERE id='$id'");
    mysqli_query($conn, "UPDATE siswa SET tempat_lahir='$tempat_lahir', tanggal_lahir='$tanggal_lahir', alamat='$alamat', no_telepon='$telepon' WHERE nisn='" . $siswa['nis'] . "'");

    // Update password di tabel users jika diisi
    if (!empty($password_baru)) {
        $pass_hash = password_hash($password_baru, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET password='$pass_hash' WHERE email='" . $siswa['nis'] . "' AND type='siswa'");
    }

    echo "<script>alert('✅ Data siswa berhasil diperbarui!'); window.location='index.php?kelas_id=" . $siswa['kelas'] . "';</script>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Siswa</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4>Edit Data Siswa</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label>NIS</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($siswa['nis']) ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($siswa['nama']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="L" <?= $siswa['jenis_kelamin'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= $siswa['jenis_kelamin'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" value="<?= htmlspecialchars($siswa['tempat_lahir']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="<?= $siswa['tanggal_lahir'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" required><?= htmlspecialchars($siswa['alamat']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>No Telepon</label>
                        <input type="text" name="no_telepon" class="form-control" value="<?= htmlspecialchars($siswa['no_telepon']) ?>" required>
                    </div>
                    <div class="form-group">
    <label>Ganti Password (kosongkan jika tidak diubah)</label>
    <input type="password" name="password" class="form-control" placeholder="Ketik password baru...">
</div>

                    <button type="submit" name="update" class="btn btn-success">💾 Simpan Perubahan</button>
                    <a href="index.php?kelas_id=<?= $siswa['kelas'] ?>" class="btn btn-secondary">🔙 Kembali</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
