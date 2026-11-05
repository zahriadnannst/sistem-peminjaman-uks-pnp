<?php
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user']['id'])) {
    http_response_code(401);
    exit('Silakan login terlebih dahulu.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metode permintaan tidak diizinkan.');
}

// ===============================
// VALIDASI INPUT
// ===============================
if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
    http_response_code(403);
    exit('Token keamanan tidak valid.');
}

$fasilitas_id = filter_input(INPUT_POST, 'fasilitas_id', FILTER_VALIDATE_INT);
$tgl_pinjam = is_string($_POST['tgl_pinjam'] ?? null) ? $_POST['tgl_pinjam'] : '';
$tgl_kembali = is_string($_POST['tgl_kembali'] ?? null) ? $_POST['tgl_kembali'] : '';
$keperluan = is_string($_POST['keperluan'] ?? null) ? trim($_POST['keperluan']) : '';
$jumlah_pinjam   = (int) ($_POST['jumlah_pinjam'] ?? 1);

$pinjamDate = DateTime::createFromFormat('!Y-m-d', $tgl_pinjam);
$kembaliDate = DateTime::createFromFormat('!Y-m-d', $tgl_kembali);
if (!$fasilitas_id || $fasilitas_id < 1
    || $jumlah_pinjam < 1
    || !$pinjamDate || $pinjamDate->format('Y-m-d') !== $tgl_pinjam
    || !$kembaliDate || $kembaliDate->format('Y-m-d') !== $tgl_kembali
    || $kembaliDate < $pinjamDate
    || $keperluan === '') {
    http_response_code(400);
    exit('Data peminjaman tidak valid.');
}

$user_id = $_SESSION['user']['id'];

// ===============================
// VALIDASI STOK (SERVER-SIDE)
// ===============================
$stmt_f = $pdo->prepare("SELECT * FROM fasilitas WHERE id = ?");
$stmt_f->execute([$fasilitas_id]);
$f = $stmt_f->fetch();

if (!$f) {
    die("Fasilitas tidak ditemukan!");
}

$stmt_stok = $pdo->prepare("SELECT SUM(jumlah_pinjam) FROM peminjaman WHERE fasilitas_id = ? AND status = 'dipinjam'");
$stmt_stok->execute([$fasilitas_id]);
$stok_terpakai = (int) $stmt_stok->fetchColumn() ?: 0;
$stok_tersisa = $f['jumlah'] - $stok_terpakai;

if ($jumlah_pinjam > $stok_tersisa) {
    set_flash('error', 'Stok Tidak Cukup', "Maaf, sisa stok tersedia hanya $stok_tersisa unit.");
    header("Location: index.php?id=" . $fasilitas_id);
    exit;
}

if ($jumlah_pinjam < 1) {
    set_flash('error', 'Input Gagal', 'Jumlah peminjaman minimal 1 unit.');
    header("Location: index.php?id=" . $fasilitas_id);
    exit;
}

// ===============================
// GENERATE KODE PEMINJAMAN UNIK
// Format: PMJ-2025-12345
// ===============================
$kode_pembayaran = "PMJ-" . date("Y") . "-" . rand(10000,99999);

// ===============================
// VALIDASI UPLOAD FILE IDENTITAS
// ===============================
if (!isset($_FILES['identitas']['error'], $_FILES['identitas']['tmp_name'])
    || $_FILES['identitas']['error'] !== UPLOAD_ERR_OK
    || !is_string($_FILES['identitas']['tmp_name'])
    || !is_uploaded_file($_FILES['identitas']['tmp_name'])) {
    set_flash('error', 'Upload Gagal', 'Harap lampirkan foto identitas Anda.');
    header("Location: index.php?id=" . $fasilitas_id);
    exit;
}

$allowed = ['jpg', 'jpeg', 'png'];
$namaFile = $_FILES['identitas']['name'];
$tmpFile  = $_FILES['identitas']['tmp_name'];

$ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

if (!in_array($ext, $allowed, true)) {
    set_flash('error', 'Format Tidak Valid', 'Identitas harus berupa JPG atau PNG.');
    header("Location: index.php?id=" . $fasilitas_id);
    exit;
}

// ===============================
// SECURITY: MIME TYPE CHECK
// ===============================
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = $finfo ? finfo_file($finfo, $tmpFile) : false;
if ($finfo) {
    finfo_close($finfo);
}

$allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];

if (!in_array($mime, $allowedMimes, true)) {
    set_flash('error', 'File Berbahaya', 'Tipe file identitas tidak diperbolehkan.');
    header("Location: index.php?id=" . $fasilitas_id);
    exit;
}

// Buat folder jika belum ada (Relative to BASE_PATH/uploads)
$folder = __DIR__ . '/../uploads/identitas/';
if (!is_dir($folder)) {
    mkdir($folder, 0750, true);
}

// Nama file unik
$newName = 'ID_' . bin2hex(random_bytes(16)) . '.' . $ext;

// Upload file identitas
if (!move_uploaded_file($tmpFile, $folder . $newName)) {
    set_flash('error', 'Upload Gagal', 'File identitas tidak dapat disimpan.');
    header("Location: index.php?id=" . $fasilitas_id);
    exit;
}

// ===============================
// VALIDASI UPLOAD BUKTI PEMBAYARAN
// ===============================
if (!isset($_FILES['bukti_pembayaran']['error'], $_FILES['bukti_pembayaran']['tmp_name'])
    || $_FILES['bukti_pembayaran']['error'] !== UPLOAD_ERR_OK
    || !is_string($_FILES['bukti_pembayaran']['tmp_name'])
    || !is_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'])) {
    @unlink($folder . $newName);
    set_flash('error', 'Upload Gagal', 'Harap lampirkan bukti pembayaran.');
    header("Location: index.php?id=" . $fasilitas_id);
    exit;
}

$allowedP = ['jpg', 'jpeg', 'png'];
$namaFileP = $_FILES['bukti_pembayaran']['name'];
$tmpFileP  = $_FILES['bukti_pembayaran']['tmp_name'];
$extP = strtolower(pathinfo($namaFileP, PATHINFO_EXTENSION));

if (!in_array($extP, $allowedP, true)) {
    @unlink($folder . $newName);
    set_flash('error', 'Format Tidak Valid', 'Bukti pembayaran harus berupa JPG atau PNG.');
    header("Location: index.php?id=" . $fasilitas_id);
    exit;
}

// Security MIME check for payment proof
$finfoP = finfo_open(FILEINFO_MIME_TYPE);
$mimeP = $finfoP ? finfo_file($finfoP, $tmpFileP) : false;
if ($finfoP) {
    finfo_close($finfoP);
}

if (!in_array($mimeP, $allowedMimes, true)) {
    @unlink($folder . $newName);
    set_flash('error', 'File Tidak Valid', 'Tipe file bukti pembayaran tidak diperbolehkan.');
    header("Location: index.php?id=" . $fasilitas_id);
    exit;
}

$folderP = __DIR__ . '/../uploads/pembayaran/';
if (!is_dir($folderP)) {
    mkdir($folderP, 0750, true);
}

$newNameP = 'PAY_' . bin2hex(random_bytes(16)) . '.' . $extP;
if (!move_uploaded_file($tmpFileP, $folderP . $newNameP)) {
    @unlink($folder . $newName);
    set_flash('error', 'Upload Gagal', 'Bukti pembayaran tidak dapat disimpan.');
    header("Location: index.php?id=" . $fasilitas_id);
    exit;
}

// ===============================
// SIMPAN KE DATABASE
// ===============================
$stmt = $pdo->prepare("
    INSERT INTO peminjaman 
    (user_id, fasilitas_id, jumlah_pinjam, tgl_pinjam, tgl_kembali, keperluan, kode_pembayaran, identitas_file, bukti_pembayaran, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
");

try {
    $stmt->execute([
        $user_id,
        $fasilitas_id,
        $jumlah_pinjam,
        $tgl_pinjam,
        $tgl_kembali,
        $keperluan,
        $kode_pembayaran,
        $newName,
        $newNameP
    ]);

    set_flash('success', 'Berhasil!', 'Peminjaman Anda telah terkirim dan sedang menunggu verifikasi.');
    header("Location: success.php?kode=" . $kode_pembayaran);
    exit;
} catch (PDOException $e) {
    @unlink($folder . $newName);
    @unlink($folderP . $newNameP);
    error_log('Loan submission failed: ' . $e->getMessage());
    set_flash('error', 'Gagal', 'Terjadi kesalahan saat menyimpan data.');
    header("Location: index.php?id=" . $fasilitas_id);
    exit;
}
?>
