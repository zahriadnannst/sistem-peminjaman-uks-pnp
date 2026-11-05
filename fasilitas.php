<?php 
include 'includes/header.php';
?>

<style>
    .facility-card {
        border-radius: 24px;
        padding: 32px;
        background: #fff;
        border: 2px solid transparent;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    .facility-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary);
    }
    .status-badge {
        position: absolute;
        top: 24px;
        right: 24px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .text-indigo { color: var(--primary); }
    .bg-indigo-soft { background: rgba(99, 102, 241, 0.08); }
</style>

<div class="container py-5">

    <div class="row mb-5 fade-in align-items-center">
        <div class="col-lg-6" data-aos="fade-up">
            <h1 class="hero-title mb-2" style="font-size: clamp(2rem, 5vw, 3.5rem);">Eksplor <span class="highlight-text">Fasilitas</span></h1>
            <p class="hero-description fs-5 mb-0">Temukan perlengkapan seni terbaik untuk mahakaryamu.</p>
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <!-- FILTER BOX -->
            <div class="card border-0 shadow-sm p-3 rounded-4 bg-white">
                <form method="GET" class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 ps-3 rounded-start-3" style="color: var(--primary);">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="q" class="form-control border-0 bg-light py-2 small fw-medium" placeholder="Cari..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="kategori" class="form-select border-0 bg-light rounded-3 py-2 small fw-medium">
                            <option value="">Semua Kategori</option>
                            <?php
                            $daftarKategori = [
                                "Musik", "Umum", "Tari", "Pertunjukan", "Seni rupa", "Phocinemart"
                            ];
                            foreach ($daftarKategori as $k) {
                                $selected = (isset($_GET['kategori']) && $_GET['kategori'] == $k) ? 'selected' : '';
                                echo "<option value=\"$k\" $selected>$k</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="filter" class="form-select border-0 bg-light rounded-3 py-2 small fw-medium">
                            <option value="">Status</option>
                            <option value="tersedia" <?= (isset($_GET['filter']) && $_GET['filter']=='tersedia')?'selected':''; ?>>Tersedia</option>
                            <option value="dipinjam" <?= (isset($_GET['filter']) && $_GET['filter']=='dipinjam')?'selected':''; ?>>Dipinjam</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100 rounded-3 py-2 fw-bold shadow-sm">
                            <i class="bi bi-filter me-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    // Query tetap sama
    $sql = "
        SELECT 
            f.*,
            (SELECT COALESCE(SUM(jumlah_pinjam), 0) 
             FROM peminjaman 
             WHERE fasilitas_id = f.id AND status = 'dipinjam') AS terpakai
        FROM fasilitas f
        WHERE 1
    ";
    $params = [];
    if (!empty($_GET['filter'])) { $sql .= " AND f.status = :status"; $params['status'] = $_GET['filter']; }
    if (!empty($_GET['kategori'])) { $sql .= " AND f.kategori = :kategori"; $params['kategori'] = $_GET['kategori']; }
    if (!empty($_GET['q'])) { $sql .= " AND f.nama LIKE :q"; $params['q'] = "%" . $_GET['q'] . "%"; }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $fasilitasData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Grouping by Category
    $groupedFasilitas = [];
    foreach ($fasilitasData as $f) {
        $cat = $f['kategori'] ?: 'Umum';
        $groupedFasilitas[$cat][] = $f;
    }
    ?>

    <?php if (empty($fasilitasData)): ?>
        <div class="row">
            <div class="col-12 text-center py-5">
                <div class="opacity-25 mb-3"><i class="bi bi-search display-1"></i></div>
                <h4 class="text-secondary fw-bold">Tidak ada fasilitas ditemukan</h4>
                <p class="text-muted">Coba ubah filter pencarianmu.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($groupedFasilitas as $kategori => $items): ?>
            <!-- Category Header -->
            <div class="row mt-5 mb-4" data-aos="fade-up">
                <div class="col-12">
                    <div class="d-flex align-items-center gap-3">
                        <h2 class="fw-bold text-dark mb-0" style="letter-spacing: -1px;"><?= htmlspecialchars($kategori) ?></h2>
                        <div class="flex-grow-1 border-top" style="opacity: 0.1;"></div>
                        <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill small"><?= count($items) ?> Item</span>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <?php foreach ($items as $f): ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up">
                    <div class="facility-card h-100 d-flex flex-column">
                        
                        <?php 
                        $stok_tersisa = $f['jumlah'] - $f['terpakai'];
                        if ($stok_tersisa > 0): 
                        ?>
                            <span class="badge bg-success-subtle text-success status-badge px-3 py-2 rounded-pill border border-success-subtle">Tersedia</span>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger status-badge px-3 py-2 rounded-pill border border-danger-subtle">Stok Habis</span>
                        <?php endif; ?>

                        <div class="mb-3 d-inline-block p-2 rounded-3 bg-indigo-soft text-primary align-self-start">
                            <i class="bi bi-box-seam fs-4"></i>
                        </div>

                        <h4 class="fw-bold text-dark mb-2"><?= htmlspecialchars($f['nama']) ?></h4>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-4 border-top pt-4">
                                <div>
                                    <div class="text-muted tiny text-uppercase fw-bold tracking-widest mb-1" style="font-size: 0.65rem;">Stok & Kondisi</div>
                                    <div class="small fw-semibold text-dark">
                                        <i class="bi bi-layers me-1 text-primary"></i> <?= $stok_tersisa ?> / <?= $f['jumlah'] ?> Unit
                                        <span class="mx-2 text-muted fw-light">|</span>
                                        <i class="bi bi-shield-check me-1 text-success"></i> <?= htmlspecialchars($f['kondisi'], ENT_QUOTES, 'UTF-8') ?>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="text-muted tiny text-uppercase fw-bold tracking-widest mb-1" style="font-size: 0.65rem;">Harga Pinjam</div>
                                    <div class="fw-bold text-primary fs-5">Rp <?= number_format($f['harga'], 0, ',', '.') ?></div>
                                </div>
                            </div>

                             <!-- ACTIONS -->
                            <?php if ($stok_tersisa > 0): ?>
                                <a href="peminjaman/index.php?id=<?= $f['id'] ?>" class="btn btn-primary-custom w-100 py-3 justify-content-center">
                                    Pinjam Sekarang <i class="bi bi-arrow-right ms-2 small"></i>
                                </a>
                            <?php else: ?>
                                <button class="btn btn-outline-secondary w-100 py-3 rounded-4 fw-bold border-2" disabled>
                                    <i class="bi bi-lock me-2"></i> Stok Sedang Kosong
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<style>
    .fw-extrabold { font-weight: 800; }
    .tracking-wider { letter-spacing: 1px; }
    .shadow-primary { box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3); }
</style>

<?php include 'includes/footer.php'; ?>
