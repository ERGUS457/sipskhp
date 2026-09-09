<?php $this->load->view('layout/dash_header'); ?>
<style>
    .title-wrapper {
        border-top: 2px solid #dee2e6;
        border-bottom: 2px solid #dee2e6;
        padding: 10px 0;
        margin-bottom: 30px;
    }
    .stat-card {
        border: none;
        border-radius: 12px;
        color: #fff;
    }
    .stat-card .card-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .stat-card .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
    }
    .stat-card .stat-icon {
        font-size: 3.5rem;
        opacity: 0.3;
    }
</style>

<div class="title-wrapper">
    <h2 class="fw-bold">DASHBOARD</h2>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card stat-card bg-primary"><div class="card-body"><div><h5 class="card-title">Jumlah Pengajuan</h5><p class="stat-number"><?= $jumlah_pengajuan ?></p></div><i class="fas fa-file-alt stat-icon"></i></div></div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card bg-success"><div class="card-body"><div><h5 class="card-title">Total SKHP</h5><p class="stat-number"><?= $total_skhp ?></p></div><i class="fas fa-certificate stat-icon"></i></div></div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card bg-warning text-dark"><div class="card-body"><div><h5 class="card-title">Jumlah Pemohon</h5><p class="stat-number"><?= $jumlah_pemohon ?></p></div><i class="fas fa-users stat-icon"></i></div></div>
    </div>
</div>
<?php $this->load->view('layout/dash_footer'); ?>