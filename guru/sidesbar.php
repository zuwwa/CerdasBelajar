<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    <!-- Profil Guru -->
    <li class="nav-item">
      <div class="d-flex flex-column align-items-center sidebar-profile" style="padding: 20px 0;">
        <!-- Foto Guru -->
        <img src="/CerdasBelajar/images/profile.png" alt="Foto Guru" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover;">

        <?php
        // Ambil email dari session
        $email = $_SESSION['email'] ?? '';

        // Include koneksi.php secara absolut
        include($_SERVER['DOCUMENT_ROOT'] . '/CerdasBelajar/koneksi.php');

        // Ambil data guru dari users
        $query = mysqli_query($conn, "SELECT fullname, email FROM users WHERE email = '$email' AND type = 'guru'");
        $guru = mysqli_fetch_assoc($query);
        ?>

        <!-- Info Profil -->
        <h3 style="font-size: 16px; margin-top: 10px; color: #fff;">
          <?= $guru['fullname'] ?? 'Guru'; ?>
        </h3>
        <p style="font-size: 13px; color: #ccc; margin-bottom: 2px;">
          <?= $guru['email'] ?? '-'; ?>
        </p>
        <p style="font-size: 13px; color: #ccc;">
          Guru
        </p>
      </div>

      <p class="sidebar-menu-title">Menu Guru</p>
    </li>

    <!-- Menu Navigasi Guru -->
    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/guru/index.php">
        <i class="typcn typcn-home menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/guru/profil/index.php">
        <i class="typcn typcn-user menu-icon"></i>
        <span class="menu-title">Profil Saya</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/guru/mapel/">
        <i class="typcn typcn-book menu-icon"></i>
        <span class="menu-title">Mapel Saya</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/guru/penilaian/">
        <i class="typcn typcn-edit menu-icon"></i>
        <span class="menu-title">Input Nilai</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/guru/agenda/">
        <i class="typcn typcn-calendar menu-icon"></i>
        <span class="menu-title">Agenda</span>
      </a>
    </li>

    <!-- Logout -->
    <li class="nav-item">
      <a class="nav-link text-danger" href="/CerdasBelajar/logout.php" onclick="return confirm('Yakin ingin logout?')">
        <i class="typcn typcn-power menu-icon"></i>
        <span class="menu-title">Logout</span>
      </a>
    </li>

  </ul>
</nav>
