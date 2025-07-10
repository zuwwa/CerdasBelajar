<?php
include('../../koneksi.php');

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nisn           = $_POST['nisn'];
    $nama           = $_POST['nama'];
    $tempat_lahir   = $_POST['tempat_lahir'];
    $tanggal_lahir  = $_POST['tanggal_lahir'];
    $jenis_kelamin  = $_POST['jenis_kelamin'];
    $agama          = $_POST['agama'];
    $alamat         = $_POST['alamat'];
    $no_telepon     = $_POST['no_telepon'];
    $email          = $_POST['email'];

    // Cek apakah NISN sudah ada
    $cek = mysqli_query($conn, "SELECT * FROM siswa WHERE nisn = '$nisn'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('NISN sudah terdaftar!'); window.location='tambah.php';</script>";
        exit();
    }

    // Insert ke database
    $insert = mysqli_query($conn, "INSERT INTO siswa 
        (nisn, nama, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, alamat, no_telepon, email)
        VALUES
        ('$nisn', '$nama', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', '$agama', '$alamat', '$no_telepon', '$email')
    ");

    if ($insert) {
        echo "<script>alert('Data siswa berhasil ditambahkan.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data siswa.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Siswa</title>
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
    <h2>Tambah Siswa Baru</h2>
    <form method="post">
        <label>NISN:</label>
        <input type="text" name="nisn" required>

        <label>Nama:</label>
        <input type="text" name="nama" required>

        <label>Tempat Lahir:</label>
        <input type="text" name="tempat_lahir" required>

        <label>Tanggal Lahir:</label>
        <input type="date" name="tanggal_lahir" required>

        <label>Jenis Kelamin:</label>
        <select name="jenis_kelamin" required>
            <option value="">-- Pilih --</option>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
        </select>

        <label>Agama:</label>
        <input type="text" name="agama" required>

        <label>Alamat:</label>
        <textarea name="alamat" required></textarea>

        <label>No Telepon:</label>
        <input type="text" name="no_telepon" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <button type="submit">Simpan</button>
    </form>
</body>
</html>
