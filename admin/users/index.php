<?php
require_once '../../config/database.php';

/* PROTEKSI ADMIN */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: " . BASE_URL . "auth/admin_login.php");
    exit;
}

/* AMBIL DATA PEMINJAMAN AKTIF & PENDING */
$stmt = $pdo->query("
    SELECT 
        p.id AS id_peminjaman,
        u.name AS nama_user,
        u.username,
        u.email,
        f.nama AS fasilitas,
        p.tgl_pinjam,
        p.tgl_kembali,
        p.status
    FROM peminjaman p
    JOIN users u ON p.user_id = u.id
    JOIN fasilitas f ON p.fasilitas_id = f.id
    WHERE p.status IN ('pending', 'dipinjam')
    ORDER BY p.tgl_kembali ASC
");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Peminjaman - Admin UKS</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --bg-soft: #f8fafc;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-soft);
            color: #1e293b;
        }
        .card-table {
            border-radius: 24px;
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.02);
            background: #fff;
            overflow: hidden;
        }
        .table thead th {
            background: #f1f5f9;
            color: #64748b;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1.25rem 1rem;
            border: none;
        }
        .table tbody td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
        }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="container py-5">
    
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-extrabold text-dark mb-1" style="letter-spacing: -1px;">👥 Monitoring <span class="text-primary">User</span></h2>
            <p class="text-secondary mb-0">Pantau status peminjaman mahasiswa secara real-time.</p>
        </div>
    </div>

    <?php if (isset($_GET['notif']) && $_GET['notif'] == 'sent'): ?>
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                <span class="fw-medium">Notifikasi pengingat berhasil dikirim ke dashboard user.</span>
            </div>
        </div>
    <?php endif; ?>

    <div class="card card-table shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Mahasiswa</th>
                        <th>Fasilitas</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($data)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted small">
                            <i class="bi bi-inbox fs-2 opacity-25 d-block mb-2"></i>
                            Tidak ada peminjaman aktif.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($data as $i => $d): ?>
                    <tr>
                        <td class="ps-4 text-secondary small"><?= $i + 1 ?></td>
                        <td>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($d['nama_user'] ?? '') ?></div>
                            <small class="text-muted" style="font-size: 0.7rem;"><?= htmlspecialchars($d['username'] ?? '') ?></small>
                        </td>
                        <td>
                            <div class="fw-medium text-dark"><?= htmlspecialchars($d['fasilitas'] ?? '') ?></div>
                        </td>
                        <td>
                            <div class="small text-secondary"><?= $d['tgl_pinjam'] ?></div>
                            <div class="small fw-bold <?= (date('Y-m-d') > $d['tgl_kembali']) ? 'text-danger' : 'text-primary' ?>">
                                <i class="bi bi-clock-history me-1"></i><?= $d['tgl_kembali'] ?>
                            </div>
                        </td>
                        <td>
                            <?php if($d['status'] === 'dipinjam'): ?>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle status-badge">Dipinjam</span>
                            <?php else: ?>
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle status-badge">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center pe-4">
                            <form method="POST" action="notify.php" class="d-inline">
                                <input type="hidden" name="id" value="<?= (int) $d['id_peminjaman'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold">
                                    <i class="bi bi-send-fill me-1"></i> Ingatkan
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<style>
    .fw-extrabold { font-weight: 800; }
    .tiny { font-size: 0.7rem; }
</style>
</body>
</html>
