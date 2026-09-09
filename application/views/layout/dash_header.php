<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Dashboard' ?> | E-METROLOGI</title>
    
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            color: #333;
            overflow-x: hidden;
        }

        /* Sidebar Styling (Premium Dark Gradient) */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 270px;
            padding: 25px 20px;
            background: linear-gradient(145deg, #111827 0%, #1f2937 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.08);
            z-index: 1000;
            transition: transform 0.3s ease-in-out;
        }
        .sidebar-header {
            text-align: center;
            margin-bottom: 35px;
            position: relative;
        }
        .sidebar-header::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 20%;
            width: 60%;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }
        .sidebar-header .logo {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            margin: 0 auto 15px;
            overflow: hidden; 
            border: 3px solid rgba(255, 255, 255, 0.2); 
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            background-color: #fff;
        }
        .sidebar-header .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain; 
            padding: 5px;
        }
        .sidebar-header h5 {
            font-size: 0.95rem;
            color: #e5e7eb;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        /* Navigation Links */
        .sidebar .nav-link {
            color: #9ca3af;
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar .nav-link i {
            margin-right: 15px;
            width: 22px;
            text-align: center;
            font-size: 1.1rem;
            transition: transform 0.3s;
        }
        .sidebar .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(5px);
        }
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #fff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        .sidebar .nav-link.active i {
            transform: scale(1.1);
        }

        /* Tombol Keluar / Bawah */
        .sidebar .logout-btn {
            margin-top: auto;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
            transition: all 0.3s;
        }
        .sidebar .logout-btn:hover {
            background: #ef4444;
            color: #fff;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
            transform: translateY(-2px);
        }

        /* Area Utama (Main Content) */
        .main-wrapper {
            margin-left: 270px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.3s ease-in-out;
        }

        /* Header (Navbar Atas) - Glassmorphism */
        .header {
            padding: 15px 35px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }
        .header .system-title {
            font-weight: 700;
            color: #1f2937;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }
        .header-user {
            background: #f3f4f6;
            padding: 6px 15px 6px 6px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            color: #4b5563;
            font-weight: 500;
            border: 1px solid #e5e7eb;
        }
        .header-user .avatar {
            width: 32px;
            height: 32px;
            background: #3b82f6;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: 700;
        }

        /* Content Area */
        .content-area {
            padding: 35px;
            flex-grow: 1;
        }

        /* Styling DataTables (Global Fix) */
        .dt-search { text-align: right; }
        
        /* Memaksa text panjang di sel data untuk turun ke bawah (wrap) sewajarnya */
        table.dataTable td {
            white-space: normal !important;
            word-wrap: normal !important;
        }
        /* Membiarkan header tetap utuh untuk menjaga lebar minimal kolom */
        table.dataTable th {
            white-space: nowrap !important;
        }
        /* Memberikan lebar minimal agar kalimat turun per kata/kalimat, bukan per huruf/berdempetan */
        table.dataTable td, table.dataTable th {
            min-width: 120px;
        }
        /* Pengecualian untuk kolom nomor, aksi atau badge agar tidak terlalu lebar */
        table.dataTable th:first-child, table.dataTable td:first-child {
            min-width: 40px;
            width: 5%;
        }
        table.dataTable th:last-child, table.dataTable td:last-child {
            min-width: 100px;
        }
        
        /* Memastikan tabel selalu merentang 100% layar walau jumlah kolom sedikit (mencegah ruang kosong) */
        table.dataTable, .dataTables_scrollHeadInner {
            min-width: 100% !important;
        }

        @media (max-width: 768px) {
            /* Mengecilkan font tabel di HP agar lebih proporsional */
            table.dataTable, table.dataTable th, table.dataTable td {
                font-size: 0.82rem !important;
            }
            table.dataTable th, table.dataTable td {
                padding: 0.5rem 0.4rem !important;
            }
            
            /* Mencegah search box ikut tergeser saat layar scroll horizontal */
            .table-responsive { overflow-x: visible !important; }
            .dt-search {
                text-align: left !important;
                margin-top: 10px;
            }
            .dt-search label { width: 100%; }
            .dt-search input {
                width: 100% !important;
                margin-left: 0 !important;
                display: block;
            }
            .dt-length { margin-bottom: 10px; }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
            /* Backdrop when sidebar is open */
            .sidebar-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 998;
                display: none;
            }
            .sidebar-backdrop.show {
                display: block;
            }
        }
    </style>
