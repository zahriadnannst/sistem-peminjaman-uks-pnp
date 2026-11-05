<?php
require_once __DIR__ . '/../config/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman UKS PNP</title>

    <!-- Google Fonts: Sora (Display) & Inter (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- SWEETALERT2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- GLOBAL CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>

<?php
// Tampilkan Flash Message jika ada
$flash = get_flash();
if ($flash): ?>
    <script>
        Swal.fire({
            icon: <?= json_encode($flash['type'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>,
            title: <?= json_encode($flash['title'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>,
            text: <?= json_encode($flash['message'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>,
            confirmButtonColor: '#6366f1'
        });
    </script>
<?php endif; ?>

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top" style="padding: 0.5rem 0;">
    <div class="container">

        <!-- LOGO -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL ?>index.php">
            <img src="<?= BASE_URL ?>assets/img/LOGO-UKS.jpg" alt="Logo UKS PNP" style="width: 40px; height: 40px; object-fit: cover; border-radius: 8px;">
            <span class="fw-bold text-primary tracking-tighter" style="font-size: 1.25rem;">UKS<span class="text-dark">PNP</span></span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">

            <!-- MENU -->
            <ul class="navbar-nav mx-auto align-items-center gap-2">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>index.php">
                        <i class="bi bi-house-door me-1"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>user/profil.php">
                        <i class="bi bi-shield-check me-1"></i> Profil UKM
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>fasilitas.php">
                        <i class="bi bi-grid-1x2 me-1"></i> Fasilitas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>user/peminjaman_saya.php">
                        <i class="bi bi-calendar-check me-1"></i> Peminjaman
                        <?php 
                        if (isset($_SESSION['user'])) {
                            $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM notifikasi WHERE user_id = ? AND is_read = 0");
                            $stmt_count->execute([$_SESSION['user']['id']]);
                            $count = $stmt_count->fetchColumn();
                            if ($count > 0) {
                                echo '<span class="badge bg-danger rounded-pill badge-pulse" style="font-size: 0.7rem; margin-left: 2px;">' . $count . '</span>';
                            }
                        }
                        ?>
                    </a>
                </li>
            </ul>

            <!-- RIGHT AREA - User Menu -->
            <div class="user-menu-area mt-3 mt-lg-0 pt-3 pt-lg-0 border-top border-lg-0">
                <?php if (isset($_SESSION['user'])): ?>
                    <!-- Desktop: Dropdown -->
                    <div class="dropdown d-none d-lg-block">
                        <a href="#" 
                           class="d-flex align-items-center text-decoration-none dropdown-toggle gap-2 text-dark fw-semibold px-3 py-2 rounded-pill bg-light" 
                           data-bs-toggle="dropdown"
                           role="button"
                           aria-expanded="false">
                            <i class="bi bi-person-circle fs-4 text-primary"></i>
                            <span><?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2" style="border-radius: 15px; min-width: 200px;">
                            <li><a class="dropdown-item py-2 px-3 rounded-3 mb-1" href="<?= BASE_URL ?>user/profil_edit.php">
                                <i class="bi bi-person-gear me-2"></i> Pengaturan Akun
                            </a></li>
                            <hr class="dropdown-divider opacity-50">
                            <li>
                                <form method="POST" action="<?= BASE_URL ?>auth/logout.php">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="dropdown-item py-2 px-3 rounded-3 text-danger" onclick="return confirm('Yakin ingin logout?')">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Mobile: Direct Grouping -->
                    <div class="d-lg-none">
                        <div class="p-3 bg-light rounded-4 mb-3 border border-light-subtle shadow-sm">
                            <div class="d-flex align-items-center gap-3 mb-3 border-bottom pb-3">
                                <div class="bg-primary text-white p-2 rounded-3">
                                    <i class="bi bi-person-fill fs-4"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($_SESSION['user']['name']) ?></div>
                                    <div class="small text-muted">Akun Mahasiswa</div>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="<?= BASE_URL ?>user/profil_edit.php" class="btn btn-white text-dark shadow-sm py-2 rounded-3 border">
                                    <i class="bi bi-person-gear me-2 text-primary"></i> Pengaturan Akun
                                </a>
                                <form method="POST" action="<?= BASE_URL ?>auth/logout.php">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="btn btn-danger-subtle text-danger py-2 rounded-3" onclick="return confirm('Yakin ingin logout?')">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>auth/login.php" class="btn btn-primary rounded-pill px-4 py-2 d-flex align-items-center justify-content-center gap-2 w-100 w-lg-auto" style="min-height: 48px;">
                        <i class="bi bi-box-arrow-in-right"></i> <span>Masuk</span>
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </div>
</nav>

<style>
/* Mobile styles */
@media (max-width: 991.98px) {
    .border-lg-0 {
        border-top: 1px solid rgba(0,0,0,0.08) !important;
    }
    .user-menu-area {
        padding-bottom: 1rem;
    }
    .btn-danger-subtle {
        background-color: rgba(239, 68, 68, 0.1);
        border: none;
    }
    .btn-white {
        background: #fff;
    }
}
@media (min-width: 992px) {
    .border-lg-0 {
        border-top: none !important;
    }
    .w-lg-auto {
        width: auto !important;
    }
}
</style>
