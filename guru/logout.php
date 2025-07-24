<?php
session_start();             // Mulai session
session_unset();             // Hapus semua variabel session
session_destroy();           // Hancurkan session di server

// (Opsional) Hapus cookie session jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect ke halaman login atau index
header("Location: ../index.php");
exit;
?>
