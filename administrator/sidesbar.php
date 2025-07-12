<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    <!-- Profil Admin -->
    <li class="nav-item">
      <div class="d-flex flex-column align-items-center sidebar-profile" style="padding: 20px 0;">
        <!-- Foto Sekolah/Admin -->
        <img src="/CerdasBelajar/administrator/images/sma.png" alt="Foto Sekolah" style="width: 70px; height: 70px; border-radius: 8px; object-fit: cover;">

        <?php
        // Ambil email dari session
        $email = $_SESSION['email'] ?? '';

        // Include koneksi.php secara absolut
        include($_SERVER['DOCUMENT_ROOT'] . '/CerdasBelajar/koneksi.php');

        // Ambil nama admin dari tabel users
        $query = mysqli_query($conn, "SELECT fullname, email FROM users WHERE email = '$email' AND type = 'admin'");
        $admin = mysqli_fetch_assoc($query);
        ?>

        <!-- Info Profil -->
        <h3 style="font-size: 16px; margin-top: 10px; color: #fff;">
          <?= $admin['username'] ?? 'Admin'; ?>
        </h3>
        <p style="font-size: 13px; color: #ccc; margin-bottom: 2px;">
          <?= $admin['email'] ?? '-'; ?>
        </p>
        <p style="font-size: 13px; color: #ccc;">
          Administrator
        </p>
      </div>

      <p class="sidebar-menu-title">Navigasi</p>
    </li>

    <!-- Menu Navigasi Admin -->
    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/administrator/index.php">
        <i class="typcn typcn-home menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/administrator/Profil/index.php">
        <i class="typcn typcn-user menu-icon"></i>
        <span class="menu-title">Profil Admin</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/administrator/t-siswa/">
        <i class="typcn typcn-group menu-icon"></i>
        <span class="menu-title">Manajemen Siswa</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/administrator/t-guru/">
        <i class="typcn typcn-briefcase menu-icon"></i>
        <span class="menu-title">Manajemen Guru</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/administrator/mata-pelajaran/">
        <i class="typcn typcn-book menu-icon"></i>
        <span class="menu-title">Mata Pelajaran</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/administrator/t-keuangan/">
        <i class="typcn typcn-credit-card menu-icon"></i>
        <span class="menu-title">Keuangan</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/administrator/laporan/">
        <i class="typcn typcn-chart-bar menu-icon"></i>
        <span class="menu-title">Laporan</span>
      </a>
    </li>

    <!-- Logout -->
    <li class="nav-item">
      <a class="nav-link text-danger" href="/CerdasBelajar/administrator/logout.php" onclick="return confirm('Yakin ingin logout?')">
        <i class="typcn typcn-power menu-icon"></i>
        <span class="menu-title">Logout</span>
      </a>
    </li>

  </ul>
</nav>
