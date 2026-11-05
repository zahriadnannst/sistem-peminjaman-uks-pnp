<?php
require_once '../config/database.php';

// proteksi admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'auth/admin_login.php');
    exit;
}

/* =====================
   STATISTIK DASHBOARD
===================== */
$totalFasilitas = (int)$pdo->query("SELECT COUNT(*) FROM fasilitas")->fetchColumn();
$totalUsers     = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

$totalPending   = (int)$pdo->query("SELECT COUNT(*) FROM peminjaman WHERE status='pending'")->fetchColumn();
$totalDipinjam  = (int)$pdo->query("SELECT COUNT(*) FROM peminjaman WHERE status='dipinjam'")->fetchColumn();
$totalKembali   = (int)$pdo->query("SELECT COUNT(*) FROM peminjaman WHERE status='dikembalikan'")->fetchColumn();

/* =====================
   DATA PEMINJAMAN TERBARU
===================== */
$stmt = $pdo->query("
    SELECT 
        p.id,
        p.tgl_pinjam,
        p.tgl_kembali,
        p.status,
        p.created_at,
        u.name AS peminjam,
        f.nama AS fasilitas
    FROM peminjaman p
    JOIN users u ON p.user_id = u.id
    JOIN fasilitas f ON p.fasilitas_id = f.id
    ORDER BY p.created_at DESC
    LIMIT 10
");
$peminjaman = $stmt->fetchAll(PDO::FETCH_ASSOC);

// tanggal hari ini (untuk cek terlambat)
$today = date('Y-m-d');

/* =====================
   DATA UNTUK GRAFIK
   ===================== */

// 1. Data Pendapatan (12 Bulan Terakhir) - Detil Sewa vs Denda
$incomeData = $pdo->query("
    SELECT 
        DATE_FORMAT(p.tgl_pinjam, '%M %Y') as bulan,
        SUM(f.harga * p.jumlah_pinjam) as rental,
        SUM(p.denda) as fine,
        DATE_FORMAT(p.tgl_pinjam, '%Y-%m') as sort_key
    FROM peminjaman p
    JOIN fasilitas f ON p.fasilitas_id = f.id
    WHERE p.status = 'dikembalikan'
    GROUP BY bulan, sort_key
    ORDER BY sort_key ASC
    LIMIT 12
")->fetchAll(PDO::FETCH_ASSOC);

$labelsBulan = [];
$valuesRental = [];
$valuesFine = [];
foreach ($incomeData as $row) {
    $labelsBulan[] = $row['bulan'];
    $valuesRental[] = (int)$row['rental'];
    $valuesFine[] = (int)$row['fine'];
}

// 2. Data Pendapatan Harian (30 Hari Terakhir)
$dailyIncome = $pdo->query("
    SELECT 
        DATE(p.tgl_pinjam) as tanggal,
        SUM((f.harga * p.jumlah_pinjam) + p.denda) as total
    FROM peminjaman p
    JOIN fasilitas f ON p.fasilitas_id = f.id
    WHERE p.status = 'dikembalikan'
      AND p.tgl_pinjam >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY tanggal
    ORDER BY tanggal ASC
")->fetchAll(PDO::FETCH_ASSOC);

$labelsDaily = [];
$valuesDaily = [];
foreach ($dailyIncome as $row) {
    $labelsDaily[] = date('d M', strtotime($row['tanggal']));
    $valuesDaily[] = (int)$row['total'];
}

// 2. Data Pinjaman per Kategori - Selalu tampilkan 6 kategori
$allCategories = ["Musik", "Umum", "Tari", "Pertunjukan", "Seni rupa", "Phocinemart"];

$categoryData = $pdo->query("
    SELECT 
        f.kategori,
        SUM(p.jumlah_pinjam) as jumlah
    FROM peminjaman p
    JOIN fasilitas f ON p.fasilitas_id = f.id
    GROUP BY f.kategori
")->fetchAll(PDO::FETCH_ASSOC);

// Buat array asosiatif untuk mapping kategori ke jumlah
$categoryMap = [];
foreach ($categoryData as $row) {
    $categoryMap[$row['kategori']] = (int)$row['jumlah'];
}

// Pastikan semua 6 kategori ditampilkan, bahkan jika tidak ada data
$labelsKat = [];
$valuesKat = [];
foreach ($allCategories as $cat) {
    $labelsKat[] = $cat;
    $valuesKat[] = isset($categoryMap[$cat]) ? $categoryMap[$cat] : 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - UKS PNP</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary: #6366f1;
            --bg-light: #f8fafc;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-light);
            color: #1e293b;
        }
        .stat-card {
            border-radius: 24px;
            border: none;
            transition: transform 0.3s ease;
            overflow: hidden;
            position: relative;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-icon {
            position: absolute;
            right: -10px;
            bottom: -10px;
            font-size: 5rem;
            opacity: 0.1;
            transform: rotate(-15deg);
        }
        .card-table { border-radius: 24px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.02); }
        .table thead th { background: #f1f5f9; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 1.25rem 1rem; border: none; }
        .table tbody td { padding: 1.25rem 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    </style>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="container py-5">

    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-extrabold text-dark mb-1" style="letter-spacing: -1.5px; font-size: 2.5rem;">Dashboard <span class="text-primary">Admin</span></h2>
            <div class="d-flex gap-2">
                <a href="laporan/index.php" class="btn btn-sm btn-primary rounded-pill px-3">
                    <i class="bi bi-file-earmark-bar-graph me-1"></i> Laporan
                </a>
            </div>
        </div>
        <div class="col-auto">
            <div class="bg-white p-3 rounded-4 shadow-sm">
                <div class="small fw-bold text-secondary mb-1">Total Pendapatan (30 Hari)</div>
                <h4 class="fw-extrabold text-primary mb-0">Rp <?= number_format(array_sum($valuesDaily), 0, ',', '.') ?></h4>
            </div>
        </div>
    </div>

    <!-- STATISTIK -->
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card bg-primary text-white shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="small fw-semibold opacity-75 mb-1">Total Fasilitas</div>
                    <h2 class="fw-bold mb-0"><?= $totalFasilitas ?></h2>
                    <i class="bi bi-hospital stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card stat-card bg-dark text-white shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="small fw-semibold opacity-75 mb-1">Total Peminjam</div>
                    <h2 class="fw-bold mb-0"><?= $totalUsers ?></h2>
                    <i class="bi bi-people stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card stat-card border border-warning-subtle bg-warning-subtle text-warning-emphasis h-100">
                <div class="card-body p-4">
                    <div class="small fw-bold mb-1">Menunggu Persetujuan</div>
                    <h2 class="fw-bold mb-0"><?= $totalPending ?></h2>
                    <i class="bi bi-clock-history stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card stat-card border border-success-subtle bg-success-subtle text-success-emphasis h-100">
                <div class="card-body p-4">
                    <div class="small fw-bold mb-1">Dikembalikan</div>
                    <h2 class="fw-bold mb-0"><?= $totalKembali ?></h2>
                    <i class="bi bi-check2-circle stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- GRAFIK SECTION -->
    <div class="row g-4 mb-5">
        <!-- Grafik Pendapatan -->
        <div class="col-lg-8">
            <div class="card card-table shadow-sm p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Pendapatan UKS</h5>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3">6 Bulan Terakhir</span>
                </div>
                <div style="height: 300px;">
                    <canvas id="incomeChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Grafik Kategori -->
        <div class="col-lg-4">
            <div class="card card-table shadow-sm p-4 h-100">
                <h5 class="fw-bold mb-4">Popolaritas Kategori</h5>
                <div style="height: 300px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- DAILY TREND SECTION -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card card-table shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Pendapatan Harian (30 Hari Terakhir)</h5>
                    <span class="text-secondary small">Total Fluktuasi Harian</span>
                </div>
                <div style="height: 250px;">
                    <canvas id="dailyChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL PEMINJAMAN -->
    <div class="card card-table overflow-hidden shadow-sm">
        <div class="card-header bg-white py-4 px-4 border-bottom-0">
            <h5 class="fw-bold mb-0 text-dark">Riwayat Peminjaman Terbaru</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Mahasiswa</th>
                        <th>Fasilitas</th>
                        <th>Status</th>
                        <th>Waktu Pinjam</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                <?php if (empty($peminjaman)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted small">
                            Belum ada aktivitas baru.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($peminjaman as $i => $p): ?>
                    <tr>
                        <td class="ps-4 text-secondary small"><?= $i + 1 ?></td>
                        <td>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($p['peminjam']) ?></div>
                            <div class="tiny text-muted"><?= $p['created_at'] ?></div>
                        </td>
                        <td>
                            <div class="fw-medium text-dark"><?= htmlspecialchars($p['fasilitas']) ?></div>
                        </td>
                        <td>
                            <?php
                            if ($p['status'] === 'pending') {
                                echo '<span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2">Pending</span>';
                            }
                            elseif ($p['status'] === 'dipinjam') {
                                // Logic baru: Terlambat jika hari ini > tgl_kembali (strictly greater, meaning tomorrow)
                                if ($today > $p['tgl_kembali']) {
                                    echo '<span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">Terlambat</span>';
                                } else {
                                    echo '<span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">Dipinjam</span>';
                                }
                            }
                            else {
                                echo '<span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2">Kembali</span>';
                            }
                            ?>
                        </td>
                        <td class="small text-secondary">
                            <?= $p['tgl_pinjam'] ?> <span class="mx-1 text-muted">→</span> <?= $p['tgl_kembali'] ?>
                        </td>
                        <td class="text-center pe-4">
                            <?php if ($p['status'] === 'dipinjam'): ?>
                                <a href="peminjaman/return.php?id=<?= $p['id'] ?>"
                                   class="btn btn-sm btn-indigo px-3 rounded-pill text-white shadow-sm"
                                   style="background: #6366f1;"
                                   onclick="return confirm('Tandai sebagai sudah dikembalikan?')">
                                   <i class="bi bi-check-lg"></i>
                                </a>
                            <?php else: ?>
                                <span class="text-muted small"><i class="bi bi-dash"></i></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>

</div>

<style>
    .fw-extrabold { font-weight: 800; }
    .tiny { font-size: 0.65rem; }
    .btn-indigo:hover { background: #4f46e5 !important; transform: scale(1.05); }
</style>

<script>
// Logic Grafik Pendapatan
const ctxIncome = document.getElementById('incomeChart').getContext('2d');
new Chart(ctxIncome, {
    type: 'bar', // Gunakan bar untuk detil sewa vs denda
    data: {
        labels: <?= json_encode($labelsBulan) ?>,
        datasets: [
            {
                label: 'Sewa Fasilitas',
                data: <?= json_encode($valuesRental) ?>,
                backgroundColor: '#6366f1',
                borderRadius: 8
            },
            {
                label: 'Denda Late',
                data: <?= json_encode($valuesFine) ?>,
                backgroundColor: '#f43f5e',
                borderRadius: 8
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top', align: 'end' },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString();
                    }
                }
            }
        },
        scales: {
            y: {
                stacked: true,
                beginAtZero: true,
                grid: { borderDash: [5, 5] },
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + (value/1000) + 'k';
                    }
                }
            },
            x: { stacked: true, grid: { display: false } }
        }
    }
});

// Logic Grafik Kategori
const ctxKat = document.getElementById('categoryChart').getContext('2d');
new Chart(ctxKat, {
    // ... (doughnut logic unchanged)
    type: 'doughnut',
    data: {
        labels: <?= json_encode($labelsKat) ?>,
        datasets: [{
            data: <?= json_encode($valuesKat) ?>,
            backgroundColor: [ '#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899' ],
            borderWidth: 0,
            hoverOffset: 15
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 } } } },
        cutout: '70%'
    }
});

// Logic Grafik Harian
const ctxDaily = document.getElementById('dailyChart').getContext('2d');
new Chart(ctxDaily, {
    // ... (logic remains same)
    type: 'line',
    data: {
        labels: <?= json_encode($labelsDaily) ?>,
        datasets: [{
            label: 'Pendapatan Harian',
            data: <?= json_encode($valuesDaily) ?>,
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            fill: true,
            tension: 0.3,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointBackgroundColor: '#10b981'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { borderDash: [5, 5] }, ticks: { callback: function(value) { return 'Rp ' + value.toLocaleString(); } } },
            x: { grid: { display: false } }
        }
    }
});

function confirmReturn(url, id) {
    if (confirm('Tandai sebagai sudah dikembalikan? (Anda akan diarahkan ke halaman konfirmasi denda)')) {
        window.location.href = url + '?id=' + id;
    }
}
</script>

</body>
</html>
