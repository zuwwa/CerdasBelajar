<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    <!-- Profil Admin -->
    <li class="nav-item">
      <div class="d-flex flex-column align-items-center sidebar-profile" style="padding: 20px 0;">
        <!-- Foto Sekolah/Admin -->
        <img src="/CerdasBelajar/images/sma.png" alt="Foto Sekolah" style="width: 70px; height: 70px; border-radius: 8px; object-fit: cover;">

        <?php
        // Ambil email dari session
        $email = $_SESSION['email'] ?? '';

        // Include koneksi.php secara absolut
        include($_SERVER['DOCUMENT_ROOT'] . '/CerdasBelajar/koneksi.php');

        // Ambil nama admin dari tabel users
        $query = mysqli_query($conn, "SELECT fullname, email FROM users WHERE email = '$email' AND type = 'kepsek'");
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
          Kepala Sekolah
        </p>
      </div>

      <p class="sidebar-menu-title">Navigasi</p>
    </li>

    <!-- Menu Navigasi Admin -->
    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/kepsek/index.php">
        <i class="typcn typcn-home menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/kepsek/nilai/">
        <i class="typcn typcn-chart-bar menu-icon"></i>
        <span class="menu-title">Niai Siswa</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/kepsek/agenda/">
        <i class="typcn typcn-book menu-icon"></i>
        <span class="menu-title">Kelola Agenda</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/kepsek/siswa/">
        <i class="typcn typcn-group menu-icon"></i>
        <span class="menu-title">Data Siswa</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/kepsek/guru/">
        <i class="typcn typcn-briefcase menu-icon"></i>
        <span class="menu-title">Data Guru</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/kepsek/extrakulikuler/">
        <i class="typcn typcn-star-outline menu-icon"></i>
        <span class="menu-title">Extrakulikuler</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/kepsek/jadwalpelajaran/">
        <i class="typcn typcn-calendar-outline menu-icon"></i>
        <span class="menu-title">Jadwal Pelajaran</span>
      </a>
    </li>

    <!-- Logout -->
    <li class="nav-item">
      <a class="nav-link text-danger" href="/CerdasBelajar/kepsek/logout.php" onclick="return confirm('Yakin ingin logout?')">
        <i class="typcn typcn-power menu-icon"></i>
        <span class="menu-title">Logout</span>
      </a>
    </li>

  </ul>
</nav>
