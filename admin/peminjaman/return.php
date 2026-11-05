<?php
require_once '../../config/database.php';

// Proteksi Admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'auth/admin_login.php');
    exit;
}

/* ==========================
   VALIDASI ID
========================== */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID peminjaman tidak valid");
}

$id = (int) $_GET['id'];

/* ==========================
   CEK DATA PEMINJAMAN
========================== */
$stmt = $pdo->prepare("
    SELECT id, status, fasilitas_id, tgl_kembali
    FROM peminjaman
    WHERE id = ?
");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    die("Data peminjaman tidak ditemukan");
}

/* ==========================
   HANYA JIKA STATUS DIPINJAM
========================== */
if ($p['status'] !== 'dipinjam') {
    set_flash('error', 'Invalid Status', 'Item tidak sedang dipinjam.');
    header("Location: index.php");
    exit;
}

/* ==========================
   HITUNG DENDA (JIKA ADA)
========================== */
$tgl_kembali_end = strtotime($p['tgl_kembali'] . ' 23:59:59');
$now = time();
$denda = 0;
$lateDays = 0;

if ($now > $tgl_kembali_end) {
    $diff = $now - $tgl_kembali_end;
    $lateDays = ceil($diff / (60 * 60 * 24));
    $denda = $lateDays * DENDA_PER_HARI;
}

/* ==========================
   KONFIRMASI PENGEMBALIAN (UI)
========================== */
// Tampilkan halaman konfirmasi untuk SEMUA pengembalian (agar admin bisa cek barang)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    include '../../includes/header.php'; 
    ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-5 text-center">
                        
                        <?php if ($denda > 0): ?>
                            <div class="mb-4 text-warning">
                                <i class="bi bi-exclamation-triangle display-1"></i>
                            </div>
                            <h3 class="fw-bold mb-3">Keterlambatan Terdeteksi</h3>
                            <p class="text-secondary mb-4">
                                Peminjaman ini terlambat <strong><?= $lateDays ?> hari</strong>.
                            </p>
                            
                            <div class="bg-danger-subtle text-danger p-4 rounded-4 mb-4">
                                <label class="small fw-bold text-uppercase tracking-wide opacity-75">Total Denda</label>
                                <div class="display-6 fw-bold">Rp <?= number_format($denda, 0, ',', '.') ?></div>
                            </div>
                            <p class="mb-4 text-dark fw-medium">Pastikan user sudah membayar denda sebelum konfirmasi.</p>
                        <?php else: ?>
                            <div class="mb-4 text-success">
                                <i class="bi bi-check-circle display-1"></i>
                            </div>
                            <h3 class="fw-bold mb-3">Konfirmasi Pengembalian</h3>
                            <p class="text-secondary mb-4">
                                Pengembalian tepat waktu. Tidak ada denda.
                            </p>
                            <div class="bg-light p-3 rounded-4 mb-4 text-start">
                                <ul class="mb-0 text-secondary small">
                                    <li>Cek kondisi fisik barang.</li>
                                    <li>Pastikan jumlah unit sesuai (Kembali: 1 Unit).</li>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                            <div class="d-grid gap-2">
                                <button type="submit" name="confirm" class="btn btn-primary py-3 fw-bold rounded-pill">
                                    <i class="bi bi-check-lg me-2"></i> Konfirmasi Barang Kembali
                                </button>
                                <a href="index.php" class="btn btn-light py-3 fw-bold rounded-pill text-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include '../../includes/footer.php';
    exit;
}

if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
    http_response_code(403);
    exit('Token keamanan tidak valid.');
}

/* ==========================
   UPDATE STATUS PEMINJAMAN
========================== */
$pdo->prepare("
    UPDATE peminjaman
    SET status = 'dikembalikan',
        denda = ?
    WHERE id = ? AND status = 'dipinjam'
")->execute([$denda, $id]);

// Status fasilitas sekarang dinamis berdasarkan stok di fasilitas.php

/* ==========================
   REDIRECT
========================== */
set_flash('success', 'Berhasil', 'Peminjaman telah diselesaikan.');
header("Location: index.php");
exit;
