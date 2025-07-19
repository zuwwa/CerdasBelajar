<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">

    <!-- Profil Siswa -->
    <li class="nav-item">
      <div class="d-flex flex-column align-items-center sidebar-profile" style="padding: 20px 0;">
        <!-- Foto -->
        <img src="../images/sma.png" alt="Foto Sekolah" style="width: 70px; height: 70px; border-radius: 8px; object-fit: cover;">

        <?php
        $email = $_SESSION['email'] ?? '';
        include($_SERVER['DOCUMENT_ROOT'] . '/CerdasBelajar/koneksi.php');
        $siswa_query = mysqli_query($conn, "SELECT nisn, nama, email FROM siswa WHERE email = '$email'");
        $siswa_data = mysqli_fetch_assoc($siswa_query);

        $nama = $siswa_data['nama'] ?? '-';
        $email = $siswa_data['email'] ?? '-';
        $nisn = $siswa_data['nisn'] ?? null;
        $kelas = '-';

        if ($nisn) {
            $kelas_query = mysqli_query($conn, "
                SELECT tk.kelas 
                FROM t_siswa ts
                LEFT JOIN t_kelas tk ON ts.kelas = tk.id
                WHERE ts.nis = '$nisn'
            ");
            $kelas_data = mysqli_fetch_assoc($kelas_query);
            if ($kelas_data) {
                $kelas = $kelas_data['kelas'];
            }
        }
        ?>

        <!-- Info Profil -->
        <h3 style="font-size: 16px; margin-top: 10px; color: #fff;"><?= $nama; ?></h3>
        <p style="font-size: 13px; color: #ccc; margin-bottom: 2px;"><?= $email; ?></p>
        <p style="font-size: 13px; color: #ccc;">Kelas: <?= $kelas; ?></p>
      </div>

      <p class="sidebar-menu-title">Navigasi</p>
    </li>

    <!-- ✅ Gunakan path absolut agar universal -->
    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/siswa/index.php">
        <i class="typcn typcn-device-desktop menu-icon"></i>
        <span class="menu-title">Beranda</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/CerdasBelajar/siswa/Profil/index.php">
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
