<?php
include('../../koneksi.php');

// Ambil data kelas untuk dropdown
$kelasQuery = mysqli_query($conn, "SELECT id, kelas, angkatan FROM t_kelas ORDER BY angkatan DESC, kelas ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $kode       = $_POST['kode'];
  $nama_mapel = $_POST['nama_mapel'];
  $nama_guru  = $_POST['nama_guru'];
  $id_kelas   = $_POST['id_kelas'];

  $insert = mysqli_query($conn, "INSERT INTO t_mapel (kode, nama_mapel, nama_guru, id_kelas) VALUES ('$kode', '$nama_mapel', '$nama_guru', '$id_kelas')");

  if ($insert) {
    header("Location: index.php");
    exit;
  } else {
    echo "<p style='color:red;'>❌ Gagal menambahkan data.</p>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Mata Pelajaran</title>
  <link rel="stylesheet" href="/CerdasBelajar/vendors/typicons.font/font/typicons.css">
  <link rel="stylesheet" href="/CerdasBelajar/css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="/CerdasBelajar/images/sma.png" />
  <style>
    body {
      background-color: #f4f8ff;
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
    input[type="text"], select {
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
  <h3>➕ Tambah Mata Pelajaran</h3>
  <form method="post">
    <label for="kode">Kode</label>
    <input type="text" id="kode" name="kode" placeholder="Contoh: BIO01" required>

    <label for="nama_mapel">Nama Mata Pelajaran</label>
    <input type="text" id="nama_mapel" name="nama_mapel" placeholder="Contoh: Biologi" required>

    <label for="nama_guru">Nama Guru Pengampu</label>
    <input type="text" id="nama_guru" name="nama_guru" placeholder="Contoh: Pak Ridwan" required>

    <label for="id_kelas">Pilih Kelas</label>
    <select id="id_kelas" name="id_kelas" required>
      <option value="">-- Pilih Kelas --</option>
      <?php while ($row = mysqli_fetch_assoc($kelasQuery)): ?>
        <option value="<?= $row['id'] ?>">
          <?= htmlspecialchars($row['kelas']) ?> - Angkatan <?= htmlspecialchars($row['angkatan']) ?>
        </option>
      <?php endwhile; ?>
    </select>

    <button type="submit">Simpan</button>
    <br>
    <a class="kembali" href="index.php">← Kembali ke Daftar</a>
  </form>
</div>

</body>
</html>
