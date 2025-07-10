<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    <!-- Profil -->
    <li class="nav-item">
      <div class="d-flex sidebar-profile">
        <div class="sidebar-profile-image">
          <img src="../images/nazwa.jpg" alt="image">
          <span class="sidebar-status-indicator"></span>
        </div>
        <div class="sidebar-profile-name">
          <p class="sidebar-name"><?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?></p>
          <p class="sidebar-designation">Online</p>
        </div>
      </div>
      <p class="sidebar-menu-title">Navigasi</p>
    </li>

    <!-- Dashboard -->
    <li class="nav-item">
      <a class="nav-link" href="../index.php">
        <i class="typcn typcn-device-desktop menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    <!-- Menu Khusus Siswa -->
    <li class="nav-item">
      <a class="nav-link" href="index.php">
        <i class="typcn typcn-user-outline menu-icon"></i>
        <span class="menu-title">Profil Siswa</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="absensi/index.php">
        <i class="typcn typcn-calendar-outline menu-icon"></i>
        <span class="menu-title">Absensi</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="nilai/index.php">
        <i class="typcn typcn-chart-bar menu-icon"></i>
        <span class="menu-title">Nilai</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="jadwal/index.php">
        <i class="typcn typcn-time menu-icon"></i>
        <span class="menu-title">Jadwal Pelajaran</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="agenda/index.php">
        <i class="typcn typcn-document-text menu-icon"></i>
        <span class="menu-title">Agenda</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="pembayaran/index.php">
        <i class="typcn typcn-credit-card menu-icon"></i>
        <span class="menu-title">Pembayaran</span>
      </a>
    </li>

    <!-- Logout -->
    <li class="nav-item">
      <a class="nav-link text-danger" href="logout.php" onclick="return confirm('Yakin ingin logout?')">
        <i class="typcn typcn-power menu-icon"></i>
        <span class="menu-title">Logout</span>
      </a>
    </li>

  </ul>
</nav>
