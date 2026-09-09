<?php $this->load->view('layout/header', ['title' => 'Dashboard Pemohon - SIAP SKHP TERA']); ?>
<?php $this->load->view('layout/nav_pemohon'); ?>

<style>
    body {
        background-color: #f0fdf4; /* Warna hijau sangat muda khas instansi yang fresh */
    }
    .pemohon-container {
        margin-top: 100px;
        margin-bottom: 60px;
        min-height: 70vh;
    }
    .title-wrapper {
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 15px;
        margin-bottom: 30px;
    }
    .welcome-banner {
        background: linear-gradient(135deg, var(--nav-primary) 0%, var(--nav-secondary) 100%);
        color: white;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(6, 95, 70, 0.2);
    }
    .welcome-banner h3 {
        font-weight: 700;
        margin-bottom: 10px;
        font-size: 1.8rem;
    }
    .welcome-banner p {
        font-size: 1.1rem;
        opacity: 0.9;
    }
    .btn-create-pengajuan {
        background-color: var(--nav-primary);
        color: white;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        transition: all 0.3s;
    }
    .btn-create-pengajuan:hover {
        background-color: var(--nav-secondary);
        color: white;
        transform: translateY(-2px);
    }
    .card-pengajuan {
        border: none;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.04);
        overflow: hidden;
    }
    .card-pengajuan .card-body {
        padding: 25px;
        background-color: white;
    }
    table.dataTable.table-hover > tbody > tr:hover > * {
        box-shadow: inset 0 0 0 9999px rgba(16, 185, 129, 0.05); /* Sedikit nuansa hijau saat baris di hover */
    }
    
    /* Styling khusus DataTables untuk Mobile agar rapi */
    table.dataTable td {
        white-space: normal !important;
        word-wrap: normal !important;
    }
    table.dataTable th {
        white-space: nowrap !important;
    }
    table.dataTable td, table.dataTable th {
        min-width: 120px;
    }
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
    
    .dt-container .row {
        margin-bottom: 10px;
    }
    .dt-search {
        text-align: right;
    }
    @media (max-width: 768px) {
        /* Mengecilkan font tabel di HP agar lebih proporsional */
        table.dataTable, table.dataTable th, table.dataTable td {
            font-size: 0.82rem !important;
        }
        table.dataTable th, table.dataTable td {
            padding: 0.5rem 0.4rem !important;
        }
        
        .dt-search {
            text-align: left !important;
            margin-top: 10px;
        }
        .dt-search label {
            width: 100%;
        }
        .dt-search input {
            width: 100% !important;
            margin-left: 0 !important;
            display: block;
        }
        .dt-length {
            margin-bottom: 10px;
        }
    }
</style>

<div class="container pemohon-container">
    <div class="welcome-banner">
        <h3>Selamat Datang, <?= htmlspecialchars((string) $this->session->userdata('username')) ?>!</h3>
        <p class="mb-0">Ini adalah halaman Dasbor Anda. Kelola dan pantau seluruh status pengajuan tera alat ukur/timbang instansi Anda di sini.</p>
    </div>

    <div class="title-wrapper d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <h2 class="fw-bold mb-0" style="color: var(--nav-primary);">DAFTAR PENGAJUAN SAYA</h2>
        <button type="button" class="btn btn-create-pengajuan" data-bs-toggle="modal" data-bs-target="#modalPengajuan">
            <i class="fas fa-plus-circle me-2"></i> Ajukan Alat Baru
        </button>
    </div>

    <?php
        $cnt_menunggu = 0;
        $cnt_diuji = 0;
        $cnt_selesai = 0;
        $cnt_tervalidasi = 0;

        foreach ($pengajuan as $row) {
            $is_tervalidasi = isset($row['status_validasi']) && $row['status_validasi'] === 'Tervalidasi';
            $is_selesai_uji = isset($row['status_uji']) && $row['status_uji'] === 'Selesai';
            $is_diproses    = !is_null($row['id_surat_tugas']);

            if ($is_tervalidasi) $cnt_tervalidasi++;
            elseif ($is_selesai_uji) $cnt_selesai++;
            elseif ($is_diproses) $cnt_diuji++;
            else $cnt_menunggu++;
        }
    ?>

    <div class="row g-4 mb-5">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body text-center p-4">
                    <i class="fas fa-hourglass-half fs-1 text-warning mb-3" style="opacity: 0.8;"></i>
                    <h2 class="fw-bold text-dark mb-1" style="font-size: 2.5rem;"><?= $cnt_menunggu ?></h2>
                    <p class="text-dark mb-0 fw-semibold" style="font-size: 0.95rem;">Menunggu Proses</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body text-center p-4">
                    <i class="fas fa-cogs fs-1 text-info mb-3" style="opacity: 0.8;"></i>
                    <h2 class="fw-bold text-dark mb-1" style="font-size: 2.5rem;"><?= $cnt_diuji ?></h2>
                    <p class="text-dark mb-0 fw-semibold" style="font-size: 0.95rem;">Sedang Diuji</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body text-center p-4">
                    <i class="fas fa-check-double fs-1 text-primary mb-3" style="opacity: 0.8;"></i>
                    <h2 class="fw-bold text-dark mb-1" style="font-size: 2.5rem;"><?= $cnt_selesai ?></h2>
                    <p class="text-dark mb-0 fw-semibold" style="font-size: 0.95rem;">Uji Selesai</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); transition: transform 0.3s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body text-center p-4">
                    <i class="fas fa-stamp fs-1 text-success mb-3" style="opacity: 0.8;"></i>
                    <h2 class="fw-bold text-dark mb-1" style="font-size: 2.5rem;"><?= $cnt_tervalidasi ?></h2>
                    <p class="text-dark mb-0 fw-semibold" style="font-size: 0.95rem;">Siap Cetak SKHP</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-2 mb-5">
        <a href="<?= site_url('pemohon-dashboard/pengajuan') ?>" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold shadow-sm">
            <i class="fas fa-list-ul me-2"></i> Lihat Semua Rincian Data
        </a>
    </div>
