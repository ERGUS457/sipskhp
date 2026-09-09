<?php $this->load->view('layout/header', ['title' => 'Dashboard Petugas - E-TERA SKHP']); ?>
<?php $this->load->view('layout/navbar'); ?>

<style>
    .profil-header-card {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        border: none;
    }
    .avatar-circle {
        width: 80px;
        height: 80px;
        background-color: white;
        color: #1e3a8a;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .tugas-card {
        border: none;
        transition: all 0.3s ease;
    }
    .tugas-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
    }
    .search-pill {
        background-color: var(--color-card);
        border: 1px solid var(--color-border);
    }
    [data-theme='dark'] .search-pill input {
        color: var(--text-main);
    }
    
    /* Custom Radio Cards */
    .radio-card {
        cursor: pointer;
        border: 2px solid var(--color-border);
        border-radius: 12px;
        transition: all 0.2s ease-in-out;
        position: relative;
        background-color: var(--color-card);
    }
    .radio-card:hover {
        border-color: #6b7280;
        background-color: var(--color-bg-alt);
    }
    .radio-card.selected-sah {
        border-color: #10b981;
        background-color: rgba(16, 185, 129, 0.05);
    }
    .radio-card.selected-batal {
        border-color: #ef4444;
        background-color: rgba(239, 68, 68, 0.05);
    }
    .radio-card-icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }
    .radio-card-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 0;
    }
    /* Hidden native radio button */
    .radio-card input[type="radio"] {
        position: absolute;
        opacity: 0;
    }
    
    .modal-header-gradient {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        border-bottom: none;
    }
    .modal-header-gradient .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* Input spesifik styles */
    .input-timbangan-group {
        border-left: 4px solid #f59e0b; /* yellow warning */
        padding-left: 10px;
    }
    .input-pompa-group {
        border-left: 4px solid #ef4444; /* red danger */
        padding-left: 10px;
    }
</style>

