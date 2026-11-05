<?php 
include 'includes/header.php';
?>

<!-- ================= MODERN ARTISTIC HERO SECTION ================= -->
<section class="hero-section position-relative overflow-hidden">
    
    <!-- Animated Background Elements -->
    <div class="bg-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>

    <!-- Main Grid Pattern Overlay -->
    <div class="grid-overlay"></div>

    <div class="container position-relative" style="z-index: 10;">
        <div class="row align-items-center min-vh-100 py-5">

            <!-- HERO TEXT CONTENT -->
            <div class="col-lg-6 col-xl-5" data-aos="fade-up">
                
                <!-- Badge -->
                <div class="badge-wrapper mb-4">
                    <div class="hero-badge">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                            <path d="M2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                        <span>Unit Kegiatan Seni PNP</span>
                    </div>
                </div>
                
                <!-- Main Heading -->
                <h1 class="hero-title mb-4">
                    Wujudkan
                    <span class="highlight-text">Karya Seni</span>
                    Terbaikmu
                </h1>

                <!-- Description -->
                <p class="hero-description mb-5">
                    Platform peminjaman peralatan seni terlengkap untuk mahasiswa PNP. 
                    Dari studio musik hingga peralatan panggung, semua ada untuk mendukung kreativitasmu.
                </p>

                <!-- CTA Buttons -->
                <div class="button-group mb-5">
                    <a href="fasilitas.php" class="btn btn-primary-custom">
                        <span>Jelajahi Fasilitas</span>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M7 17L17 7M17 7H7M17 7v10"/>
                        </svg>
                    </a>

                    <?php if (!isset($_SESSION['user'])): ?>
                        <a href="auth/login.php" class="btn btn-secondary-custom">
                            <span>Daftar Sekarang</span>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Stats -->
                <div class="stats-container">
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Peralatan Seni</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Akses Studio</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <div class="stat-number">100+</div>
                        <div class="stat-label">Anggota Aktif</div>
                    </div>
                </div>

            </div>

            <!-- HERO IMAGE -->
            <div class="col-lg-6 col-xl-6 offset-xl-1" data-aos="fade-left" data-aos-delay="200">
                <div class="hero-image-wrapper">
                    <!-- Premium Glass Background Elements -->
                    <div class="glass-element ge-1"></div>
                    <div class="glass-element ge-2"></div>
                    
                    <!-- Decorative Ornaments -->
                    <div class="ornament orn-1">
                        <i class="bi bi-camera shadow-sm"></i>
                    </div>
                    <div class="ornament orn-2">
                        <i class="bi bi-music-note-beamed shadow-sm"></i>
                    </div>
                    <div class="ornament orn-3">
                        <i class="bi bi-palette shadow-sm"></i>
                    </div>

                    <div class="image-container">
                        <div class="image-frame">
                            <img src="<?= BASE_URL ?>assets/img/beranda.jpg" 
                                 alt="Unit Kegiatan Seni PNP" 
                                 class="hero-image">
                            <!-- Overlay Gradient -->
                            <div class="image-overlay"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="scroll-indicator">
        <div class="scroll-line"></div>
        <span>Scroll</span>
    </div>
</section>

<!-- ================= FEATURES SECTION ================= -->
<section class="features-section py-5">
    <div class="container py-5">
        
        <div class="row mb-5" data-aos="fade-up">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title mb-3">Mengapa Memilih UKS PNP?</h2>
                <p class="section-subtitle">Fasilitas lengkap dan sistem peminjaman yang mudah untuk mendukung setiap kreativitasmu</p>
            </div>
        </div>

        <div class="row g-4">
            
            <!-- Feature 1 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="3" width="20" height="14" rx="2"/>
                            <line x1="8" y1="21" x2="16" y2="21"/>
                            <line x1="12" y1="17" x2="12" y2="21"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Peralatan Lengkap</h3>
                    <p class="feature-description">
                        Dari kamera profesional hingga lighting studio, semua tersedia untuk kebutuhan produksimu
                    </p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                            <path d="M2 17l10 5 10-5"/>
                            <path d="M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Sistem Mudah</h3>
                    <p class="feature-description">
                        Proses peminjaman online yang cepat dan transparan, kapan saja dan dimana saja
                    </p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Komunitas Aktif</h3>
                    <p class="feature-description">
                        Bergabung dengan komunitas seniman dan kolaborasi dalam berbagai projek seni
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
/* ================= CUSTOM FONTS ================= */
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap');

