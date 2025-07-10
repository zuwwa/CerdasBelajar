<?php
session_start();
include('../../koneksi.php');

// Cek login & role siswa
if (!isset($_SESSION['email']) || $_SESSION['type'] !== 'siswa') {
  echo "<script>alert('⛔ Akses ditolak'); window.location='../../logout.php';</script>";
  exit;
}

// Ambil data siswa
$email = $_SESSION['email'];
$siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM siswa WHERE email = '$email'"));
if (!$siswa) {
  echo "<script>alert('❌ Siswa tidak ditemukan.'); window.location='../../logout.php';</script>";
  exit;
}
$siswa_id = $siswa['id'];

// Proses form submit
if (isset($_POST['submit'])) {
  $tagihan_id = $_POST['tagihan_id'];
  $jumlah_bayar = $_POST['jumlah_bayar'];
  $metode = $_POST['metode'];
  $tanggal = date('Y-m-d');

  // Upload bukti pembayaran
  $bukti = $_FILES['bukti']['name'];
  $tmp = $_FILES['bukti']['tmp_name'];
  $folder = '../../uploads/bukti_pembayaran/';
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

// Ambil daftar tagihan aktif siswa
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
              <h2 class="mb-0"><?= $siswa['nama'] ?></h2>
            </div>
          </div>
        </div>

        <!-- Form Konfirmasi -->
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
