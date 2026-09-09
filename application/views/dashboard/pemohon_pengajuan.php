<?php $this->load->view('layout/header', ['title' => $title]); ?>
<?php $this->load->view('layout/nav_pemohon'); ?>

<style>
    body { background-color: #f0fdf4; }
    .pemohon-container { margin-top: 100px; margin-bottom: 60px; min-height: 70vh; }
    .title-wrapper { border-bottom: 2px solid #e5e7eb; padding-bottom: 15px; margin-bottom: 30px; }
    .btn-create-pengajuan { background-color: var(--nav-primary); color: white; font-weight: 600; padding: 10px 20px; border-radius: 8px; border: none; transition: all 0.3s; }
    .btn-create-pengajuan:hover { background-color: var(--nav-secondary); color: white; transform: translateY(-2px); }
    .card-pengajuan { border: none; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.04); overflow: hidden; }
    .card-pengajuan .card-body { padding: 25px; background-color: white; }
</style>

<div class="container pemohon-container">
    <div class="title-wrapper d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <h2 class="fw-bold mb-0" style="color: var(--nav-primary);"><i class="fas fa-clipboard-list me-2"></i> DATA PENGAJUAN TERA</h2>
        <button type="button" class="btn btn-create-pengajuan" data-bs-toggle="modal" data-bs-target="#modalPengajuan">
            <i class="fas fa-plus-circle me-2"></i> Ajukan Alat Baru
        </button>
    </div>

    <div class="card card-pengajuan">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablePengajuan">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--nav-primary);">
                            <th class="text-center" width="5%">No</th>
                            <th>Alat Timbang/Ukur</th>
                            <th>Merk / Kapasitas</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pengajuan)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-box-open fs-1 mb-3" style="color: #cbd5e1;"></i><br>
                                Belum ada riwayat pengajuan.
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php $no=1; foreach($pengajuan as $row): ?>
                            <tr>
                                <td class="text-center fw-bold"><?= $no++ ?></td>
                                <td class="fw-semibold" style="color: #334155;"><?= htmlspecialchars($row['nama_alat']) ?></td>
                                <td class="text-muted"><?= htmlspecialchars($row['merk']) ?> / <span class="fw-medium"><?= htmlspecialchars($row['kapasitas']) ?></span></td>
                                <td class="text-center"><?= htmlspecialchars($row['jumlah']) ?> Unit</td>
                                <?php
                                    $is_tervalidasi = isset($row['status_validasi']) && $row['status_validasi'] === 'Tervalidasi';
                                    $is_selesai_uji = isset($row['status_uji']) && $row['status_uji'] === 'Selesai';
                                    $is_diproses    = !is_null($row['id_surat_tugas']);

                                    $tgl_uji_indo = '';
                                    if (!empty($row['tgl_tugas'])) {
                                        $bulan_indo = [
                                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                        ];
                                        $parts = explode('-', $row['tgl_tugas']);
                                        if (count($parts) === 3) {
                                            $tgl_uji_indo = $parts[2] . ' ' . $bulan_indo[(int)$parts[1]] . ' ' . $parts[0];
                                        }
                                    }

                                    if ($is_tervalidasi) {
                                        $badge = '<span class="badge bg-success rounded-pill px-3 py-2"><i class="fas fa-stamp me-1"></i> Tervalidasi</span>';
                                        if ($tgl_uji_indo) {
                                            $badge .= '<div class="small text-muted mt-1" style="font-size: 0.8rem;"><i class="far fa-calendar-alt me-1"></i> Diuji: ' . htmlspecialchars($tgl_uji_indo) . '</div>';
                                        }
                                    } elseif ($is_selesai_uji) {
                                        $badge = '<span class="badge bg-primary rounded-pill px-3 py-2"><i class="fas fa-check-double me-1"></i> Uji Selesai</span>';
                                        if ($tgl_uji_indo) {
                                            $badge .= '<div class="small text-muted mt-1" style="font-size: 0.8rem;"><i class="far fa-calendar-alt me-1"></i> Diuji: ' . htmlspecialchars($tgl_uji_indo) . '</div>';
                                        }
                                    } elseif ($is_diproses) {
                                        $badge = '<span class="badge bg-info rounded-pill px-3 py-2"><i class="fas fa-cogs me-1"></i> Sedang Diuji</span>';
                                        if ($tgl_uji_indo) {
                                            $badge .= '<div class="small text-primary fw-semibold mt-1" style="font-size: 0.8rem;"><i class="far fa-calendar-alt me-1"></i> Jadwal Uji: ' . htmlspecialchars($tgl_uji_indo) . '</div>';
                                        }
                                    } else {
                                        $badge = '<span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="fas fa-hourglass-half me-1"></i> Menunggu Proses</span>';
                                        
                                        $tgl_pengajuan = isset($row['created_at']) ? new DateTime($row['created_at']) : new DateTime();
                                        $target_uji = clone $tgl_pengajuan;
                                        $target_uji->modify('+2 days');
                                        $sekarang = new DateTime();
                                        
                                        if ($target_uji > $sekarang) {
                                            $sisa_hari = $sekarang->diff($target_uji)->days;
                                            $keterangan = ($sisa_hari == 0) ? '1 hari kemudian' : ($sisa_hari + 1) . ' hari kemudian';
                                            $badge .= '<div class="small text-muted mt-1 fw-medium" style="font-size: 0.75rem;"><i class="fas fa-info-circle text-warning me-1"></i>Petugas akan melakukan<br>pengujian ' . $keterangan . '</div>';
                                        } else {
                                            $badge .= '<div class="small text-muted mt-1 fw-medium" style="font-size: 0.75rem;"><i class="fas fa-info-circle text-warning me-1"></i>Akan segera dijadwalkan<br>oleh petugas</div>';
                                        }
                                    }
                                ?>
                                <td class="text-center">
                                    <?= $badge ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

