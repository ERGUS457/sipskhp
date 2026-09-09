<?php $this->load->view('layout/dash_header'); ?>

<style>
.pill-tervalidasi { background:#dcfce7; color:#16a34a; padding:4px 14px; border-radius:20px; font-size:.8rem; font-weight:600; display:inline-block; }
.pill-sah   { background:#dcfce7; color:#16a34a; padding:4px 12px; border-radius:20px; font-size:.8rem; font-weight:600; display:inline-block; }
.pill-batal { background:#fee2e2; color:#dc2626; padding:4px 12px; border-radius:20px; font-size:.8rem; font-weight:600; display:inline-block; }
</style>

<div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-stamp text-primary me-2"></i>Penerbitan SKHP</h4>
        <p class="text-muted mb-0">Daftar alat UTTP yang siap diterbitkan Sertifikat/Surat Keterangan Hasil Pengujian (SKHP).</p>
    </div>
</div>

<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="fas fa-check-circle me-2"></i><?= $this->session->flashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i><?= $this->session->flashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (empty($list)): ?>
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body text-center py-5">
        <i class="fas fa-certificate fa-3x text-muted mb-3 d-block"></i>
        <h5 class="fw-semibold text-muted">Belum Ada Data Tervalidasi</h5>
        <p class="text-muted">Daftar ini akan terisi setelah Kepala UPT memvalidasi hasil pengujian dari menu <strong>Otorisasi Hasil Uji</strong>.</p>
    </div>
</div>
<?php else: ?>
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom border-light pt-4 pb-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fw-bold"><i class="fas fa-list text-primary me-2"></i>Daftar Siap SKHP</h5>
        <span class="badge bg-success rounded-pill px-3 py-2"><?= count($list) ?> Alat Tervalidasi</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="skhpTable" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">No</th>
                        <th>Alat UTTP</th>
                        <th>No. Seri</th>
                        <th>Pemohon</th>
                        <th>Tgl. Uji</th>
                        <th>Hasil Uji</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($list as $row): ?>
                    <tr>
                        <td class="px-4 fw-bold"><?= $no++ ?></td>
                        <td>
                            <span class="d-block fw-semibold text-dark"><?= htmlspecialchars((string)$row['nama_alat']) ?></span>
                            <small class="text-muted"><?= htmlspecialchars((string)$row['merk']) ?><?= !empty($row['kapasitas']) ? ' | ' . htmlspecialchars((string)$row['kapasitas']) : '' ?></small>
                        </td>
                        <td><span class="badge bg-light text-dark border px-3"><?= htmlspecialchars((string)($row['nomor_seri'] ?: '-')) ?></span></td>
                        <td>
                            <span class="d-block fw-semibold"><?= htmlspecialchars((string)$row['nama_pemilik']) ?></span>
                            <small class="text-muted"><?= htmlspecialchars((string)($row['jenis_usaha'] ?: '-')) ?></small>
                        </td>
                        <td><?= !empty($row['tgl_pengujian']) ? date('d M Y', strtotime($row['tgl_pengujian'])) : '-' ?></td>
                        <td>
                            <?php if ($row['hasil_uji'] === 'Sah'): ?>
                                <span class="pill-sah"><i class="fas fa-check-circle me-1"></i> Sah</span>
                            <?php else: ?>
                                <span class="pill-batal"><i class="fas fa-times-circle me-1"></i> Batal</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="pill-tervalidasi"><i class="fas fa-stamp me-1"></i> Tervalidasi KaUPT</span></td>
                        <td class="text-center">
                            <?php if (($row['hasil_uji'] ?? '') === 'Sah'): ?>
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button"
                                        class="btn btn-success btn-sm rounded-pill px-3 fw-bold shadow-sm btn-pilih-petugas"
                                        data-id-alat="<?= $row['id_alat'] ?>"
                                        data-mode="cetak">
                                        <i class="fas fa-certificate me-1"></i> Cetak SKHP
                                    </button>
                                    <button type="button"
                                        class="btn btn-danger btn-sm rounded-pill px-3 fw-bold shadow-sm btn-pilih-petugas"
                                        data-id-alat="<?= $row['id_alat'] ?>"
                                        data-mode="pdf">
                                        <i class="fas fa-file-pdf me-1"></i> Export PDF
                                    </button>
                                </div>
                            <?php else: ?>
                                <span class="text-muted small">Tidak ada SKHP</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ===== MODAL PILIH PETUGAS ===== -->
<div class="modal fade" id="modalPilihPetugas" tabindex="-1" aria-labelledby="modalPilihPetugasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="modalPilihPetugasLabel">
                    <i class="fas fa-user-check me-2"></i>Pilih Petugas Pelaksana
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">
                    <i class="fas fa-info-circle me-1 text-primary"></i>
                    Pilih satu petugas yang akan dicantumkan pada kolom <strong>"Dilaksanakan Oleh"</strong> di SKHP.
                </p>
                <div id="loadingPetugas" class="text-center py-3">
                    <div class="spinner-border text-success" role="status"></div>
                    <div class="mt-2 text-muted small">Memuat data petugas...</div>
                </div>
                <div id="wrapperSelectPetugas" style="display:none;">
                    <label class="form-label fw-bold text-secondary">Petugas Pelaksana</label>
                    <select id="selectPetugas" class="form-select rounded-3 border-light-subtle">
                    </select>
                    <div id="infoPetugasTerpilih" class="mt-3 p-3 bg-light rounded-3 small" style="display:none;">
                        <div><i class="fas fa-id-card text-primary me-1"></i> <strong>NIP:</strong> <span id="nipPetugas"></span></div>
                        <div><i class="fas fa-briefcase text-success me-1"></i> <strong>Jabatan:</strong> <span id="jabatanPetugas"></span></div>
                    </div>
                </div>
                <div id="errorPetugas" class="alert alert-warning small" style="display:none;">
                    <i class="fas fa-exclamation-triangle me-1"></i> Belum ada surat tugas atau petugas untuk alat ini.
                </div>
            </div>
            <div class="modal-footer border-0 bg-white">
                <button type="button" class="btn btn-light px-4 rounded-pill fw-semibold" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btnLanjutCetak" class="btn btn-success px-4 rounded-pill fw-bold shadow-sm" disabled>
                    <i class="fas fa-print me-2"></i>Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#skhpTable').DataTable({ order: [] });

    var currentIdAlat = null;
    var currentMode   = null;
    var petugasData   = [];

    // Buka modal saat tombol cetak/pdf diklik
    $('.btn-pilih-petugas').on('click', function() {
        currentIdAlat = $(this).data('id-alat');
        currentMode   = $(this).data('mode'); // 'cetak' atau 'pdf'

        // Reset state modal
        $('#loadingPetugas').show();
        $('#wrapperSelectPetugas').hide();
        $('#errorPetugas').hide();
        $('#infoPetugasTerpilih').hide();
        $('#btnLanjutCetak').prop('disabled', true).text('Lanjutkan');
        if (currentMode === 'cetak') {
            $('#btnLanjutCetak').html('<i class="fas fa-print me-2"></i>Cetak SKHP');
        } else {
            $('#btnLanjutCetak').html('<i class="fas fa-file-pdf me-2"></i>Export PDF');
        }

        $('#modalPilihPetugas').modal('show');

        // AJAX ambil daftar petugas
        $.getJSON('<?= site_url('kelola-skhp/get-petugas/') ?>' + currentIdAlat, function(data) {
            $('#loadingPetugas').hide();
            petugasData = data;

            if (!data || data.length === 0) {
                $('#errorPetugas').show();
                return;
            }

            // Isi dropdown
            var opts = '';
            $.each(data, function(i, p) {
                opts += '<option value="' + p.id_petugas + '">' + p.nama_petugas + ' — ' + p.jabatan + '</option>';
            });
            $('#selectPetugas').html(opts).trigger('change');
            $('#wrapperSelectPetugas').show();
            $('#btnLanjutCetak').prop('disabled', false);
        }).fail(function() {
            $('#loadingPetugas').hide();
            $('#errorPetugas').show();
        });
    });

    // Update info saat pilihan dropdown berubah
    $('#selectPetugas').on('change', function() {
        var selectedId = $(this).val();
        var found = $.grep(petugasData, function(p) { return p.id_petugas == selectedId; });
        if (found.length > 0) {
            $('#nipPetugas').text(found[0].nip);
            $('#jabatanPetugas').text(found[0].jabatan);
            $('#infoPetugasTerpilih').show();
        }
    });

    // Tombol lanjutkan: buka halaman cetak/pdf dengan petugas_id terpilih
    $('#btnLanjutCetak').on('click', function() {
        var petugasId = $('#selectPetugas').val();
        var baseUrl;
        if (currentMode === 'cetak') {
            baseUrl = '<?= site_url('kelola-skhp/cetak/') ?>';
        } else {
            baseUrl = '<?= site_url('kelola-skhp/export_pdf/') ?>';
        }
        var url = baseUrl + currentIdAlat + '?petugas_id=' + encodeURIComponent(petugasId);

        $('#modalPilihPetugas').modal('hide');

        if (currentMode === 'cetak') {
            window.open(url, '_blank');
        } else {
            window.location.href = url;
        }
    });
});
</script>
<?php $this->load->view('layout/dash_footer'); ?>