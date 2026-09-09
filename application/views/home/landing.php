<?php $this->load->view('layout/header', ['title' => 'SIP SKHP TERA - Layanan Resmi UPT Metrologi Singkawang']); ?>
<?php $this->load->view('layout/navbar'); ?>

<style>
    /* Styling Dasar */
    html {
        scroll-behavior: smooth;
    }

    /* Preloader */
    #preloader {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: var(--color-bg);
        z-index: 9999;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        transition: opacity 0.6s ease, visibility 0.6s ease;
    }
    #preloader.hidden {
        opacity: 0; visibility: hidden;
    }
    .spinner-border {
        width: 3.5rem; height: 3.5rem;
        border-width: 0.3em;
        color: var(--color-primary);
        margin-bottom: 20px;
    }
    .status-text {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--color-primary);
    }

    /* ============================================
       HERO SECTION - Full-width, Background-based
    ============================================ */
    .hero-section {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        overflow: hidden;
        background-color: #031c10;
    }
    /* Parallax background slideshow */
    .hero-bg-slides {
        position: absolute;
        inset: 0;
        z-index: 0;
    }
    .hero-bg-slide {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        opacity: 0;
        transition: opacity 1.2s ease;
        transform: scale(1.05);
        animation: heroKenBurns 8s ease-in-out infinite alternate;
    }
    .hero-bg-slide.active {
        opacity: 1;
    }
    @keyframes heroKenBurns {
        from { transform: scale(1.05); }
        to   { transform: scale(1.12); }
    }
    /* Gradient overlay yang kuat */
    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            135deg,
            rgba(3, 28, 16, 0.88) 0%,
            rgba(6, 95, 70, 0.55) 60%,
            rgba(0, 0, 0, 0.4) 100%
        );
        z-index: 1;
    }
    /* Decorative blobs */
    .hero-blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.15;
        z-index: 1;
        animation: blobFloat 8s ease-in-out infinite alternate;
    }
    .hero-blob-1 {
        width: 500px; height: 500px;
        background: #16a085;
        top: -100px; right: -100px;
        animation-delay: 0s;
    }
    .hero-blob-2 {
        width: 350px; height: 350px;
        background: #27ae60;
        bottom: -50px; left: -80px;
        animation-delay: 3s;
    }
    @keyframes blobFloat {
        from { transform: translate(0, 0) scale(1); }
        to   { transform: translate(30px, 20px) scale(1.1); }
    }
    /* Hero content */
    .hero-content {
        position: relative;
        z-index: 2;
        padding: 120px 0 80px;
    }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.25);
        backdrop-filter: blur(10px);
        color: #fff;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 28px;
        animation: fadeInDown 0.8s ease both;
    }
    .hero-badge i {
        color: #4ade80;
        font-size: 0.8rem;
    }
    .hero-title {
        font-size: 3.6rem;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.15;
        margin-bottom: 24px;
        animation: fadeInDown 0.9s ease 0.1s both;
    }
    .hero-title .accent {
        background: linear-gradient(90deg, #4ade80, #34d399);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .hero-text {
        font-size: 1.15rem;
        color: rgba(255,255,255,0.78);
        margin-bottom: 40px;
        line-height: 1.75;
        max-width: 560px;
        animation: fadeInDown 1s ease 0.2s both;
    }
    .hero-actions {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        animation: fadeInDown 1.1s ease 0.3s both;
    }
    .btn-hero-primary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 16px 36px;
        font-size: 1.05rem;
        font-weight: 700;
        border-radius: 10px;
        background: linear-gradient(135deg, #16a085, #27ae60);
        color: #fff;
        border: none;
        box-shadow: 0 8px 30px rgba(22, 160, 133, 0.45);
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .btn-hero-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 40px rgba(22, 160, 133, 0.6);
        color: #fff;
    }
    .btn-hero-outline {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 15px 32px;
        font-size: 1.05rem;
        font-weight: 600;
        border-radius: 10px;
        background: rgba(255,255,255,0.1);
        color: #fff;
        border: 1.5px solid rgba(255,255,255,0.35);
        backdrop-filter: blur(8px);
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .btn-hero-outline:hover {
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.6);
        color: #fff;
        transform: translateY(-3px);
    }
    /* Scroll indicator */
    .hero-scroll-hint {
        position: absolute;
        bottom: 36px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        color: rgba(255,255,255,0.5);
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        animation: fadeInDown 1.5s ease 0.8s both;
    }
    .hero-scroll-hint .scroll-mouse {
        width: 26px;
        height: 40px;
        border: 2px solid rgba(255,255,255,0.4);
        border-radius: 13px;
        display: flex;
        justify-content: center;
        padding-top: 6px;
    }
    .hero-scroll-hint .scroll-dot {
        width: 4px;
        height: 8px;
        background: rgba(255,255,255,0.6);
        border-radius: 2px;
        animation: scrollDot 1.5s ease-in-out infinite;
    }
    @keyframes scrollDot {
        0%   { transform: translateY(0); opacity: 1; }
        100% { transform: translateY(10px); opacity: 0; }
    }
    /* Slide indicators */
    .hero-slide-dots {
        position: absolute;
        bottom: 36px;
        right: 40px;
        z-index: 2;
        display: flex;
        gap: 8px;
    }
    .hero-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: rgba(255,255,255,0.35);
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .hero-dot.active {
        width: 28px;
        border-radius: 4px;
        background: #4ade80;
    }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Section Heading Styles */
    .section-title {
        color: var(--color-primary);
        font-weight: 700;
        font-size: 2.25rem;
        margin-bottom: 1rem;
    }
    .section-subtitle {
        font-size: 1.15rem;
        color: var(--text-muted);
        margin-bottom: 3rem;
    }
    .btn-main {
        padding: 15px 35px;
        font-size: 1.25rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        background-color: var(--color-primary);
        color: white;
        border: none;
        box-shadow: 0 4px 10px rgba(6, 95, 70, 0.3);
    }
    .btn-main:hover {
        transform: translateY(-3px);
        background-color: var(--color-secondary);
        color: white;
        box-shadow: 0 6px 15px rgba(6, 95, 70, 0.4);
    }

    /* Kartu Informasi & Panduan */
    .info-card {
        background: var(--color-card);
        border-radius: 10px;
        padding: 30px 25px;
        height: 100%;
        border: 1px solid var(--color-border);
        box-shadow: 0 4px 6px rgba(0,0,0,0.04);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        border-color: var(--color-primary);
    }
    .info-icon {
        font-size: 2.5rem;
        margin-bottom: 20px;
        color: var(--color-primary);
    }
    .info-card-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 15px;
    }
    .info-card-text {
        color: var(--text-muted);
        font-size: 1.05rem;
        line-height: 1.6;
    }

    /* Langkah-Langkah */
    .step-box {
        text-align: center;
        padding: 20px;
        color: var(--text-main);
    }
    .step-number {
        width: 70px;
        height: 70px;
        background-color: var(--color-primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: bold;
        margin: 0 auto 20px auto;
        box-shadow: 0 5px 15px rgba(6, 95, 70, 0.3);
    }
    .step-title {
        font-weight: 700;
        font-size: 1.3rem;
        margin-bottom: 10px;
        color: var(--color-primary);
    }
    .step-desc {
        color: var(--text-muted);
        font-size: 1.05rem;
    }

    /* Kontak Section */
    .contact-wrapper {
        background-color: var(--color-card);
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        overflow: hidden;
    }
    .contact-info-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 25px;
    }
    .contact-icon-box {
        background-color: var(--color-bg-alt);
        color: var(--color-primary);
        width: 55px;
        height: 55px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
        margin-right: 20px;
        border: 1px solid var(--color-border);
    }
    .contact-details h5 {
        font-weight: 700;
        font-size: 1.25rem;
        margin-bottom: 5px;
        color: var(--color-primary);
    }
    .contact-details p {
        color: var(--text-muted);
        font-size: 1.1rem;
        margin-bottom: 0;
    }

    /* ============================================
       GALERI SECTION - Horizontal Infinite Slider
    ============================================ */
    .gallery-section {
        padding: 80px 0;
        background: var(--color-bg);
        overflow: hidden;
    }
    /* Track wrapper: clip overflow */
    .gallery-slider-track-outer {
        overflow: hidden;
        position: relative;
        /* Edge fade masks */
        -webkit-mask-image: linear-gradient(to right, transparent 0%, black 6%, black 94%, transparent 100%);
        mask-image:         linear-gradient(to right, transparent 0%, black 6%, black 94%, transparent 100%);
    }
    /* The scrolling strip */
    .gallery-slider-track {
        display: flex;
        gap: 20px;
        width: max-content;
        animation: gallerySlide 32s linear infinite;
        will-change: transform;
    }
    .gallery-slider-track:hover {
        animation-play-state: paused;
    }
    @keyframes gallerySlide {
        0%   { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    /* Each card */
    .gallery-slide-item {
        position: relative;
        width: 340px;
        height: 240px;
        border-radius: 14px;
        overflow: hidden;
        flex-shrink: 0;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }
    .gallery-slide-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
        display: block;
    }
    .gallery-slide-item:hover img {
        transform: scale(1.08);
    }
    .gallery-slide-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(3,28,16,0.85) 0%, transparent 55%);
        opacity: 0;
        transition: opacity 0.4s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        padding: 18px;
    }
    .gallery-slide-item:hover .gallery-slide-overlay {
        opacity: 1;
    }
    .gallery-slide-caption {
        color: #fff;
        font-size: 0.92rem;
        font-weight: 600;
        text-align: center;
        transform: translateY(10px);
        transition: transform 0.4s ease;
        margin-bottom: 8px;
    }
    .gallery-slide-item:hover .gallery-slide-caption {
        transform: translateY(0);
    }
    .gallery-slide-icon {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        border: 1.5px solid rgba(255,255,255,0.6);
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        font-size: 0.9rem;
        transform: scale(0.6);
        transition: transform 0.4s ease;
    }
    .gallery-slide-item:hover .gallery-slide-icon {
        transform: scale(1);
    }

    /* Lightbox */
    .gallery-lightbox {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.93);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        backdrop-filter: blur(6px);
    }
    .gallery-lightbox.active {
        opacity: 1;
        visibility: visible;
    }
    .lightbox-inner {
        position: relative;
        max-width: 90vw;
        max-height: 85vh;
        text-align: center;
    }
    .lightbox-inner img {
        max-width: 100%;
        max-height: 78vh;
        border-radius: 12px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.6);
        object-fit: contain;
        transition: opacity 0.3s ease;
    }
    .lightbox-caption {
        color: #fff;
        margin-top: 14px;
        font-size: 1.05rem;
        font-weight: 500;
        opacity: 0.9;
    }
    .lightbox-close {
        position: absolute;
        top: -15px; right: -15px;
        width: 40px; height: 40px;
        border-radius: 50%;
        background: var(--color-primary);
        border: none;
        color: #fff;
        font-size: 1.1rem;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background 0.3s ease, transform 0.3s ease;
        z-index: 1;
    }
    .lightbox-close:hover {
        background: #e74c3c;
        transform: rotate(90deg) scale(1.1);
    }
    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 48px; height: 48px;
        border-radius: 50%;
        background: rgba(255,255,255,0.15);
        border: 2px solid rgba(255,255,255,0.5);
        color: #fff;
        font-size: 1.2rem;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.3s ease;
    }
    .lightbox-nav:hover { background: var(--color-primary); border-color: var(--color-primary); }
    .lightbox-prev { left: -70px; }
    .lightbox-next { right: -70px; }
    .lightbox-counter {
        position: absolute;
        top: -40px;
        left: 50%; transform: translateX(-50%);
        color: rgba(255,255,255,0.7);
        font-size: 0.95rem;
    }

    /* Floating WhatsApp Button */
    .float-wa {
        position: fixed;
        width: 60px; height: 60px;
        bottom: 30px; right: 30px;
        background-color: #25d366;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        font-size: 30px;
        box-shadow: 0px 4px 15px rgba(37, 211, 102, 0.4);
        z-index: 1000;
        display: flex; align-items: center; justify-content: center;
        transition: transform 0.3s ease;
        text-decoration: none;
    }
    .float-wa:hover { transform: scale(1.1); color: #FFF; }
    .float-wa .ping {
        position: absolute;
        width: 100%; height: 100%;
        border-radius: 50%;
        background-color: #25d366;
        opacity: 0.6;
        z-index: -1;
        animation: ping 1.5s ease-in-out infinite;
    }
    @keyframes ping {
        0%   { transform: scale(1);   opacity: 0.8; }
        100% { transform: scale(1.5); opacity: 0; }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-title { font-size: 2.2rem; }
        .hero-text  { font-size: 1rem; }
        .section-title { font-size: 1.8rem; }
        .step-number { width: 60px; height: 60px; font-size: 1.5rem; }
        .gallery-slide-item { width: 260px; height: 185px; }
        .lightbox-prev { left: -50px; }
        .lightbox-next { right: -50px; }
    }
    @media (max-width: 480px) {
        .hero-actions { flex-direction: column; }
        .btn-hero-primary, .btn-hero-outline { width: 100%; justify-content: center; }
    }
</style>

<!-- Preloader -->
<div id="preloader">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <div class="status-text" id="loader-text">Memuat Sistem...</div>
</div>

<!-- Tombol WhatsApp Melayang -->
<a href="https://wa.me/6281234567890?text=Halo%20Admin%20UPT%20Metrologi%20Singkawang,%20saya%20butuh%20bantuan%20terkait%20SIM%20TERA..." class="float-wa" target="_blank" title="Butuh Bantuan? Hubungi Kami">
    <i class="fab fa-whatsapp"></i>
    <span class="ping"></span>
</a>

<!-- ============================================
     HERO SECTION - Full Background Cinematic
     ============================================ -->
<section class="hero-section" id="Beranda">

    <!-- Background Slides -->
    <div class="hero-bg-slides" id="heroBgSlides">
        <div class="hero-bg-slide active" style="background-image: url('<?= base_url('asset/kegiatan1.jpg') ?>');"></div>
        <div class="hero-bg-slide" style="background-image: url('<?= base_url('asset/kegiatan3.jpg') ?>');"></div>
        <div class="hero-bg-slide" style="background-image: url('<?= base_url('asset/kegiatan5.jpg') ?>');"></div>
    </div>

    <!-- Overlay -->
    <div class="hero-overlay"></div>
    <div class="hero-blob hero-blob-1"></div>
    <div class="hero-blob hero-blob-2"></div>

    <!-- Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-xl-7">
                <div class="hero-content">
                    <div class="hero-badge">
                        <i class="fas fa-shield-alt"></i>
                        Layanan Resmi Pemerintah &nbsp;·&nbsp; UPT Metrologi Legal Kota Singkawang
                    </div>
                    <h1 class="hero-title">
                        Sistem Pelayanan<br>
                        <span class="accent">Tera & Tera Ulang</span><br>
                        Alat Ukur Timbang
                    </h1>
                    <p class="hero-text">
                        Daftarkan alat UTTP (Alat Ukur, Takar, Timbang, dan Perlengkapannya) Anda untuk mendapatkan pengujian resmi secara <strong style="color:#4ade80;">Transparan, Cepat, dan Pasti</strong> langsung dari kantor UPT Metrologi Legal Kota Singkawang.
                    </p>
                    <div class="hero-actions">
                        <a href="<?= base_url('auth/daftar') ?>" class="btn-hero-primary">
                            <i class="fas fa-file-signature"></i> Ajukan Permohonan
                        </a>
                        <a href="#Langkah_Pengajuan" class="btn-hero-outline">
                            <i class="fas fa-info-circle"></i> Lihat Panduan
                        </a>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="hero-slide-dots" id="heroSlideDots">
        <div class="hero-dot active" data-index="0"></div>
        <div class="hero-dot" data-index="1"></div>
        <div class="hero-dot" data-index="2"></div>
    </div>
</section>


<section class="py-5" id="Langkah_Pengajuan">
    <div class="container py-4">
        <div class="text-center">
            <h2 class="section-title">Panduan Langkah Mudah</h2>
            <p class="section-subtitle">Bagaimana cara mengajukan Tera/Tera Ulang melalui aplikasi ini?</p>
        </div>
        <div class="row g-4 mt-2">
            <div class="col-md-3">
                <div class="step-box">
                    <div class="step-number">1</div>
                    <h4 class="step-title">Mendaftar</h4>
                    <p class="step-desc">Buat akun dengan mengisi data diri dan nama Instansi / Perusahaan Anda di menu Pendaftaran.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step-box">
                    <div class="step-number">2</div>
                    <h4 class="step-title">Pendataan Alat</h4>
                    <p class="step-desc">Setelah login, masukkan jenis dan jumlah alat timbang/ukur yang ingin ditera.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step-box">
                    <div class="step-number">3</div>
                    <h4 class="step-title">Pengujian</h4>
                    <p class="step-desc">Petugas resmi kami akan mengecek dokumen pengajuan dan menjadwalkan pengujian alat.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step-box">
                    <div class="step-number">4</div>
                    <h4 class="step-title">SKHP Terbit</h4>
                    <p class="step-desc">Jika alat Anda dinyatakan lulus, Surat Keterangan Hasil Pengujian (SKHP) akan diterbitkan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section Pentingnya Tera -->
<section class="py-5" id="Tentang_Tera" style="background-color: var(--color-bg-alt);">
    <div class="container py-4">
        <div class="row align-items-center mb-5">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="section-title">Pentingnya Memiliki SKHP</h2>
                <p class="section-subtitle">Mengapa setiap alat ukur di tempat usaha Anda wajib ditera?</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h3 class="info-card-title">Jaminan Akurasi</h3>
                    <p class="info-card-text">
                        Memastikan alat timbang Anda menunjuk hasil yang tepat, sehingga tidak ada pihak yang dirugikan dalam proses jual beli.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <h3 class="info-card-title">Taat Hukum (UU RI)</h3>
                    <p class="info-card-text">
                        Sesuai Undang-Undang No. 2 Tahun 1981, penggunaan alat takar yang tidak ditera dapat dikenakan sanksi pidana dan denda.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-card">
                    <div class="info-icon" style="color: var(--color-secondary);">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3 class="info-card-title">Kepercayaan Pelanggan</h3>
                    <p class="info-card-text">
                        Pelanggan akan lebih percaya dan merasa aman berbelanja jika mengetahui alat ukur Anda memiliki sertifikat kelayakan resmi.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="gallery-section" id="Galeri">
    <div class="text-center mb-2 px-3">
        <span class="badge px-3 py-2 fs-6 mb-3 shadow-sm" style="background-color: var(--color-primary); letter-spacing: 0.5px;">DOKUMENTASI</span>
        <h2 class="section-title">Galeri Kegiatan Tera</h2>
        <p class="section-subtitle">Dokumentasi kegiatan pengujian dan tera alat ukur oleh petugas resmi UPT Metrologi Legal Kota Singkawang.</p>
    </div>

    <!-- Slider Track -->
    <div class="gallery-slider-track-outer">
        <div class="gallery-slider-track" id="galleryTrack">
            <!-- Set 1 (original) -->
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan1.jpg') ?>"
                 data-caption="Kegiatan Tera & Tera Ulang UTTP di Lapangan">
                <img src="<?= base_url('asset/kegiatan1.jpg') ?>"
                     onerror="this.src='https://images.unsplash.com/photo-1580975618797-e81881dc71cb?auto=format&fit=crop&w=700&q=80'"
                     alt="Kegiatan 1" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Kegiatan Tera & Tera Ulang UTTP di Lapangan</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan2.jpg') ?>"
                 data-caption="Pengujian Timbangan Digital Pasar">
                <img src="<?= base_url('asset/kegiatan2.jpg') ?>"
                     onerror="this.src='https://images.unsplash.com/photo-1563211568-d06ef8e81566?auto=format&fit=crop&w=700&q=80'"
                     alt="Kegiatan 2" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Pengujian Timbangan Digital Pasar</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan3.jpg') ?>"
                 data-caption="Petugas Metrologi Melakukan Verifikasi Alat Ukur">
                <img src="<?= base_url('asset/kegiatan3.jpg') ?>"
                     onerror="this.src='https://images.unsplash.com/photo-1581093450021-4a7360e9a6b5?auto=format&fit=crop&w=700&q=80'"
                     alt="Kegiatan 3" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Petugas Metrologi Melakukan Verifikasi Alat Ukur</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan4.jpg') ?>"
                 data-caption="Proses Penerbitan SKHP oleh Tim UPT">
                <img src="<?= base_url('asset/kegiatan4.jpg') ?>"
                     onerror="this.src='https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=700&q=80'"
                     alt="Kegiatan 4" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Proses Penerbitan SKHP oleh Tim UPT</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan5.jpg') ?>"
                 data-caption="Pengujian Pompa Ukur BBM di SPBU">
                <img src="<?= base_url('asset/kegiatan5.jpg') ?>"
                     onerror="this.src='https://images.unsplash.com/photo-1614621811703-bbe2a7c8c4ae?auto=format&fit=crop&w=700&q=80'"
                     alt="Kegiatan 5" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Pengujian Pompa Ukur BBM di SPBU</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan6.jpg') ?>"
                 data-caption="Pemeriksaan Alat Ukur di Lokasi Usaha">
                <img src="<?= base_url('asset/kegiatan6.jpg') ?>"
                     alt="Kegiatan 6" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Pemeriksaan Alat Ukur di Lokasi Usaha</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan7.jpg') ?>"
                 data-caption="Tim Penera Melaksanakan Tugas di Lapangan">
                <img src="<?= base_url('asset/kegiatan7.jpg') ?>"
                     alt="Kegiatan 7" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Tim Penera Melaksanakan Tugas di Lapangan</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan8.jpg') ?>"
                 data-caption="Pengujian Timbangan Industri">
                <img src="<?= base_url('asset/kegiatan8.jpg') ?>"
                     alt="Kegiatan 8" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Pengujian Timbangan Industri</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan9.jpg') ?>"
                 data-caption="Verifikasi Meter Air dan Meter Listrik">
                <img src="<?= base_url('asset/kegiatan9.jpg') ?>"
                     alt="Kegiatan 9" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Verifikasi Meter Air dan Meter Listrik</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan10.jpg') ?>"
                 data-caption="Dokumentasi Kegiatan Tera Resmi UPT Metrologi">
                <img src="<?= base_url('asset/kegiatan10.jpg') ?>"
                     alt="Kegiatan 10" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Dokumentasi Kegiatan Tera Resmi UPT Metrologi</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <!-- Set 2 (clone for seamless loop) -->
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan1.jpg') ?>"
                 data-caption="Kegiatan Tera & Tera Ulang UTTP di Lapangan">
                <img src="<?= base_url('asset/kegiatan1.jpg') ?>"
                     alt="Kegiatan 1" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Kegiatan Tera & Tera Ulang UTTP di Lapangan</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan2.jpg') ?>"
                 data-caption="Pengujian Timbangan Digital Pasar">
                <img src="<?= base_url('asset/kegiatan2.jpg') ?>"
                     alt="Kegiatan 2" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Pengujian Timbangan Digital Pasar</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan3.jpg') ?>"
                 data-caption="Petugas Metrologi Melakukan Verifikasi Alat Ukur">
                <img src="<?= base_url('asset/kegiatan3.jpg') ?>"
                     alt="Kegiatan 3" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Petugas Metrologi Melakukan Verifikasi Alat Ukur</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan4.jpg') ?>"
                 data-caption="Proses Penerbitan SKHP oleh Tim UPT">
                <img src="<?= base_url('asset/kegiatan4.jpg') ?>"
                     alt="Kegiatan 4" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Proses Penerbitan SKHP oleh Tim UPT</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan5.jpg') ?>"
                 data-caption="Pengujian Pompa Ukur BBM di SPBU">
                <img src="<?= base_url('asset/kegiatan5.jpg') ?>"
                     alt="Kegiatan 5" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Pengujian Pompa Ukur BBM di SPBU</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan6.jpg') ?>"
                 data-caption="Pemeriksaan Alat Ukur di Lokasi Usaha">
                <img src="<?= base_url('asset/kegiatan6.jpg') ?>"
                     alt="Kegiatan 6" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Pemeriksaan Alat Ukur di Lokasi Usaha</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan7.jpg') ?>"
                 data-caption="Tim Penera Melaksanakan Tugas di Lapangan">
                <img src="<?= base_url('asset/kegiatan7.jpg') ?>"
                     alt="Kegiatan 7" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Tim Penera Melaksanakan Tugas di Lapangan</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan8.jpg') ?>"
                 data-caption="Pengujian Timbangan Industri">
                <img src="<?= base_url('asset/kegiatan8.jpg') ?>"
                     alt="Kegiatan 8" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Pengujian Timbangan Industri</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan9.jpg') ?>"
                 data-caption="Verifikasi Meter Air dan Meter Listrik">
                <img src="<?= base_url('asset/kegiatan9.jpg') ?>"
                     alt="Kegiatan 9" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Verifikasi Meter Air dan Meter Listrik</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
            <div class="gallery-slide-item"
                 data-src="<?= base_url('asset/kegiatan10.jpg') ?>"
                 data-caption="Dokumentasi Kegiatan Tera Resmi UPT Metrologi">
                <img src="<?= base_url('asset/kegiatan10.jpg') ?>"
                     alt="Kegiatan 10" loading="lazy">
                <div class="gallery-slide-overlay">
                    <div class="gallery-slide-caption">Dokumentasi Kegiatan Tera Resmi UPT Metrologi</div>
                    <div class="gallery-slide-icon"><i class="fas fa-expand"></i></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div class="gallery-lightbox" id="galleryLightbox">
    <div class="lightbox-inner">
        <button class="lightbox-close" id="lightboxClose"><i class="fas fa-times"></i></button>
        <div class="lightbox-counter" id="lightboxCounter">1 / 5</div>
        <button class="lightbox-nav lightbox-prev" id="lightboxPrev"><i class="fas fa-chevron-left"></i></button>
        <img src="" id="lightboxImg" alt="">
        <button class="lightbox-nav lightbox-next" id="lightboxNext"><i class="fas fa-chevron-right"></i></button>
        <div class="lightbox-caption" id="lightboxCaption"></div>
    </div>
</div>

<!-- Section Kontak -->
<section class="py-5" id="Kontak">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="section-title">Pusat Bantuan & Kontak</h2>
            <p class="section-subtitle">Jika Anda memiliki kendala atau pertanyaan, silakan hubungi kami atau kunjungi kantor UPT Metrologi Legal.</p>
        </div>

        <div class="contact-wrapper">
            <div class="row g-0">
                <div class="col-lg-5 p-5" style="background-color: var(--color-bg-alt);">
                    <h3 class="mb-4" style="color: var(--color-primary); font-weight: 700;">Informasi Kantor</h3>

                    <div class="contact-info-item">
                        <div class="contact-icon-box">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Alamat Lengkap</h5>
                            <p>Jl. Firdaus No.38, Pasiran, Kec. Singkawang Barat.<br>Kota Singkawang, Kalimantan Barat 79123</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-icon-box">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Telepon</h5>
                            <p>(0562) 631425<br>Senin - Jumat (08:00 - 15:00)</p>
                        </div>
                    </div>

                    <div class="contact-info-item mb-0">
                        <div class="contact-icon-box">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h5>Email Resmi</h5>
                            <p>metrologi@singkawangkota.go.id</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <iframe src="https://maps.google.com/maps?q=Jl.+Firdaus+No.38,+Pasiran,+Kec.+Singkawang+Barat.,+Kota+Singkawang&t=&z=15&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border:0; min-height: 400px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $this->load->view('layout/footer'); ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        // ===========================================
        // PRELOADER
        // ===========================================
        const preloader = document.getElementById('preloader');
        setTimeout(function () {
            preloader.classList.add('hidden');
        }, 800);

        // ===========================================
        // HERO BACKGROUND SLIDESHOW
        // ===========================================
        const heroSlides = document.querySelectorAll('.hero-bg-slide');
        const heroDots   = document.querySelectorAll('.hero-dot');
        let heroIndex    = 0;

        function switchHeroSlide(idx) {
            heroSlides[heroIndex].classList.remove('active');
            heroDots[heroIndex].classList.remove('active');
            heroIndex = idx;
            heroSlides[heroIndex].classList.add('active');
            heroDots[heroIndex].classList.add('active');
        }

        // Auto advance every 5 seconds
        let heroTimer = setInterval(function () {
            switchHeroSlide((heroIndex + 1) % heroSlides.length);
        }, 5000);

        // Click dots
        heroDots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                clearInterval(heroTimer);
                switchHeroSlide(parseInt(this.dataset.index));
                heroTimer = setInterval(function () {
                    switchHeroSlide((heroIndex + 1) % heroSlides.length);
                }, 5000);
            });
        });

        // ===========================================
        // GALLERY LIGHTBOX
        // ===========================================
        const lightbox       = document.getElementById('galleryLightbox');
        const lightboxImg    = document.getElementById('lightboxImg');
        const lightboxCap    = document.getElementById('lightboxCaption');
        const lightboxClose  = document.getElementById('lightboxClose');
        const lightboxPrev   = document.getElementById('lightboxPrev');
        const lightboxNext   = document.getElementById('lightboxNext');
        const lightboxCounter= document.getElementById('lightboxCounter');

        // Only the first set of 5 for lightbox navigation
        const galleryItems = document.querySelectorAll('.gallery-slide-item');
        const uniqueItems  = [...galleryItems].slice(0, 5); // first 5 = original set
        let currentIndex   = 0;

        function openLightbox(index) {
            currentIndex = index;
            updateLightbox();
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function updateLightbox() {
            const item = uniqueItems[currentIndex];
            lightboxImg.style.opacity = 0;
            setTimeout(function () {
                lightboxImg.src = item.dataset.src || '';
                lightboxImg.alt = item.dataset.caption || '';
                lightboxCap.textContent = item.dataset.caption || '';
                lightboxCounter.textContent = (currentIndex + 1) + ' / ' + uniqueItems.length;
                lightboxImg.style.opacity = 1;
            }, 200);
        }

        function closeLightbox() {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        }

        galleryItems.forEach(function (item, index) {
            item.addEventListener('click', function () {
                openLightbox(index % 10); // map cloned items back to original
            });
        });

        lightboxClose.addEventListener('click', closeLightbox);
        lightbox.addEventListener('click', function (e) {
            if (e.target === lightbox) closeLightbox();
        });
        lightboxNext.addEventListener('click', function () {
            currentIndex = (currentIndex + 1) % uniqueItems.length;
            updateLightbox();
        });
        lightboxPrev.addEventListener('click', function () {
            currentIndex = (currentIndex - 1 + uniqueItems.length) % uniqueItems.length;
            updateLightbox();
        });
        document.addEventListener('keydown', function (e) {
            if (!lightbox.classList.contains('active')) return;
            if (e.key === 'ArrowRight') lightboxNext.click();
            if (e.key === 'ArrowLeft')  lightboxPrev.click();
            if (e.key === 'Escape')     closeLightbox();
        });
    });
</script>
