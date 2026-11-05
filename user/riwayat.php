<?php
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user']['id'])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit;
}
$user_id = (int) $_SESSION['user']['id'];

$stmt = $pdo->prepare("
    SELECT p.*, f.nama AS nama_fasilitas
    FROM peminjaman p
    JOIN fasilitas f ON p.fasilitas_id = f.id
    WHERE p.user_id = ?
    ORDER BY p.id DESC
");
$stmt->execute([$user_id]);
$data = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Peminjaman Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">
    <h3>Riwayat Peminjaman Saya</h3>

    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Fasilitas</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $d): ?>
            <tr>
                <td><?= htmlspecialchars($d['kode_pembayaran'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($d['nama_fasilitas']) ?></td>
                <td><?= htmlspecialchars($d['tgl_pinjam'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($d['tgl_kembali'], ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <span class="badge bg-primary"><?= htmlspecialchars($d['status'], ENT_QUOTES, 'UTF-8') ?></span>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

</body>
</html>
