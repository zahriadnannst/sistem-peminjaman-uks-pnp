<?php
require_once '../../config/database.php';

// proteksi admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'auth/admin_login.php');
    exit;
}

// =====================
// FILTER TANGGAL
// =====================
$start_date = $_GET['start_date'] ?? date('Y-m-01'); // awal bulan ini
$end_date   = $_GET['end_date']   ?? date('Y-m-d');    // hari ini
if (!is_string($start_date) || !is_string($end_date)) {
    http_response_code(400);
    exit('Rentang tanggal laporan tidak valid.');
}
$startDate = DateTime::createFromFormat('!Y-m-d', $start_date);
$endDate = DateTime::createFromFormat('!Y-m-d', $end_date);
if (!$startDate || $startDate->format('Y-m-d') !== $start_date
    || !$endDate || $endDate->format('Y-m-d') !== $end_date
    || $endDate < $startDate) {
    http_response_code(400);
    exit('Rentang tanggal laporan tidak valid.');
}

/* =====================
   AMBIL DATA LAPORAN
   ===================== */
$sql = "
    SELECT 
        p.id,
        p.tgl_pinjam,
        p.tgl_kembali,
        p.status,
        p.denda,
        p.created_at,
        u.name AS nama_peminjam,
        f.nama AS nama_fasilitas,
        f.harga AS harga_sewa
    FROM peminjaman p
    JOIN users u ON p.user_id = u.id
    JOIN fasilitas f ON p.fasilitas_id = f.id
    WHERE DATE(p.tgl_pinjam) BETWEEN ? AND ? AND p.status = 'dikembalikan'
    ORDER BY p.tgl_pinjam ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$start_date, $end_date]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =====================
   REKAP TOTAL
   ===================== */
$total_sewa  = 0;
$total_denda = 0;
foreach ($data as $row) {
    $total_sewa += (int)$row['harga_sewa'];
    $total_denda += (int)$row['denda'];
}
$total_pendapatan = $total_sewa + $total_denda;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Laporan - ADMIN UKS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Inter', sans-serif; }
        .card-report { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .print-only { display: none; }
        
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            body { background: white; padding: 0; }
            .card-report { box-shadow: none; border: 1px solid #eee; }
            .container { max-width: 100% !important; width: 100% !important; margin: 0 !important; }
        }

        .stat-box {
            padding: 20px;
            border-radius: 15px;
            background: #fff;
            border: 1px solid rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<div class="no-print">
    <?php include '../includes/navbar.php'; ?>
</div>

<div class="container py-5">

    <!-- HEADER LAPORAN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Rekap Laporan Peminjaman</h2>
            <p class="text-secondary small mb-0">
                UKM UKS PNP - Periode: <b><?= date('d M Y', strtotime($start_date)) ?></b> sampai <b><?= date('d M Y', strtotime($end_date)) ?></b>
            </p>
        </div>
        <div class="no-print">
            <button onclick="window.print()" class="btn btn-dark rounded-pill px-4">
                <i class="bi bi-printer me-2"></i> Cetak Laporan
            </button>
        </div>
    </div>

    <!-- FILTER (NO PRINT) -->
    <div class="card card-report p-4 mb-4 no-print">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($start_date, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($end_date, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="col-md-6">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 rounded-3">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                    <a href="export.php?tgl_mulai=<?= rawurlencode($start_date) ?>&tgl_selesai=<?= rawurlencode($end_date) ?>" class="btn btn-success rounded-3">
                        <i class="bi bi-file-earmark-excel me-1"></i> CSV
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- RINGKASAN STATS -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-box text-center shadow-sm">
                <div class="text-secondary tiny fw-bold text-uppercase mb-2">Total Sewa</div>
                <h3 class="fw-bold mb-0 text-dark">Rp <?= number_format($total_sewa, 0, ',', '.') ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box text-center shadow-sm border-danger-subtle bg-danger-subtle bg-opacity-10">
                <div class="text-danger tiny fw-bold text-uppercase mb-2 text-danger">Total Denda</div>
                <h3 class="fw-bold mb-0 text-danger">Rp <?= number_format($total_denda, 0, ',', '.') ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box text-center shadow-sm bg-primary text-white">
                <div class="tiny fw-bold text-uppercase mb-2 opacity-75">Total Pendapatan</div>
                <h3 class="fw-bold mb-0">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>

    <!-- TABEL DATA -->
    <div class="card card-report overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Tgl Pinjam</th>
                        <th>Nama Mahasiswa</th>
                        <th>Fasilitas</th>
                        <th>Sewa</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">Tidak ada data ditemukan untuk periode ini.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data as $i => $row): ?>
                            <tr>
                                <td class="ps-4 text-secondary small"><?= $i + 1 ?></td>
                                <td><?= date('d/m/Y', strtotime($row['tgl_pinjam'])) ?></td>
                                <td class="fw-bold text-dark"><?= htmlspecialchars($row['nama_peminjam']) ?></td>
                                <td class="small"><?= htmlspecialchars($row['nama_fasilitas']) ?></td>
                                <td>Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?></td>
                                <td class="text-danger">Rp <?= number_format($row['denda'], 0, ',', '.') ?></td>
                                <td>
                                    <?php 
                                        $cls = 'secondary';
                                        if ($row['status'] == 'dipinjam') $cls = 'primary';
                                        if ($row['status'] == 'dikembalikan') $cls = 'success';
                                        if ($row['status'] == 'ditolak') $cls = 'danger';
                                    ?>
                                    <span class="badge bg-<?= $cls ?>-subtle text-<?= $cls ?> border border-<?= $cls ?>-subtle px-2">
                                        <?= ucfirst($row['status']) ?>
                                    </span>
                                </td>
                                <td class="pe-4 text-end fw-bold">
                                    Rp <?= number_format($row['harga_sewa'] + $row['denda'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-5 print-only text-center">
        <hr>
        <p class="small text-secondary">Dicetak pada: <?= date('d/m/Y H:i:s') ?> | UKM UKS Politeknik Negeri Padang</p>
    </div>

</div>

</body>
</html>
