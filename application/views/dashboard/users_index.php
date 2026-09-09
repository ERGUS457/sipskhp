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
        <h4 class="mb-2 fw-bold text-dark"><i class="fas fa-users-cog text-primary me-2"></i>Kelola Pengguna Sistem</h4>
        <p class="text-muted mb-0">Kelola master data kredensial, role, dan otorisasi akses pengguna aplikasi.</p>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap mt-3 mt-md-0">
        <button class="btn btn-add-premium text-white rounded-pill px-4 py-2" data-bs-toggle="modal" data-bs-target="#tambahUserModal">
            <i class="fas fa-user-plus me-2"></i> Tambah Pengguna Baru
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
            <h6 class="mb-0 fw-bold text-secondary"><i class="fas fa-list align-middle me-2"></i>Daftar Pengguna Aktif</h6>
        </div>
        <div class="p-4">
            <div class="table-responsive">
                <table id="usersTable" class="table table-hover align-middle border-bottom">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="fw-semibold rounded-start" width="5%">No</th>
                            <th class="fw-semibold">Nama Pengguna (Username)</th>
                            <th class="fw-semibold">Alamat Surel</th>
                            <th class="fw-semibold">Hak Akses (Level)</th>
                            <th class="fw-semibold rounded-end text-center" width="18%">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($users as $user): ?>
                            <?php $gradNum = ($user['id_user'] % 5) + 1; ?>
                            <tr>
                                <td class="fw-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-gradient-<?= $gradNum ?> text-white d-flex align-items-center justify-content-center rounded-circle me-3 shadow-sm" style="width:40px;height:40px;font-weight:700;font-size:1rem;">
                                            <?= strtoupper(substr(htmlspecialchars((string)$user['username']), 0, 1)) ?>
                                        </div>
                                        <div>
                                            <span class="d-block fw-bold text-dark fs-6"><?= htmlspecialchars((string) $user['username']) ?></span>
                                            <small class="text-muted"><i class="fas fa-id-badge me-1" style="font-size:0.75rem;"></i>ID: <?= $user['id_user'] ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-secondary"><i class="fas fa-envelope text-muted me-2"></i><?= htmlspecialchars((string) $user['email']) ?></span>
                                </td>
                                <td>
                                    <?php if ($user['level'] === 'Admin'): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill"><i class="fas fa-shield-alt me-1"></i> Admin</span>
                                    <?php elseif ($user['level'] === 'Kepala UPT'): ?>
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill"><i class="fas fa-user-tie me-1"></i> Kepala UPT</span>
                                    <?php elseif ($user['level'] === 'Petugas'): ?>
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill"><i class="fas fa-hard-hat me-1"></i> Petugas</span>
                                    <?php elseif ($user['level'] === 'Pemohon'): ?>
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill"><i class="fas fa-building me-1"></i> Pemohon</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary px-3 py-2 rounded-pill"><?= htmlspecialchars($user['level']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center" data-id="<?= $user['id_user'] ?>">
                                    <button class="btn btn-light btn-sm px-3 shadow-sm rounded-pill text-warning border btn-edit me-1" title="Ubah Profil">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </button>
                                    <button class="btn btn-light btn-sm px-3 shadow-sm rounded-pill text-danger border btn-delete" title="Hapus Akun">
                                        <i class="fas fa-trash-alt me-1"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="tambahUserModal" tabindex="-1" aria-labelledby="tambahUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header modal-header-gradient text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="tambahUserModalLabel"><i class="fas fa-user-plus me-2"></i>Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('users/simpan') ?>" method="post">
                <div class="modal-body p-4 bg-light">
                    <!-- Menampilkan pesan error validasi -->
                    <?php if ($this->session->flashdata('validation_errors')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 py-2 px-3 mb-3" role="alert">
                            <div class="d-flex align-items-center mb-1">
                                <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                                <strong class="text-danger small">Gagal Menyimpan Data!</strong>
                            </div>
                            <div class="small ps-4">
                                <?= $this->session->flashdata('validation_errors') ?>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 0.75rem;"></button>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold text-secondary">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-light-subtle rounded-start-3 text-secondary"><i class="fas fa-user-circle"></i></span>
                            <input type="text" class="form-control border-light-subtle bg-white border-start-0 rounded-end-3" id="username" name="username" value="<?= $this->session->flashdata('old_username') ?>" placeholder="Masukkan username" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold text-secondary">Alamat Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-light-subtle rounded-start-3 text-secondary"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control border-light-subtle bg-white border-start-0 rounded-end-3" id="email" name="email" value="<?= $this->session->flashdata('old_email') ?>" placeholder="Masukkan email" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold text-secondary">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-light-subtle rounded-start-3 text-secondary"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control border-light-subtle bg-white border-start-0" id="password" name="password" placeholder="Minimal 8 karakter" required>
                            <button class="btn btn-outline-secondary border-light-subtle bg-white border-start-0 rounded-end-3" type="button" id="togglePassword">
                                <i class="fas fa-eye text-secondary"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="level" class="form-label fw-bold text-secondary">Level Pengguna <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-light-subtle rounded-start-3 text-secondary"><i class="fas fa-shield-alt"></i></span>
                            <select class="form-select border-light-subtle bg-white border-start-0 rounded-end-3" id="level" name="level" required>
                                <option value="" disabled selected>-- Pilih Level --</option>
                                <option value="Admin" <?= $this->session->flashdata('old_level') === 'Admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="Kepala UPT" <?= $this->session->flashdata('old_level') === 'Kepala UPT' ? 'selected' : '' ?>>Kepala UPT</option>
                                <option value="Petugas" <?= $this->session->flashdata('old_level') === 'Petugas' ? 'selected' : '' ?>>Petugas</option>
                                <option value="Pemohon" <?= $this->session->flashdata('old_level') === 'Pemohon' ? 'selected' : '' ?>>Pemohon</option>
                            </select>
                        </div>
                    </div>

                    <!-- ===== SECTION DATA PETUGAS (muncul otomatis jika level = Petugas) ===== -->
                    <div id="sectionDataPetugas" style="display:none;">
                        <hr class="my-2">
                        <p class="fw-bold text-warning mb-3"><i class="fas fa-hard-hat me-2"></i>Data Profil Petugas</p>

                        <div class="mb-3">
                            <label for="nip" class="form-label fw-bold text-secondary">NIP <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-light-subtle rounded-start-3 text-secondary"><i class="fas fa-id-card"></i></span>
                                <input type="text" class="form-control border-light-subtle bg-white border-start-0 rounded-end-3" id="nip" name="nip" value="<?= $this->session->flashdata('old_nip') ?>" placeholder="Nomor Induk Pegawai">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nama_petugas" class="form-label fw-bold text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-light-subtle rounded-start-3 text-secondary"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control border-light-subtle bg-white border-start-0 rounded-end-3" id="nama_petugas" name="nama_petugas" value="<?= $this->session->flashdata('old_nama_petugas') ?>" placeholder="Nama lengkap petugas">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="pangkat" class="form-label fw-bold text-secondary">Pangkat / Golongan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-light-subtle rounded-start-3 text-secondary"><i class="fas fa-award"></i></span>
                                <input type="text" class="form-control border-light-subtle bg-white border-start-0 rounded-end-3" id="pangkat" name="pangkat" value="<?= $this->session->flashdata('old_pangkat') ?>" placeholder="cth: Penata Muda / III-a">
                            </div>
                        </div>

                        <div class="mb-1">
                            <label for="jabatan" class="form-label fw-bold text-secondary">Jabatan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-light-subtle rounded-start-3 text-secondary"><i class="fas fa-briefcase"></i></span>
                                <input type="text" class="form-control border-light-subtle bg-white border-start-0 rounded-end-3" id="jabatan" name="jabatan" value="<?= $this->session->flashdata('old_jabatan') ?>" placeholder="cth: Penera">
                            </div>
                        </div>
                    </div>
                    <!-- ===== END SECTION DATA PETUGAS ===== -->
                </div>
                <div class="modal-footer border-0 bg-white">
                    <button type="button" class="btn btn-light px-4 rounded-pill fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold shadow-sm"><i class="fas fa-save me-2"></i>Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Pengguna -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-warning border-0 py-3">
                <h5 class="modal-title fw-bold text-dark" id="editUserModalLabel"><i class="fas fa-user-edit me-2"></i>Edit Data Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" action="" method="post">
                <div class="modal-body p-4 bg-light">
                    <div class="mb-3">
                        <label for="edit_username" class="form-label fw-bold text-secondary">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-light-subtle rounded-start-3 text-secondary"><i class="fas fa-user-circle"></i></span>
                            <input type="text" class="form-control border-light-subtle bg-white border-start-0 rounded-end-3" id="edit_username" name="username" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_email" class="form-label fw-bold text-secondary">Alamat Email <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-light-subtle rounded-start-3 text-secondary"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control border-light-subtle bg-white border-start-0 rounded-end-3" id="edit_email" name="email" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_password" class="form-label fw-bold text-secondary">Password <small class="fw-normal text-muted">(Opsional)</small></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-light-subtle rounded-start-3 text-secondary"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control border-light-subtle bg-white border-start-0" id="edit_password" name="password">
                            <button class="btn btn-outline-secondary border-light-subtle bg-white border-start-0 rounded-end-3" type="button" id="toggleEditPassword">
                                <i class="fas fa-eye text-secondary"></i>
                            </button>
                        </div>
                        <div class="form-text mt-1 text-muted"><i class="fas fa-info-circle me-1"></i> Kosongkan jika tidak ingin mengubah password.</div>
                    </div>

                    <div class="mb-1">
                        <label for="edit_level" class="form-label fw-bold text-secondary">Level Pengguna <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-light-subtle rounded-start-3 text-secondary"><i class="fas fa-shield-alt"></i></span>
                            <select class="form-select border-light-subtle bg-white border-start-0 rounded-end-3" id="edit_level" name="level" required>
                                <option value="Admin">Admin</option>
                                <option value="Kepala UPT">Kepala UPT</option>
                                <option value="Petugas">Petugas</option>
                                <option value="Pemohon">Pemohon</option>
                            </select>
                        </div>
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
    // Inisialisasi DataTables
    const usersTable = $('#usersTable').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari pengguna...",
        }
    });
    $('.dataTables_filter input').addClass('form-control rounded-pill border-light-subtle shadow-sm').css('padding', '0.4rem 1rem');

    const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
    const tambahUserModal = new bootstrap.Modal(document.getElementById('tambahUserModal'));

    // Menampilkan notifikasi sukses (dari create/update) dengan SweetAlert
    <?php if ($this->session->flashdata('success')) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= $this->session->flashdata('success') ?>',
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>

    // Menangani toggle lihat/sembunyi password di modal tambah
    $('#togglePassword').on('click', function() {
        const passwordInput = $('#password');
        const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
        passwordInput.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    // Show/hide section data petugas berdasarkan pilihan level
    function toggleSectionPetugas() {
        const level = $('#level').val();
        const section = $('#sectionDataPetugas');
        if (level === 'Petugas') {
            section.slideDown(200);
            $('#nip, #nama_petugas').attr('required', true);
        } else {
            section.slideUp(200);
            $('#nip, #nama_petugas').removeAttr('required');
        }
    }
    $('#level').on('change', toggleSectionPetugas);
    // Jalankan saat halaman load (untuk kasus validasi error & modal dibuka ulang)
    toggleSectionPetugas();

    // Otomatis buka modal tambah jika ada validation error
    <?php if ($this->session->flashdata('open_tambah_modal')) : ?>
        tambahUserModal.show();
    <?php endif; ?>

    // Menangani klik tombol Edit
    $('#usersTable tbody').on('click', '.btn-edit', function() {
        const userId = $(this).closest('td').data('id');
            
        $.get(`<?= site_url('users/edit/') ?>${userId}`, function(data) {
            $('#editUserForm').attr('action', `<?= site_url('users/update/') ?>${userId}`);
            $('#edit_username').val(data.username);
            $('#edit_email').val(data.email);
            $('#edit_level').val(data.level);
            $('#edit_password').val('');
            
            editUserModal.show();
        }).fail(function() {
            Swal.fire('Error!', 'Gagal mengambil data pengguna.', 'error');
        });
    });

    // Menangani toggle lihat/sembunyi password di modal edit
    $('#toggleEditPassword').on('click', function() {
        const editPasswordInput = $('#edit_password');
        const type = editPasswordInput.attr('type') === 'password' ? 'text' : 'password';
        editPasswordInput.attr('type', type);
        $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    // Menangani klik tombol Hapus
    $('#usersTable tbody').on('click', '.btn-delete', function() {
        const userId = $(this).closest('td').data('id');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Anda yakin?',
            text: "Data pengguna ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `<?= site_url('users/delete/') ?>${userId}`,
                    type: 'DELETE',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(data) {
                        if (data.status === 'success') {
                            Swal.fire('Dihapus!', data.message, 'success');
                            usersTable.row(row).remove().draw();
                        } else {
                            Swal.fire('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON.message || 'Tidak dapat terhubung ke server.', 'error');
                    }
                });
            }
        });
    });
});
</script>
<?php $this->load->view('layout/dash_footer'); ?>