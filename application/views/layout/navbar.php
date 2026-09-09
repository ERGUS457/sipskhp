<style>
    /* Variabel Tema Khusus Navbar (Menyesuaikan dengan Landing Page) */
    :root {
        --nav-primary: #065f46; /* Hijau Zamrud Tua */
        --nav-secondary: #10b981; /* Hijau Zamrud Cerah */
        --nav-bg: #ffffff;
        --nav-text: #495057;
        --nav-text-hover: #333333;
        --nav-hover-bg: #f0fdf4;
        --nav-border: #e0e0e0;
    }

    [data-theme="dark"] {
        --nav-primary: #34d399; /* Hijau Terang untuk Dark Mode */
        --nav-secondary: #059669;
        --nav-bg: #1f2937;
        --nav-text: #d1d5db;
        --nav-text-hover: #ffffff;
        --nav-hover-bg: #374151;
        --nav-border: #4b5563;
    }

    /* Styling khusus Navbar agar lebih ramah lansia/dewasa */
    .custom-navbar {
        background-color: var(--nav-bg);
        box-shadow: 0 2px 15px rgba(0,0,0,0.08); /* Bayangan sedikit lebih tebal untuk kedalaman */
        padding-top: 12px;
        padding-bottom: 12px;
        transition: all 0.3s ease;
    }
    .custom-navbar .navbar-brand {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .brand-text {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }
    .brand-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--nav-primary); 
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }
    .brand-subtitle {
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--nav-text);
    }
    .custom-nav-link {
        color: var(--nav-text) !important;
        font-weight: 600;
        font-size: 0.95rem;
        padding: 8px 16px !important;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .custom-nav-link:hover, .custom-nav-link.active {
        color: var(--nav-primary) !important;
        background-color: var(--nav-hover-bg); 
    }
    .nav-btn {
        font-weight: 600;
        font-size: 0.95rem;
        border-radius: 8px;
        padding: 8px 20px;
        display: flex;
        align-items: center;
        gap: 6px;
        justify-content: center;
    }
    .nav-btn-outline {
        border: 2px solid var(--nav-primary);
        color: var(--nav-primary);
        background-color: transparent;
    }
    .nav-btn-outline:hover {
        background-color: var(--nav-primary);
        color: #ffffff;
    }
    .nav-btn-solid {
        background-color: var(--nav-primary);
        color: #ffffff;
        border: none;
    }
    .nav-btn-solid:hover {
        background-color: var(--nav-secondary);
        color: #ffffff;
    }
    .navbar-toggler {
        border: 1px solid var(--nav-border);
        padding: 12px;
        border-radius: 8px;
        background-color: var(--nav-bg);
    }
    .navbar-toggler-icon {
        filter: invert(0.5); /* Supaya terlihat di light/dark mode */
    }
    .offcanvas {
        background-color: var(--nav-bg);
    }
    .offcanvas-title {
        color: var(--nav-primary) !important;
    }
    .navbar-toggler:focus {
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25);
    }
</style>

<nav class="navbar navbar-expand-lg fixed-top custom-navbar">
    <div class="container">
        <!-- Logo dan Nama Aplikasi yang Lebih Rapi -->
        <a class="navbar-brand" href="<?= base_url() ?>">
            <img src="<?= base_url('asset/Logo_UPT.png') ?>" alt="Logo UPT Metrologi" height="50">
            <div class="brand-text d-none d-sm-flex">
                <span class="brand-title">SIAP SKHP TERA</span>
                <span class="brand-subtitle">UPT Metrologi Singkawang</span>
            </div>
            <!-- Versi Mobile Singkat (Agar tidak sempit di layar HP) -->
            <div class="brand-text d-flex d-sm-none">
                <span class="brand-title">SIAP SKHP TERA</span>
            </div>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title fw-bold text-primary d-flex align-items-center gap-2" id="offcanvasNavbarLabel">
                    <img src="<?= base_url('asset/Logo_UPT.png') ?>" alt="Logo" height="35">
                    Menu E-TERA
                </h5>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas" aria-label="Close" style="padding: 15px;"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 align-items-center">
                    <li class="nav-item w-100 w-lg-auto mb-2 mb-lg-0">
                        <a class="nav-link custom-nav-link" href="<?= site_url('register?from=pengajuan') ?>">
                            <i class="fas fa-file-signature" style="color: var(--nav-primary);"></i> Pengajuan Baru
                        </a>
                    </li>
                    <li class="nav-item w-100 w-lg-auto mb-2 mb-lg-0">
                        <a class="nav-link custom-nav-link" href="<?= base_url() ?>#Tentang_Tera">
                            <i class="fas fa-info-circle text-info"></i> Tentang
                        </a>
                    </li>
                    <li class="nav-item w-100 w-lg-auto mb-2 mb-lg-0">
                        <a class="nav-link custom-nav-link" href="<?= base_url() ?>#Kontak">
                            <i class="fas fa-headset text-success"></i> Kontak
                        </a>
                    </li>
                    
                    <!-- Garis Pemisah Khusus untuk Tampilan Layar HP -->
                    <li class="nav-item w-100 d-lg-none mt-2 mb-3"><hr class="my-0 border-secondary opacity-25"></li>

                    <?php if ($this->session->userdata('logged_in')): ?>
                        <li class="nav-item w-100 w-lg-auto mt-2 mt-lg-0 ms-lg-3">
                            <a class="nav-link custom-nav-link" href="<?= ($this->session->userdata('level') === 'Pemohon') ? site_url('pemohon-dashboard') : site_url('dashboard') ?>">
                                <i class="fas fa-user-circle" style="color: var(--nav-primary); font-size: 1.2rem;"></i> <?= htmlspecialchars((string) $this->session->userdata('username')) ?>
                            </a>
                        </li>
                        <li class="nav-item w-100 w-lg-auto mt-2 mt-lg-0 ms-lg-2">
                            <a class="btn nav-btn w-100 shadow-sm" href="<?= site_url('logout') ?>" style="background-color: #ef4444; color: white; border: none;">
                                <i class="fas fa-sign-out-alt"></i> Keluar
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item w-100 w-lg-auto mt-2 mt-lg-0 ms-lg-3">
                            <a class="btn nav-btn nav-btn-outline w-100" href="<?= site_url('login') ?>">
                                <i class="fas fa-sign-in-alt"></i> Masuk
                            </a>
                        </li>
                        <li class="nav-item w-100 w-lg-auto mt-2 mt-lg-0 ms-lg-2">
                            <!-- Tombol daftar menggunakan warna utama agar paling mencolok bagi pengguna baru -->
                            <a class="btn nav-btn nav-btn-solid w-100 shadow-sm" href="<?= site_url('register') ?>">
                                <i class="fas fa-user-plus"></i> Daftar Akun
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</nav>