</div>

<!-- Modal Pengajuan -->
<div class="modal fade" id="modalPengajuan" tabindex="-1" aria-labelledby="modalPengajuanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--nav-primary) 0%, var(--nav-secondary) 100%); color: white; border-radius: 12px 12px 0 0;">
                <h5 class="modal-title fw-bold" id="modalPengajuanLabel"><i class="fas fa-plus-circle me-2"></i> Form Pengajuan Tera Alat UTTP</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('pemohon-dashboard/tambah_pengajuan') ?>" method="POST">
                <div class="modal-body p-4">
                    <div class="alert alert-info" style="font-size: 0.9rem;">
                        <i class="fas fa-info-circle me-2"></i> Pilih Jenis Alat UTTP terlebih dahulu. Form akan menyesuaikan data spesifik yang dibutuhkan.
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-primary">Jenis Alat UTTP <span class="text-danger">*</span></label>
                        <select class="form-select border-primary" name="jenis_alat" id="jenis_alat" required onchange="toggleFields()">
                            <option value="">-- Pilih Jenis Alat --</option>
                            <option value="Timbangan Umum">Timbangan Umum (Meja, Duduk, dll)</option>
                            <option value="Timbangan Berat Badan">Timbangan Berat Badan</option>
                            <option value="Alat Ukur Panjang">Alat Ukur Panjang</option>
                            <option value="Pompa Ukur BBM (SPBU)">Pompa Ukur BBM (SPBU)</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama / Detail Alat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_alat" required placeholder="Contoh: Timbangan Meja / SPBU No. 1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Merk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="merk" required placeholder="Merk Alat">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Tipe / Model <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="tipe_model" required placeholder="Tipe/Model">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Nomor Seri <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nomor_seri" required placeholder="Nomor Seri">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Buatan (Negara/Pabrik) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="buatan" required placeholder="Buatan">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Jumlah (Unit) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="jumlah" required min="1" value="1">
                        </div>
                    </div>

                    <!-- Field Khusus Umum / Panjang / Berat Badan -->
                    <div class="row" id="field_umum" style="display: none; background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 10px;">
                        <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-sliders-h me-2"></i>Spesifikasi <span id="label_umum">Alat</span></h6>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Kapasitas / Daya Baca <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="kapasitas" id="input_kapasitas" placeholder="Contoh: 100 kg / 50 g">
                        </div>
                    </div>

                    <!-- Field Khusus SPBU -->
                    <div class="row" id="field_spbu" style="display: none; background-color: #e0f2fe; padding: 15px; border-radius: 8px; margin-top: 10px;">
                        <h6 class="fw-bold text-info mb-3"><i class="fas fa-gas-pump me-2"></i>Spesifikasi Pompa Ukur BBM (SPBU)</h6>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Kecepatan Alir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="kecepatan_alir" id="input_kecepatan" placeholder="Contoh: 40 L/min">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">No. Pulau - No. Mesin <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="no_pulau_mesin" id="input_pulau" placeholder="Contoh: P.1 - 1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">No. Nozel - Jenis BBM <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="no_nozel_bbm" id="input_nozel" placeholder="Contoh: A-Solar">
                        </div>
                    </div>

                </div>
                <div class="modal-footer" style="background-color: #f8f9fa; border-radius: 0 0 12px 12px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn" style="background-color: var(--nav-primary); color: white;"><i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Pastikan jQuery & DataTables sudah dipanggil di header atau tambahkan manual jika diperlukan -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#tablePengajuan').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json',
        }
    });

});

function toggleFields() {
    var jenis = document.getElementById('jenis_alat').value;
    var fieldUmum = document.getElementById('field_umum');
    var fieldSpbu = document.getElementById('field_spbu');
    var labelUmum = document.getElementById('label_umum');
    
    // reset required
    document.getElementById('input_kapasitas').required = false;
    document.getElementById('input_kecepatan').required = false;
    document.getElementById('input_pulau').required = false;
    document.getElementById('input_nozel').required = false;

    if (jenis === 'Pompa Ukur BBM (SPBU)') {
        fieldSpbu.style.display = 'flex';
        fieldUmum.style.display = 'none';
        
        document.getElementById('input_kecepatan').required = true;
        document.getElementById('input_pulau').required = true;
        document.getElementById('input_nozel').required = true;
    } else if (jenis !== '') {
        fieldUmum.style.display = 'flex';
        fieldSpbu.style.display = 'none';
        labelUmum.innerText = jenis;
        
        document.getElementById('input_kapasitas').required = true;
    } else {
        fieldUmum.style.display = 'none';
        fieldSpbu.style.display = 'none';
    }
}
</script>

<?php $this->load->view('layout/footer'); ?>

<script>
    // Tampilkan SweetAlert jika ada flashdata success
    <?php if ($this->session->flashdata('success')) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= $this->session->flashdata('success') ?>',
            confirmButtonColor: '#065f46',
            confirmButtonText: 'OKE'
        });
    <?php endif; ?>

    // Tampilkan SweetAlert jika ada flashdata error
    <?php if ($this->session->flashdata('error')) : ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?= $this->session->flashdata('error') ?>',
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Tutup'
        });
    <?php endif; ?>
</script>
