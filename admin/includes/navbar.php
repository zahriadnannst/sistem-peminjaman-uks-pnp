<?php
// admin/includes/navbar.php
// keamanan: hanya admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: " . BASE_URL . "auth/admin_login.php");
    exit;
}
?>
<!-- SWEETALERT2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- GLOBAL CSS (supplement to Bootstrap already loaded in page head) -->
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">

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

<nav class="navbar navbar-expand-lg sticky-top shadow-sm" style="background: rgba(255, 255, 255, 0.85) !important; backdrop-filter: blur(10px); border-bottom: 1px solid rgba(0,0,0,0.05);">
    <div class="container">

        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= BASE_URL ?>admin/index.php">
            <div class="bg-primary text-white p-2 rounded-3 d-flex align-items-center shadow-sm">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <span class="fw-bold text-dark tracking-tighter">ADMIN<span class="text-primary">UKS</span></span>
        </a>

        <!-- Toggle mobile - improved for touch -->
        <button class="navbar-toggler border-0 shadow-none p-2" type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#adminNavbar" 
                aria-controls="adminNavbar" 
                aria-expanded="false" 
                aria-label="Toggle navigation"
                style="min-width: 48px; min-height: 48px;">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav mx-lg-auto mb-2 mb-lg-0 gap-1">

                <li class="nav-item">
                    <a class="nav-link px-3 rounded-pill fw-medium" href="<?= BASE_URL ?>admin/index.php">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link px-3 rounded-pill fw-medium" href="<?= BASE_URL ?>admin/fasilitas/index.php">
                        <i class="bi bi-hospital me-1"></i> Fasilitas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link px-3 rounded-pill fw-medium" href="<?= BASE_URL ?>admin/peminjaman/index.php">
                        <i class="bi bi-clipboard-check me-1"></i> Peminjaman
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link px-3 rounded-pill fw-medium" href="<?= BASE_URL ?>admin/laporan/index.php">
                        <i class="bi bi-file-earmark-text me-1"></i> Laporan
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link px-3 rounded-pill fw-medium" href="<?= BASE_URL ?>admin/users/index.php">
                        <i class="bi bi-people me-1"></i> Users
                    </a>
                </li>
            </ul>

            <!-- ADMIN AREA - Mobile optimized -->
            <div class="admin-actions d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 gap-lg-3 mt-3 mt-lg-0 pt-3 pt-lg-0 border-top border-lg-0">
                <div class="d-flex align-items-center justify-content-center gap-2 bg-light px-3 py-2 rounded-pill">
                    <i class="bi bi-person-circle text-primary"></i>
                    <span class="text-dark fw-semibold small">
                        <?= htmlspecialchars($_SESSION['user']['username'] ?? 'Admin') ?>
                    </span>
                </div>

                <form method="POST" action="<?= BASE_URL ?>auth/logout.php">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generate_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit"
                            class="btn btn-danger rounded-pill px-3 py-2 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2"
                            style="min-height: 44px;"
                            onclick="return confirm('Yakin ingin logout?')">
                        <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Bootstrap JS Bundle (includes Popper) - Required for mobile navbar toggle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .nav-link { color: #64748b !important; transition: all 0.3s ease; }
    .nav-link:hover { background: rgba(99, 102, 241, 0.08); color: #6366f1 !important; transform: translateY(-1px); }
    .tracking-tighter { letter-spacing: -1px; }
    
    /* Mobile specific styles for admin navbar */
    @media (max-width: 991.98px) {
        .admin-actions {
            border-top: 1px solid rgba(0,0,0,0.05) !important;
        }
        .border-lg-0 {
            border: none !important;
        }
    }
    @media (min-width: 992px) {
        .admin-actions {
            border-top: none !important;
        }
    }
</style>
