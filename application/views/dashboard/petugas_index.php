<?php $this->load->view('layout/dash_header'); ?>
<style>
    .avatar-gradient-1 { background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%); }
    .avatar-gradient-2 { background: linear-gradient(135deg, #4E65FF 0%, #92EFFD 100%); }
    .avatar-gradient-3 { background: linear-gradient(135deg, #11998E 0%, #38EF7D 100%); }
    .avatar-gradient-4 { background: linear-gradient(135deg, #8E2DE2 0%, #4A00E0 100%); }
    .avatar-gradient-5 { background: linear-gradient(135deg, #F2994A 0%, #F2C94C 100%); }
    .table-hover tbody tr { transition: all 0.2s ease-in-out; }
    .table-hover tbody tr:hover { background-color: rgba(59,130,246,0.04) !important; transform: scale(1.002); }
    .btn-add-premium { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none; box-shadow: 0 4px 15px rgba(37,99,235,0.3); transition: all 0.3s; }
    .btn-add-premium:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,99,235,0.4); }
    .modal-header-gradient { background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); }
</style>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mb-4">
    <div>
        <h4 class="mb-2 fw-bold text-dark"><i class="fas fa-users-cog text-primary me-2"></i>Manajemen Petugas Tera</h4>
        <p class="text-muted mb-0">Kelola master data personil kalibrasi dan penera yang berwenang di lapangan.</p>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap mt-3 mt-md-0">
        <button class="btn btn-add-premium text-white rounded-pill px-4 py-2" data-bs-toggle="modal" data-bs-target="#tambahPetugasModal">
            <i class="fas fa-user-plus me-2"></i> Registrasi Petugas
        </button>
    </div>
</div>

<?php if ($this->session->flashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
        <i class="fas fa-check-circle me-2 fs-5 align-middle"></i> <span class="align-middle"><?= $this->session->flashdata('success') ?></span>
        <button type="button" class="btn-close mt-1" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
        <i class="fas fa-exclamation-triangle me-2 fs-5 align-middle"></i> <span class="align-middle"><?= $this->session->flashdata('error') ?></span>
        <button type="button" class="btn-close mt-1" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="p-4 bg-light border-bottom">
            <h6 class="mb-0 fw-bold text-secondary"><i class="fas fa-list align-middle me-2"></i>Daftar Petugas Aktif</h6>
        </div>
        <div class="p-4">
            <div class="table-responsive">
                <table id="petugasTable" class="table table-hover align-middle border-bottom">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="fw-semibold rounded-start" width="5%">No</th>
                            <th class="fw-semibold">Profil Pegawai</th>
                            <th class="fw-semibold">NIP / NI PPPK</th>
                            <th class="fw-semibold">Pangkat / Gol</th>
                            <th class="fw-semibold">Jabatan</th>
                            <th class="fw-semibold rounded-end text-center" width="18%">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($petugas as $p): ?>
                            <?php $gradNum = ($no % 5) ?: 5; ?>
                            <tr>
                                <td class="fw-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-gradient-<?= $gradNum ?> text-white d-flex align-items-center justify-content-center rounded-circle me-3 shadow-sm" style="width:45px;height:45px;font-weight:700;font-size:1.1rem;">
                                            <?= strtoupper(substr(htmlspecialchars((string)$p['nama_petugas']), 0, 1)) ?>
                                        </div>
                                        <div>
                                            <span class="d-block fw-bold text-dark fs-6"><?= htmlspecialchars((string)$p['nama_petugas']) ?></span>
                                            <small class="text-muted"><i class="fas fa-check-circle text-success me-1" style="font-size:0.75rem;"></i>Terverifikasi</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-3 shadow-sm" style="font-size:0.85rem;">
                                        <i class="fas fa-id-card text-primary me-2"></i><?= htmlspecialchars((string)$p['nip']) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars((string)($p['pangkat'] ?? '-')) ?></td>
                                <td>
                                    <div class="d-inline-flex align-items-center px-3 py-1 rounded-pill bg-primary bg-opacity-10 text-primary fw-semibold" style="font-size:0.85rem;">
                                        <i class="fas fa-briefcase me-2"></i><?= htmlspecialchars((string)$p['jabatan']) ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-light btn-sm px-3 shadow-sm rounded-pill text-primary fw-semibold border btn-edit me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editPetugasModal"
                                            data-id="<?= $p['id_petugas'] ?>"
                                            data-nama="<?= htmlspecialchars((string)$p['nama_petugas']) ?>"
                                            data-nip="<?= htmlspecialchars((string)$p['nip']) ?>"
                                            data-pangkat="<?= htmlspecialchars((string)($p['pangkat'] ?? '')) ?>"
                                            data-jabatan="<?= htmlspecialchars((string)$p['jabatan']) ?>"
                                            data-username="<?= htmlspecialchars((string)($p['username'] ?? '')) ?>">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </button>
                                    <a href="<?= site_url('petugas/hapus/' . $p['id_petugas']) ?>"
                                       class="btn btn-light btn-sm px-3 shadow-sm rounded-pill text-danger fw-semibold border btn-delete"
                                       onclick="return confirm('Yakin ingin menghapus petugas ini?')">
                                        <i class="fas fa-trash-alt me-1"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Petugas -->
<div class="modal fade" id="tambahPetugasModal" tabindex="-1" aria-labelledby="tambahPetugasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header modal-header-gradient text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="tambahPetugasModalLabel"><i class="fas fa-user-plus me-2"></i>Registrasi Petugas Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('petugas/simpan') ?>" method="post">
                <div class="modal-body p-4 bg-light">
                    <div class="mb-3">
                        <label for="nama_petugas" class="form-label fw-bold text-secondary">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-light-subtle bg-white" id="nama_petugas" name="nama_petugas" required placeholder="Contoh: Budi Santoso, S.T.">
                    </div>
                    <div class="mb-3">
                        <label for="nip" class="form-label fw-bold text-secondary">NIP / NI PPPK <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-light-subtle bg-white" id="nip" name="nip" required placeholder="Masukkan nomor induk pegawai">
                    </div>
                    <div class="mb-3">
                        <label for="pangkat" class="form-label fw-bold text-secondary">Pangkat / Golongan</label>
                        <input type="text" class="form-control border-light-subtle bg-white" id="pangkat" name="pangkat" placeholder="Contoh: Penata Muda / III-a atau Golongan V">
                    </div>
                    <div class="mb-1">
                        <label for="jabatan" class="form-label fw-bold text-secondary">Jabatan Fungsional <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-light-subtle bg-white" id="jabatan" name="jabatan" required placeholder="Contoh: Penera Ahli Pertama">
                    </div>
                    <hr>
                    <h6 class="fw-bold mb-3"><i class="fas fa-lock text-primary me-2"></i>Akun Aplikasi Petugas</h6>
                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold text-secondary">Username Login <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-light-subtle bg-white" id="username" name="username" required placeholder="Contoh: agus_tera / NIP">
                    </div>
                    <div class="mb-1">
                        <label for="password" class="form-label fw-bold text-secondary">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control border-light-subtle bg-white" id="password" name="password" required placeholder="Minimal 6 karakter">

                    </div>
                </div>
                <div class="modal-footer border-0 bg-white">
                    <button type="button" class="btn btn-light px-4 rounded-pill fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold shadow-sm"><i class="fas fa-save me-2"></i>Simpan Petugas</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Petugas -->
<div class="modal fade" id="editPetugasModal" tabindex="-1" aria-labelledby="editPetugasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-warning border-0 py-3">
                <h5 class="modal-title fw-bold text-dark" id="editPetugasModalLabel"><i class="fas fa-user-edit me-2"></i>Ubah Data Petugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('petugas/update') ?>" method="post">
                <div class="modal-body p-4 bg-light">
                    <input type="hidden" name="id" id="edit_id_petugas">
                    <div class="mb-3">
                        <label for="edit_nama_petugas" class="form-label fw-bold text-secondary">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-light-subtle bg-white" id="edit_nama_petugas" name="nama_petugas" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nip" class="form-label fw-bold text-secondary">NIP / NI PPPK <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-light-subtle bg-white" id="edit_nip" name="nip" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_pangkat" class="form-label fw-bold text-secondary">Pangkat / Golongan</label>
                        <input type="text" class="form-control border-light-subtle bg-white" id="edit_pangkat" name="pangkat" placeholder="Contoh: Penata Muda / III-a atau Golongan V">
                    </div>
                    <div class="mb-1">
                        <label for="edit_jabatan" class="form-label fw-bold text-secondary">Jabatan Fungsional <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-light-subtle bg-white" id="edit_jabatan" name="jabatan" required>
                    </div>
                    <hr>
                    <h6 class="fw-bold mb-3"><i class="fas fa-lock text-warning me-2"></i>Akun Aplikasi Petugas</h6>
                    <div class="mb-3">
                        <label for="edit_username" class="form-label fw-bold text-secondary">Username Login <span class="text-danger">*</span></label>
                        <input type="text" class="form-control border-light-subtle bg-white" id="edit_username" name="username" required>
                    </div>
                    <div class="mb-1">
                        <label for="edit_password" class="form-label fw-bold text-secondary">Ganti Password <small class="fw-normal">(Kosongkan jika tidak ingin mengubah password)</small></label>
                        <input type="password" class="form-control border-light-subtle bg-white" id="edit_password" name="password" placeholder="Ketik sandi baru...">

                    </div>
                </div>
                <div class="modal-footer border-0 bg-white">
                    <button type="button" class="btn btn-light px-4 rounded-pill fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning px-4 rounded-pill fw-bold shadow-sm text-dark"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#petugasTable').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari petugas...",
        }
    });
    $('.dataTables_filter input').addClass('form-control rounded-pill border-light-subtle shadow-sm').css('padding', '0.4rem 1rem');

    // Pass data ke modal edit
    $(document).on('click', '.btn-edit', function() {
        $('#edit_id_petugas').val($(this).data('id'));
        $('#edit_nama_petugas').val($(this).data('nama'));
        $('#edit_nip').val($(this).data('nip'));
        $('#edit_pangkat').val($(this).data('pangkat'));
        $('#edit_jabatan').val($(this).data('jabatan'));
        $('#edit_username').val($(this).data('username'));
    });
});
</script>
<?php $this->load->view('layout/dash_footer'); ?>