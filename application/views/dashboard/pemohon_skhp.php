<?php $this->load->view('layout/header', ['title' => $title]); ?>
<?php $this->load->view('layout/nav_pemohon'); ?>

<style>
    body { background-color: #f0fdf4; }
    .pemohon-container { margin-top: 100px; margin-bottom: 60px; min-height: 70vh; }
    .title-wrapper { border-bottom: 2px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 30px; }
    .card-skhp { border: none; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.04); overflow: hidden; }
    .card-skhp .card-body { padding: 25px; background-color: white; }
    .btn-print { background-color: #10b981; color: white; border: none; border-radius: 6px; padding: 6px 15px; font-size: 0.9rem; font-weight: 500; transition: all 0.2s; }
    .btn-print:hover { background-color: #059669; color: white; }
</style>

<div class="container pemohon-container">
    <div class="title-wrapper d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <h2 class="fw-bold mb-0" style="color: var(--nav-primary);"><i class="fas fa-certificate me-2"></i> CETAK SKHP (Surat Keterangan Hasil Pengujian)</h2>
    </div>

    <div class="card card-skhp">
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <i class="fas fa-info-circle me-2"></i> Daftar di bawah ini adalah alat yang telah selesai diuji dan SKHP-nya sudah diterbitkan. Anda dapat melihat dan mencetak dokumennya langsung.
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tableSKHP">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--nav-primary);">
                            <th class="text-center" width="5%">No</th>
                            <th>Alat Timbang/Ukur</th>
                            <th>Merk / Kapasitas</th>
                            <th class="text-center">Tanggal Terbit</th>
                            <th class="text-center">Aksi Cetak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pengajuan)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-file-excel fs-1 mb-3" style="color: #cbd5e1;"></i><br>
                                Belum ada SKHP yang terbit.
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php $no=1; foreach($pengajuan as $row): ?>
                            <tr>
                                <td class="text-center fw-bold"><?= $no++ ?></td>
                                <td class="fw-semibold" style="color: #334155;"><?= htmlspecialchars($row['nama_alat']) ?></td>
                                <td class="text-muted"><?= htmlspecialchars($row['merk']) ?> / <span class="fw-medium"><?= htmlspecialchars($row['kapasitas']) ?></span></td>
                                <td class="text-center">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                        <i class="far fa-calendar-check me-1"></i>
                                        <?= !empty($row['tgl_tugas']) ? date('d M Y', strtotime($row['tgl_tugas'])) : date('d M Y') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="<?= site_url('kelola-skhp/cetak/' . $row['id_alat']) ?>" target="_blank" class="btn btn-print shadow-sm">
                                        <i class="fas fa-print me-1"></i> Cetak PDF
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#tableSKHP').DataTable({
        responsive: true,
        language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json' }
    });
});
</script>

<?php $this->load->view('layout/footer'); ?>
