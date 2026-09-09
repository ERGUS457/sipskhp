<?php
// Ambil data pemohon
$id_user_session = $this->session->userdata('id_user');
$pemohon_nav = $this->db->get_where('pemohon', ['id_user' => $id_user_session])->row_array();
$user_nav = $this->db->get_where('user', ['id_user' => $id_user_session])->row_array();

$nama_tampil = $pemohon_nav ? $pemohon_nav['nama_pemilik'] : $this->session->userdata('username');

if (!empty($user_nav['foto_profil']) && file_exists(FCPATH . 'uploads/profil/' . $user_nav['foto_profil'])) {
    $foto_profil = base_url('uploads/profil/' . $user_nav['foto_profil']);
} else {
    $foto_profil = 'https://ui-avatars.com/api/?name=' . urlencode($nama_tampil) . '&background=065f46&color=fff';
}
?>
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
        margin-bottom: 0px;
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
    .profile-img {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--nav-primary);
    }
    .dropdown-menu-custom {
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 10px 0;
    }
    .dropdown-item-custom {
        padding: 10px 20px;
        font-weight: 500;
        color: var(--nav-text);
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.2s;
    }
    .dropdown-item-custom:hover {
        background-color: var(--nav-hover-bg);
        color: var(--nav-primary);
    }
</style>

<nav class="navbar navbar-expand-lg fixed-top custom-navbar">
    <div class="container">
        <!-- Logo dan Nama Aplikasi -->
        <a class="navbar-brand" href="<?= site_url('pemohon-dashboard') ?>">
            <img src="<?= base_url('asset/Logo_UPT.png') ?>" alt="Logo UPT Metrologi" height="50">
            <div class="brand-text d-none d-sm-flex">
                <span class="brand-title">SIAP SKHP TERA</span>
            </div>
            <!-- Versi Mobile Singkat -->
            <div class="brand-text d-flex d-sm-none">
                <span class="brand-title">SIAP SKHP</span>
            </div>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title fw-bold text-primary d-flex align-items-center gap-2" id="offcanvasNavbarLabel">
                    <img src="<?= base_url('asset/Logo_UPT.png') ?>" alt="Logo" height="35">
                    Menu Pemohon
                </h5>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas" aria-label="Close" style="padding: 15px;"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 align-items-center">
                    
                    <li class="nav-item w-100 w-lg-auto mb-2 mb-lg-0">
                        <a class="nav-link custom-nav-link <?= ($this->uri->segment(1) == 'pemohon-dashboard' && $this->uri->segment(2) == '') ? 'active' : '' ?>" href="<?= site_url('pemohon-dashboard') ?>">
                            <i class="fas fa-home" style="color: var(--nav-primary);"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item w-100 w-lg-auto mb-2 mb-lg-0">
                        <a class="nav-link custom-nav-link <?= ($this->uri->segment(2) == 'pengajuan') ? 'active' : '' ?>" href="<?= site_url('pemohon-dashboard/pengajuan') ?>">
                            <i class="fas fa-clipboard-list text-info"></i> Data Pengajuan
                        </a>
                    </li>
                    <li class="nav-item w-100 w-lg-auto mb-2 mb-lg-0">
                        <a class="nav-link custom-nav-link <?= ($this->uri->segment(2) == 'skhp') ? 'active' : '' ?>" href="<?= site_url('pemohon-dashboard/skhp') ?>">
                            <i class="fas fa-certificate text-success"></i> Cetak SKHP
                        </a>
                    </li>
                    
                    <!-- Garis Pemisah Khusus untuk Tampilan Layar HP -->
                    <li class="nav-item w-100 d-lg-none mt-2 mb-3"><hr class="my-0 border-secondary opacity-25"></li>

                    <?php if ($this->session->userdata('logged_in')): ?>
                        <li class="nav-item dropdown w-100 w-lg-auto mt-2 mt-lg-0 ms-lg-3">
                            <a class="nav-link custom-nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="<?= $foto_profil ?>" alt="Profil" class="profile-img">
                                <span><?= htmlspecialchars((string) $nama_tampil) ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item dropdown-item-custom" href="<?= site_url('pemohon-dashboard/profil') ?>">
                                        <i class="fas fa-id-card"></i> Profil Detail
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item dropdown-item-custom text-danger" href="<?= site_url('logout') ?>">
                                        <i class="fas fa-sign-out-alt"></i> Keluar
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</nav>
