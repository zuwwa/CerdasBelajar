<?php
include('../../koneksi.php');

// Ambil NISN dari URL
if (!isset($_GET['nisn'])) {
    echo "NISN tidak ditemukan!";
    exit;
}

$nisn = $_GET['nisn'];

// Ambil data siswa
$query = mysqli_query($conn, "SELECT * FROM siswa WHERE nisn = '$nisn'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}

// Simpan perubahan jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama           = $_POST['nama'];
    $tempat_lahir   = $_POST['tempat_lahir'];
    $tanggal_lahir  = $_POST['tanggal_lahir'];
    $jenis_kelamin  = $_POST['jenis_kelamin'];
    $agama          = $_POST['agama'];
    $alamat         = $_POST['alamat'];
    $no_telepon     = $_POST['no_telepon'];
    $email          = $_POST['email'];

    $update = mysqli_query($conn, "UPDATE siswa SET 
        nama = '$nama',
        tempat_lahir = '$tempat_lahir',
        tanggal_lahir = '$tanggal_lahir',
        jenis_kelamin = '$jenis_kelamin',
        agama = '$agama',
        alamat = '$alamat',
        no_telepon = '$no_telepon',
        email = '$email'
        WHERE nisn = '$nisn'
    ");

    if ($update) {
        echo "<script>alert('Data berhasil diupdate.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input, select, textarea {
            width: 50%;
            padding: 8px;
        }
        button {
            margin-top: 15px;
            padding: 10px 20px;
        }
    </style>
</head>
<body>
    <h2>Edit Profil Siswa</h2>
    <form method="post">
        <label>NISN (tidak bisa diubah):</label>
        <input type="text" name="nisn" value="<?= $data['nisn'] ?>" disabled>

        <label>Nama:</label>
        <input type="text" name="nama" value="<?= $data['nama'] ?>" required>

        <label>Tempat Lahir:</label>
        <input type="text" name="tempat_lahir" value="<?= $data['tempat_lahir'] ?>" required>

        <label>Tanggal Lahir:</label>
        <input type="date" name="tanggal_lahir" value="<?= $data['tanggal_lahir'] ?>" required>

        <label>Jenis Kelamin:</label>
        <select name="jenis_kelamin" required>
            <option value="Laki-laki" <?= $data['jenis_kelamin'] == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
            <option value="Perempuan" <?= $data['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
        </select>

        <label>Agama:</label>
        <input type="text" name="agama" value="<?= $data['agama'] ?>" required>

        <label>Alamat:</label>
        <textarea name="alamat" required><?= $data['alamat'] ?></textarea>

        <label>No Telepon:</label>
        <input type="text" name="no_telepon" value="<?= $data['no_telepon'] ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= $data['email'] ?>" required>

        <button type="submit">Simpan Perubahan</button>
    </form>
</body>
</html>
