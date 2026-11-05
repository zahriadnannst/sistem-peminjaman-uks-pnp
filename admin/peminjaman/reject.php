<?php
require_once '../../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    set_flash('error', 'Akses Ditolak', 'Status login tidak valid.');
    header('Location: ' . BASE_URL . 'auth/admin_login.php');
    exit;
}

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    set_flash('error', 'Gagal', 'ID tidak valid.');
    header("Location: index.php");
    exit;
}

if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
    set_flash('error', 'Keamanan', 'Token keamanan tidak valid.');
    header("Location: index.php");
    exit;
}

$id = (int) $_POST['id'];

$pdo->prepare("
    UPDATE peminjaman 
    SET status = 'ditolak'
    WHERE id = ? AND status = 'pending'
")->execute([$id]);

set_flash('info', 'Ditolak', 'Peminjaman telah ditolak.');
header("Location: index.php");
exit;