<!-- Form Pengajuan -->
<div class="modal fade" id="modalPengajuan" tabindex="-1" aria-labelledby="modalPengajuanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--nav-primary) 0%, var(--nav-secondary) 100%); color: white; border-radius: 12px 12px 0 0;">
                <h5 class="modal-title fw-bold" id="modalPengajuanLabel"><i class="fas fa-plus-circle me-2"></i> Form Pengajuan Tera Alat UTTP</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('pemohon-dashboard/tambah_pengajuan') ?>" method="POST">
                <div class="modal-body p-4">
                    <div class="alert alert-info" style="font-size: 0.9rem;">
                        <i class="fas fa-info-circle me-2"></i> Pilih Jenis Alat UTTP terlebih dahulu. Form akan menyesuaikan data spesifik yang dibutuhkan.
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-primary">Jenis Alat UTTP <span class="text-danger">*</span></label>
                        <select class="form-select border-primary" name="jenis_alat" id="jenis_alat_pengajuan" required onchange="toggleFieldsPengajuan()">
                            <option value="">-- Pilih Jenis Alat --</option>
                            <option value="Timbangan Umum">Timbangan Umum (Meja, Duduk, dll)</option>
                            <option value="Timbangan Berat Badan">Timbangan Berat Badan</option>
                            <option value="Alat Ukur Panjang">Alat Ukur Panjang</option>
                            <option value="Pompa Ukur BBM (SPBU)">Pompa Ukur BBM (SPBU)</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama / Detail Alat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_alat" required placeholder="Contoh: Timbangan Meja / SPBU No. 1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Merk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="merk" required placeholder="Merk Alat">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Tipe / Model <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="tipe_model" required placeholder="Tipe/Model">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Nomor Seri <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nomor_seri" required placeholder="Nomor Seri">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Buatan (Negara/Pabrik) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="buatan" required placeholder="Buatan">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Jumlah (Unit) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="jumlah" required min="1" value="1">
                        </div>
                    </div>

                    <!-- Field Khusus Umum / Panjang / Berat Badan -->
                    <div class="row" id="field_umum_pengajuan" style="display: none; background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 10px;">
                        <h6 class="fw-bold text-secondary mb-3"><i class="fas fa-sliders-h me-2"></i>Spesifikasi <span id="label_umum_pengajuan">Alat</span></h6>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Kapasitas / Daya Baca <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="kapasitas" id="input_kapasitas_pengajuan" placeholder="Contoh: 100 kg / 50 g">
                        </div>
                    </div>

                    <!-- Field Khusus SPBU -->
                    <div class="row" id="field_spbu_pengajuan" style="display: none; background-color: #e0f2fe; padding: 15px; border-radius: 8px; margin-top: 10px;">
                        <h6 class="fw-bold text-info mb-3"><i class="fas fa-gas-pump me-2"></i>Spesifikasi Pompa Ukur BBM (SPBU)</h6>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Kecepatan Alir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="kecepatan_alir" id="input_kecepatan_pengajuan" placeholder="Contoh: 40 L/min">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">No. Pulau - No. Mesin <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="no_pulau_mesin" id="input_pulau_pengajuan" placeholder="Contoh: P.1 - 1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">No. Nozel - Jenis BBM <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="no_nozel_bbm" id="input_nozel_pengajuan" placeholder="Contoh: A-Solar">
                        </div>
                    </div>

                </div>
                <div class="modal-footer" style="background-color: #f8f9fa; border-radius: 0 0 12px 12px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn" style="background-color: var(--nav-primary); color: white;"><i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#tablePengajuan').DataTable({
        responsive: true,
        language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json' }
    });

});

function toggleFieldsPengajuan() {
    var jenis = document.getElementById('jenis_alat_pengajuan').value;
    var fieldUmum = document.getElementById('field_umum_pengajuan');
    var fieldSpbu = document.getElementById('field_spbu_pengajuan');
    var labelUmum = document.getElementById('label_umum_pengajuan');
    
    // reset required
    document.getElementById('input_kapasitas_pengajuan').required = false;
    document.getElementById('input_kecepatan_pengajuan').required = false;
    document.getElementById('input_pulau_pengajuan').required = false;
    document.getElementById('input_nozel_pengajuan').required = false;

    if (jenis === 'Pompa Ukur BBM (SPBU)') {
        fieldSpbu.style.display = 'flex';
        fieldUmum.style.display = 'none';
        
        document.getElementById('input_kecepatan_pengajuan').required = true;
        document.getElementById('input_pulau_pengajuan').required = true;
        document.getElementById('input_nozel_pengajuan').required = true;
    } else if (jenis !== '') {
        fieldUmum.style.display = 'flex';
        fieldSpbu.style.display = 'none';
        labelUmum.innerText = jenis;
        
        document.getElementById('input_kapasitas_pengajuan').required = true;
    } else {
        fieldUmum.style.display = 'none';
        fieldSpbu.style.display = 'none';
    }
}
</script>

<?php $this->load->view('layout/footer'); ?>

<script>
    // Tampilkan SweetAlert jika ada flashdata success
    <?php if ($this->session->flashdata('success')) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= $this->session->flashdata('success') ?>',
            confirmButtonColor: '#065f46',
            confirmButtonText: 'OKE'
        });
    <?php endif; ?>

    // Tampilkan SweetAlert jika ada flashdata error
    <?php if ($this->session->flashdata('error')) : ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?= $this->session->flashdata('error') ?>',
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Tutup'
        });
    <?php endif; ?>
</script>
