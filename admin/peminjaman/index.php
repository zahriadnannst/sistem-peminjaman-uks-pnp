<?php
require_once '../../config/database.php';

/* CEK LOGIN ADMIN */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'auth/admin_login.php');
    exit;
}

/* AMBIL DATA PEMINJAMAN */
$data = $pdo->query("
    SELECT 
        p.id,
        p.jumlah_pinjam,
        p.tgl_pinjam,
        p.tgl_kembali,
        p.status,
        p.identitas_file,
        p.bukti_pembayaran,
        p.created_at,
        u.name AS nama_peminjam,
        f.nama AS nama_fasilitas
    FROM peminjaman p
    JOIN users u ON p.user_id = u.id
    JOIN fasilitas f ON p.fasilitas_id = f.id
    ORDER BY p.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Peminjaman - Admin UKS</title>
    
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
        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }
        
        /* Mobile action buttons - stack vertically */
        @media (max-width: 768px) {
            .table td .btn-action {
                display: block;
                width: 100%;
                margin-bottom: 0.25rem;
            }
            .table td .btn-action:last-child {
                margin-bottom: 0;
            }
        }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="container py-5">
    
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-extrabold text-dark mb-1" style="letter-spacing: -1px;">📋 Data <span class="text-primary">Peminjaman</span></h2>
            <p class="text-secondary mb-0">Atur dan pantau semua aktivitas peminjaman fasilitas.</p>
        </div>
    </div>

    <div class="card card-table shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Mahasiswa</th>
                        <th>Fasilitas</th>
                        <th class="text-center">Jumlah</th>
                        <th>Jadwal Pinjam</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($data)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted small">
                            Belum ada data peminjaman.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($data as $i => $d): ?>
                    <tr>
                        <td class="ps-4 text-secondary small"><?= $i + 1 ?></td>
                        <td>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($d['nama_peminjam']) ?></div>
                            <div class="small text-muted" style="font-size: 0.7rem;"><?= $d['created_at'] ?></div>
                        </td>
                        <td>
                            <div class="fw-medium text-dark"><?= htmlspecialchars($d['nama_fasilitas']) ?></div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border px-2 py-1"><?= $d['jumlah_pinjam'] ?> Unit</span>
                        </td>
                        <td>
                            <span class="small text-secondary"><?= $d['tgl_pinjam'] ?></span>
                            <i class="bi bi-arrow-right mx-1 text-muted small"></i>
                            <span class="small text-secondary"><?= $d['tgl_kembali'] ?></span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <?php if($d['identitas_file']): ?>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-secondary p-1 proof-button"
                                        data-proof-url="<?= htmlspecialchars(BASE_URL . 'files/loan-document.php?id=' . (int) $d['id'] . '&type=identitas', ENT_QUOTES, 'UTF-8') ?>"
                                        data-proof-title="<?= htmlspecialchars('Identitas: ' . $d['nama_peminjam'], ENT_QUOTES, 'UTF-8') ?>"
                                        title="Lihat Identitas">
                                    <i class="bi bi-person-badge"></i>
                                </button>
                                <?php endif; ?>
                                <?php if($d['bukti_pembayaran']): ?>
                                <button type="button" 
                                        class="btn btn-sm btn-outline-primary p-1 proof-button"
                                        data-proof-url="<?= htmlspecialchars(BASE_URL . 'files/loan-document.php?id=' . (int) $d['id'] . '&type=pembayaran', ENT_QUOTES, 'UTF-8') ?>"
                                        data-proof-title="<?= htmlspecialchars('Bukti Bayar: ' . $d['nama_peminjam'], ENT_QUOTES, 'UTF-8') ?>"
                                        title="Lihat Bukti Bayar">
                                    <i class="bi bi-receipt"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <?php if ($d['status'] === 'pending'): ?>
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle status-badge">Pending</span>
                            <?php elseif ($d['status'] === 'dipinjam'): ?>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle status-badge">Dipinjam</span>
                            <?php elseif ($d['status'] === 'ditolak'): ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle status-badge">Ditolak</span>
                            <?php else: ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle status-badge">Kembali</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($d['status'] === 'pending'): ?>
                                <button type="button" 
                                        class="btn btn-success btn-action me-1"
                                        onclick="confirmAction('approve.php', '<?= (int) $d['id'] ?>', 'Setujui peminjaman ini?')">
                                   Setujui
                                </button>
                                <button type="button" 
                                        class="btn btn-danger btn-action"
                                        onclick="confirmAction('reject.php', '<?= (int) $d['id'] ?>', 'Tolak peminjaman ini?')">
                                   Tolak
                                </button>
                            <?php elseif ($d['status'] === 'dipinjam'): ?>
                                <a href="return.php?id=<?= $d['id'] ?>"
                                   class="btn btn-indigo btn-action text-white"
                                   style="background: #6366f1;">
                                   <i class="bi bi-check2-circle me-1"></i> Kembalikan
                                </a>
                            <?php else: ?>
                                <span class="text-muted small">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Viewer Bukti -->
<div class="modal fade" id="proofModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="proofTitle">Detail Bukti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="bg-light p-2 rounded-3 mb-3">
                    <img id="proofImage" src="" class="img-fluid rounded-3 shadow-sm" style="max-height: 70vh;" alt="Bukti">
                </div>
                <div class="d-grid">
                    <button type="button" class="btn btn-light fw-bold py-2" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Tersembunyi untuk Action POST (Hanya Admin) -->
<form id="actionForm" method="POST" style="display:none;">
    <input type="hidden" name="id" id="actionId">
    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const proofModal = new bootstrap.Modal(document.getElementById('proofModal'));
    const proofImage = document.getElementById('proofImage');
    const proofTitle = document.getElementById('proofTitle');

    document.querySelectorAll('.proof-button').forEach(button => {
        button.addEventListener('click', () => {
            showProof(button.dataset.proofUrl, button.dataset.proofTitle);
        });
    });

    function showProof(url, title) {
        proofImage.src = url;
        proofTitle.innerText = title;
        proofModal.show();
    }

    function confirmAction(url, id, message) {
        if (confirm(message)) {
            const form = document.getElementById('actionForm');
            form.action = url;
            document.getElementById('actionId').value = id;
            form.submit();
        }
    }
</script></body>
</html>
