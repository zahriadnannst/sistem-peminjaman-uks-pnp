<?php
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

fwrite(STDOUT, "Password admin baru: ");
$newPassword = trim((string) fgets(STDIN));
if (strlen($newPassword) < 8) {
    fwrite(STDERR, "Password harus berisi minimal 8 karakter.\n");
    exit(1);
}

require_once __DIR__ . '/../config/database.php';

$pass = password_hash($newPassword, PASSWORD_DEFAULT);
unset($newPassword);

$stmt = $pdo->prepare("
    UPDATE users 
    SET password = :pass 
    WHERE username = 'admin' AND role = 'admin'
");
$stmt->execute(['pass' => $pass]);

if ($stmt->rowCount() !== 1) {
    fwrite(STDERR, "Akun admin tidak ditemukan.\n");
    exit(1);
}

fwrite(STDOUT, "Password admin berhasil diperbarui.\n");
