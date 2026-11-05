<?php
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("ID peminjaman tidak valid");
}

$id = (int)$_GET['id'];
$user_id = $_SESSION['user']['id'];

// Ambil data peminjaman
$stmt = $pdo->prepare("
    SELECT p.*, f.nama as nama_fasilitas 
    FROM peminjaman p
    JOIN fasilitas f ON p.fasilitas_id = f.id
    WHERE p.id = ? AND p.user_id = ? AND p.status = 'dipinjam'
");
$stmt->execute([$id, $user_id]);
$data = $stmt->fetch();

if (!$data) {
    die("Data tidak ditemukan atau akses ditolak");
}

// Hitung denda
$tgl_kembali = strtotime($data['tgl_kembali']);
$tgl_kembali_end = strtotime($data['tgl_kembali'] . ' 23:59:59');
$now = time();
$denda = 0;
$lateDays = 0;

if ($now > $tgl_kembali_end) {
    $diff = $now - $tgl_kembali_end;
    $lateDays = ceil($diff / (60 * 60 * 24));
    $denda = $lateDays * DENDA_PER_HARI;
}

// Jika tidak ada denda, arahkan kembali dengan info
if ($denda <= 0) {
    header("Location: peminjaman_saya.php");
    exit;
}

// Proses Pembayaran tidak lagi dilakukan otomatis oleh user.
// User harus menemui petugas UKS.

include __DIR__ . '/../includes/header.php';
?>

<div class="container mt-5 py-5" style="min-height: 70vh;">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 30px;">
                <div class="p-5 text-center">
                    
                    <div class="mb-4 d-inline-block p-4 rounded-circle" style="background: rgba(239, 68, 68, 0.1);">
                        <i class="bi bi-cash-coin text-danger display-3"></i>
                    </div>
                    
                    <h3 class="fw-bold text-dark mb-2">Pembayaran Denda</h3>
                    <p class="text-secondary small mb-4">
                        Terdapat keterlambatan pengembalian pada fasilitas 
                        <span class="text-dark fw-bold"><?= htmlspecialchars($data['nama_fasilitas']) ?></span>
                    </p>

                    <div class="bg-light p-4 rounded-4 mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary small">Keterlambatan</span>
                            <span class="fw-bold text-danger"><?= $lateDays ?> Hari</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">Total yang harus dibayar</span>
                            <span class="fw-extrabold text-dark fs-4 text-primary">Rp <?= number_format($denda, 0, ',', '.') ?></span>
                        </div>
                    </div>

                    <p class="text-muted tiny mb-4 px-3 italic">
                        <i class="bi bi-info-circle me-1"></i> 
                        Batas pengembalian seharusnya adalah tanggal <b><?= date('d M Y', $tgl_kembali) ?></b>.
                    </p>

                    <div class="alert alert-warning border-0 rounded-4 text-start small mb-4">
                        <div class="d-flex gap-2">
                            <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                            <div>
                                <strong>Perhatian!</strong><br>
                                Saat pengembalian, petugas UKS akan melakukan pengecekan properti terlebih dahulu. Apabila terjadi keterlambatan, lakukan pembayaran denda langsung kepada petugas UKS.
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-3">
                        <a href="peminjaman_saya.php" class="btn btn-outline-primary btn-lg rounded-pill fw-bold">
                            <i class="bi bi-arrow-left me-2"></i> Kembali ke Riwayat
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .shadow-primary { box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3); }
    .tiny { font-size: 0.75rem; }
    .fw-extrabold { font-weight: 800; }
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>