</head>
<body>
    <?php
    if (!function_exists('time_ago_id')) {
        function time_ago_id($datetime) {
            $time = strtotime($datetime);
            $time_difference = time() - $time;
            
            if ($time_difference < 1) { return 'baru saja'; }
            $condition = array(
                12 * 30 * 24 * 60 * 60  =>  'tahun',
                30 * 24 * 60 * 60       =>  'bulan',
                24 * 60 * 60            =>  'hari',
                60 * 60                 =>  'jam',
                60                      =>  'menit',
                1                       =>  'detik'
            );

            foreach ($condition as $secs => $str) {
                $d = $time_difference / $secs;
                if ($d >= 1) {
                    $t = round($d);
                    return $t . ' ' . $str . ' yang lalu';
                }
            }
            return 'baru saja';
        }
    }

    // Cek jumlah pengajuan baru yang belum diproses (khusus Admin)
    $pending_pengajuan = 0;
    $pending_pengajuan_list = [];
    if ($this->session->userdata('level') === 'Admin') {
        $CI =& get_instance();
        $hasCreatedAt = $CI->db->field_exists('created_at', 'alat_uttp');

        // Ambil daftar 5 pengajuan terbaru
        $CI->db->select('alat_uttp.id_alat, alat_uttp.nama_alat, pemohon.nama_pemilik' . ($hasCreatedAt ? ', alat_uttp.created_at' : ''));
        $CI->db->from('alat_uttp');
        $CI->db->join('pemohon', 'pemohon.id_pemohon = alat_uttp.id_pemohon', 'left');
        $CI->db->where('alat_uttp.is_read_admin', 0);
        $CI->db->order_by($hasCreatedAt ? 'alat_uttp.created_at' : 'alat_uttp.id_alat', 'DESC');
        $CI->db->limit(5);
        $pending_pengajuan_list = $CI->db->get()->result_array();

        // Hitung total keseluruhan
        $CI->db->from('alat_uttp');
        $CI->db->where('alat_uttp.is_read_admin', 0);
        $pending_pengajuan = $CI->db->count_all_results();
    }
    ?>

    <div class="sidebar-backdrop d-lg-none" id="sidebarBackdrop"></div>

    <!-- Sidebar Menu Kiri -->
    <div class="sidebar" id="mainSidebar">
        <div class="sidebar-header">
            <div class="logo"><img src="<?= base_url('asset/Logo_UPT.png') ?>" alt="Logo UPT Metrologi"></div> 
            <h5>UPT Metrologi <br>Kota Singkawang</h5>
        </div>
        <ul class="nav flex-column">
            <?php if ($this->session->userdata('level') === 'Admin'): ?>
                <li class="nav-item"><a class="nav-link <?= ($this->uri->segment(1) === 'dashboard') ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>"><i class="fas fa-home"></i> Dashboard Utama</a></li>
                <li class="nav-item">
                    <a class="nav-link <?= ($this->uri->segment(1) === 'pengajuan') ? 'active' : '' ?> d-flex justify-content-between align-items-center" href="<?= site_url('pengajuan') ?>">
                        <span><i class="fas fa-clipboard-list"></i> Data Pengajuan</span>
                        <?php if ($pending_pengajuan > 0): ?>
                            <span class="badge bg-danger rounded-pill" style="font-size: 0.75rem; font-weight: 600; box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);"><?= $pending_pengajuan ?> Baru</span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link <?= ($this->uri->segment(1) === 'surat-tugas') ? 'active' : '' ?>" href="<?= site_url('surat-tugas') ?>"><i class="fas fa-file-contract"></i> Kelola Surat Tugas</a></li>
                <li class="nav-item"><a class="nav-link <?= ($this->uri->segment(1) === 'kelola-skhp') ? 'active' : '' ?>" href="<?= site_url('kelola-skhp') ?>"><i class="fas fa-stamp"></i> Penerbitan SKHP</a></li>
                <li class="nav-item"><a class="nav-link <?= ($this->uri->segment(1) === 'kelola-pemohon') ? 'active' : '' ?>" href="<?= site_url('kelola-pemohon') ?>"><i class="fas fa-building"></i> Profil Pemohon</a></li>
                <li class="nav-item"><a class="nav-link <?= ($this->uri->segment(1) === 'petugas') ? 'active' : '' ?>" href="<?= site_url('petugas') ?>"><i class="fas fa-users-cog"></i> Manajemen Petugas</a></li>
                <li class="nav-item"><a class="nav-link <?= ($this->uri->segment(1) === 'laporan') ? 'active' : '' ?>" href="<?= site_url('laporan') ?>"><i class="fas fa-chart-pie"></i> Rekap Laporan</a></li>
                <li class="nav-item"><a class="nav-link <?= ($this->uri->segment(1) === 'log-aktivitas') ? 'active' : '' ?>" href="<?= site_url('log-aktivitas') ?>"><i class="fas fa-users-cog"></i> Log Aktivitas</a></li>
                <li class="nav-item"><a class="nav-link <?= ($this->uri->segment(1) === 'users') ? 'active' : '' ?>" href="<?= site_url('users') ?>"><i class="fas fa-user-shield"></i> Pengaturan Akun Admin</a></li>
            <?php elseif ($this->session->userdata('level') === 'Kepala UPT'): ?>
                <li class="nav-item"><a class="nav-link <?= ($this->uri->segment(1) === 'dashboard') ? 'active' : '' ?>" href="<?= site_url('dashboard') ?>"><i class="fas fa-chart-line"></i> Ringkasan Dasbor</a></li>
                <li class="nav-item"><a class="nav-link <?= ($this->uri->segment(1) === 'validasi-skhp') ? 'active' : '' ?>" href="<?= site_url('validasi-skhp') ?>"><i class="fas fa-signature"></i> Otorisasi Hasil Uji</a></li>
                <li class="nav-item"><a class="nav-link <?= ($this->uri->segment(1) === 'laporan') ? 'active' : '' ?>" href="<?= site_url('laporan') ?>"><i class="fas fa-file-invoice"></i> Pembukuan Laporan</a></li>
            <?php elseif ($this->session->userdata('level') === 'Petugas'): ?>
                <li class="nav-item"><a class="nav-link <?= ($this->uri->segment(1) === 'petugas-dashboard') ? 'active' : '' ?>" href="<?= site_url('petugas-dashboard') ?>"><i class="fas fa-tasks"></i> Daftar Tugasku</a></li>
            <?php elseif ($this->session->userdata('level') === 'Pemohon'): ?>
                <li class="nav-item"><a class="nav-link <?= ($this->uri->segment(1) === 'pemohon-dashboard') ? 'active' : '' ?>" href="<?= site_url('pemohon-dashboard') ?>"><i class="fas fa-home"></i> Dashboard Pemohon</a></li>
            <?php endif; ?>
        </ul>
        <a href="<?= site_url('logout') ?>" class="btn logout-btn"><i class="fas fa-power-off me-2"></i> KELUAR SISTEM</a>
    </div>

    <!-- Wrapper area utama di sebelah kanan Sidebar -->
    <div class="main-wrapper">
        <header class="header">
            <div class="system-title d-flex align-items-center">
                <button id="sidebarToggle" class="btn btn-light d-lg-none me-2" style="border: 1px solid #e5e7eb;">
                    <i class="fas fa-bars"></i>
                </button>
                <i class="fas fa-balance-scale text-primary me-2 d-none d-sm-inline"></i> 
                <span class="d-none d-sm-inline">E-TERA ADMINISTRATION</span>
            </div>
            
            <div class="header-user d-flex align-items-center gap-3">


                <div class="d-flex align-items-center gap-2">
                    <div class="avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <span>Welcome, <strong><?= htmlspecialchars((string) $this->session->userdata('username')) ?></strong></span>
                </div>
            </div>
        </header>

        <main class="content-area">
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const sidebarToggle = document.getElementById('sidebarToggle');
                    const sidebar = document.getElementById('mainSidebar');
                    const backdrop = document.getElementById('sidebarBackdrop');

                    if(sidebarToggle && sidebar) {
                        sidebarToggle.addEventListener('click', function() {
                            sidebar.classList.toggle('show');
                            if(backdrop) backdrop.classList.toggle('show');
                        });
                        
                        if(backdrop) {
                            backdrop.addEventListener('click', function() {
                                sidebar.classList.remove('show');
                                backdrop.classList.remove('show');
                            });
                        }
                    }
                });
            </script>