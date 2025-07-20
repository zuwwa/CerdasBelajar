<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    <!-- Profil Siswa -->
    <li class="nav-item">
      <div class="d-flex flex-column align-items-center sidebar-profile" style="padding: 20px 0;">
        <!-- Foto -->
        <img src="/CerdasBelajar/siswa/images/sma.png" alt="Foto Sekolah" style="width: 70px; height: 70px; border-radius: 8px; object-fit: cover;">

        <?php
          // Ambil email dari session
          $email = $_SESSION['email'] ?? '';

          // Include koneksi.php secara absolut agar universal
          include($_SERVER['DOCUMENT_ROOT'] . '/CerdasBelajar/koneksi.php');

          // Query data siswa dan kelas
          $sql = mysqli_query($conn, "SELECT s.nama, s.email, k.kelas 
                                      FROM siswa s 
                                      LEFT JOIN kelas k ON s.kelas_id = k.id 
                                      WHERE s.email = '$email'");
          $siswa = mysqli_fetch_assoc($sql);
        ?>

        <!-- Info Profil -->
        <h3 style="font-size: 16px; margin-top: 10px; color: #fff;">
          <?= $siswa['nama'] ?? 'Nama Siswa'; ?>
        </h3>
        <p style="font-size: 13px; color: #ccc; margin-bottom: 2px;">
          <?= $siswa['email'] ?? '-'; ?>
        </p>
        <p style="font-size: 13px; color: #ccc;">
          Kelas: <?= $siswa['kelas'] ?? '-'; ?>
        </p>
      </div>

      <p class="sidebar-menu-title">Navigasi</p>
    </li>

    <!-- Menu Navigasi -->
    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/siswa/index.php">
        <i class="typcn typcn-device-desktop menu-icon"></i>
        <span class="menu-title">Beranda</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/siswa/profil/index.php">
        <i class="typcn typcn-user-outline menu-icon"></i>
        <span class="menu-title">Profil Siswa</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/siswa/nilai/index.php">
        <i class="typcn typcn-chart-bar menu-icon"></i>
        <span class="menu-title">Nilai</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/siswa/jadwal/index.php">
        <i class="typcn typcn-time menu-icon"></i>
        <span class="menu-title">Jadwal Pelajaran</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/siswa/agenda/index.php">
        <i class="typcn typcn-document-text menu-icon"></i>
        <span class="menu-title">Agenda</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/siswa/pembayaran/index.php">
        <i class="typcn typcn-credit-card menu-icon"></i>
        <span class="menu-title">Pembayaran</span>
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
