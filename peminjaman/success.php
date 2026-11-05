<?php
require_once __DIR__ . '/../config/database.php';
$kode = $_GET['kode'] ?? '';
if (!is_string($kode)) {
    $kode = '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Sukses - UKS PNP</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: #f0f4f8;
        }
        .success-card {
            max-width: 500px;
            padding: 3rem;
            text-align: center;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background: var(--success);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
        }
    </style>
</head>
<body>

<div class="container px-3">
    <div class="card success-card mx-auto shadow-lg border-0">
        <div class="success-icon slide-up">
            <i class="bi bi-check-lg"></i>
        </div>
        
        <h2 class="fw-bold mb-3">Peminjaman Berhasil!</h2>
        <p class="text-secondary mb-4">
            Permohonan peminjaman Anda telah terdaftar dengan kode:<br>
            <span class="badge bg-primary-subtle text-primary fs-6 px-3 py-2 mt-2">#<?= htmlspecialchars($kode) ?></span>
        </p>
        
        <div class="alert alert-info border-0 rounded-4 small mb-5">
            <i class="bi bi-info-circle me-1"></i>
            Harap menunggu tim admin untuk memverifikasi bukti pembayaran Anda.
        </div>

        <div class="d-grid gap-3">
            <a href="<?= BASE_URL ?>user/peminjaman_saya.php" class="btn btn-primary-custom justify-content-center">
                <i class="bi bi-clock-history me-2"></i> Pantau Riwayat Saya
            </a>
            <a href="<?= BASE_URL ?>index.php" class="text-secondary text-decoration-none small">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

</body>
</html>
