<?php $this->load->view('layout/dash_header'); ?>
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white shadow-sm border-0 rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="avatar bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-4 shadow" style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: bold;">
                    <?= strtoupper(substr(htmlspecialchars((string) $profil['nama_petugas']), 0, 1)) ?>
                </div>
                <div>
                    <h4 class="mb-1 fw-bold">Selamat Bertugas, <?= htmlspecialchars((string) $profil['nama_petugas']) ?>!</h4>
                    <p class="mb-0 text-white-50"><i class="fas fa-id-card me-2"></i>NIP: <?= htmlspecialchars((string) $profil['nip']) ?> | <i class="fas fa-briefcase mx-2"></i><?= htmlspecialchars((string) $profil['jabatan']) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mb-4 gap-3">
    <div>
        <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-clipboard-list text-primary me-2"></i>Daftar Penugasan Saya</h5>
        <p class="text-muted mb-0">Daftar tempat dan alat UTTP penugasan Anda.</p>
    </div>
    <div class="flex-grow-1" style="max-width: 350px;">
        <div class="input-group shadow-sm rounded-pill overflow-hidden">
            <span class="input-group-text bg-white border-end-0 text-muted px-3"><i class="fas fa-search"></i></span>
            <input type="text" id="searchTugas" class="form-control border-start-0 ps-0" placeholder="Cari pemohon, nomor order, alat...">
        </div>
    </div>
</div>

<div class="row">
    <?php if (empty($tugas)): ?>
        <div class="col-12">
            <div class="alert alert-light border shadow-sm text-center p-5 rounded-4">
                <i class="fas fa-check-double fa-3x text-success mb-3"></i>
                <h5 class="fw-bold text-secondary">Semua Tugas Selesai!</h5>
                <p class="text-muted mb-0">Belum ada surat tugas baru yang ditugaskan kepada Anda saat ini.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach($tugas as $t): ?>
            <div class="col-md-6 col-lg-4 mb-4 tugas-card">
                <div class="card border-0 shadow-sm rounded-4 h-100 table-hover">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary border rounded-pill px-3 py-2">
                                <i class="fas fa-calendar-alt me-2"></i><?= date('d M Y', strtotime($t['tanggal_tugas'])) ?>
                            </span>
                            <?php if ($t['status_tugas'] === 'Selesai'): ?>
                                <span class="badge bg-success ms-auto rounded-pill px-3 py-2"><i class="fas fa-check me-1"></i>Selesai</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark ms-auto rounded-pill px-3 py-2"><i class="fas fa-clock me-1"></i>Di Lapangan</span>
                            <?php endif; ?>
                        </div>
                        
                        <h5 class="fw-bold text-dark mb-1">Order: <?= htmlspecialchars((string) $t['nomor_order']) ?></h5>
                        <p class="text-secondary fw-semibold mb-3"><i class="fas fa-building me-2 text-muted"></i><?= htmlspecialchars((string) $t['nama_pemilik']) ?></p>

                        <div class="bg-light p-3 rounded-4 mb-3 border">
                            <small class="text-muted d-block mb-1">Merek/Alat UTTP</small>
                            <span class="fw-bold text-dark"><?= htmlspecialchars((string) $t['nama_alat']) ?></span>
                        </div>

                        <?php if ($t['status_tugas'] !== 'Selesai'): ?>
                            <button class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm btn-input-hasil" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalInputHasil"
                                    data-id-surat="<?= $t['id_surat_tugas'] ?>"
                                    data-id-alat="<?= $t['id_alat'] ?? '' ?>"
                                    data-nama-alat="<?= htmlspecialchars((string) $t['nama_alat']) ?>"
                                    data-detail='<?= htmlspecialchars($t['detail_spesifik'] ?? '{}', ENT_QUOTES, 'UTF-8') ?>'>
                                <i class="fas fa-upload me-2"></i> Input Hasil / Laporan
                            </button>
                        <?php else: ?>
                            <button class="btn btn-success w-100 rounded-pill fw-bold shadow-sm btn-detail-hasil" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalDetailHasil"
                                    data-id-alat="<?= $t['id_alat'] ?>">
                                <i class="fas fa-search me-2"></i> Lihat Detail Laporan
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <div class="col-12 text-center py-5" id="noTugasFound" style="display: none;">
        <i class="fas fa-search-minus fa-3x text-muted mb-3 d-block"></i>
        <h5 class="text-secondary fw-bold">Penugasan Tidak Ditemukan</h5>
        <p class="text-muted mb-0">Tidak ada kartu tugas atau nama pemohon yang sesuai dengan kata pencarian Anda.</p>
    </div>