/* ================= CSS VARIABLES ================= */
:root {
    --primary-color: #6366F1;
    --primary-dark: #4F46E5;
    --secondary-color: #8B5CF6;
    --accent-color: #EC4899;
    --success-color: #10B981;
    --text-dark: #0F172A;
    --text-light: #64748B;
    --bg-light: #F8FAFC;
    --bg-gradient: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
    --border-color: #E2E8F0;
    --shadow-sm: 0 2px 8px rgba(99, 102, 241, 0.08);
    --shadow-md: 0 4px 20px rgba(99, 102, 241, 0.12);
    --shadow-lg: 0 10px 40px rgba(99, 102, 241, 0.15);
    --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ================= HERO SECTION ================= */
.hero-section {
    background: linear-gradient(135deg, #FFFFFF 0%, #F8FAFB 100%);
    position: relative;
    padding: 0;
}

/* Background Shapes */
.bg-shapes {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 1;
}

.shape {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    opacity: 0.3;
    animation: float 20s infinite ease-in-out;
}

.shape-1 {
    width: 500px;
    height: 500px;
    background: linear-gradient(135deg, #6366F1, #8B5CF6);
    top: -150px;
    right: -100px;
    animation-delay: 0s;
}

.shape-2 {
    width: 400px;
    height: 400px;
    background: linear-gradient(135deg, #EC4899, #8B5CF6);
    bottom: -100px;
    left: -100px;
    animation-delay: 5s;
}

.shape-3 {
    width: 300px;
    height: 300px;
    background: linear-gradient(135deg, #6366F1, #EC4899);
    top: 40%;
    left: 30%;
    animation-delay: 10s;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    33% { transform: translate(50px, -50px) rotate(120deg); }
    66% { transform: translate(-30px, 30px) rotate(240deg); }
}

/* Grid Overlay */
.grid-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: 
        linear-gradient(rgba(99, 102, 241, 0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(99, 102, 241, 0.03) 1px, transparent 1px);
    background-size: 50px 50px;
    z-index: 2;
}

/* Badge */
.badge-wrapper {
    animation: slideDown 0.8s ease-out;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: white;
    border: 2px solid var(--border-color);
    border-radius: 50px;
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    font-size: 14px;
    color: var(--text-dark);
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.hero-badge:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary-color);
}

.hero-badge svg {
    color: var(--primary-color);
}

/* Hero Title */
.hero-title {
    font-family: 'Sora', sans-serif;
    font-size: clamp(2.5rem, 6vw, 4.5rem);
    font-weight: 800;
    line-height: 1.1;
    color: var(--text-dark);
    margin-bottom: 2rem;
    animation: slideUp 0.8s ease-out 0.2s both;
}

.highlight-text {
    position: relative;
    color: var(--primary-color);
    display: inline-block;
}

.highlight-text::after {
    content: '';
    position: absolute;
    bottom: 5px;
    left: 0;
    width: 100%;
    height: 15px;
    background: var(--accent-color);
    opacity: 0.25;
    z-index: -1;
    transform: skew(-12deg);
}

/* Hero Description */
.hero-description {
    font-family: 'Inter', sans-serif;
    font-size: 1.125rem;
    line-height: 1.8;
    color: var(--text-light);
    max-width: 540px;
    animation: slideUp 0.8s ease-out 0.4s both;
}

/* Button Group */
.button-group {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    animation: slideUp 0.8s ease-out 0.6s both;
}

.btn-primary-custom,
.btn-secondary-custom {
    font-family: 'Sora', sans-serif;
    font-weight: 600;
    font-size: 16px;
    padding: 18px 32px;
    border-radius: 16px;
    border: none;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: var(--transition);
    cursor: pointer;
}

.btn-primary-custom {
    background: var(--primary-color);
    color: white;
    box-shadow: 0 8px 24px rgba(99, 102, 241, 0.35);
}

.btn-primary-custom:hover {
    background: var(--primary-dark);
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(99, 102, 241, 0.45);
    color: white;
}

.btn-secondary-custom {
    background: white;
    color: var(--text-dark);
    border: 2px solid var(--border-color);
}

.btn-secondary-custom:hover {
    background: var(--text-dark);
    color: white;
    border-color: var(--text-dark);
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

/* Stats Container */
.stats-container {
    display: flex;
    align-items: center;
    gap: 24px;
    padding: 24px 0;
    animation: slideUp 0.8s ease-out 0.8s both;
}

.stat-item {
    display: flex;
    flex-direction: column;
}

.stat-number {
    font-family: 'Sora', sans-serif;
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-dark);
    line-height: 1;
    margin-bottom: 8px;
}

.stat-label {
    font-family: 'Inter', sans-serif;
    font-size: 0.875rem;
    color: var(--text-light);
    font-weight: 500;
}

.stat-divider {
    width: 1px;
    height: 40px;
    background: var(--border-color);
}

/* Hero Image Wrapper */
.hero-image-wrapper {
    position: relative;
    padding: 20px;
    animation: fadeIn 1s ease-out 0.6s both;
}

.image-container {
    position: relative;
    width: 100%;
    max-width: 550px;
    margin: 0 auto;
    z-index: 5;
}

.image-frame {
    position: relative;
    border-radius: 40px;
    overflow: hidden;
    padding: 12px;
    background: rgba(255, 255, 255, 0.4);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.6);
    box-shadow: 
        0 20px 50px rgba(0, 0, 0, 0.1),
        inset 0 0 0 1px rgba(255, 255, 255, 0.5);
    transition: var(--transition);
}

.hero-image {
    width: 100%;
    height: auto;
    border-radius: 30px;
    display: block;
    transition: var(--transition);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, transparent 60%, rgba(99, 102, 241, 0.1));
    pointer-events: none;
}

/* Glass Elements */
.glass-element {
    position: absolute;
    border-radius: 24px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.4);
    box-shadow: var(--shadow-md);
    z-index: 1;
}

.ge-1 {
    width: 160px;
    height: 160px;
    top: -20px;
    right: -20px;
    animation: float 8s infinite ease-in-out;
}

.ge-2 {
    width: 120px;
    height: 120px;
    bottom: -30px;
    left: -10px;
    animation: float 10s infinite ease-in-out reverse;
}

/* Ornaments */
.ornament {
    position: absolute;
    width: 54px;
    height: 54px;
    background: white;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: var(--primary-color);
    box-shadow: var(--shadow-lg);
    z-index: 10;
    animation: float-small 6s infinite ease-in-out;
}

.orn-1 { top: 10%; left: -15px; animation-delay: 0s; }
.orn-2 { bottom: 20%; right: -25px; animation-delay: 1.5s; color: var(--accent-color); }
.orn-3 { top: 40%; right: -15px; animation-delay: 3s; color: var(--secondary-color); }

@keyframes float-small {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(0, -10px) scale(1.05); }
}

