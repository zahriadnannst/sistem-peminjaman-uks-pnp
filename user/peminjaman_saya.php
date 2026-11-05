<?php
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

// Ambil data peminjaman
$stmt = $pdo->prepare("
    SELECT 
        p.id,
        p.jumlah_pinjam,
        p.tgl_pinjam,
        p.tgl_kembali,
        p.status,
        f.nama AS fasilitas
    FROM peminjaman p
    JOIN fasilitas f ON p.fasilitas_id = f.id
    WHERE p.user_id = ?
    ORDER BY p.created_at DESC
");
$stmt->execute([$user_id]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../includes/header.php'; 
?>

<div class="container mt-5" style="min-height: 70vh;">

    <div class="row mb-5 align-items-center" data-aos="fade-up">
        <div class="col-lg-7">
            <h1 class="hero-title mb-1">
                Pinjaman <span class="highlight-text">Saya</span>
            </h1>
            <p class="hero-description fs-5 mb-0">Kelola dan pantau status peminjaman fasilitas Anda secara real-time.</p>
        </div>
        <div class="col-lg-5 text-lg-end mt-4 mt-lg-0">
            <a href="<?= BASE_URL ?>fasilitas.php" class="btn btn-primary-custom">
                <i class="bi bi-plus-lg me-1"></i> Pinjam Lagi
            </a>
        </div>
    </div>

    <?php
    // Cek Notifikasi Pengingat
    $stmt_notif = $pdo->prepare("SELECT * FROM notifikasi WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC");
    $stmt_notif->execute([$user_id]);
    $notifs = $stmt_notif->fetchAll();

    if ($notifs):
        foreach ($notifs as $n):
    ?>
        <div class="alert alert-white border-start border-warning border-4 shadow-sm fade show p-3 mb-4 rounded-4 position-relative" role="alert">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-warning text-white rounded-circle p-2 px-3">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <div>
                    <h6 class="mb-1 fw-bold text-dark">Pesan Pengingat</h6>
                    <div class="text-secondary small"><?= htmlspecialchars($n['pesan'], ENT_QUOTES, 'UTF-8') ?></div>
                </div>
            </div>
            <form method="POST" action="notifikasi_baca.php" class="position-absolute top-0 end-0 mt-2 me-2">
                <input type="hidden" name="notif_id" value="<?= $n['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                <button type="submit" class="btn-close shadow-none" aria-label="Close" style="font-size: 0.7rem;"></button>
            </form>
        </div>
    <?php 
        endforeach;
    endif; 
    ?>

    <!-- DESKTOP TABLE VIEW -->
    <div class="card border-0 shadow-lg d-none d-md-block" style="border-radius: 24px;">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary">Fasilitas</th>
                        <th class="py-3 text-uppercase small fw-bold text-secondary">Jumlah</th>
                        <th class="py-3 text-uppercase small fw-bold text-secondary">Waktu Pinjam</th>
                        <th class="py-3 text-uppercase small fw-bold text-secondary">Tenggat Kembali</th>
                        <th class="py-3 text-uppercase small fw-bold text-secondary">Status</th>
                        <th class="py-3 text-uppercase small fw-bold text-secondary text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($data)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <div class="mb-3"><i class="bi bi-inbox fs-1 opacity-25"></i></div>
                            <p class="mb-0 fw-medium">Belum ada peminjaman.</p>
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($data as $d): ?>
                    <tr>
                        <td class="ps-4 py-4">
                            <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($d['fasilitas']) ?></div>
                            <small class="text-muted">ID: #<?= $d['id'] ?></small>
                        </td>
                        <td class="py-4">
                            <span class="badge bg-light text-dark border fw-medium"><?= $d['jumlah_pinjam'] ?> Unit</span>
                        </td>
                        <td class="py-4">
                            <div class="text-dark small fw-medium"><?= date('d M Y', strtotime($d['tgl_pinjam'])) ?></div>
                            <small class="text-muted">Mulai Pinjam</small>
                        </td>
                        <td class="py-4">
                            <?php 
                                $kembali_end = strtotime($d['tgl_kembali'] . ' 23:59:59');
                                $isLate = time() > $kembali_end && $d['status'] === 'dipinjam';
                            ?>
                            <div class="fw-medium small <?= $isLate ? 'text-danger' : 'text-dark' ?>">
                                <?= date('d M Y', strtotime($d['tgl_kembali'])) ?>
                            </div>
                            <?php if ($isLate): ?>
                                <span class="badge bg-danger-subtle text-danger tiny px-2 py-1 mt-1">TERLAMBAT</span>
                            <?php else: ?>
                                <small class="text-muted small">Batas Waktu</small>
                            <?php endif; ?>
                        </td>
                        <td class="py-4">
                            <?php if ($d['status'] === 'pending'): ?>
                                <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis px-3 py-2 border border-warning-subtle">
                                    <i class="bi bi-clock-history me-1"></i> Pending
                                </span>
                            <?php elseif ($d['status'] === 'dipinjam'): ?>
                                <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis px-3 py-2 border border-primary-subtle">
                                    <i class="bi bi-play-circle me-1"></i> Sedang Dipinjam
                                </span>
                            <?php elseif ($d['status'] === 'ditolak'): ?>
                                <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis px-3 py-2 border border-danger-subtle">
                                    <i class="bi bi-x-circle me-1"></i> Ditolak
                                </span>
                            <?php else: ?>
                                <span class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2 border border-success-subtle">
                                    <i class="bi bi-check2-circle me-1"></i> Dikembalikan
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center py-4 pe-4">
                            <?php if ($d['status'] === 'dipinjam'): ?>
                                <?php 
                                $kembali_end = strtotime($d['tgl_kembali'] . ' 23:59:59');
                                $now = time();
                                $denda = 0;
                                if ($now > $kembali_end) {
                                    $diff = $now - $kembali_end;
                                    $days = ceil($diff / (60 * 60 * 24));
                                    $denda = $days * DENDA_PER_HARI;
                                }
                                ?>
                                <?php if ($denda > 0): ?>
                                    <a href="pembayaran_denda.php?id=<?= $d['id'] ?>" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm">
                                        <i class="bi bi-wallet2 me-1"></i> Bayar Denda
                                    </a>
                                <?php else: ?>
                                    <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis px-3 py-2 border border-danger-subtle text-wrap" style="max-width: 160px; line-height: 1.3;">
                                        <i class="bi bi-exclamation-circle-fill me-1"></i> Kembalikan ke petugas sebelum tenggat!
                                    </span>
                                <?php endif; ?>
                            <?php elseif ($d['status'] === 'pending'): ?>
                                <small class="text-secondary fw-medium">Menunggu Admin</small>
                            <?php else: ?>
                                <i class="bi bi-check-all text-success fs-5"></i>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MOBILE CARD VIEW -->
    <div class="d-md-none">
        <?php if (empty($data)): ?>
            <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                <i class="bi bi-inbox fs-1 opacity-25"></i>
                <p class="mt-2 text-muted">Belum ada peminjaman.</p>
            </div>
        <?php endif; ?>

        <?php foreach ($data as $d): ?>
            <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="fw-bold text-dark fs-6"><?= htmlspecialchars($d['fasilitas']) ?></div>
                            <div class="d-flex align-items-center gap-2">
                                <small class="text-muted">ID: #<?= $d['id'] ?></small>
                                <span class="badge bg-light text-dark border tiny"><?= $d['jumlah_pinjam'] ?> Unit</span>
                            </div>
                        </div>
                        <?php if ($d['status'] === 'pending'): ?>
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle pt-1 pb-1 px-2 rounded-3 small">Pending</span>
                        <?php elseif ($d['status'] === 'dipinjam'): ?>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle pt-1 pb-1 px-2 rounded-3 small">Dipinjam</span>
                        <?php elseif ($d['status'] === 'ditolak'): ?>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle pt-1 pb-1 px-2 rounded-3 small">Ditolak</span>
                        <?php else: ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle pt-1 pb-1 px-2 rounded-3 small">Kembali</span>
                        <?php endif; ?>
                    </div>

                    <div class="row g-2 mb-3 bg-light rounded-3 p-2">
                        <div class="col-6 border-end">
                            <div class="tiny text-muted text-uppercase fw-bold mb-1">Mulai Pinjam</div>
                            <div class="small fw-semibold"><?= date('d/m/Y', strtotime($d['tgl_pinjam'])) ?></div>
                        </div>
                        <div class="col-6 ps-3">
                            <div class="tiny text-muted text-uppercase fw-bold mb-1">Batas Waktu</div>
                            <?php 
                                $kembali_end = strtotime($d['tgl_kembali'] . ' 23:59:59');
                                $isLate = time() > $kembali_end && $d['status'] === 'dipinjam';
                            ?>
                            <div class="small fw-semibold <?= $isLate ? 'text-danger' : 'text-dark' ?>">
                                <?= date('d/m/Y', strtotime($d['tgl_kembali'])) ?>
                                <?php if ($isLate): ?>
                                    <div class="badge bg-danger text-white border-0 mt-1" style="font-size: 8px;">LATE</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid mt-2">
                        <?php if ($d['status'] === 'dipinjam'): ?>
                            <?php 
                            $denda = 0;
                            if (time() > $kembali_end) {
                                $days = ceil((time() - $kembali_end) / (60 * 60 * 24));
                                $denda = $days * DENDA_PER_HARI;
                            }
                            ?>
                            <?php if ($denda > 0): ?>
                                <a href="pembayaran_denda.php?id=<?= $d['id'] ?>" class="btn btn-danger py-2 rounded-3 fw-bold">
                                    <i class="bi bi-wallet2 me-2"></i> Bayar Denda (Rp <?= number_format($denda, 0, ',', '.') ?>)
                                </a>
                            <?php else: ?>
                                <div class="alert alert-warning border-0 small mb-0 py-2 text-center rounded-3">
                                    <i class="bi bi-info-circle me-1"></i> Kembalikan alat ke petugas sebelum tenggat.
                                </div>
                            <?php endif; ?>
                        <?php elseif ($d['status'] === 'pending'): ?>
                            <button class="btn btn-outline-secondary py-2 rounded-3 disabled" style="font-size: 0.85rem;">
                                <i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi Admin
                            </button>
                        <?php else: ?>
                            <button class="btn btn-light py-2 rounded-3 text-success fw-bold disabled" style="font-size: 0.85rem;">
                                <i class="bi bi-patch-check-fill me-1"></i> Selesai
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<style>
    .bg-indigo-soft { background: rgba(99, 102, 241, 0.1); }
    .tiny { font-size: 0.7rem; }
    .alert-white { background: #fff; }
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>