<div class="container py-4">
    <!-- Header Profil -->
    <div class="card profil-header-card rounded-4 shadow-sm mb-5">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex flex-column flex-md-row align-items-center text-center text-md-start">
                <div class="avatar-circle mb-3 mb-md-0 me-md-4">
                    BU
                </div>
                <div>
                    <h2 class="fw-bold mb-1">Budi Utama, M.T.</h2>
                    <p class="mb-1" style="color: rgba(255,255,255,0.85);"><i class="fa-solid fa-id-badge me-2"></i>NIP. 19850101 201001 1 001</p>
                    <p class="mb-0" style="color: rgba(255,255,255,0.85);"><i class="fa-solid fa-briefcase me-2"></i>Petugas Penera Berhak</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Daftar -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <h3 class="fw-bold mb-0" style="color: var(--color-primary);">Daftar Penugasan Saya</h3>
        </div>
        <div class="col-md-6 d-flex justify-content-md-end">
            <div class="input-group search-pill rounded-pill overflow-hidden shadow-sm" style="max-width: 350px;">
                <input type="text" class="form-control border-0 shadow-none bg-transparent ps-4" placeholder="Cari nomor order...">
                <button class="btn btn-link text-muted border-0 shadow-none pe-4" type="button">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Tampilkan Daftar Tugas -->
    <div class="row g-4">
        
        <!-- Tugas 1 : Di Lapangan -->
        <div class="col-md-6 col-lg-4">
            <div class="card tugas-card rounded-4 shadow-sm h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge rounded-pill bg-warning text-dark px-3 py-2 fw-semibold shadow-sm">
                            <i class="fa-solid fa-person-walking me-1"></i> Di Lapangan
                        </span>
                        <span class="text-muted" style="font-size: 0.85rem;">Hari ini</span>
                    </div>
                    <h4 class="fw-bold mb-1 text-primary">#ORD-2026-001</h4>
                    <p class="text-muted mb-3" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-scale-balanced me-1"></i> Timbangan Sentisimal<br>
                        <i class="fa-solid fa-location-dot me-1 mt-2"></i> Ps. Alianyang, Singkawang
                    </p>
                    <div class="mt-auto pt-3 border-top" style="border-color: var(--color-border) !important;">
                        <!-- Tombol Input Hasil yang men-trigger modal -->
                        <button type="button" class="btn btn-primary w-100 rounded-pill fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalInputHasil" data-alat="timbangan" data-ord="ORD-2026-001">
                            <i class="fa-solid fa-pen-to-square me-2"></i>Input Hasil Uji
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tugas 2 : Di Lapangan -->
        <div class="col-md-6 col-lg-4">
            <div class="card tugas-card rounded-4 shadow-sm h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge rounded-pill bg-warning text-dark px-3 py-2 fw-semibold shadow-sm">
                            <i class="fa-solid fa-person-walking me-1"></i> Di Lapangan
                        </span>
                        <span class="text-muted" style="font-size: 0.85rem;">Hari ini</span>
                    </div>
                    <h4 class="fw-bold mb-1 text-primary">#ORD-2026-002</h4>
                    <p class="text-muted mb-3" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-gas-pump me-1"></i> Pompa Ukur BBM<br>
                        <i class="fa-solid fa-location-dot me-1 mt-2"></i> SPBU Sedau, Singkawang
                    </p>
                    <div class="mt-auto pt-3 border-top" style="border-color: var(--color-border) !important;">
                        <button type="button" class="btn btn-primary w-100 rounded-pill fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalInputHasil" data-alat="pompa" data-ord="ORD-2026-002">
                            <i class="fa-solid fa-pen-to-square me-2"></i>Input Hasil Uji
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tugas 3 : Selesai -->
        <div class="col-md-6 col-lg-4">
            <div class="card tugas-card rounded-4 shadow-sm h-100">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge rounded-pill bg-success px-3 py-2 fw-semibold shadow-sm text-white">
                            <i class="fa-solid fa-check-circle me-1"></i> Selesai
                        </span>
                        <span class="text-muted" style="font-size: 0.85rem;">Kemarin</span>
                    </div>
                    <h4 class="fw-bold mb-1 text-primary">#ORD-2026-003</h4>
                    <p class="text-muted mb-3" style="font-size: 0.9rem;">
                        <i class="fa-solid fa-weight-hanging me-1"></i> Anak Timbangan PK<br>
                        <i class="fa-solid fa-location-dot me-1 mt-2"></i> Toko Emas Sinar, Ps. Baru
                    </p>
                    <div class="mt-auto pt-3 border-top" style="border-color: var(--color-border) !important;">
                        <button type="button" class="btn btn-success w-100 rounded-pill fw-semibold shadow-sm text-white" disabled>
                            <i class="fa-solid fa-check-double me-2"></i>Sudah Diinput
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Input Hasil Premium -->
<div class="modal fade" id="modalInputHasil" tabindex="-1" aria-labelledby="modalInputHasilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header modal-header-gradient rounded-top-4 py-3">
                <h5 class="modal-title fw-bold" id="modalInputHasilLabel"><i class="fa-solid fa-clipboard-check me-2"></i> Form Hasil Pengujian <span id="judulOrderModal" class="ms-1 px-2 py-1 bg-white text-primary rounded-1" style="font-size: 0.85rem;">---</span></h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <?= form_open('dashboard/simpan_hasil', ['id' => 'formHasilUji']) ?>
            <div class="modal-body p-4">
                
                <p class="text-muted mb-3">Tentukan keputusan akhir kelayakan / kesahan (Sah/Batal) untuk alat ukur tersebut.</p>
                
                <!-- Custom Radio Cards Keputusan -->
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="radio-card p-3 text-center" id="cardSah">
                            <i class="fa-solid fa-circle-check radio-card-icon" style="color: #10b981;"></i>
                            <h5 class="radio-card-title text-success">SAH</h5>
                            <p class="text-muted mb-0" style="font-size: 0.8rem;">Sesuai toleransi</p>
                            <input type="radio" class="stretched-link" name="keputusan" id="radioSah" value="sah">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="radio-card p-3 text-center" id="cardBatal">
                            <i class="fa-solid fa-circle-xmark radio-card-icon" style="color: #ef4444;"></i>
                            <h5 class="radio-card-title text-danger">BATAL</h5>
                            <p class="text-muted mb-0" style="font-size: 0.8rem;">Melebihi BKD</p>
                            <input type="radio" class="stretched-link" name="keputusan" id="radioBatal" value="batal">
                        </div>
                    </div>
                </div>

                <hr style="border-color: var(--color-border); opacity: 1;">

                <!-- Form Spesifik Timbangan (Default disembunyikan) -->
                <div id="formMekanikTimbangan" style="display: none;">
                    <h6 class="fw-bold mb-3" style="color: #f59e0b;"><i class="fa-solid fa-scale-unbalanced me-2"></i>Parameter Uji Timbangan</h6>
                    <div class="input-timbangan-group mb-3">
                        <div class="mb-2">
                            <label class="form-label mb-1 fw-medium" style="font-size: 0.9rem;">Uji Kebenaran (Eror Maksimal) <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm shadow-sm">
                                <input type="number" step="0.01" class="form-control" name="error_timbangan" placeholder="Contoh: +0.5" required>
                                <span class="input-group-text">gram</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label mb-1 fw-medium" style="font-size: 0.9rem;">Uji Eksentrisitas <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm shadow-sm" name="eksentrisitas" placeholder="Keterangan singkat hasil eksentrisitas" required>
                        </div>
                    </div>
                </div>

                <!-- Form Spesifik Pompa BBM (Default disembunyikan) -->
                <div id="formNozzlePompa" style="display: none;">
                    <h6 class="fw-bold mb-3" style="color: #ef4444;"><i class="fa-solid fa-droplet me-2"></i>Parameter Uji Pompa Ukur BBM</h6>
                    <div class="input-pompa-group mb-3">
                        <div class="mb-2">
                            <label class="form-label mb-1 fw-medium" style="font-size: 0.9rem;">Kesalahan Penunjukan (20 L) <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm shadow-sm">
                                <span class="input-group-text">Bejana 1</span>
                                <input type="number" step="0.5" class="form-control" name="penunjukan_1" placeholder="mL" required>
                                <span class="input-group-text">Bejana 2</span>
                                <input type="number" step="0.5" class="form-control" name="penunjukan_2" placeholder="mL" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label mb-1 fw-medium" style="font-size: 0.9rem;">Totalisator Akhir <span class="text-danger">*</span></label>
                            <input type="number" class="form-control form-control-sm shadow-sm" name="totalisator" placeholder="Angka totalisator" required>
                        </div>
                    </div>
                </div>
                
                <!-- General Remarks -->
                <div class="mb-1 mt-3">
                    <label for="catatan" class="form-label fw-medium">Catatan / Keterangan Petugas</label>
                    <textarea class="form-control shadow-sm" id="catatan" name="catatan" rows="3" placeholder="Tuliskan catatan opsional.."></textarea>
                </div>

            </div>
            <div class="modal-footer bg-light rounded-bottom-4 border-top-0 py-3" style="background-color: var(--color-bg-alt) !important;">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="fa-solid fa-save me-2"></i>Simpan Laporan</button>
            </div>
            <?= form_close() ?>
            
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>

