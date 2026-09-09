<?php $this->load->view('layout/dash_header'); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Detail Data Pemohon</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="<?= site_url('kelola-pemohon') ?>" class="btn btn-secondary btn-icon-text mb-2 mb-md-0">
            <i class="fas fa-arrow-left me-2"></i>
            Kembali ke Daftar Pemohon
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5 col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom border-light pt-4 pb-3 px-4 rounded-top-4">
                <h5 class="card-title mb-0 fw-bold"><i class="fas fa-id-card text-success me-2"></i>Profil Data Pemohon</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-4">
                    <small class="text-muted d-block fw-semibold mb-1">ID Registrasi Pemohon</small>
                    <div class="d-flex align-items-center">
                        <div class="fw-bold fs-5 text-primary me-3"><?= htmlspecialchars((string) $pemohon['id_pemohon']) ?></div>
                        <button type="button" class="btn btn-sm btn-outline-primary shadow-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#editIdModal">
                            <i class="fas fa-edit me-1"></i> Ubah ID
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <small class="text-muted d-block fw-semibold mb-1">Nama Pemilik / Pimpinan</small>
                    <div class="fw-bold fs-6 text-dark"><?= htmlspecialchars((string) $pemohon['nama_pemilik']) ?></div>
                </div>
                <div class="mb-4">
                    <small class="text-muted d-block fw-semibold mb-1">Sektor Usaha</small>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border px-3 py-2 rounded-pill"><?= htmlspecialchars((string) $pemohon['jenis_usaha']) ?></span>
                </div>
                <div class="mb-4">
                    <small class="text-muted d-block fw-semibold mb-1">Kontak Telepon</small>
                    <div class="fw-bold text-success"><i class="fab fa-whatsapp me-2"></i><?= htmlspecialchars((string) $pemohon['kontak_pemohon']) ?></div>
                </div>
                <div class="mb-4">
                    <small class="text-muted d-block fw-semibold mb-1">Alamat Operasional/Usaha</small>
                    <div class="fw-semibold text-dark p-3 bg-light rounded-3 mt-1"><?= htmlspecialchars((string) $pemohon['alamat_usaha']) ?></div>
                </div>

                <hr class="my-4 border-light">

                <div class="mb-4">
                    <small class="text-muted d-block fw-semibold mb-1">Username Portal</small>
                    <div class="fw-semibold text-primary"><i class="fas fa-user-circle me-1"></i><?= htmlspecialchars((string) $pemohon['username']) ?></div>
                </div>
                <div>
                    <small class="text-muted d-block fw-semibold mb-1">Alamat Surel (Email)</small>
                    <div class="fw-semibold text-dark"><i class="fas fa-envelope text-warning me-1"></i><?= htmlspecialchars((string) $pemohon['email']) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7 col-xl-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom border-light pt-4 pb-3 px-4 rounded-top-4">
                <h5 class="card-title mb-0 fw-bold"><i class="fas fa-history text-primary me-2"></i>Riwayat Pengajuan Alat Tersimpan</h5>
            </div>
            <div class="card-body p-4">
                <?php if(empty($riwayat_alat)): ?>
                    <div class="text-center p-5 text-muted bg-light rounded-4 border border-dashed">
                        <i class="fas fa-box-open fs-1 text-secondary mb-3"></i>
                        <p class="mb-0">Belum ada riwayat alat yang didaftarkan oleh pemohon ini.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border rounded-3 overflow-hidden">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Deskripsi Alat</th>
                                    <th>Ciri Fisik (Merk / Tipe)</th>
                                    <th>No Seri Terdaftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($riwayat_alat as $alat): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark"><?= htmlspecialchars((string) $alat['nama_alat']) ?></td>
                                        <td class="text-secondary"><?= htmlspecialchars((string) $alat['merk']) ?> <br> <small class="text-muted"><?= htmlspecialchars((string) $alat['tipe_model']) ?></small></td>
                                        <td><span class="badge bg-light text-dark border"><i class="fas fa-barcode me-1"></i><?= htmlspecialchars((string) $alat['nomor_seri']) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit ID -->
<div class="modal fade" id="editIdModal" tabindex="-1" aria-labelledby="editIdModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="<?= site_url('kelola-pemohon/update_id/' . $pemohon['id_pemohon']) ?>" method="post">
                <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" id="editIdModalLabel">Ubah ID Registrasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label for="new_id" class="form-label fw-semibold">ID Registrasi Baru</label>
                        <input type="number" class="form-control form-control-lg bg-light" id="new_id" name="new_id" value="<?= htmlspecialchars((string) $pemohon['id_pemohon']) ?>" required>
                        <small class="text-muted mt-2 d-block"><i class="fas fa-info-circle me-1"></i>Peringatan: Mengubah ID akan berpengaruh pada data terkait. Pastikan ID baru berjumlah 8 angka dan belum digunakan oleh pemohon lain.</small>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('layout/dash_footer'); ?>