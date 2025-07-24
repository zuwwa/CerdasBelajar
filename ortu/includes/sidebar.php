<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    :root {
      --primary-color: rgb(2, 40, 122);
      --secondary-color: rgb(27, 127, 219);
      --navbar-height: 70px;
      --sidebar-width: 250px;
    }

    body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f5f5;
      overflow-x: hidden;
      padding-top: var(--navbar-height);
    }

    .sidebar {
      width: var(--sidebar-width);
      height: 100vh;
      position: fixed;
      left: 0;
      top: var(--navbar-height);
      background-color: #252531;
      color: white;
      transition: all 0.3s;
      z-index: 1000;
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }



    .sidebar-menu {
      padding: 0;
      list-style: none;
    }

    .sidebar-menu li {
      position: relative;
    }

    .sidebar-menu li a {
      display: block;
      padding: 15px 20px;
      color: #b8c7ce;
      text-decoration: none;
      transition: all 0.3s;
      border-left: 3px solid transparent;
    }

    .sidebar-menu li a:hover {
      color: white;
      background-color: #525252;
      border-left: 3px solid var(--primary-color);
    }

    .sidebar-menu li a i {
      margin-right: 10px;
      width: 20px;
      text-align: center;
    }

    .login-prompt {
      color: white;
      padding: 20px;
      text-align: center;
    }

    .login-prompt a {
      color: #ffffff;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <!-- Jika user sudah login -->
    <ul class="sidebar-menu mt-4">
      <li>
        <a href="/CerdasBelajar/ortu/index.php" class="active">
          <i class="fas fa-tachometer-alt"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li>
        <a href="/CerdasBelajar/ortu/modules/profil/">
          <i class="fas fa-user"></i>
          <span>Profil</span>
        </a>
      </li>
      <li>
        <a href="/CerdasBelajar/ortu/modules/tagihan/">
          <i class="fas fa-file-invoice-dollar"></i>
          <span>Tagihan</span>
        </a>
      </li>
      <li>
        <a href="/CerdasBelajar/ortu/modules/pembayaran/">
          <i class="fas fa-money-bill-wave"></i>
          <span>Pembayaran</span>
        </a>
      </li>
      <li>
        <a href="/CerdasBelajar/ortu/modules/nilai/index.php">
          <i class="fas fa-book"></i>
          <span>Nilai</span>
        </a>
      </li>
      <li>
        <a href="/CerdasBelajar/ortu/modules/absensi/index.php">
          <i class="fas fa-calendar-check"></i>
          <span>Absensi</span>
        </a>
      </li>
      <li>
        <a href="/CerdasBelajar/logout.php">
          <i class="fas fa-sign-out-alt"></i>
          <span>Logout</span>
        </a>
      </li>
    </ul>

    <!-- Jika user belum login (hapus bagian di atas jika pakai ini saja) -->
    <!--
    <p class="login-prompt">
      <a href="login.php">
        <i class="fas fa-sign-in-alt me-1"></i>Silakan login terlebih dahulu
      </a>
    </p>
    -->
  </div>
</body>
</html>
