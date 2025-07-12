<?php
session_start();
include("koneksi.php");

// Cek apakah user sudah login dari Google
if (!isset($_SESSION['email'])) {
    echo "<script>alert('⚠️ Sesi tidak valid. Silakan login ulang.'); window.location='index.php';</script>";
    exit;
}

$email     = strtolower(trim($_SESSION['email']));
$fullname  = $_SESSION['username'];
$picture   = $_SESSION['picture'] ?? '';
$created   = date("Y-m-d H:i:s");
$lastLogin = $created;
$role_dipilih = $_SESSION['dipilih'] ?? null;

// Cek apakah user sudah terdaftar
$query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    // Insert user baru ke tabel users saja
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, status, picture, type, last_login, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $password = "-";
    $status   = 1;
    $stmt->bind_param("ssssssss", $fullname, $email, $password, $status, $picture, $role_dipilih, $lastLogin, $created);
    $stmt->execute();

    // Ambil ulang data user baru
    $data = [
        'id'       => $conn->insert_id,
        'fullname' => $fullname,
        'email'    => $email,
        'type'     => $role_dipilih,
    ];
}

// Simpan session user
$_SESSION['id_user'] = $data['id'];
$_SESSION['nama']    = $data['fullname'];
$_SESSION['role']    = $data['type'];
$_SESSION['email']   = $data['email'];

// Jika role adalah siswa, pastikan siswa sudah ada di tabel `siswa`
if ($data['type'] === 'siswa') {
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

    if (mysqli_num_rows($cek) === 0) {
        echo "<script>alert('⚠️ Data siswa belum terdaftar. Hubungi admin.'); window.location='logout.php';</script>";
        exit;
    }
}

// Redirect berdasarkan role
switch ($data['type']) {
    case 'admin':
        header("Location: administrator/index.php");
        break;
    case 'siswa':
        header("Location: siswa/index.php?page=dashboard");
        break;
    case 'guru':
        header("Location: guru/index.php");
        break;
    case 'ortu':
        header("Location: ortu/index.php");
        break;
    case 'kepsek':
        header("Location: kepsek/index.php");
        break;
    case 'perpus':
        header("Location: perpus/index.php");
        break;
    default:
        echo "<script>alert('⚠️ Role tidak dikenali.'); window.location='logout.php';</script>";
        break;
}
exit;
?>