.hero-image-wrapper:hover .image-frame {
    transform: translateY(-10px) rotate(1deg);
    background: rgba(255, 255, 255, 0.6);
}

.hero-image-wrapper:hover .hero-image {
    transform: scale(1.05);
}

/* Scroll Indicator */
.scroll-indicator {
    position: absolute;
    bottom: 40px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    z-index: 10;
    animation: bounce 2s infinite;
}

.scroll-line {
    width: 2px;
    height: 40px;
    background: linear-gradient(to bottom, transparent, var(--primary-color));
    border-radius: 2px;
}

.scroll-indicator span {
    font-family: 'Sora', sans-serif;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-light);
    text-transform: uppercase;
    letter-spacing: 1px;
}

@keyframes bounce {
    0%, 100% { transform: translateX(-50%) translateY(0); }
    50% { transform: translateX(-50%) translateY(10px); }
}

/* ================= FEATURES SECTION ================= */
.features-section {
    background: white;
    position: relative;
}

.section-title {
    font-family: 'Sora', sans-serif;
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 800;
    color: var(--text-dark);
    line-height: 1.2;
}

.section-subtitle {
    font-family: 'Inter', sans-serif;
    font-size: 1.125rem;
    color: var(--text-light);
    line-height: 1.7;
}

.feature-card {
    background: var(--bg-light);
    border-radius: 24px;
    padding: 40px 32px;
    height: 100%;
    border: 2px solid transparent;
    transition: var(--transition);
}

.feature-card:hover {
    background: white;
    border-color: var(--primary-color);
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
}

.feature-icon {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, #6366F1, #8B5CF6);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 24px;
    transition: var(--transition);
}

.feature-card:hover .feature-icon {
    transform: scale(1.1) rotate(5deg);
}

.feature-icon svg {
    color: white;
}

.feature-title {
    font-family: 'Sora', sans-serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 12px;
}

.feature-description {
    font-family: 'Inter', sans-serif;
    font-size: 1rem;
    line-height: 1.7;
    color: var(--text-light);
    margin: 0;
}

/* ================= ANIMATIONS ================= */
@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* ================= RESPONSIVE ================= */
@media (max-width: 991px) {
    .hero-title {
        font-size: 3rem;
    }
    
    .stats-container {
        justify-content: space-between;
    }
}

@media (max-width: 767px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .button-group {
        flex-direction: column;
    }
    
    .btn-primary-custom,
    .btn-secondary-custom {
        width: 100%;
        justify-content: center;
    }
    
    .stats-container {
        flex-wrap: wrap;
    }
    
    .stat-divider {
        display: none;
    }
    
    .scroll-indicator {
        display: none;
    }
}
</style>

<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });
</script>

<?php include 'includes/footer.php'; ?>