<?php
require_once __DIR__ . '/../config/database.php'; // koneksi + session

$error = "";

// Ambil halaman tujuan jika user diarahkan dari peminjaman
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : BASE_URL . "fasilitas.php";

if (isset($_POST['login'])) {
    // Validasi token CSRF
    if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
        set_flash('error', 'Keamanan', 'Token tidak valid. Silakan coba lagi.');
        header("Location: login.php");
        exit;
    }

    $email = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

    // Ambil user berdasarkan email
    $stmt = $pdo->prepare("SELECT id, name, email, username, role, password, identitas_file FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Validasi password
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        unset($user['password']);
        $_SESSION['user'] = $user;

        // Default redirect
        $target = BASE_URL . "fasilitas.php";
        $requestedRedirect = $_POST['redirect'] ?? '';
        $parsed = is_string($requestedRedirect) ? parse_url($requestedRedirect) : false;
        if ($parsed !== false
            && !isset($parsed['scheme'])
            && empty($parsed['host'])
            && isset($parsed['path'])
            && strpos($parsed['path'], BASE_URL) === 0
            && !preg_match('/[\r\n]/', $requestedRedirect)) {
            $target = $requestedRedirect;
        }
        
        set_flash('success', 'Selamat Datang', 'Halo ' . $user['name'] . '!');
        header("Location: " . $target);
        exit;

    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UKS PNP</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fcfcfd;
            overflow: hidden;
        }
        .hero-title { font-family: 'Sora', sans-serif; }
        
        .auth-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 10;
        }

        .login-card-artistic {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 32px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .login-card-artistic::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 6px;
            background: linear-gradient(90deg, #6366f1, #4f46e5);
        }

        .logo-box {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        .form-control {
            border-radius: 14px;
            padding: 0.75rem 1.25rem;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            transition: all 0.2s;
        }

        .form-control:focus {
            background: white;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .btn-login-modern {
            padding: 0.875rem;
            border-radius: 14px;
            font-weight: 700;
            background: #6366f1;
            border: none;
            color: white;
            transition: all 0.3s;
        }

        .btn-login-modern:hover {
            background: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
        }

        /* Bg Shapes */
        .bg-art {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 1;
        }
        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
        }
        .shape-1 {
            width: 400px; height: 400px;
            background: #6366f1;
            top: -100px; right: -100px;
        }
        .shape-2 {
            width: 300px; height: 300px;
            background: #a855f7;
            bottom: -50px; left: -50px;
        }
        .grid-art {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(#64748b 0.5px, transparent 0.5px);
            background-size: 30px 30px;
            opacity: 0.05;
        }
    </style>
</head>
<body>

<div class="bg-art">
    <div class="grid-art"></div>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
</div>

<?php
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

<div class="auth-container">
    <div class="container d-flex justify-content-center">
        <div class="login-card-artistic" data-aos="zoom-in" data-aos-duration="800">
            
            <div class="logo-box">
                 <i class="bi bi-shield-lock-fill text-white fs-3"></i>
            </div>

            <h2 class="text-center fw-bold hero-title text-dark mb-1" style="letter-spacing: -1px;">Selamat Datang</h2>
            <p class="text-center text-secondary mb-5">Silakan masuk ke akun UKS PNP Anda</p>

            <?php if ($error): ?>
                <div class="alert alert-danger border-0 rounded-4 p-3 small text-center mb-4">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
                <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect) ?>">

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary ps-1">Alamat Email</label>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold text-secondary ps-1">Password</label>
                    <div class="position-relative">
                        <input type="password" name="password" id="password" class="form-control pe-5" placeholder="••••••••" required>
                        <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent text-secondary px-3" onclick="togglePassword('password', 'toggleIcon')">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <button class="btn btn-login-modern w-100 mb-4" name="login">
                    Masuk Sekarang
                </button>
                
                <div class="text-center small text-secondary mb-4">
                    Belum punya akun? <a href="register.php" class="text-primary fw-bold text-decoration-none border-bottom border-2 border-primary border-opacity-25 pb-1">Daftar Akun Baru</a>
                </div>

                <div class="d-flex align-items-center gap-3 my-4">
                    <hr class="flex-grow-1 opacity-10">
                    <span class="text-light small fw-bold">OR</span>
                    <hr class="flex-grow-1 opacity-10">
                </div>

                <div class="text-center">
                    <a href="<?= BASE_URL ?>index.php" class="text-secondary text-decoration-none small fw-medium hover-text-primary transition-all">
                        <i class="bi bi-arrow-left-circle me-1"></i> Kembali ke Beranda
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
function togglePassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    }
}
</script>

<!-- AOS -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();
</script>

</body>
</html>
