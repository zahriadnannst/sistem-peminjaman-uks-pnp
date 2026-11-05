<?php
require_once '../../config/database.php';

// proteksi admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    set_flash('error', 'Akses Ditolak', 'Anda bukan administrator.');
    header('Location: ' . BASE_URL . 'auth/admin_login.php');
    exit;
}

// validasi ID & CSRF
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    set_flash('error', 'Input Error', 'ID peminjaman tidak valid.');
    header("Location: index.php");
    exit;
}

if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
    set_flash('error', 'Keamanan', 'Token keamanan tidak valid.');
    header("Location: index.php");
    exit;
}

$id = (int) $_POST['id'];

/* ==========================
   SETUJUI PEMINJAMAN
========================== */

// ubah status peminjaman
$stmt = $pdo->prepare("
    UPDATE peminjaman 
    SET status = 'dipinjam'
    WHERE id = ? AND status = 'pending'
");
$stmt->execute([$id]);

// ambil fasilitas terkait
$fasilitas = $pdo->prepare("
    SELECT fasilitas_id 
    FROM peminjaman 
    WHERE id = ?
");
$fasilitas->execute([$id]);
$data = $fasilitas->fetch(PDO::FETCH_ASSOC);

if ($data) {
// Status fasilitas sekarang dinamis berdasarkan stok di fasilitas.php
}

set_flash('success', 'Disetujui', 'Peminjaman telah disetujui.');

// kembali ke halaman data peminjaman
header("Location: index.php");
exit;