<script>
$(document).ready(function() {
    
    // Custom Radio Card Logic
    $('input[name="keputusan"]').change(function() {
        $('.radio-card').removeClass('selected-sah selected-batal');
        
        if($('#radioSah').is(':checked')) {
            $('#cardSah').addClass('selected-sah');
        } 
        else if($('#radioBatal').is(':checked')) {
            $('#cardBatal').addClass('selected-batal');
        }
    });

    // Pass data into modal & Trigger appropriate Form
    $('#modalInputHasil').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var jenisAlat = button.data('alat');
        var ordNumber = button.data('ord');
        
        var modal = $(this);
        modal.find('#judulOrderModal').text(ordNumber);
        
        // Reset forms & radios
        $('#formHasilUji')[0].reset();
        $('.radio-card').removeClass('selected-sah selected-batal');
        
        // Sembunyikan semua parameter spesifik lalu disable input required saat hide
        $('#formMekanikTimbangan, #formNozzlePompa').hide().find('input').prop('disabled', true);
        
        // Munculkan sesuai jenis
        if (jenisAlat === 'timbangan') {
            $('#formMekanikTimbangan').fadeIn().find('input').prop('disabled', false);
        } else if (jenisAlat === 'pompa') {
            $('#formNozzlePompa').fadeIn().find('input').prop('disabled', false);
        }
    });

    // Form Submit Interception via SweetAlert2 for demo
    $('#formHasilUji').submit(function(e) {
        e.preventDefault();
        
        // Cek radio terpilih
        if(!$('input[name="keputusan"]:checked').val()) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Belum Lengkap',
                text: 'Silakan pilih keputusan SAH atau BATAL terlebih dahulu.',
                confirmButtonColor: 'var(--color-primary)',
                background: 'var(--color-card)',
                color: 'var(--text-main)',
            });
            return;
        }

        // Demo Success Alert
        Swal.fire({
            title: 'Menyimpan Data...',
            text: 'Merekam hasil uji ke database',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            timer: 1500,
            background: 'var(--color-card)',
            color: 'var(--text-main)',
        }).then(() => {
            $('#modalInputHasil').modal('hide');
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data hasil pengujian berhasil disimpan.',
                icon: 'success',
                confirmButtonColor: 'var(--color-primary)',
                background: 'var(--color-card)',
                color: 'var(--text-main)',
            });
        });
    });
});
</script>
<?php $this->load->view('layout/dash_footer'); ?>