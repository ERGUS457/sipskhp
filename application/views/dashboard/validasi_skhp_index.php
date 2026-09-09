<?php $this->load->view('layout/dash_header'); ?>

<style>
.stat-kup { border-radius: 16px; padding: 24px 28px; color: #fff; position: relative; overflow: hidden; transition: transform .3s, box-shadow .3s; }
.stat-kup:hover { transform: translateY(-6px); box-shadow: 0 16px 32px rgba(0,0,0,.15); }
.stat-kup .bg-deco { position:absolute; width:130px; height:130px; border-radius:50%; background:rgba(255,255,255,.1); top:-30px; right:-30px; }
.stat-kup .num { font-size: 2.5rem; font-weight: 800; line-height:1; }
.stat-kup .lbl { font-size: .88rem; opacity:.85; margin-bottom:6px; }
.bg-blue    { background: linear-gradient(135deg,#3b82f6,#1d4ed8); }
.bg-amber   { background: linear-gradient(135deg,#f59e0b,#b45309); }
.bg-green   { background: linear-gradient(135deg,#10b981,#047857); }
.status-pill-ok  { background:#dcfce7; color:#16a34a; padding:4px 14px; border-radius:20px; font-size:.8rem; font-weight:600; }
.status-pill-pend { background:#fef3c7; color:#d97706; padding:4px 14px; border-radius:20px; font-size:.8rem; font-weight:600; }
</style>

<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
    <i class="fas fa-check-circle me-2"></i><?= $this->session->flashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i><?= $this->session->flashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-signature text-primary me-2"></i>Otorisasi Hasil Uji</h4>
        <p class="text-muted mb-0">Validasi dan tandatangani hasil pengujian yang diunggah oleh petugas.</p>
    </div>
</div>

<div class="row g-3 mb-5">
    <div class="col-md-4">
        <div class="stat-kup bg-blue">
            <div class="bg-deco"></div>
            <div class="lbl"><i class="fas fa-check-double me-1"></i> Total Selesai Uji</div>
            <div class="num"><?= $total_selesai ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-kup bg-amber">
            <div class="bg-deco"></div>
            <div class="lbl"><i class="fas fa-hourglass-half me-1"></i> Menunggu Validasi</div>
            <div class="num"><?= $total_pending ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-kup bg-green">
            <div class="bg-deco"></div>
            <div class="lbl"><i class="fas fa-stamp me-1"></i> Sudah Divalidasi</div>
            <div class="num"><?= $total_validated ?></div>
        </div>
    </div>
</div>

<?php if (empty($list)): ?>
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body text-center py-5">
        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
        <h5 class="fw-semibold text-muted">Belum Ada Pengujian Selesai</h5>
        <p class="text-muted">Daftar ini akan terisi otomatis setelah Petugas mengunggah Cerapan Tera.</p>
    </div>
</div>
<?php else: ?>
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom border-light pt-4 pb-3 px-4 rounded-top-4">
        <h5 class="card-title mb-0 fw-bold"><i class="fas fa-list-check text-primary me-2"></i>Daftar Pengujian Selesai</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="validasiTable" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">No</th>
                        <th>Alat UTTP</th>
                        <th>Pemohon</th>
                        <th>Surat Tugas</th>
                        <th>Cerapan</th>
                        <th>Status Validasi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($list as $row): ?>
                    <tr>
                        <td class="px-4 fw-bold"><?= $no++ ?></td>
                        <td>
                            <span class="d-block fw-semibold text-dark"><?= htmlspecialchars((string)$row['nama_alat']) ?></span>
                            <small class="text-muted"><?= htmlspecialchars((string)$row['merk']) ?> / <?= htmlspecialchars((string)$row['tipe_model']) ?></small>
                        </td>
                        <td>
                            <span class="d-block fw-semibold"><?= htmlspecialchars((string)$row['nama_pemilik']) ?></span>
                            <small class="text-muted"><i class="fas fa-store me-1"></i><?= htmlspecialchars((string)$row['jenis_usaha']) ?></small>
                        </td>
                        <td>
                            <span class="d-block fw-bold text-primary" style="font-size:.82rem;"><?= htmlspecialchars((string)$row['no_surat_tugas']) ?></span>
                            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i><?= date('d M Y', strtotime($row['tgl_tugas'])) ?></small>
                        </td>
                        <td>
                            <?php if (!empty($row['file_cerapan'])): ?>
                                <a href="<?= base_url('asset/cerapan_tera/' . $row['file_cerapan']) ?>" target="_blank"
                                   class="btn btn-sm btn-outline-info rounded-pill px-3 mb-1">
                                    <i class="fas fa-eye me-1"></i> Lihat Cerapan
                                </a>
                                <?php if (!empty($row['nama_petugas_upload'])): ?>
                                <div><small class="text-muted"><i class="fas fa-user-tie me-1"></i><?= htmlspecialchars((string)$row['nama_petugas_upload']) ?></small></div>
                                <?php endif; ?>
                                <?php if (!empty($row['tgl_upload'])): ?>
                                <div><small class="text-muted"><i class="far fa-clock me-1"></i><?= date('d M Y H:i', strtotime($row['tgl_upload'])) ?></small></div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic" style="font-size:.82rem;">Belum ada</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['status_validasi'] === 'Tervalidasi'): ?>
                                <span class="status-pill-ok"><i class="fas fa-check-circle me-1"></i> Tervalidasi</span>
                            <?php else: ?>
                                <span class="status-pill-pend"><i class="fas fa-hourglass-half me-1"></i> Menunggu</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($row['status_validasi'] !== 'Tervalidasi'): ?>
                                <?php if (!empty($row['file_cerapan'])): ?>
                                    <button class="btn btn-success btn-sm rounded-pill px-3 mb-1"
                                        onclick="bukaModalValidasi(<?= (int)$row['id_alat'] ?>, <?= (int)$row['id_surat_tugas'] ?>, '<?= addslashes(htmlspecialchars((string)$row['nama_alat'])) ?>', '<?= addslashes(htmlspecialchars((string)$row['nama_pemilik'])) ?>', '<?= date('Y-m-d', strtotime($row['tgl_tugas'])) ?>')">
                                        <i class="fas fa-signature me-1"></i> Validasi
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm rounded-pill px-3 mb-1"
                                        onclick="bukaModalTolak(<?= (int)$row['id_surat_tugas'] ?>, '<?= addslashes(htmlspecialchars((string)$row['nama_alat'])) ?>')">
                                        <i class="fas fa-times me-1"></i> Tolak
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted fw-semibold" style="font-size:.82rem;"><i class="fas fa-clock me-1"></i>Menunggu Cerapan</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-success fw-semibold" style="font-size:.82rem;"><i class="fas fa-check me-1"></i>Selesai</span>
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

<!-- MODAL Validasi -->
<div class="modal fade" id="modalValidasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0" style="background:linear-gradient(135deg,#16a34a,#15803d);border-radius:16px 16px 0 0;">
                <h5 class="modal-title text-white fw-bold"><i class="fas fa-signature me-2"></i> Otorisasi Hasil Uji</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('validasi-skhp/validasi') ?>" method="post">
                <div class="modal-body px-4 py-4">
                    <input type="hidden" name="id_alat" id="modal_id_alat">
                    <input type="hidden" name="id_surat_tugas" id="modal_id_st">
                    <div class="alert alert-success border-0 rounded-3 mb-4">
                        <div class="row">
                            <div class="col-sm-6">
                                <small class="text-muted d-block fw-semibold">Alat UTTP</small>
                                <div class="fw-bold" id="modal_nama_alat"></div>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted d-block fw-semibold">Pemohon</small>
                                <div class="fw-bold" id="modal_nama_pemohon"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Pengujian <span class="text-danger">*</span></label>
                            <input type="date" name="tgl_pengujian" id="modal_tgl" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hasil Uji <span class="text-danger">*</span></label>
                            <select name="hasil_uji" class="form-select rounded-3" required>
                                <option value="Sah">Sah — Alat Memenuhi Syarat</option>
                                <option value="Batal">Batal — Alat Tidak Memenuhi Syarat</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Metode Pengujian</label>
                            <input type="text" name="metode_uji" class="form-control rounded-3" value="Tera / Tera Ulang">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Standar / Regulasi</label>
                            <input type="text" name="standar_uji" class="form-control rounded-3" value="Permendag No. 24 Tahun 2024">
                        </div>
                    </div>
                    <div class="mt-4 p-3 bg-light rounded-3 border-start border-4 border-success">
                        <small class="text-muted"><i class="fas fa-info-circle me-2"></i>Dengan menekan tombol di bawah, Anda selaku <strong>Kepala UPT</strong> menyatakan hasil uji ini telah diperiksa dan disetujui untuk diterbitkan SKHP-nya.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">
                        <i class="fas fa-signature me-2"></i> Sahkan & Otorisasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL Tolak -->
<div class="modal fade" id="modalTolak" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0" style="background:linear-gradient(135deg,#ef4444,#b91c1c);border-radius:16px 16px 0 0;">
                <h5 class="modal-title text-white fw-bold"><i class="fas fa-times-circle me-2"></i> Kembalikan ke Petugas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= site_url('validasi-skhp/tolak') ?>" method="post">
                <div class="modal-body px-4 py-4">
                    <input type="hidden" name="id_surat_tugas" id="tolak_id_st">
                    <p class="mb-3">Anda akan mengembalikan cerapan untuk alat <strong id="tolak_nama_alat"></strong> kepada petugas untuk diperbaiki.</p>
                    <label class="form-label fw-semibold">Catatan / Alasan Pengembalian <span class="text-danger">*</span></label>
                    <textarea name="catatan_tolak" rows="3" class="form-control rounded-3" required placeholder="Jelaskan alasan pengembalian cerapan..."></textarea>
                    <div class="mt-3 p-3 bg-warning bg-opacity-10 rounded-3 border-start border-4 border-warning">
                        <small><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Status surat tugas akan dikembalikan ke <strong>Menunggu</strong> dan cerapan lama akan dihapus agar petugas bisa mengunggah ulang.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-5 fw-bold shadow-sm">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Kembali
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#validasiTable').DataTable({ order: [] });
});
function bukaModalValidasi(id_alat, id_st, nama_alat, nama_pemohon, tgl) {
    document.getElementById('modal_id_alat').value = id_alat;
    document.getElementById('modal_id_st').value   = id_st;
    document.getElementById('modal_nama_alat').textContent    = nama_alat;
    document.getElementById('modal_nama_pemohon').textContent = nama_pemohon;
    document.getElementById('modal_tgl').value = tgl;
    new bootstrap.Modal(document.getElementById('modalValidasi')).show();
}
function bukaModalTolak(id_st, nama_alat) {
    document.getElementById('tolak_id_st').value          = id_st;
    document.getElementById('tolak_nama_alat').textContent = nama_alat;
    new bootstrap.Modal(document.getElementById('modalTolak')).show();
}
</script>

<?php $this->load->view('layout/dash_footer'); ?>