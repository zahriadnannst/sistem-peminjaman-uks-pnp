<?php
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user'])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit;
}

$user = $_SESSION['user'];
$id = $user['id'];

if (isset($_POST['update_profile'])) {
    // CSRF Check
    if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
        set_flash('error', 'Keamanan', 'Token tidak valid.');
        header("Location: profil_edit.php");
        exit;
    }

    $name = is_string($_POST['name'] ?? null) ? trim($_POST['name']) : '';
    $email = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';
    $new_password = is_string($_POST['new_password'] ?? null) ? $_POST['new_password'] : '';

    if ($name === '' || strlen($name) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL)
        || strlen($email) > 200 || ($new_password !== '' && strlen($new_password) < 8)) {
        set_flash('error', 'Input Tidak Valid', 'Nama, email, atau password baru tidak memenuhi ketentuan.');
        header("Location: profil_edit.php");
        exit;
    }

    try {
        // Cek email duplikat (kecuali punya sendiri)
        $stmt_cek = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt_cek->execute([$email, $id]);
        if ($stmt_cek->rowCount() > 0) {
            set_flash('error', 'Gagal', 'Email sudah digunakan oleh user lain.');
        } else {
            if (!empty($new_password)) {
                // Update dengan password baru
                $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
                $stmt->execute([$name, $email, $hashed, $id]);
            } else {
                // Update tanpa ganti password
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
                $stmt->execute([$name, $email, $id]);
            }

            // Update session
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['email'] = $email;

            set_flash('success', 'Berhasil', 'Profil Anda telah diperbarui.');
            header("Location: profil_edit.php");
            exit;
        }
    } catch (PDOException $e) {
        set_flash('error', 'Error', 'Terjadi kesalahan sistem.');
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card shadow-sm p-4 p-lg-5">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary-subtle text-primary p-3 rounded-4">
                        <i class="bi bi-person-gear fs-3"></i>
                    </div>
                    <div>
                        <h2 class="fw-bold mb-0">Pengaturan <span class="highlight-text">Profil</span></h2>
                        <p class="text-secondary small mb-0">Kelola informasi akun Anda</p>
                    </div>
                </div>

                <form method="POST">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Alamat Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Password Baru <span class="text-muted fw-normal">(Kosongkan jika tidak ingin ganti)</span></label>
                        <input type="password" name="new_password" class="form-control" placeholder="Minimal 6 karakter" minlength="6">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="update_profile" class="btn btn-primary-custom justify-content-center py-3">
                            <i class="bi bi-save me-2"></i> Simpan Perubahan
                        </button>
                        <a href="<?= BASE_URL ?>index.php" class="btn btn-light rounded-4 py-3 fw-semibold text-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
