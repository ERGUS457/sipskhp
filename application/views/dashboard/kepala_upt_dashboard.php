<?php $this->load->view('layout/dash_header'); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Dashboard Kepala UPT</h4>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card text-white bg-warning shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-2">Tinjauan Validasi</h6>
                        <h3 class="fw-bold"><?= $menunggu_validasi ?></h3>
                        <p class="card-text small mb-0">Tugas Baru</p>
                    </div>
                    <i class="fas fa-clipboard-check fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-secondary shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-2">Histori Laporan</h6>
                        <h3 class="fw-bold"><?= $total_laporan ?></h3>
                        <p class="card-text small mb-0">Selesai</p>
                    </div>
                    <i class="fas fa-folder-open fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-2">Sertifikat (SKHP)</h6>
                        <h3 class="fw-bold"><?= $total_skhp_terbit ?></h3>
                        <p class="card-text small mb-0">SKHP Terbit</p>
                    </div>
                    <i class="fas fa-stamp fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-2">Total Pemohon</h6>
                        <h3 class="fw-bold"><?= $pemohon_aktif ?></h3>
                        <p class="card-text small mb-0">Pengguna</p>
                    </div>
                    <i class="fas fa-users fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <i class="fas fa-check-double fa-3x text-primary mb-3"></i>
                <h5 class="card-title fw-bold">Otorisasi Hasil Uji</h5>
                <p class="card-text text-muted">Lihat dan konfirmasi (Terima) draf laporan dari Petugas Lapangan untuk validasi final.</p>
                <a href="<?= site_url('validasi-skhp') ?>" class="btn btn-primary mt-3">Buka Menu Otorisasi</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <i class="fas fa-file-invoice fa-3x text-secondary mb-3"></i>
                <h5 class="card-title fw-bold">Laporan Rekapitulasi</h5>
                <p class="card-text text-muted">Akses dan arsip laporan rekapitulasi seluruh pengujian serta cetak SKHP dari alat yang lolos (Sah).</p>
                <a href="<?= site_url('laporan') ?>" class="btn btn-secondary mt-3">Lihat Buku Laporan</a>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('layout/dash_footer'); ?>