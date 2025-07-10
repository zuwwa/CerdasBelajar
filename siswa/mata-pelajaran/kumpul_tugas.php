<?php
session_start();
include('../../koneksi.php');

// Cek login siswa
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'siswa') {
  echo "<script>alert('⛔ Akses ditolak!'); window.location='../../logout.php';</script>";
  exit;
}

$siswa_id = $_SESSION['id_user'];
$tugas_id = $_GET['id'] ?? '';

if (!$tugas_id) {
  echo "<script>alert('❌ Tugas tidak ditemukan.'); window.location='index.php';</script>";
  exit;
}

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $upload_dir = '../../uploads/jawaban/';
  if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

  $file_name = basename($_FILES['file_jawaban']['name']);
  $file_tmp = $_FILES['file_jawaban']['tmp_name'];
  $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
  $allowed_ext = ['pdf', 'doc', 'docx', 'ppt', 'pptx'];

  if (!in_array($file_ext, $allowed_ext)) {
    echo "<script>alert('❌ Format file tidak didukung!');</script>";
    exit;
  }

  $new_file_name = time() . '_' . preg_replace('/[^a-zA-Z0-9\._]/', '_', $file_name);
  $target_path = $upload_dir . $new_file_name;

  if (move_uploaded_file($file_tmp, $target_path)) {
    $tanggal = date('Y-m-d H:i:s');
    $cek = mysqli_query($conn, "SELECT * FROM pengumpulan_tugas WHERE tugas_id = $tugas_id AND siswa_id = $siswa_id");

    if (mysqli_num_rows($cek) > 0) {
      // Update pengumpulan
      mysqli_query($conn, "
        UPDATE pengumpulan_tugas 
        SET file_jawaban = '$new_file_name', tanggal_kumpul = '$tanggal', status = 'Terkumpul' 
        WHERE tugas_id = $tugas_id AND siswa_id = $siswa_id
      ");
    } else {
      // Insert pengumpulan
      mysqli_query($conn, "
        INSERT INTO pengumpulan_tugas (tugas_id, siswa_id, file_jawaban, tanggal_kumpul, status) 
        VALUES ($tugas_id, $siswa_id, '$new_file_name', '$tanggal', 'Terkumpul')
      ");
    }

    echo "<script>alert('✅ Tugas berhasil dikumpulkan!'); window.location='index.php';</script>";
  } else {
    echo "<script>alert('❌ Gagal mengupload file.');</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kumpulkan Tugas</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
  <div class="card shadow p-4">
    <h4 class="mb-4 text-primary">📥 Kumpulkan Tugas</h4>
    <form method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="file_jawaban">Upload File Jawaban (.pdf, .doc, .ppt, .docx, .pptx):</label>
        <input type="file" name="file_jawaban" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-success">Kumpulkan</button>
      <a href="index.php" class="btn btn-secondary ml-2">Batal</a>
    </form>
  </div>
</div>
</body>
</html>
