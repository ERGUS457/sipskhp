<?php $this->load->view('layout/dash_header'); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0 fw-bold"><i class="fas fa-file-contract text-primary me-2"></i>Kelola Surat Tugas</h4>
        <p class="text-muted">Proses pengujian teknis alat berdasarkan Surat Tugas yang diterbitkan.</p>
    </div>
</div>

<?php if ($this->session->flashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i><?= $this->session->flashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('validator')) : ?>
    <div class="alert alert-danger shadow-sm" role="alert">
        <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Terjadi Kesalahan!</h5>
        <hr>
        <?= $this->session->flashdata('validator') ?>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table id="tugasTable" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="rounded-start">No</th>
                        <th>Surat Tugas</th>
                        <th>Penugasan</th>
                        <th>Detail Alat</th>
                        <th>Pemohon</th>
                        <th>Status Uji</th>
                        <th class="rounded-end text-center">Tindak Lanjut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($surat_tugas as $st): ?>
                        <tr>
                            <td class="fw-bold"><?= $no++ ?></td>
                            <td>
                                <span class="d-block fw-bold text-dark"><?= htmlspecialchars((string) $st['no_surat_tugas']) ?></span>
                                <small class="text-muted"><i class="far fa-calendar-alt me-1"></i><?= date('d M Y', strtotime($st['tgl_tugas'])) ?></small>
                            </td>
                            <td>
                                <?php if (!empty($st['petugas_array'])): ?>
                                    <?php 
                                        $chunks = array_chunk($st['petugas_array'], 3);
                                        foreach ($chunks as $chunk): 
                                    ?>
                                        <div class="mb-1">
                                            <span class="badge bg-light text-dark border text-start" style="white-space: normal; line-height: 1.4;">
                                                <i class="fas fa-user-tie me-1"></i> <?= htmlspecialchars(implode(', ', $chunk)) ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="badge bg-light text-dark border"><i class="fas fa-user-tie me-1"></i>Belum Ada</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="d-block fw-semibold"><?= htmlspecialchars((string) $st['nama_alat']) ?></span>
                                <small class="text-muted"><?= htmlspecialchars((string) $st['merk']) ?> / <?= htmlspecialchars((string) $st['tipe_model']) ?></small>
                            </td>
                            <td><?= htmlspecialchars((string) $st['nama_pemilik']) ?></td>
                            <td>
                                <?php if ($st['status'] === 'Selesai' && !empty($st['file_cerapan'])): ?>
                                    <?php
                                        // Check validasi
                                        $pengujian = $this->db->get_where('pengujian', ['id_alat' => $st['id_alat']])->row_array();
                                        $is_terval = (!empty($pengujian) && $pengujian['status_validasi'] === 'Tervalidasi');
                                    ?>
                                    <?php if ($is_terval): ?>
                                        <span class="badge bg-success text-white px-3 py-2 rounded-pill"><i class="fas fa-stamp me-1"></i> Tervalidasi KaUPT</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill"><i class="fas fa-check-double me-1"></i> Uji Selesai</span>
                                    <?php endif; ?>
                                <?php elseif ($st['status'] === 'Selesai'): ?>
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill"><i class="fas fa-check-double me-1"></i> Uji Selesai</span>
                                <?php else: ?>
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill"><i class="fas fa-hourglass-half me-1"></i> Menunggu Hasil</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= site_url('surat-tugas/cetak/' . $st['id_surat_tugas']) ?>" target="_blank" class="btn btn-outline-primary btn-sm px-3 shadow-sm rounded-pill mb-1" title="Cetak Surat Tugas">
                                    <i class="fas fa-print me-1"></i> Cetak ST
                                </a>
                                <?php if ($st['status'] === 'Selesai' && !empty($st['file_cerapan'])): ?>
                                    <a href="<?= base_url('asset/cerapan_tera/' . $st['file_cerapan']) ?>" target="_blank" class="btn btn-success btn-sm px-3 shadow-sm rounded-pill mb-1" title="Lihat/Unduh Dokumen Cerapan">
                                        <i class="fas fa-file-download me-1"></i> Cerapan
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#tugasTable').DataTable();
    });
</script>
<?php $this->load->view('layout/dash_footer'); ?>