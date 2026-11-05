<?php
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user'])) {
    http_response_code(404);
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$type = is_string($_GET['type'] ?? null) ? $_GET['type'] : '';
$columns = [
    'identitas' => 'identitas_file',
    'pembayaran' => 'bukti_pembayaran',
];

if (!$id || $id < 1 || !isset($columns[$type])) {
    http_response_code(404);
    exit;
}

$column = $columns[$type];
$stmt = $pdo->prepare("SELECT user_id, `$column` AS filename FROM peminjaman WHERE id = ?");
$stmt->execute([$id]);
$document = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$document
    || empty($document['filename'])
    || ($_SESSION['user']['role'] !== 'admin'
        && (int) $document['user_id'] !== (int) $_SESSION['user']['id'])) {
    http_response_code(404);
    exit;
}

$filename = $document['filename'];
if (!preg_match('/\A[A-Za-z0-9_-]+\.(?:jpg|jpeg|png)\z/i', $filename)) {
    http_response_code(404);
    exit;
}

$folder = $type === 'identitas' ? 'identitas' : 'pembayaran';
$uploadRoot = realpath(__DIR__ . '/../uploads/' . $folder);
$filePath = $uploadRoot === false ? false : realpath($uploadRoot . DIRECTORY_SEPARATOR . $filename);

if ($filePath === false
    || strpos($filePath, $uploadRoot . DIRECTORY_SEPARATOR) !== 0
    || !is_file($filePath)) {
    http_response_code(404);
    exit;
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = $finfo ? finfo_file($finfo, $filePath) : false;
if ($finfo) {
    finfo_close($finfo);
}

if (!in_array($mime, ['image/jpeg', 'image/png'], true)) {
    http_response_code(404);
    exit;
}

header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($filePath));
header('Content-Disposition: inline; filename="' . basename($filename) . '"');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store, max-age=0');
readfile($filePath);
