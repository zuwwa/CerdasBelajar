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

// Ambil tagihan aktif
$tagihan_query = mysqli_query($conn, "
  SELECT 
    kt.id, kt.jenis_tagihan, kt.jml_tagihan,
    CAST(REPLACE(REPLACE(kt.jml_tagihan, 'Rp. ', ''), '.', '') AS UNSIGNED) AS total_tagihan,
    IFNULL(SUM(CAST(REPLACE(REPLACE(kp.jml_bayar, 'Rp. ', ''), '.', '') AS UNSIGNED)), 0) AS total_bayar
  FROM t_keuangan_tagihan kt
  LEFT JOIN t_keuangan_pembayaran kp ON kt.nis = kp.nis AND kt.jenis_tagihan = kp.jenis_tagihan
  WHERE kt.nis = '$nis'
  GROUP BY kt.id, kt.jenis_tagihan, kt.jml_tagihan
  HAVING total_bayar < total_tagihan
");

$tagihan_aktif = [];
while ($row = mysqli_fetch_assoc($tagihan_query)) {
  $tagihan_aktif[] = $row;
}

// Proses submit form
if (isset($_POST['submit'])) {
  $jenis_tagihan = $_POST['jenis_tagihan'];
  $jumlah_bayar = $_POST['jumlah_bayar'];
  $metode = $_POST['metode'];
  $tanggal_bayar = date('Y-m-d H:i:s');

  $bukti = $_FILES['bukti']['name'];
  $tmp = $_FILES['bukti']['tmp_name'];
  $folder = '../../uploads/bukti_pembayaran/';
  $filename = uniqid() . '_' . $bukti;

  if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
  }

  if (move_uploaded_file($tmp, $folder . $filename)) {
    $query = mysqli_query($conn, "
      INSERT INTO t_keuangan_pembayaran (nis, nama, kelas, alamat, jml_bayar, jenis_tagihan, metode, tanggal_bayar)
      VALUES ('$nis', '$nama', '$kelas', '$alamat', '$jumlah_bayar', '$jenis_tagihan', '$metode', '$tanggal_bayar')
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
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Konfirmasi Pembayaran - CerdasBelajar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/vertical-layout-light/style.css">
  <style>
    .bg-gradient-primary {
      background: linear-gradient(90deg, rgb(2, 40, 122), rgb(27, 127, 219));
    }
    .form-group label {
      font-weight: bold;
    }
    .btn-primary {
      background-color: #004080;
      border-color: #004080;
    }
  </style>
</head>
<body>
<div class="container-scroller">
  <div class="container-fluid page-body-wrapper">
    <?php include '../sidebar.php'; ?>

    <div class="main-panel">
      <div class="content-wrapper">

        <!-- Header -->
        <div class="row mb-4">
          <div class="col-md-12">
            <div class="bg-gradient-primary text-white p-4 rounded shadow">
              <h4>Konfirmasi Pembayaran</h4>
              <h2 class="mb-0"><?= htmlspecialchars($siswa['nama']) ?></h2>
            </div>
          </div>
        </div>

        <!-- Form Konfirmasi -->
        <div class="card p-4">
          <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <label for="jenis_tagihan">Pilih Tagihan</label>
              <select name="jenis_tagihan" class="form-control" required>
                <option value="">-- Pilih Tagihan --</option>
                <?php foreach ($tagihan_aktif as $tagihan): ?>
                  <option value="<?= htmlspecialchars($tagihan['jenis_tagihan']) ?>">
                    <?= htmlspecialchars($tagihan['jenis_tagihan']) ?> - Rp<?= number_format($tagihan['total_tagihan'], 0, ',', '.') ?>
                  </option>
                <?php endforeach; ?>
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

        <!-- Footer -->
        <footer class="footer mt-4">
          <div class="text-center">© SMAN 1 Kota Sukabumi 2025</div>
        </footer>
      </div>
    </div>
  </div>
</div>
</body>
</html>
