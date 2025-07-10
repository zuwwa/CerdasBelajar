<?php 
session_start();
include '../../koneksi.php';

// Cek login siswa
if (!isset($_SESSION['email']) || $_SESSION['role'] != 2) {
    header("location:../index.php");
    exit;
}

$email = $_SESSION['email'];

// Ambil data siswa
$cek_siswa = mysqli_query($conn, "SELECT * FROM siswa WHERE email = '$email'");
$siswa = mysqli_fetch_assoc($cek_siswa);
if (!$siswa) {
    echo "<script>alert('❌ Data siswa tidak ditemukan.'); window.location='../../logout.php';</script>";
    exit();
}

$id_siswa = $siswa['id'];

// Proses gabung mapel
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['kode_gabung'])) {
    $kode = mysqli_real_escape_string($conn, $_POST['kode_gabung']);
    $cek = mysqli_query($conn, "SELECT * FROM mapel WHERE kode_mapel = '$kode'");

    if (mysqli_num_rows($cek) > 0) {
        $mapel = mysqli_fetch_assoc($cek);
        $id_mapel = $mapel['id'];

        // Cek apakah sudah tergabung
        $cekGabung = mysqli_query($conn, "SELECT * FROM anggota_mapel WHERE siswa_id = $id_siswa AND mapel_id = $id_mapel");
        if (mysqli_num_rows($cekGabung) < 1) {
            mysqli_query($conn, "INSERT INTO anggota_mapel (siswa_id, mapel_id) VALUES ($id_siswa, $id_mapel)");
            echo "<script>alert('✅ Berhasil gabung mapel!'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('⚠️ Kamu sudah tergabung pada mapel ini.');</script>";
        }
    } else {
        echo "<script>alert('❌ Kode mapel tidak ditemukan.');</script>";
    }
}

// Ambil daftar mapel yang sudah digabung siswa
$result = mysqli_query($conn, "
  SELECT mapel.*, kelas.kelas AS nama_kelas, guru.nama AS nama_guru
  FROM anggota_mapel
  JOIN mapel ON anggota_mapel.mapel_id = mapel.id
  LEFT JOIN kelas ON mapel.kelas_id = kelas.id
  LEFT JOIN guru ON mapel.guru_id = guru.id
  WHERE anggota_mapel.siswa_id = $id_siswa
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Mata Pelajaran - SMAN 1 Kota Sukabumi</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
  <link rel="shortcut icon" href="../images/sma.png" />
  <style>
    body { background-color: #f4f6f9; }
    .header-section {
      background-color: #162d6a;
      color: white;
      padding: 40px 20px;
      text-align: center;
    }
    .header-section h2 { font-weight: bold; }
    .btn-success {
      border-radius: 20px;
      font-weight: 500;
      padding: 8px 20px;
    }
    .btn-kembali {
      position: absolute;
      top: 20px;
      left: 20px;
      background: white;
      color: #162d6a;
      border-radius: 20px;
      padding: 6px 15px;
      font-weight: 500;
      border: none;
    }
    .btn-kembali:hover {
      background: #f0f0f0;
      color: #0d2a5e;
    }
    .table td, .table th { vertical-align: middle; }
  </style>
</head>
<body>

<!-- Header -->
<div class="header-section position-relative">
  <a href="../index.php" class="btn btn-light btn-sm btn-kembali">← Kembali ke Beranda</a>
  <h2>Daftar Mata Pelajaran</h2>
  <p class="mt-2">Berikut daftar mata pelajaran yang telah kamu ikuti. Gunakan kode unik untuk bergabung.</p>
  <button class="btn btn-success mt-3" onclick="document.getElementById('popupGabung').style.display='block'">+ Gabung Mata Pelajaran</button>
</div>

<!-- Konten -->
<div class="container my-5">
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table id="tabelMapel" class="table table-striped">
          <thead class="thead-dark">
            <tr>
              <th>No</th>
              <th>Nama Mapel</th>
              <th>Kode</th>
              <th>Guru</th>
              <th>Kelas</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) : ?>
              <tr>
                <td><?= $no++; ?></td>
                <td class="text-primary font-weight-bold"><?= htmlspecialchars($row['nama_mapel']); ?></td>
                <td><?= htmlspecialchars($row['kode_mapel']); ?></td>
                <td><?= htmlspecialchars($row['nama_guru']); ?></td>
                <td><?= htmlspecialchars($row['nama_kelas']); ?></td>
                <td><a href="mapel.php?kode=<?= $row['kode_mapel']; ?>" class="btn btn-primary btn-sm">Masuk</a></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- POPUP GABUNG -->
<div id="popupGabung" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;">
  <div style="background:#fff; max-width:500px; margin:100px auto; padding:30px 40px; border-radius:12px; box-shadow:0 8px 25px rgba(0,0,0,0.2); animation:fadeIn 0.3s;">
    <h5 class="text-center mb-4">Gabung Mata Pelajaran</h5>
    <form method="post">
      <input type="text" name="kode_gabung" class="form-control" placeholder="Masukkan Kode Mapel" required>
      <div class="d-flex justify-content-center mt-4">
        <button type="submit" class="btn btn-success mr-3">Gabung</button>
        <button type="button" onclick="document.getElementById('popupGabung').style.display='none'" class="btn btn-danger">Batal</button>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
  $(document).ready(function() {
    $('#tabelMapel').DataTable();
  });
</script>

</body>
</html>
