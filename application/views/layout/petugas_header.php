<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Petugas | E-TERA SKHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: #1e293b;
            margin: 0;
            zoom: 0.8;
        }

        /* ===== NAVBAR ===== */
        .p-navbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 32px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .p-navbar .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .p-navbar .brand img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: contain;
        }
        .p-navbar .brand-text strong {
            display: block;
            font-size: 0.9rem;
            font-weight: 700;
            color: #0f4c75;
            line-height: 1.2;
        }
        .p-navbar .brand-text small {
            display: block;
            font-size: 0.7rem;
            color: #64748b;
        }
        .p-navbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .p-navbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f1f5f9;
            border-radius: 30px;
            padding: 6px 16px 6px 8px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .p-navbar-user .avatar-sm {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #1d4ed8, #0891b2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
            color: white;
        }
        .btn-logout {
            background: transparent;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 6px 16px;
            font-size: 0.82rem;
            font-weight: 500;
            color: #64748b;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-logout:hover {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #dc2626;
        }

        /* ===== PROFILE BANNER ===== */
        .profile-banner {
            background: linear-gradient(135deg, #1e40af 0%, #0891b2 60%, #0ea5e9 100%);
            color: white;
            padding: 36px 40px;
            margin: 24px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 24px;
            box-shadow: 0 8px 30px rgba(30, 64, 175, 0.25);
            position: relative;
            overflow: hidden;
        }
        .profile-banner::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }
        .profile-banner::after {
            content: '';
            position: absolute;
            bottom: -60px;
            right: 80px;
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }
        .profile-avatar {
            width: 72px;
            height: 72px;
            background: rgba(255,255,255,0.25);
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        .profile-info h4 {
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 4px;
        }
        .profile-info .profile-meta {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .profile-info .profile-meta span {
            font-size: 0.82rem;
            opacity: 0.85;
        }
        .badge-role {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin-top: 6px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .p-navbar {
                padding: 0 16px;
            }
            .p-navbar .brand-text {
                display: none;
            }
            .p-navbar-user span {
                display: none;
            }
            .btn-logout .logout-text {
                display: none;
            }
            .profile-banner {
                flex-direction: column;
                text-align: center;
                padding: 24px 16px;
                gap: 16px;
                margin: 16px;
            }
            .profile-info .profile-meta {
                align-items: center;
            }
            .p-content {
                padding: 0 16px 20px;
            }
        }

        /* ===== CONTENT AREA ===== */
        .p-content {
            padding: 0 24px 40px;
        }
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .section-header h5 {
            font-weight: 700;
            font-size: 1.1rem;
            color: #0f4c75;
            margin: 0;
        }
        .search-box {
            position: relative;
        }
        .search-box input {
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 8px 16px 8px 38px;
            font-size: 0.83rem;
            width: 220px;
            outline: none;
            transition: border-color 0.2s;
        }
        .search-box input:focus {
            border-color: #1d4ed8;
        }
        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.8rem;
        }

        /* ===== ASSIGNMENT CARDS ===== */
        .task-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 18px;
        }
        .task-card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .task-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }
        .task-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        .badge-status {
            font-size: 0.73rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .badge-lapangan {
            background: #fef3c7;
            color: #d97706;
        }
        .badge-selesai {
            background: #dcfce7;
            color: #16a34a;
        }
        .task-date {
            font-size: 0.75rem;
            color: #94a3b8;
        }
        .task-no {
            font-weight: 700;
            color: #1d4ed8;
            font-size: 1rem;
            margin-bottom: 10px;
        }
        .task-detail-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: #475569;
            margin-bottom: 5px;
        }
        .task-detail-row i {
            width: 14px;
            color: #94a3b8;
            flex-shrink: 0;
        }
        .task-actions {
            margin-top: 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .btn-rincian {
            display: block;
            text-align: center;
            background: linear-gradient(135deg, #1d4ed8, #0891b2);
            color: white;
            border-radius: 8px;
            padding: 9px 16px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            transition: opacity 0.2s;
        }
        .btn-rincian:hover {
            opacity: 0.88;
            color: white;
        }
        .btn-rincian-done {
            background: linear-gradient(135deg, #16a34a, #059669);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #94a3b8;
        }
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 16px;
            display: block;
            opacity: 0.4;
        }

        /* Footer */
        .p-footer {
            background: #1e293b;
            color: #94a3b8;
            text-align: center;
            font-size: 0.8rem;
            padding: 16px;
        }
    </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="p-navbar">
    <a class="brand" href="<?= site_url('petugas-dashboard') ?>">
        <img src="<?= base_url('asset/Logo_UPT.png') ?>" alt="Logo UPT">
        <div class="brand-text">
            <strong>E-TERA SKHP</strong>
            <small>UPT Metrologi Singkawang</small>
        </div>
    </a>
    <div class="p-navbar-right">
        <div class="p-navbar-user">
            <div class="avatar-sm">
                <?= strtoupper(substr($this->session->userdata('username'), 0, 2)) ?>
            </div>
            <span><?= htmlspecialchars((string) $this->session->userdata('username')) ?></span>
        </div>
        <a href="<?= site_url('logout') ?>" class="btn-logout">
            <i class="fas fa-sign-out-alt me-1"></i> <span class="logout-text">Keluar</span>
        </a>
    </div>
</nav>

<?php
// Build initials for avatar from petugas name
$initials = '';
if (isset($petugas)) {
    $parts = explode(' ', $petugas['nama_petugas']);
    foreach (array_slice($parts, 0, 2) as $p) {
        $initials .= strtoupper(substr($p, 0, 1));
    }
}
?>

<!-- ===== PROFILE BANNER ===== -->
<div class="profile-banner">
    <div style="position:relative; flex-shrink:0;">
        <?php if (!empty($petugas['foto'])): ?>
            <img src="<?= base_url('asset/foto_petugas/' . $petugas['foto']) ?>"
                 alt="Foto Profil"
                 style="width:80px; height:80px; border-radius:50%; object-fit:cover; border:3px solid rgba(255,255,255,0.4); display:block;">
        <?php else: ?>
            <div class="profile-avatar"><?= $initials ?></div>
        <?php endif; ?>
        <!-- Camera button -->
        <button onclick="bukaFoto()" title="Ganti Foto"
                style="position:absolute; bottom:0; right:0; width:26px; height:26px; background:#fff; border:none; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 2px 6px rgba(0,0,0,0.2);">
            <i class="fas fa-camera" style="font-size:0.65rem; color:#1e40af;"></i>
        </button>
    </div>
    <div class="profile-info">
        <h4><?= htmlspecialchars((string) $petugas['nama_petugas']) ?></h4>
        <div class="profile-meta">
            <span><i class="fas fa-id-card me-1"></i> NIP. <?= htmlspecialchars((string) $petugas['nip']) ?></span>
            <span><i class="fas fa-briefcase me-1"></i> <?= htmlspecialchars((string) $petugas['jabatan']) ?></span>
            <?php if (!empty($petugas['pangkat'])): ?>
                <span><i class="fas fa-layer-group me-1"></i> <?= htmlspecialchars((string) $petugas['pangkat']) ?></span>
            <?php endif; ?>
        </div>
        <span class="badge-role"><i class="fas fa-hard-hat me-1"></i> Petugas Tera</span>
    </div>
</div>

<!-- ===== CONTENT ===== -->
<div class="p-content">
