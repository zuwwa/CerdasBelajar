<?php
include('../../koneksi.php');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = mysqli_query($conn, "SELECT * FROM t_mapel WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
  echo "<h4 style='color:red;'>Data tidak ditemukan untuk ID $id</h4>";
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $kode       = $_POST['kode'];
  $mapel      = $_POST['nama_mapel'];
  $guru       = $_POST['nama_guru'];
  $id_kelas   = $_POST['id_kelas'];

  $update = mysqli_query($conn, "UPDATE t_mapel SET kode='$kode', nama_mapel='$mapel', nama_guru='$guru', id_kelas='$id_kelas' WHERE id=$id");

  if ($update) {
    header("Location: index.php");
    exit;
  } else {
    echo "<p style='color:red;'>Gagal update data.</p>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Mata Pelajaran</title>
  <link rel="stylesheet" href="../../vendors/typicons.font/font/typicons.css">
  <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="../images/smk.png" />
  <style>
    body {
      background-color: #f2f6fc;
      font-family: 'Poppins', sans-serif;
    }
    .form-container {
      max-width: 600px;
      margin: 80px auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    h3 {
      margin-bottom: 20px;
      color: #004080;
    }
    label {
      font-weight: 500;
      display: block;
      margin-bottom: 8px;
      color: #333;
    }
    input[type="text"], input[type="number"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    button {
      background: linear-gradient(90deg,#02287a,#1b7fdb);
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
    }
    button:hover {
      background: linear-gradient(90deg,#1b7fdb,#02287a);
    }
    a.kembali {
      display: inline-block;
      margin-top: 10px;
      color: #004080;
      text-decoration: none;
      font-weight: 500;
    }
    a.kembali:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h3>Edit Mata Pelajaran</h3>
  <form method="post">
    <label for="kode">Kode</label>
    <input type="text" id="kode" name="kode" value="<?= htmlspecialchars($data['kode']); ?>" required>

    <label for="nama_mapel">Nama Mata Pelajaran</label>
    <input type="text" id="nama_mapel" name="nama_mapel" value="<?= htmlspecialchars($data['nama_mapel']); ?>" required>

    <label for="nama_guru">Nama Guru</label>
    <input type="text" id="nama_guru" name="nama_guru" value="<?= htmlspecialchars($data['nama_guru']); ?>" required>

    <label for="id_kelas">ID Kelas</label>
    <input type="number" id="id_kelas" name="id_kelas" value="<?= htmlspecialchars($data['id_kelas']); ?>" required>

    <button type="submit">💾 Simpan Perubahan</button>
    <br>
    <a class="kembali" href="index.php">← Kembali ke daftar</a>
  </form>
</div>

</body>
</html>
