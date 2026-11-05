<?php
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit;
}


// DISABLE USER RETURN - Redirect to dashboard
header("Location: peminjaman_saya.php");
exit;

/* 
   LOGIC NON-AKTIF:
   User tidak diperbolehkan mengembalikan sendiri.
   Harus melalui Admin.
*/
?>