</div>

<!-- Modal Input Hasil Pengujian -->
<div class="modal fade" id="modalInputHasil" tabindex="-1" aria-labelledby="modalInputHasilLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header text-white border-0 py-3" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                <h5 class="modal-title fw-bold" id="modalInputHasilLabel"><i class="fas fa-microscope me-2"></i>Formulir Input Hasil Uji</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('petugas-dashboard/simpan-hasil') ?>" method="post">
                <div class="modal-body p-4 bg-light">
                    
                    <input type="hidden" name="id_surat_tugas" id="id_surat_tugas">
                    <input type="hidden" name="id_alat" id="id_alat">
                    
                    <div class="alert alert-primary bg-primary bg-opacity-10 border-primary border-opacity-25 rounded-3 mb-4">
                        <i class="fas fa-info-circle me-2"></i>Anda sedang menginput hasil uji untuk alat: <strong id="label_nama_alat"></strong>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="tgl_pengujian" class="form-label fw-bold text-secondary">Tanggal Pengujian <span class="text-danger">*</span></label>
                            <input type="date" class="form-control form-control-lg border-light-subtle shadow-sm" id="tgl_pengujian" name="tgl_pengujian" value="<?= date('Y-m-d') ?>" required
                                   oninvalid="this.setCustomValidity('Harap tentukan tanggal pengujian')" oninput="this.setCustomValidity('')">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="suhu_dasar" class="form-label fw-bold text-secondary">Suhu Kondisi Uji (°C) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg border-light-subtle shadow-sm" id="suhu_dasar" name="suhu_dasar" required placeholder="Contoh: 28.5"
                                   oninvalid="this.setCustomValidity('Harap isi suhu dasar saat pengujian')" oninput="this.setCustomValidity('')">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="metode_uji" class="form-label fw-bold text-secondary">Metode Pengujian <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg border-light-subtle shadow-sm" id="metode_uji" name="metode_uji" required placeholder="Contoh: CORD.123"
                                   oninvalid="this.setCustomValidity('Harap isi metode uji yang digunakan')" oninput="this.setCustomValidity('')">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="standar_uji" class="form-label fw-bold text-secondary">Standar Acuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg border-light-subtle shadow-sm" id="standar_uji" name="standar_uji" required placeholder="Contoh: AT.001"
                                   oninvalid="this.setCustomValidity('Harap isi standar acuan uji')" oninput="this.setCustomValidity('')">
                        </div>
                        
                        <div class="col-12 mt-4">
                            <label class="form-label fw-bold text-dark d-block mb-3 fs-5 border-bottom pb-2">Kesimpulan Hasil Uji (Berdasarkan Capan Tanda Tera) <span class="text-danger">*</span></label>
                            <div class="d-flex gap-4">
                                <div class="form-check border p-3 rounded-3 shadow-sm bg-white flex-fill text-center position-relative" style="cursor: pointer;">
                                    <input class="form-check-input float-none mx-auto d-block mb-2" type="radio" name="hasil_uji" id="hasil_sah" value="Sah" required style="width:1.5rem; height:1.5rem;"
                                           oninvalid="this.setCustomValidity('Pilih salah satu kesimpulan hasil uji (Sah/Batal)')" onclick="document.querySelectorAll('input[name=hasil_uji]').forEach(el => el.setCustomValidity(''));">
                                    <label class="form-check-label fw-bold text-success fs-5 stretched-link" for="hasil_sah">
                                        <i class="fas fa-check-circle me-1"></i> SAH
                                    </label>
                                </div>
                                <div class="form-check border p-3 rounded-3 shadow-sm bg-white flex-fill text-center position-relative" style="cursor: pointer;">
                                    <input class="form-check-input float-none mx-auto d-block mb-2" type="radio" name="hasil_uji" id="hasil_batal" value="Batal" required style="width:1.5rem; height:1.5rem;"
                                           oninvalid="this.setCustomValidity('Pilih salah satu kesimpulan hasil uji (Sah/Batal)')" onclick="document.querySelectorAll('input[name=hasil_uji]').forEach(el => el.setCustomValidity(''));">
                                    <label class="form-check-label fw-bold text-danger fs-5 stretched-link" for="hasil_batal">
                                        <i class="fas fa-times-circle me-1"></i> BATAL
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Spesifik Timbangan -->
                        <div class="col-12 mt-3 p-3 bg-warning bg-opacity-10 rounded-3 border border-warning specific-fields" id="timbangan_fields" style="display: none;">
                            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-balance-scale me-2 text-warning"></i>Detail Spesifikasi Timbangan</h6>
                            <div class="row g-2">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label text-dark fw-semibold">Kapasitas Nominal</label>
                                    <input type="text" class="form-control border-warning shadow-sm field-input" id="kapasitas_nominal" name="kapasitas_nominal" placeholder="Misal: 30 kg">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label text-dark fw-semibold">Kelas Ketelitian</label>
                                    <select class="form-select border-warning shadow-sm field-input" id="kelas_ketelitian" name="kelas_ketelitian">
                                        <option value="" disabled selected>-- Pilih Kelas --</option>
                                        <option value="Kelas I (Khusus)">Kelas I (Khusus)</option>
                                        <option value="Kelas II (Halus)">Kelas II (Halus)</option>
                                        <option value="Kelas III (Biasa)">Kelas III (Biasa)</option>
                                        <option value="Kelas IIII (Biasa)">Kelas IIII (Biasa)</option>
                                    </select>
                                </div>
                            </div>
                            <small class="text-secondary mt-2 d-block"><i class="fas fa-info-circle me-1"></i>Lengkapi atau perbaiki spesifikasi dari Pemohon jika salah.</small>
                        </div>

                        <!-- Kolom Spesifik Pompa Ukur BBM -->
                        <div class="col-12 mt-3 p-3 bg-danger bg-opacity-10 rounded-3 border border-danger specific-fields" id="spbu_fields" style="display: none;">
                            <h6 class="fw-bold text-danger mb-3"><i class="fas fa-gas-pump me-2"></i>Detail Spesifikasi Pompa / SPBU</h6>
                            <div class="row g-2">
                                <div class="col-md-4 mb-2">
                                    <label class="form-label fw-semibold text-danger">Nomor Pulau</label>
                                    <input type="text" class="form-control shadow-sm field-input" id="nomor_pulau" name="nomor_pulau">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label fw-semibold text-danger">Kecepatan Alir</label>
                                    <input type="text" class="form-control shadow-sm field-input" id="kecepatan_alir" name="kecepatan_alir">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label fw-semibold text-danger">Jumlah Nozzle</label>
                                    <input type="number" class="form-control shadow-sm field-input" id="jumlah_nozzle" name="jumlah_nozzle">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold text-danger">Produk BBM</label>
                                    <input type="text" class="form-control shadow-sm field-input" id="daftar_produk_bbm" name="daftar_produk_bbm">
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Spesifik Alat Titik Ukur / Panjang -->
                        <div class="col-12 mt-3 p-3 bg-success bg-opacity-10 rounded-3 border border-success specific-fields" id="tinggi_fields" style="display: none;">
                            <h6 class="fw-bold text-success mb-3"><i class="fas fa-ruler-vertical me-2"></i>Detail Alat Ukur / Meteran</h6>
                            <div class="row g-2">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label fw-semibold text-success">Kapasitas Ukur Maks</label>
                                    <input type="text" class="form-control shadow-sm border-success field-input" id="kapasitas_ukur" name="kapasitas_ukur">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label fw-semibold text-success">Material/Bahan</label>
                                    <input type="text" class="form-control shadow-sm border-success field-input" id="jenis_bahan" name="jenis_bahan">
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Spesifik Alat Takaran -->
                        <div class="col-12 mt-3 p-3 bg-info bg-opacity-10 rounded-3 border border-info specific-fields" id="takaran_fields" style="display: none;">
                            <h6 class="fw-bold text-info mb-3"><i class="fas fa-flask me-2"></i>Detail Spesifikasi Takaran</h6>
                            <div class="row g-2">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label fw-semibold text-info">Kapasitas Takaran</label>
                                    <input type="text" class="form-control shadow-sm border-info field-input" id="kapasitas_takaran" name="kapasitas_takaran">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label fw-semibold text-info">Kegunaan Cairan</label>
                                    <input type="text" class="form-control shadow-sm border-info field-input" id="jenis_cairan" name="jenis_cairan">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-white p-3">
                    <button type="button" class="btn btn-light px-4 rounded-pill fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold shadow-sm"><i class="fas fa-save me-2"></i>Kirim Hasil Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Hasil Pengujian -->
