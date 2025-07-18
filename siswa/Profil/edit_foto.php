<?php
session_start();
include('../../koneksi.php');

// Pastikan sudah login
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../logout.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Foto</title>
  <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
</head>
<body>
<div class="container-scroller">
  <div class="container-fluid page-body-wrapper">
    <div class="main-panel w-100">
      <div class="content-wrapper">
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="card p-4">
              <h4 class="card-title">Edit Foto Profil</h4>
              <form action="proses_edit_foto.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="foto">Pilih Foto Baru</label>
                  <input type="file" class="form-control" name="foto" required accept="image/*">
                  <small class="text-muted">Format JPG/PNG, max 2MB</small>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
              </form>
            </div>
          </div>
        </div>
      </div>    
    </div>
  </div>
</div>
</body>
</html>
