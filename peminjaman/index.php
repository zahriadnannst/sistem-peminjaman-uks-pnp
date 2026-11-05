<?php
require_once __DIR__ . '/../config/database.php';

/* =========================
   CEK LOGIN USER
========================= */
if (!isset($_SESSION['user'])) {
    $redirect = urlencode($_SERVER['REQUEST_URI']);
    header("Location: ../auth/login.php?redirect=$redirect");
    exit;
}

/* =========================
   VALIDASI ID FASILITAS
========================= */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Fasilitas tidak ditemukan");
}

$id = (int) $_GET['id'];

/* =========================
   AMBIL DATA FASILITAS
========================= */
$stmt = $pdo->prepare("SELECT * FROM fasilitas WHERE id = ?");
$stmt->execute([$id]);
$fasilitas = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fasilitas) {
    die("Data fasilitas tidak ditemukan!");
}

/* =========================
   KODE PEMBAYARAN (REFERENSI)
========================= */
$kodePembayaran = "PAY-" . $id . "-" . time();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="col-md-6 mx-auto">
        <div class="card shadow">
            <div class="card-body">

                <h3 class="text-center fw-bold">Form Peminjaman</h3>
                <hr>

                <!-- INFORMASI FASILITAS -->
                <h5>Informasi Fasilitas</h5>
                <p><b>Nama:</b> <?= htmlspecialchars($fasilitas['nama']) ?></p>
                <p>
                    <b>Harga Sewa:</b>
                    Rp <?= number_format($fasilitas['harga'], 0, ',', '.') ?>
                </p>

                <hr>

                <!-- FORM PEMINJAMAN -->
                <form action="process.php" method="POST" enctype="multipart/form-data">

                    <input type="hidden" name="fasilitas_id" value="<?= $fasilitas['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Pinjam</label>
                            <input type="date" name="tgl_pinjam" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Kembali</label>
                            <input type="date" name="tgl_kembali" class="form-control" required>
                        </div>
                    </div>

                    <?php 
                    // Hitung stok terpakai (hanya yang statusnya 'dipinjam')
                    $stmt_stok = $pdo->prepare("SELECT SUM(jumlah_pinjam) FROM peminjaman WHERE fasilitas_id = ? AND status = 'dipinjam'");
                    $stmt_stok->execute([$id]);
                    $stok_terpakai = $stmt_stok->fetchColumn() ?: 0;
                    $stok_tersisa = $fasilitas['jumlah'] - $stok_terpakai;
                    ?>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Unit yang Dipinjam</label>
                        <div class="input-group">
                            <input type="number" name="jumlah_pinjam" class="form-control" value="1" min="1" max="<?= $stok_tersisa ?>" required>
                            <span class="input-group-text bg-light text-muted small">
                                Tersedia: <?= $stok_tersisa ?> / <?= $fasilitas['jumlah'] ?> Unit
                            </span>
                        </div>
                        <small class="text-muted">Maksimal peminjaman sesuai stok tersisa.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keperluan</label>
                        <textarea name="keperluan" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Upload Kartu Identitas (KTP / Kartu Pelajar)
                        </label>
                        <input type="file"
                               name="identitas"
                               class="form-control"
                               accept=".jpg,.jpeg,.png"
                               required>
                        <small class="text-muted">
                            Format diperbolehkan: JPG, JPEG, PNG
                        </small>
                    </div>

                    <hr>

                    <!-- PEMBAYARAN -->
                    <h5 class="text-center">Pembayaran</h5>
                    <p class="text-center">
                        Silakan scan QR DANA berikut untuk melakukan pembayaran
                    </p>

                    <div class="text-center mb-3">
                        <img src="<?= BASE_URL ?>assets/img/QR.jpg"
                             alt="QR Pembayaran DANA"
                             class="img-fluid"
                             style="max-width:260px;">
                    </div>

                   

                    <div class="mb-4">
                        <label class="form-label fw-bold">Upload Bukti Pembayaran</label>
                        <input type="file"
                               name="bukti_pembayaran"
                               class="form-control"
                               accept=".jpg,.jpeg,.png"
                               required>
                        <small class="text-muted">
                            Upload screenshot transfer atau struk pembayaran (JPG, PNG)
                        </small>
                    </div>

                    <p class="text-center text-muted">
                        Setelah upload bukti pembayaran, klik <b>Kirim Peminjaman</b>.<br>
                        Pembayaran akan diverifikasi oleh admin.
                    </p>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-3 fw-bold">
                            Kirim Peminjaman
                        </button>
                        <a href="<?= BASE_URL ?>fasilitas.php" class="btn btn-light py-3 fw-semibold text-secondary">
                            Batal & Kembali
                        </a>
                    </div>

                </form>

                <script>
                const tglPinjam = document.querySelector('input[name="tgl_pinjam"]');
                const tglKembali = document.querySelector('input[name="tgl_kembali"]');
                const form = document.querySelector('form');

                form.onsubmit = function(e) {
                    // Check required fields
                    const keperluan = document.querySelector('textarea[name="keperluan"]');
                    const identitas = document.querySelector('input[name="identitas"]');
                    const buktiPembayaran = document.querySelector('input[name="bukti_pembayaran"]');

                    // Validate keperluan (textarea)
                    if (!keperluan.value.trim()) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Form Belum Lengkap',
                            text: 'Mohon isi keperluan peminjaman!',
                            confirmButtonColor: '#6366f1'
                        });
                        keperluan.focus();
                        return false;
                    }

                    // Validate tanggal pinjam
                    if (!tglPinjam.value) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Form Belum Lengkap',
                            text: 'Mohon pilih tanggal pinjam!',
                            confirmButtonColor: '#6366f1'
                        });
                        tglPinjam.focus();
                        return false;
                    }

                    // Validate tanggal kembali
                    if (!tglKembali.value) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Form Belum Lengkap',
                            text: 'Mohon pilih tanggal kembali!',
                            confirmButtonColor: '#6366f1'
                        });
                        tglKembali.focus();
                        return false;
                    }

                    // Validate date logic
                    const pinjam = new Date(tglPinjam.value);
                    const kembali = new Date(tglKembali.value);

                    if (kembali < pinjam) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Tanggal Tidak Valid',
                            text: 'Tanggal kembali tidak boleh sebelum tanggal pinjam!',
                            confirmButtonColor: '#6366f1'
                        });
                        tglKembali.focus();
                        return false;
                    }

                    // Check if dates are the same
                    if (kembali.getTime() === pinjam.getTime()) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tanggal Tidak Valid',
                            text: 'Tanggal kembali harus berbeda dari tanggal pinjam!',
                            confirmButtonColor: '#6366f1'
                        });
                        tglKembali.focus();
                        return false;
                    }

                    // Validate identitas file
                    if (!identitas.files || identitas.files.length === 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Form Belum Lengkap',
                            text: 'Mohon upload kartu identitas (KTP/Kartu Pelajar)!',
                            confirmButtonColor: '#6366f1'
                        });
                        identitas.focus();
                        return false;
                    }

                    // Validate bukti pembayaran file
                    if (!buktiPembayaran.files || buktiPembayaran.files.length === 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Form Belum Lengkap',
                            text: 'Mohon upload bukti pembayaran!',
                            confirmButtonColor: '#6366f1'
                        });
                        buktiPembayaran.focus();
                        return false;
                    }

                    // Validate file types
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                    
                    if (identitas.files[0] && !allowedTypes.includes(identitas.files[0].type)) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'File Tidak Valid',
                            text: 'Format file identitas harus JPG, JPEG, atau PNG!',
                            confirmButtonColor: '#6366f1'
                        });
                        identitas.focus();
                        return false;
                    }

                    if (buktiPembayaran.files[0] && !allowedTypes.includes(buktiPembayaran.files[0].type)) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'File Tidak Valid',
                            text: 'Format file bukti pembayaran harus JPG, JPEG, atau PNG!',
                            confirmButtonColor: '#6366f1'
                        });
                        buktiPembayaran.focus();
                        return false;
                    }

                    // All validations passed
                    return true;
                };
                </script>

            </div>
        </div>
    </div>

</div>

</body>
</html>
