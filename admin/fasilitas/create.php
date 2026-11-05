<?php
require_once '../../config/database.php';

// proteksi admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: " . BASE_URL . "auth/admin_login.php");
    exit;
}

// proses simpan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
        http_response_code(403);
        exit('Token keamanan tidak valid.');
    }

    $nama = is_string($_POST['nama'] ?? null) ? trim($_POST['nama']) : '';
    $kategori = $_POST['kategori'] ?? '';
    $kondisi = $_POST['kondisi'] ?? '';
    $harga = filter_var($_POST['harga'] ?? null, FILTER_VALIDATE_INT);
    $jumlah = filter_var($_POST['jumlah'] ?? null, FILTER_VALIDATE_INT);
    $status = $_POST['status'] ?? '';

    $allowedCategories = ['Musik', 'Umum', 'Tari', 'Pertunjukan', 'Seni rupa', 'Phocinemart'];
    $allowedConditions = ['Sangat Baik', 'Baik', 'Cukup'];
    if ($nama === '' || strlen($nama) > 150
        || !in_array($kategori, $allowedCategories, true)
        || !in_array($kondisi, $allowedConditions, true)
        || $harga === false || $harga < 0
        || $jumlah === false || $jumlah < 1
        || !in_array($status, ['tersedia', 'dipinjam'], true)) {
        http_response_code(400);
        exit('Data fasilitas tidak valid.');
    }

    $stmt = $pdo->prepare("
        INSERT INTO fasilitas 
        (nama, kategori, kondisi, harga, jumlah, status)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $nama,
        $kategori,
        $kondisi,
        $harga,
        $jumlah,
        $status
    ]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Fasilitas - Admin UKS</title>
    
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
            min-height: 100vh;
        }
        .form-card {
            background: #fff;
            border-radius: 30px;
            padding: 2.5rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0,0,0,0.02);
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        .form-control, .form-select {
            border-radius: 12px;
            padding: 0.75rem 1rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }
        .btn-primary {
            background: var(--primary);
            border: none;
            border-radius: 12px;
            padding: 0.8rem 1.5rem;
            font-weight: 700;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
        }
        .btn-secondary {
            background: #e2e8f0;
            color: #475569;
            border: none;
            border-radius: 12px;
            padding: 0.8rem 1.5rem;
            font-weight: 600;
        }
        .btn-secondary:hover {
            background: #cbd5e1;
            color: #1e293b;
        }
        .input-group-text {
            border-radius: 12px 0 0 12px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #64748b;
        }
        .has-icon .form-control {
            border-radius: 0 12px 12px 0;
        }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-card" data-aos="fade-up">
                
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-4">
                        <i class="bi bi-plus-circle-dotted fs-3 text-primary"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">Tambah Fasilitas Baru</h3>
                        <p class="text-secondary small mb-0">Lengkapi detail fasilitas seni yang akan dipinjamkan.</p>
                    </div>
                </div>

                <hr class="opacity-10 mb-4">

                <form method="post">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                    <div class="row">
                        <div class="col-md-7 mb-4">
                            <label class="form-label small fw-bold text-secondary">Nama Fasilitas</label>
                            <input type="text" name="nama" class="form-control" placeholder="Contoh: Gitar Akustik Yamaha" required>
                        </div>
                        <div class="col-md-5 mb-4">
                            <label class="form-label small fw-bold text-secondary">Kategori</label>
                            <select name="kategori" class="form-select" required>
                                <option value="" disabled selected>Pilih Kategori</option>
                                <option value="Musik">Musik</option>
                                <option value="Umum">Umum</option>
                                <option value="Tari">Tari</option>
                                <option value="Pertunjukan">Pertunjukan</option>
                                <option value="Seni rupa">Seni rupa</option>
                                <option value="Phocinemart">Phocinemart</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label class="form-label small fw-bold text-secondary">Jumlah Stok</label>
                            <div class="input-group has-icon">
                                <span class="input-group-text"><i class="bi bi-boxes"></i></span>
                                <input type="number" name="jumlah" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label small fw-bold text-secondary">Harga Sewa (Rp)</label>
                            <div class="input-group has-icon">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label small fw-bold text-secondary">Kondisi Barang</label>
                            <select name="kondisi" class="form-select" required>
                                <option value="Sangat Baik">Sangat Baik</option>
                                <option value="Baik">Baik</option>
                                <option value="Cukup">Cukup</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">Status Awal</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status1" value="tersedia" checked>
                                <label class="form-check-label" for="status1">Tersedia</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="status2" value="dipinjam">
                                <label class="form-check-label" for="status2">Dipinjam</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <button class="btn btn-primary d-flex align-items-center gap-2">
                            <i class="bi bi-cloud-upload"></i> Simpan Fasilitas
                        </button>
                        <a href="index.php" class="btn btn-secondary">
                            Batal
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
