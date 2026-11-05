<?php
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notif_id'])) {
    
    // Security: CSRF Validation
    if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
        set_flash('error', 'Akses Ditolak', 'Token keamanan tidak valid.');
        header("Location: peminjaman_saya.php");
        exit;
    }

    $id = filter_var($_POST['notif_id'], FILTER_VALIDATE_INT);
    if (!$id || $id < 1) {
        http_response_code(400);
        exit('ID notifikasi tidak valid.');
    }
    $user_id = (int) $_SESSION['user']['id'];

    try {
        $stmt = $pdo->prepare("UPDATE notifikasi SET is_read = 1 WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);
        
        // Cek apakah ada baris yang terupdate
        if ($stmt->rowCount() > 0) {
            set_flash('success', 'Berhasil', 'Notifikasi telah ditandai sebagai sudah dibaca.');
        } else {
            set_flash('warning', 'Peringatan', 'Notifikasi tidak ditemukan atau sudah dibaca sebelumnya.');
        }
    } catch (PDOException $e) {
        error_log('Notification update failed: ' . $e->getMessage());
        set_flash('error', 'Gagal', 'Terjadi kesalahan saat memproses notifikasi.');
    }
} else {
    set_flash('error', 'Invalid Request', 'Permintaan tidak valid.');
}

header("Location: peminjaman_saya.php");
exit;
?>
