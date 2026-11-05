<?php
require_once '../../config/database.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ' . BASE_URL . 'auth/admin_login.php');
    exit;
}

$search = isset($_GET['q']) ? $_GET['q'] : '';
$sql = "SELECT id, nama FROM fasilitas";
$params = [];

if (!empty($search)) {
    $sql .= " WHERE nama LIKE :search";
    $params['search'] = "%$search%";
}

$sql .= " ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Fasilitas - Admin UKS</title>
    
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
        .btn-plus {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 700;
            transition: all 0.3s ease;
        }
        .btn-plus:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
            color: white;
        }
        .action-btn {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            transition: all 0.2s;
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }
        .btn-edit { background: #fef9c3; color: #a16207; }
        .btn-edit:hover { background: #fde68a; }
        .btn-delete { background: #fee2e2; color: #991b1b; }
        .btn-delete:hover { background: #fecaca; }
        
        /* Mobile responsive for header and search */
        @media (max-width: 768px) {
            .row.mb-4 {
                flex-direction: column;
                gap: 1rem;
            }
            .col-auto.d-flex {
                flex-direction: column;
                gap: 0.75rem !important;
            }
            .form-control[name="q"] {
                min-width: 100% !important;
            }
            .btn-plus {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="container py-5">
    
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="fw-extrabold text-dark mb-1" style="letter-spacing: -1px;">📦 Manajemen <span class="text-primary">Fasilitas</span></h2>
            <p class="text-secondary mb-0">Kelola daftar peralatan seni yang tersedia untuk dipinjam.</p>
        </div>
        <div class="col-auto d-flex gap-2">
            <form method="GET" class="d-flex position-relative align-items-center">
                <i class="bi bi-search position-absolute ms-3 text-secondary small"></i>
                <input type="text" name="q" class="form-control ps-5 border-0 shadow-sm" placeholder="Cari..." value="<?= htmlspecialchars($search) ?>" style="border-radius: 12px; height: 45px; min-width: 250px;">
            </form>
            <a href="create.php" class="btn btn-plus d-flex align-items-center gap-2 shadow-sm" style="height: 45px;">
                <i class="bi bi-plus-circle"></i> <span>Tambah</span>
            </a>
        </div>
    </div>

    <div class="card card-table shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Nama Fasilitas</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($data)): ?>
                    <tr>
                        <td colspan="3" class="text-center py-5 text-muted small">
                            Belum ada data fasilitas.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($data as $i => $f): ?>
                    <tr>
                        <td class="ps-4 text-secondary small"><?= $i + 1 ?></td>
                        <td>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($f['nama']) ?></div>
                        </td>
                        <td class="text-center">
                            <a href="edit.php?id=<?= $f['id'] ?>" class="action-btn btn-edit me-1" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form method="POST" action="delete.php" class="d-inline">
                                <input type="hidden" name="id" value="<?= (int) $f['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                <button type="submit"
                                        class="action-btn btn-delete border-0"
                                        title="Hapus"
                                        onclick="return confirm('Yakin hapus fasilitas ini?')">
                                    <i class="bi bi-trash"></i>
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
