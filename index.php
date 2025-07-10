<?php 
session_start();
include("configGoogle.php");
include("koneksi.php");

// Ambil role dari URL kalau ada
$role_dipilih = isset($_GET['role']) ? $_GET['role'] : null;
if ($role_dipilih) {
    $_SESSION['dipilih'] = $role_dipilih;
}

// Jika sudah login dan punya session email, arahkan ke login.php untuk set session lengkap
if (isset($_SESSION['access_token']) && isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Jika kembali dari Google OAuth
if (isset($_GET["code"])) {
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
    if (!isset($token['error'])) {
        $google_client->setAccessToken($token['access_token']);
        $_SESSION['access_token'] = $token['access_token'];

        $google_service = new Google_Service_Oauth2($google_client);
        $data = $google_service->userinfo->get();

        $email     = strtolower(trim($data['email']));
        $fullname  = $data['name'];
        $picture   = $data['picture'];
        $created   = date("Y-m-d H:i:s");

        // Cek apakah user sudah ada
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Jika belum ada, masukkan user baru ke tabel
        if ($result->num_rows < 1) {
            $role_from_url = $_SESSION['dipilih'] ?? null;
            $stmt_insert = $conn->prepare("INSERT INTO users (fullname, email, picture, created_at, id_role) VALUES (?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("sssss", $fullname, $email, $picture, $created, $role_from_url);
            $stmt_insert->execute();

            echo "<script>alert('✅ Akun berhasil dibuat. Silakan hubungi admin untuk menetapkan role.'); window.location='logout.php';</script>";
            exit;
        }

        // Simpan session dasar, lanjut ke login.php
        $_SESSION['email']     = $email;
        $_SESSION['username']  = $fullname;
        $_SESSION['picture']   = $picture;

        header("Location: login.php");
        exit;
    }
}

// URL Login Google
$login_url = $google_client->createAuthUrl();
?>




<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login CerdasBelajar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background: #f5f5f5;
    }

    .topbar {
      background: #15264e;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 30px;
      flex-wrap: wrap;
    }

    .topbar-left {
      display: flex;
      align-items: center;
    }

    .topbar img {
      height: 50px;
      margin-right: 12px;
    }

    .topbar .text h1 {
      font-size: 18px;
      margin: 0;
    }

    .topbar .text p {
      font-size: 14px;
      margin: 0;
      color: #ccc;
    }

    .topbar .login-buttons a {
      margin: 5px;
      text-decoration: none;
    }

    .btn {
      padding: 8px 16px;
      font-size: 14px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      color: white;
    }

    .siswa  { background: #1b7fdb; }
    .guru   { background: #28a745; }
    .ortu   { background: #ff9800; }
    .kepsek { background: #9c27b0; }
    .admin  { background: #333; }

    .login-box {
      max-width: 420px;
      margin: 50px auto 40px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      text-align: center;
    }

    .google {
      background: #db4437;
      color: white;
      margin-top: 20px;
      padding: 12px;
      width: 100%;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .info {
      max-width: 960px;
      margin: 0 auto 40px;
      padding: 20px;
      text-align: center;
      font-size: 16px;
      color: #333;
    }

    .features {
      display: flex;
      justify-content: center;
      gap: 20px;
      flex-wrap: wrap;
      margin-bottom: 60px;
    }

    .feature {
      width: 260px;
      background: #15264e;
      color: white;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      text-align: center;
    }

    .feature h3 {
      margin-top: 10px;
      font-size: 18px;
    }

    .feature img {
      width: 60px;
      height: 60px;
    }
  </style>
</head>
<body>

  <div class="topbar">
    <div class="topbar-left">
      <img src="aset/gambar/sma.png" alt="Logo">
      <div class="text">
        <h1>MANAGEMENT SYSTEM</h1>
        <p>SMA NEGERI 1 KOTA SUKABUMI</p>
      </div>
    </div>
    <div class="login-buttons">
      <a href="?role=2"><button class="btn siswa">Siswa</button></a>
      <a href="?role=3"><button class="btn guru">Guru</button></a>
      <a href="?role=4"><button class="btn ortu">Orang Tua</button></a>
      <a href="?role=Kepala Sekolah"><button class="btn kepsek">Kepala Sekolah</button></a>
      <a href="?role=1"><button class="btn admin">Admin</button></a>
    </div>
  </div>

  <!-- Gambar Header SMA Full Lebar -->
  <div style="margin: 0; padding: 0;">
    <img id="header-img" src="aset/gambar/header sma.webp" alt="Header SMA" style="width: 100%; height: 350px; object-fit: cover; display: block; margin: 0; padding: 0;">
  </div>

  <?php if ($role_dipilih): ?>
    <div class="login-box">
      <h2>Halaman <?= ucfirst($role_dipilih) ?></h2>
      <p style="color: #666; font-size: 14px;">
        Masuk menggunakan akun Google Anda atau metode lain yang terdaftar.
      </p>

      <input type="text" placeholder="NIM / Username" style="width: 100%; padding: 10px; margin: 12px 0; border-radius: 6px; border: 1px solid #ccc; font-size: 14px;">
      <input type="password" placeholder="Kata Sandi" style="width: 100%; padding: 10px; margin-bottom: 16px; border-radius: 6px; border: 1px solid #ccc; font-size: 14px;">

      <button style="width: 100%; background: #15264e; color: white; padding: 12px; border: none; border-radius: 6px; font-size: 14px; margin-bottom: 10px; cursor: pointer;">
        Masuk
      </button>

      <div style="margin: 12px 0; font-size: 14px; color: #888;">Atau</div>

      <a href="<?= $login_url ?>">
        <button class="google">Lanjutkan dengan Google</button>
      </a>

      <p style="margin-top: 16px; font-size: 13px; color: #888;">
        Ada masalah saat login? <a href="#" style="color: #1b7fdb;">Cari bantuan</a>
      </p>
    </div>
  <?php else: ?>
    <div class="info">
      <p>Untuk pengalaman terbaik menggunakan sistem ini, silakan gunakan browser <strong>Chrome</strong> atau <strong>Microsoft Edge</strong>.</p>
    </div>

    <div class="features">
      <div class="feature">
        <img src="aset/gambar/academic.png" alt="Academic">
        <h3>Sistem Informasi Akademik</h3>
      </div>
      <div class="feature">
        <img src="aset/gambar/sekolah.png" alt="Website">
        <h3>Website Resmi Sekolah</h3>
      </div>
      <div class="feature">
        <img src="aset/gambar/perpus.png" alt="Library">
        <h3>Sistem Informasi Perpustakaan</h3>
      </div>
    </div>
  <?php endif; ?>

<script>
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has('role')) {
    const headerImg = document.getElementById('header-img');
    if (headerImg) {
      headerImg.style.display = 'none';
    }
  }
</script>
</body>
</html>
