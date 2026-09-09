<?php $this->load->view('layout/dash_header'); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Kelola Data Pemohon</h4>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table id="pemohonTable" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="rounded-start">No</th>
                        <th>ID Registrasi</th>
                        <th>Kreditensial Usaha</th>
                        <th>Sektor Bidang</th>
                        <th>Kontak Telepon</th>
                        <th>Alamat Surel (Akun)</th>
                        <th class="rounded-end text-center">Tinjauan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($pemohon as $item): ?>
                        <tr>
                            <td class="fw-bold"><?= $no++ ?></td>
                            <td class="text-primary fw-bold"><?= htmlspecialchars((string) $item['id_pemohon']) ?></td>
                            <td>
                                <span class="d-block fw-bold text-dark"><?= htmlspecialchars((string) $item['nama_pemilik']) ?></span>
                                <small class="text-muted"><i class="fas fa-building me-1"></i> Terdaftar Resmi</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border px-3 py-2 rounded-pill"><?= htmlspecialchars((string) $item['jenis_usaha']) ?></span>
                            </td>
                            <td class="fw-semibold text-primary"><i class="fab fa-whatsapp me-1 text-success"></i><?= htmlspecialchars((string) $item['kontak_pemohon']) ?></td>
                            <td class="text-muted"><?= htmlspecialchars((string) $item['email']) ?></td>
                            <td class="text-center">
                                <a href="<?= site_url('kelola-pemohon/detail/' . $item['id_pemohon']) ?>" class="btn btn-info btn-sm px-3 shadow-sm rounded-pill text-white" title="Lihat Profil Detail"><i class="fas fa-eye me-1"></i> Profil</a>
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
        $('#pemohonTable').DataTable();
    });
</script>
<?php $this->load->view('layout/dash_footer'); ?>