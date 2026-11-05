<?php
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    exit('Akses ditolak.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST'
    || !isset($_POST['csrf_token'])
    || !validate_csrf_token($_POST['csrf_token'])) {
    http_response_code(405);
    exit('Permintaan penghapusan tidak valid.');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    http_response_code(400);
    exit('ID fasilitas tidak valid.');
}

// cek apakah fasilitas dipakai di peminjaman
$cek = $pdo->prepare("SELECT COUNT(*) FROM peminjaman WHERE fasilitas_id = ?");
$cek->execute([$id]);
$jumlah = $cek->fetchColumn();

if ($jumlah > 0) {
    set_flash('error', 'Tidak dapat menghapus', 'Fasilitas masih digunakan pada data peminjaman.');
    header('Location: index.php');
    exit;
}

// jika tidak dipakai, baru hapus
$hapus = $pdo->prepare("DELETE FROM fasilitas WHERE id = ?");
$hapus->execute([$id]);

set_flash('success', 'Berhasil', 'Fasilitas telah dihapus.');
header('Location: index.php');
exit;
?>
