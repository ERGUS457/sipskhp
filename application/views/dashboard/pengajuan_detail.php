<?php $this->load->view('layout/dash_header'); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Detail Pengajuan Alat UTTP</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="<?= site_url('pengajuan') ?>" class="btn btn-secondary btn-icon-text mb-2 mb-md-0">
            <i class="fas fa-arrow-left me-2"></i>
            Kembali ke Daftar Pengajuan
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7 col-xl-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom border-light pt-4 pb-3 px-4 rounded-top-4">
                <h5 class="card-title mb-0 fw-bold"><i class="fas fa-boxes text-primary me-2"></i>Informasi Spesifikasi Alat</h5>
            </div>
            <div class="card-body p-4">
                <div class="row align-items-center mb-3">
                    <div class="col-sm-4 text-muted fw-semibold">Jenis Alat Terukur</div>
                    <div class="col-sm-8 fw-bold fs-5 text-dark"><?= htmlspecialchars((string) $alat['nama_alat']) ?></div>
                </div>
                <div class="row align-items-center mb-3">
                    <div class="col-sm-4 text-muted fw-semibold">Merk / Brand</div>
                    <div class="col-sm-8 fw-semibold"><?= htmlspecialchars((string) $alat['merk']) ?></div>
                </div>
                <div class="row align-items-center mb-3">
                    <div class="col-sm-4 text-muted fw-semibold">Tipe / Model</div>
                    <div class="col-sm-8 fw-semibold"><?= htmlspecialchars((string) $alat['tipe_model']) ?></div>
                </div>
                <div class="row align-items-center mb-3">
                    <div class="col-sm-4 text-muted fw-semibold">Nomor Seri</div>
                    <div class="col-sm-8 fw-semibold"><span class="badge bg-light text-dark border px-3 py-2"><?= htmlspecialchars((string) $alat['nomor_seri']) ?></span></div>
                </div>
                <div class="row align-items-center mb-3">
                    <div class="col-sm-4 text-muted fw-semibold">Buatan Negara</div>
                    <div class="col-sm-8 fw-semibold"><?= htmlspecialchars((string) $alat['buatan']) ?></div>
                </div>
                <div class="row align-items-center mb-3">
                    <div class="col-sm-4 text-muted fw-semibold">Kapasitas / Daya Baca</div>
                    <div class="col-sm-8 fw-bold text-primary"><?= htmlspecialchars((string) $alat['kapasitas']) ?></div>
                </div>
                <div class="row align-items-center mb-3">
                    <div class="col-sm-4 text-muted fw-semibold">Jumlah (Unit)</div>
                    <div class="col-sm-8 fw-semibold"><?= htmlspecialchars((string) $alat['jumlah']) ?> Unit</div>
                </div>

                <?php if ($detail_alat): ?>
                    <hr class="my-4 border-light">
                    <h6 class="fw-bold mb-4 text-secondary"><i class="fas fa-microchip me-2"></i>Detail Parameter Khusus</h6>
                    <div class="bg-light bg-opacity-50 p-4 rounded-4 border">
                        <?php foreach($detail_alat as $key => $value): ?>
                            <?php if ($key !== 'id_alat'): ?>
                                <div class="row mb-3 last-mb-0">
                                    <div class="col-sm-5 text-muted fw-semibold"><?= htmlspecialchars((string) ucwords(str_replace('_', ' ', $key))) ?></div>
                                    <div class="col-sm-7 fw-bold text-dark"><?= htmlspecialchars((string) $value) ?></div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-5 col-xl-4">
        <!-- Card Status Pengujian & Cerapan -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom border-light pt-4 pb-3 px-4 rounded-top-4">
                <h5 class="card-title mb-0 fw-bold"><i class="fas fa-clipboard-check text-success me-2"></i>Status Pengujian</h5>
            </div>
            <div class="card-body p-4">
                <?php if (!isset($surat_tugas) || !$surat_tugas): ?>
                    <div class="alert alert-warning mb-0 border-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Belum ada Surat Tugas yang diterbitkan.
                        <?php if ($this->session->userdata('level') === 'Admin'): ?>
                        <div class="mt-3">
                            <button class="btn btn-primary btn-sm px-3 shadow-sm rounded-pill fw-bold"
                                data-bs-toggle="modal"
                                data-bs-target="#prosesModal"
                                data-id="<?= $alat['id_alat'] ?>"
                                data-alat-info="<?= htmlspecialchars((string) $alat['nama_alat'] . ' - ' . $alat['merk']) ?>">
                                <i class="fas fa-file-signature me-1"></i> Buat Surat Tugas
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="mb-4">
                        <small class="text-muted d-block fw-semibold mb-2">Status Saat Ini</small>
                        <?php if (isset($alat['status_validasi']) && $alat['status_validasi'] === 'Tervalidasi'): ?>
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fs-6"><i class="fas fa-stamp me-2"></i> Tervalidasi KaUPT</span>
                        <?php elseif (isset($surat_tugas['status']) && $surat_tugas['status'] === 'Selesai' && isset($cerapan) && $cerapan): ?>
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fs-6"><i class="fas fa-check-circle me-2"></i> Selesai Uji</span>
                        <?php else: ?>
                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fs-6"><i class="fas fa-clock me-2"></i> Menunggu / Proses Uji</span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (isset($cerapan) && $cerapan): ?>
                        <div class="p-3 bg-light rounded-3 border">
                            <div class="mb-2">
                                <small class="text-muted d-block fw-semibold mb-1">Diupload Oleh</small>
                                <div class="fw-bold text-dark"><i class="fas fa-user-tie text-secondary me-2"></i><?= htmlspecialchars((string) $cerapan['nama_petugas_upload']) ?></div>
                                <small class="text-muted"><i class="far fa-clock me-1"></i> <?= date('d M Y, H:i', strtotime($cerapan['tgl_upload'])) ?></small>
                            </div>
                            <?php if ($cerapan['catatan']): ?>
                                <div class="mb-3 mt-3">
                                    <small class="text-muted d-block fw-semibold mb-1">Catatan Petugas</small>
                                    <div class="p-2 bg-white rounded text-dark fs-6 border"><?= nl2br(htmlspecialchars((string) $cerapan['catatan'])) ?></div>
                                </div>
                            <?php endif; ?>
                            <div class="mt-3 d-flex flex-column gap-2">
                                <?php if (isset($alat['status_validasi']) && $alat['status_validasi'] === 'Tervalidasi'): ?>
                                    <a href="<?= site_url('kelola-skhp/cetak/' . $alat['id_alat']) ?>" target="_blank" class="btn btn-success btn-sm w-100 fw-bold rounded-pill">
                                        <i class="fas fa-certificate me-2"></i> Cetak Dokumen SKHP
                                    </a>
                                <?php endif; ?>
                                <a href="<?= base_url('asset/cerapan_tera/' . $cerapan['file_cerapan']) ?>" target="_blank" class="btn btn-primary btn-sm w-100 fw-bold rounded-pill">
                                    <i class="fas fa-file-download me-2"></i> Lihat Dokumen Cerapan
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php if (isset($surat_tugas['status']) && $surat_tugas['status'] === 'Selesai'): ?>
                            <div class="alert alert-danger mb-0 border-0">
                                <i class="fas fa-exclamation-circle me-2"></i>Data dokumen cerapan tidak ditemukan atau telah terhapus.
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mb-0 border-0">
                                <i class="fas fa-info-circle me-2"></i>Dokumen Cerapan Tera belum diunggah oleh petugas.
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Card Profil Pemohon -->
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom border-light pt-4 pb-3 px-4 rounded-top-4">
                <h5 class="card-title mb-0 fw-bold"><i class="fas fa-user-circle text-info me-2"></i>Profil Pemohon</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <small class="text-muted d-block fw-semibold mb-1">Nama Pemilik / Pimpinan</small>
                    <div class="fw-bold fs-6 text-dark"><?= htmlspecialchars((string) $alat['nama_pemilik']) ?></div>
                </div>
                <div class="mb-4">
                    <small class="text-muted d-block fw-semibold mb-1">Jenis Usaha</small>
                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill"><?= htmlspecialchars((string) $alat['jenis_usaha']) ?></span>
                </div>
                <div class="mb-4">
                    <small class="text-muted d-block fw-semibold mb-1">Sektor / Bidang</small>
                    <div class="fw-semibold text-dark"><i class="fas fa-industry text-secondary me-2"></i><?= htmlspecialchars((string) $alat['jenis_usaha']) ?></div>
                </div>
                <div class="mb-4">
                    <small class="text-muted d-block fw-semibold mb-1">Kontak Telepon</small>
                    <div class="fw-bold text-success"><i class="fab fa-whatsapp me-2"></i><?= htmlspecialchars((string) $alat['kontak_pemohon']) ?></div>
                </div>
                <div>
                    <small class="text-muted d-block fw-semibold mb-1">Alamat Lengkap Usaha</small>
                    <div class="p-3 bg-light rounded-3 text-dark mt-2 border-start border-4 border-info">
                        <?= htmlspecialchars((string) $alat['alamat_usaha']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Buat Surat Tugas -->
<div class="modal fade" id="prosesModal" tabindex="-1" aria-labelledby="prosesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prosesModalLabel">Terbitkan Surat Tugas Terkalibrasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('surat-tugas/proses') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id_alat" id="modal_id_alat">
                    <div class="alert alert-info">
                        Penugasan Alat: <strong id="modal_alat_info"></strong>
                    </div>
                    <div class="mb-3">
                        <label for="no_surat_tugas" class="form-label">Nomor Surat Tugas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="no_surat_tugas" name="no_surat_tugas" value="<?= set_value('no_surat_tugas', 'ST-'.date('Ymd').'-'.rand(100,999)) ?>" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_tugas" class="form-label">Tanggal Penugasan <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tgl_tugas" name="tgl_tugas" value="<?= set_value('tgl_tugas', date('Y-m-d')) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="id_petugas" class="form-label fw-semibold">Tugaskan Kepada (Pilih 1 atau Lebih Petugas) <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_petugas" name="id_petugas[]" multiple="multiple" required data-placeholder="Ketik/Pilih Nama Petugas...">
                            <?php foreach ($petugas as $p): ?>
                                <option value="<?= $p['id_petugas'] ?>">
                                    <?= htmlspecialchars((string) $p['nama_petugas']) ?> - <?= htmlspecialchars((string) $p['jabatan']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text text-muted mt-2"><i class="fas fa-info-circle me-1"></i>Anda dapat memilih lebih dari satu petugas.</div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold"><i class="fas fa-paper-plane me-2"></i> Terbitkan & Tugaskan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        if ($('#id_petugas').length) {
            $('#id_petugas').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#prosesModal'),
                width: '100%',
                allowClear: true
            });
        }
        
        const prosesModal = document.getElementById('prosesModal');
        if (prosesModal) {
            prosesModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const idAlat = button.getAttribute('data-id');
                const alatInfo = button.getAttribute('data-alat-info');
                prosesModal.querySelector('#modal_alat_info').textContent = alatInfo;
                prosesModal.querySelector('#modal_id_alat').value = idAlat;
            });
        }
    });
</script>

<?php $this->load->view('layout/dash_footer'); ?>