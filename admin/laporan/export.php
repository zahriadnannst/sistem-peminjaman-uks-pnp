<?php
require_once '../../config/database.php';

// Cek Role Admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    exit('Akses ditolak.');
}

$tgl_mulai   = $_GET['tgl_mulai'] ?? date('Y-m-01');
$tgl_selesai = $_GET['tgl_selesai'] ?? date('Y-m-d');
if (!is_string($tgl_mulai) || !is_string($tgl_selesai)) {
    http_response_code(400);
    exit('Rentang tanggal laporan tidak valid.');
}
$dateStart = DateTime::createFromFormat('!Y-m-d', $tgl_mulai);
$dateEnd = DateTime::createFromFormat('!Y-m-d', $tgl_selesai);

if (!$dateStart || $dateStart->format('Y-m-d') !== $tgl_mulai
    || !$dateEnd || $dateEnd->format('Y-m-d') !== $tgl_selesai
    || $dateEnd < $dateStart) {
    http_response_code(400);
    exit('Rentang tanggal laporan tidak valid.');
}

// Ambil Data
$sql = "
    SELECT 
        p.id,
        p.tgl_pinjam,
        p.tgl_kembali,
        p.status,
        p.denda,
        u.name AS nama_peminjam,
        f.nama AS nama_fasilitas,
        f.harga AS harga_sewa
    FROM peminjaman p
    JOIN users u ON p.user_id = u.id
    JOIN fasilitas f ON p.fasilitas_id = f.id
    WHERE DATE(p.tgl_pinjam) BETWEEN ? AND ?
    ORDER BY p.tgl_pinjam ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$tgl_mulai, $tgl_selesai]);
$data = $stmt->fetchAll();

// Set Header Download CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=laporan_peminjaman_' . $tgl_mulai . '_sd_' . $tgl_selesai . '.csv');

$output = fopen('php://output', 'w');
if ($output === false) {
    http_response_code(500);
    exit('Laporan tidak dapat dibuat.');
}

// Header Kolom
fputcsv($output, ['ID', 'Tanggal Pinjam', 'Peminjam', 'Fasilitas', 'Harga Sewa', 'Denda', 'Status', 'Total']);

$total_pendapatan = 0;

foreach ($data as $row) {
    if ($row['status'] == 'dikembalikan') {
        $total = (int)$row['harga_sewa'] + (int)$row['denda'];
        $total_pendapatan += $total;
    } else {
        $total = 0;
    }

    $safeName = preg_match('/\A[\s]*[=+\-@]/u', $row['nama_peminjam'])
        ? "'" . $row['nama_peminjam']
        : $row['nama_peminjam'];
    $safeFacility = preg_match('/\A[\s]*[=+\-@]/u', $row['nama_fasilitas'])
        ? "'" . $row['nama_fasilitas']
        : $row['nama_fasilitas'];

    fputcsv($output, [
        $row['id'],
        $row['tgl_pinjam'],
        $safeName,
        $safeFacility,
        $row['harga_sewa'],
        $row['denda'],
        strtoupper($row['status']),
        $total
    ]);
}

// Baris Total
fputcsv($output, ['', '', '', '', '', '', 'TOTAL PENDAPATAN', $total_pendapatan]);

fclose($output);
exit;