<div class="modal fade" id="modalDetailHasil" tabindex="-1" aria-labelledby="modalDetailHasilLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header text-white border-0 py-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <h5 class="modal-title fw-bold" id="modalDetailHasilLabel"><i class="fas fa-clipboard-check me-2"></i>Detail Rekam Jejak Laporan Uji</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light" id="modalDetailHasilBody">
                <div class="text-center p-5">
                    <div class="spinner-border text-success" role="status"><span class="visually-hidden">Loading...</span></div>
                    <p class="mt-2 text-muted">Mengambil data laporan...</p>
                </div>
            </div>
            <div class="modal-footer border-0 bg-white p-3">
                <button type="button" class="btn btn-secondary px-4 rounded-pill fw-semibold shadow-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    .table-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
        transition: all 0.3s ease;
    }
    
    /* Hover effects for radio cards */
    .form-check.border:hover {
        border-color: #3b82f6 !important;
        background-color: #eff6ff !important;
    }
</style>


<script>
    $(document).ready(function() {
        // Pass data ke modal input
        $('.btn-input-hasil').on('click', function() {
            const id_surat = $(this).data('id-surat');
            const id_alat = $(this).data('id-alat');
            const nama_alat = $(this).data('nama-alat');
            const raw_detail = $(this).attr('data-detail');
            let det = {};
            try { det = JSON.parse(raw_detail); } catch(e) {}

            $('#id_surat_tugas').val(id_surat);
            $('#id_alat').val(id_alat);
            $('#label_nama_alat').text(nama_alat);

            // Sembunyikan & bersihkan semua target fields
            $('.specific-fields').hide();
            $('.field-input').val('').prop('required', false);

            if (nama_alat === 'Timbangan') {
                $('#timbangan_fields').show();
                $('#kapasitas_nominal').val(det.kapasitas_nominal || '');
                $('#kelas_ketelitian').val((det.kelas_ketelitian && det.kelas_ketelitian !== '-') ? det.kelas_ketelitian : '');
            } else if (nama_alat === 'Pompa Ukur BBM') {
                $('#spbu_fields').show();
                $('#nomor_pulau').val(det.nomor_pulau || '');
                $('#kecepatan_alir').val(det.kecepatan_alir || '');
                $('#jumlah_nozzle').val(det.jumlah_nozzle || '');
                $('#daftar_produk_bbm').val(det.daftar_produk_bbm || '');
            } else if (nama_alat === 'Alat Ukur Tinggi') {
                $('#tinggi_fields').show();
                $('#kapasitas_ukur').val(det.kapasitas_ukur || '');
                $('#jenis_bahan').val(det.jenis_bahan || '');
            } else if (nama_alat === 'Alat Takaran') {
                $('#takaran_fields').show();
                $('#kapasitas_takaran').val(det.kapasitas_takaran || '');
                $('#jenis_cairan').val(det.jenis_cairan || '');
            }
        });

        // AJAX untuk modal Detail
        const detailModal = document.getElementById('modalDetailHasil');
        const detailModalBody = document.getElementById('modalDetailHasilBody');

        detailModal.addEventListener('show.bs.modal', function (event) {
            detailModalBody.innerHTML = `<div class="text-center p-5"><div class="spinner-border text-success" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2 text-muted">Mengambil data laporan...</p></div>`;
            const button = event.relatedTarget;
            const idAlat = button.getAttribute('data-id-alat');

            $.ajax({
                url: '<?= site_url('petugas-dashboard/detail/') ?>' + idAlat,
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    let hasilUjiBadge = data.hasil_uji === 'Sah'
                        ? '<span class="badge bg-success px-3 py-2 rounded-pill"><i class="fas fa-check-circle me-1"></i>Sah (Lulus)</span>'
                        : '<span class="badge bg-danger px-3 py-2 rounded-pill"><i class="fas fa-times-circle me-1"></i>Batal (Gagal)</span>';

                    let html = '<h6 class="fw-bold text-success"><i class="fas fa-info-circle me-2"></i>Informasi Alat & Pemohon</h6>';
                    html += '<dl class="row">';
                    html += '<dt class="col-sm-4 text-secondary">Pemohon</dt><dd class="col-sm-8 fw-semibold">' + (data.nama_pemilik || '-') + '</dd>';
                    html += '<dt class="col-sm-4 text-secondary">Jenis Alat</dt><dd class="col-sm-8 fw-semibold">' + (data.nama_alat || '-') + '</dd>';
                    html += '<dt class="col-sm-4 text-secondary">Merk / Tipe</dt><dd class="col-sm-8">' + (data.merk || '-') + ' / ' + (data.tipe_model || '-') + '</dd>';
                    html += '</dl>';

                    if (data.detail_spesifik) {
                        html += '<hr><h6 class="fw-bold text-success"><i class="fas fa-cog me-2"></i>Detail Spesifik Tersimpan</h6><dl class="row">';
                        for (const [key, value] of Object.entries(data.detail_spesifik)) {
                            const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                            html += '<dt class="col-sm-4 text-secondary">' + label + '</dt><dd class="col-sm-8 fw-semibold">' + (value || '-') + '</dd>';
                        }
                        html += '</dl>';
                    }

                    html += '<hr><h6 class="fw-bold text-dark"><i class="fas fa-clipboard-list me-2"></i>Data Laporan Pengujian Anda</h6><dl class="row">';
                    html += '<dt class="col-sm-4 text-secondary">Tanggal Uji</dt><dd class="col-sm-8 fw-semibold">' + new Date(data.tgl_pengujian).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }) + '</dd>';
                    html += '<dt class="col-sm-4 text-secondary">Metode Uji</dt><dd class="col-sm-8">' + (data.metode_uji || '-') + '</dd>';
                    html += '<dt class="col-sm-4 text-secondary">Standar Uji</dt><dd class="col-sm-8">' + (data.standar_uji || '-') + '</dd>';
                    html += '<dt class="col-sm-4 text-secondary">Suhu Kondisi</dt><dd class="col-sm-8">' + (data.suhu_dasar || '-') + ' °C</dd>';
                    html += '<dt class="col-sm-4 text-secondary">Kesimpulan</dt><dd class="col-sm-8 mt-2">' + hasilUjiBadge + '</dd>';
                    
                    let statusColor = data.status_validasi === 'Tervalidasi' ? 'bg-primary' : 'bg-warning text-dark';
                    let statusIcon = data.status_validasi === 'Tervalidasi' ? 'fa-check-double' : 'fa-hourglass-half';
                    html += '<dt class="col-sm-4 text-secondary mt-2">Status Validasi Ka. UPT</dt><dd class="col-sm-8 mt-2"><span class="badge ' + statusColor + ' px-3 py-2 rounded-pill"><i class="fas ' + statusIcon + ' me-1"></i>' + data.status_validasi + '</span></dd>';
                    html += '</dl>';

                    detailModalBody.innerHTML = html;
                },
                error: function() {
                    detailModalBody.innerHTML = '<div class="alert alert-danger text-center"><i class="fas fa-exclamation-triangle fa-2x mb-2 d-block"></i>Gagal memuat data detail riwayat. Silakan coba lagi.</div>';
                }
            });
        });
        
        // Cek dan tampilkan SWAL jika ada flash object session success/error dari controller
        <?php if ($this->session->flashdata('success')) : ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?= $this->session->flashdata('success') ?>',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'OKE'
            });
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')) : ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '<?= $this->session->flashdata('error') ?>',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Tutup'
            });
        <?php endif; ?>

        // Fitur Pencarian Dinamis untuk Petugas Dashboard
        $('#searchTugas').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            var matches = 0;
            
            // Filter card tugas
            $('.tugas-card').filter(function() {
                var toggle = $(this).text().toLowerCase().indexOf(value) > -1;
                $(this).toggle(toggle);
                if (toggle) matches++;
            });
            
            // Tampilkan pesan kosong jika tidak ada yang ditemukan
            if (matches === 0 && value !== '') {
                $('#noTugasFound').show();
            } else {
                $('#noTugasFound').hide();
            }
        });
    });
</script>
<?php $this->load->view('layout/dash_footer'); ?>