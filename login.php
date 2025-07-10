<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['email'])) {
    echo "<script>alert('⚠️ Sesi tidak valid. Silakan login ulang.'); window.location='index.php';</script>";
    exit;
}

$email = strtolower(trim($_SESSION['email']));
$fullname = $_SESSION['username'];
$picture = $_SESSION['picture'] ?? '';
$created = date("Y-m-d H:i:s");
$role_dipilih = $_SESSION['dipilih'] ?? null;

// Cek user di database
$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    // Insert user baru tanpa password, pakai prepared statement
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, picture, created_at, id_role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullname, $email, $picture, $created, $role_dipilih);
    $stmt->execute();

    // Ambil user yang baru dibuat
    $id_user_baru = $conn->insert_id;

    // Kalau role dipilih adalah 2 (Siswa), insert ke tabel siswa juga
    if ($role_dipilih == '2') {
        // Asumsikan id siswa = NISN sama dengan id user
        // Jika id user auto increment numerik, bisa generate id siswa sesuai kebutuhan
        $nisn = $id_user_baru;  // ganti jika mau pakai NISN lain
        $nama_siswa = $fullname;

        // Insert ke tabel siswa (pastikan kolom id di siswa VARCHAR atau INT sesuai)
        $stmtSiswa = $conn->prepare("INSERT INTO siswa (id, nisn, nama) VALUES (?, ?, ?)");
        $stmtSiswa->bind_param("sss", $nisn, $nisn, $nama_siswa);
        $stmtSiswa->execute();
    }

    echo "<script>alert('✅ Akun berhasil dibuat. Silakan hubungi admin untuk verifikasi role.'); window.location='logout.php';</script>";
    exit;
}

// Simpan ke session
$_SESSION['id_user'] = $data['id'];
$_SESSION['nama']    = $data['fullname'];
$_SESSION['role']    = $data['id_role'];
$_SESSION['email']   = $data['email'];

// Kalau role = 2 (Siswa), cek dan insert ke tabel siswa jika belum ada
if ($data['id_role'] == 2) {
    $nisn = $data['id'];   // asumsi id di users sama dengan nisn
    $nama_siswa = $data['fullname'];

    // Cek dulu data siswa berdasarkan primary key 'id' (atau nisn) sudah ada atau belum
    $cekSiswa = mysqli_query($conn, "SELECT * FROM siswa WHERE id = '$nisn'");
    
    if (mysqli_num_rows($cekSiswa) == 0) {
        $stmtSiswa = $conn->prepare("INSERT INTO siswa (id, nisn, nama) VALUES (?, ?, ?)");
        $stmtSiswa->bind_param("sss", $nisn, $nisn, $nama_siswa);
        $stmtSiswa->execute();
    }
}

// Redirect sesuai role
switch ($data['id_role']) {
    case 1:
        header("Location: administrator/index.php");
        break;
    case 2:
        header("Location: siswa/index.php?page=dashboard");
        break;
    case 3:
        header("Location: guru/index.php");
        break;
    case 4:
        header("Location: ortu/index.php");
        break;
    case 5:
        header("Location: kepsek/index.php");
        break;
    case 6:
        header("Location: perpus/index.php");
        break;
    default:
        echo "<script>alert('⚠️ Role tidak dikenali.'); window.location='logout.php';</script>";
        break;
}
exit;
?>
