<?php $this->load->view('layout/dash_header'); // Memuat bagian header dari layout ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0 fw-bold">Daftar Pengajuan Alat UTTP</h4>
    </div>
</div>

<!-- Menampilkan notifikasi flash data (pesan sukses atau error) setelah aksi tertentu -->
<?php if ($this->session->flashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $this->session->flashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $this->session->flashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <!-- Tabel daftar pengajuan, diinisialisasi dengan DataTables di bagian script -->
            <table id="pengajuanTable" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="rounded-start">No</th>
                        <th>Data Alat</th>
                        <th>Pemilik / Usaha</th>
                        <th>Log Status</th>
                        <th class="rounded-end text-center">Tindak Lanjut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($pengajuan as $item): ?>
                        <?php
                            // Mengecek status pengajuan berdasarkan data relasi
                            $is_processed   = !is_null($item['id_surat_tugas']); // Apakah sudah ada surat tugas?
                            $is_selesai_uji = isset($item['status']) && $item['status'] === 'Selesai'; // Apakah status uji selesai oleh petugas?
                            $is_tervalidasi = isset($item['status_validasi']) && $item['status_validasi'] === 'Tervalidasi'; // Apakah sudah divalidasi oleh Kepala UPT?

                            // Menentukan desain badge/label status
                            if ($is_tervalidasi) {
                                $status_badge = '<span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill"><i class="fas fa-stamp me-1"></i> Tervalidasi KaUPT</span>';
                            } elseif ($is_selesai_uji) {
                                $status_badge = '<span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill"><i class="fas fa-check-double me-1"></i> Uji Selesai</span>';
                            } elseif ($is_processed) {
                                $status_badge = '<span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill"><i class="fas fa-cogs me-1"></i> Proses Uji</span>';
                            } else {
                                $status_badge = '<span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill"><i class="fas fa-hourglass-half me-1"></i> Menunggu Tugas</span>';
                            }
                        ?>
                        <tr>
                            <td class="fw-bold"><?= $no++ ?></td>
                            <td>
                                <!-- Menampilkan nama alat, merk, dan tipe -->
                                <span class="d-block fw-semibold text-dark"><?= htmlspecialchars((string) $item['nama_alat']) ?></span>
                                <small class="text-muted"><?= htmlspecialchars((string) $item['merk']) ?> / <?= htmlspecialchars((string) $item['tipe_model']) ?></small>
                            </td>
                            <td>
                                <!-- Menampilkan identitas pemohon -->
                                <span class="d-block fw-semibold"><?= htmlspecialchars((string) $item['nama_pemilik']) ?></span>
                                <small class="text-muted"><i class="fas fa-store me-1"></i><?= htmlspecialchars((string) $item['jenis_usaha']) ?></small>
                            </td>
                            <td><?= $status_badge ?></td>
                            <td class="text-center">
                                <!-- Tombol aksi berbeda tergantung dari status pengajuan -->
                                <?php if ($is_tervalidasi): ?>
                                    <a href="<?= site_url('kelola-skhp/cetak/' . $item['id_alat']) ?>" target="_blank"
                                       class="btn btn-success btn-sm px-3 shadow-sm rounded-pill text-white fw-bold" title="Cetak SKHP">
                                        <i class="fas fa-certificate me-1"></i> Cetak SKHP
                                    </a>
                                <?php elseif ($is_selesai_uji): ?>
                                    <button class="btn btn-warning btn-sm px-3 rounded-pill text-dark fw-semibold" disabled title="Menunggu Validasi Kepala UPT">
                                        <i class="fas fa-signature me-1"></i> Menunggu Validasi
                                    </button>
                                <?php elseif ($is_processed): ?>
                                    <button class="btn btn-light btn-sm px-3 rounded-pill text-muted" disabled title="Sedang Proses Uji Lapangan">
                                        <i class="fas fa-cogs me-1"></i> Proses Uji
                                    </button>
                                <?php else: ?>
                                    <!-- Jika belum diproses, tombol ini memunculkan form modal Surat Tugas -->
                                    <button class="btn btn-primary btn-sm px-3 shadow-sm rounded-pill btn-proses"
                                        data-bs-toggle="modal"
                                        data-bs-target="#prosesModal"
                                        data-id="<?= $item['id_alat'] ?>"
                                        data-alat-info="<?= htmlspecialchars((string) $item['nama_alat'] . ' - ' . $item['merk']) ?>"
                                        title="Buat Surat Tugas Petugas">
                                        <i class="fas fa-file-signature me-1"></i> Buat Tugas
                                    </button>
                                <?php endif; ?>
                                <a href="<?= site_url('pengajuan/detail/' . $item['id_alat']) ?>" class="btn btn-info btn-sm px-3 shadow-sm rounded-pill text-white" title="Lihat Detail"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Form Buat Surat Tugas (Modal Popup) -->
<div class="modal fade" id="prosesModal" tabindex="-1" aria-labelledby="prosesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prosesModalLabel">Terbitkan Surat Tugas Terkalibrasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('surat-tugas/proses') ?>" method="post">
                <div class="modal-body">
                    <!-- id_alat akan diisi secara dinamis oleh javascript saat tombol ditekan -->
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
        // Menerapkan library DataTables ke tabel untuk fitur pencarian & pagination
        $('#pengajuanTable').DataTable();
        
        // Menerapkan library Select2 pada dropdown petugas di dalam modal
        $('#id_petugas').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#prosesModal'),
            width: '100%',
            allowClear: true
        });
        
        // Menyuntikkan data alat ke dalam modal ketika modal dimunculkan
        const prosesModal = document.getElementById('prosesModal');
        prosesModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const idAlat = button.getAttribute('data-id');
            const alatInfo = button.getAttribute('data-alat-info');
            prosesModal.querySelector('#modal_alat_info').textContent = alatInfo;
            prosesModal.querySelector('#modal_id_alat').value = idAlat;
        });
    });
</script>
<?php $this->load->view('layout/dash_footer'); // Memuat bagian footer dari layout ?>