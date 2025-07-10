<?php
include('../../koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $kode       = $_POST['kode'];
  $nama_mapel = $_POST['nama_mapel'];
  $nama_guru  = $_POST['nama_guru'];
  $id_kelas   = $_POST['id_kelas'];

  $insert = mysqli_query($conn, "INSERT INTO t_mapel (kode, nama_mapel, nama_guru, id_kelas) VALUES ('$kode', '$nama_mapel', '$nama_guru', '$id_kelas')");

  if ($insert) {
    echo "<script>alert('✅ Mata Pelajaran berhasil ditambahkan!'); window.location='index.php';</script>";
    exit;
  } else {
    echo "<script>alert('❌ Gagal menambahkan data!');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Gabung / Tambah Mata Pelajaran</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body {
      background: rgba(0, 0, 0, 0.4);
      font-family: 'Segoe UI', sans-serif;
      height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .popup-container {
      background-color: #fff;
      border-radius: 10px;
      width: 100%;
      max-width: 500px;
      padding: 30px 40px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.2);
      animation: fadeIn 0.3s ease-in-out;
    }
    .popup-container h4 {
      text-align: center;
      margin-bottom: 25px;
      font-weight: 600;
    }
    .form-group label {
      font-weight: 500;
    }
    .form-control {
      border-radius: 8px;
      padding: 10px;
    }
    .btn-group {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-top: 20px;
    }
    .btn-join {
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 25px;
      padding: 10px 24px;
      font-weight: 600;
    }
    .btn-cancel {
      background-color: #dc3545;
      color: white;
      border: none;
      border-radius: 25px;
      padding: 10px 24px;
      font-weight: 600;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to   { opacity: 1; transform: scale(1); }
    }
  </style>
</head>
<body>

  <form method="post" class="popup-container">
    <h4>Tambah Mata Pelajaran</h4>

    <div class="form-group">
      <label for="kode">Kode Unik</label>
      <input type="text" class="form-control" id="kode" name="kode" placeholder="Contoh: 2344" required>
    </div>

    <div class="form-group">
      <label for="nama_mapel">Nama Mata Pelajaran</label>
      <input type="text" class="form-control" id="nama_mapel" name="nama_mapel" placeholder="Contoh: Fisika" required>
    </div>

    <div class="form-group">
      <label for="nama_guru">Nama Guru</label>
      <input type="text" class="form-control" id="nama_guru" name="nama_guru" placeholder="Contoh: Bpk. Ujang" required>
    </div>

    <div class="form-group">
      <label for="id_kelas">ID Kelas</label>
      <input type="number" class="form-control" id="id_kelas" name="id_kelas" placeholder="Contoh: 1" required>
    </div>

    <div class="btn-group">
      <button type="submit" class="btn-join">GABUNG</button>
      <a href="index.php" class="btn-cancel">BATAL</a>
    </div>
  </form>

</body>
</html>
