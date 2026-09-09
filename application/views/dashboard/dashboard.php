<?php $this->load->view('layout/dash_header'); // Memuat bagian header dari layout ?>
<style>
    /* Styling untuk bagian judul halaman */
    .title-wrapper {
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 15px;
        margin-bottom: 30px;
    }
    .title-wrapper h2 {
        font-weight: 700;
        color: #1f2937;
        letter-spacing: -0.5px;
    }

    /* Kartu Statistik Mewah */
    .stat-card {
        border: none;
        border-radius: 16px;
        color: #fff;
        overflow: hidden;
        position: relative;
        box-shadow: 0 10px 20px rgba(0,0,0,0.06);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.12);
    }
    
    /* Gradients Modern untuk warna latar kartu statistik */
    .grad-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    }
    .grad-success {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
    }
    .grad-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);
    }

    /* Elemen di dalam kartu statistik */
    .stat-card .card-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 30px;
        position: relative;
        z-index: 2;
    }
    .stat-card .card-title {
        font-size: 1.1rem;
        font-weight: 500;
        opacity: 0.9;
        margin-bottom: 5px;
    }
    .stat-card .stat-number {
        font-size: 2.8rem;
        font-weight: 800;
        margin: 0;
        line-height: 1;
    }
    .stat-card .stat-icon {
        font-size: 4rem;
        opacity: 0.2;
        transition: transform 0.3s;
    }
    .stat-card:hover .stat-icon {
        transform: scale(1.15) rotate(5deg);
    }

    /* Pola Hiasan Latar Belakang Kartu */
    .card-decoration {
        position: absolute;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: -30px;
        right: -30px;
        z-index: 1;
    }
</style>

<div class="title-wrapper">
    <h2><i class="fas fa-chart-line me-2 text-primary"></i> RINGKASAN DATA E-TERA</h2>
    <p class="text-muted mb-0">Pemantauan aktivitas pelayanan pengujian secara terkini.</p>
</div>

<?php 
// Hanya Admin yang dapat melihat notifikasi pengajuan baru di bagian atas
if ($this->session->userdata('level') === 'Admin'): ?>
<div class="row g-4 mb-5">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                <div>
                    <h5 class="mb-1">Notifikasi Pengajuan Baru</h5>
                    <p class="mb-0 text-white-75" style="font-size: 0.95rem;">Pengajuan terbaru dari pemohon yang belum diproses.</p>
                </div>
                <span class="badge bg-warning text-dark py-2 px-3 fw-semibold"><?= $pending_pengajuan ?> Baru</span>
            </div>
            <?php
            // === Helper: Format tanggal Indonesia dari created_at (WIB) ===
            // Fungsi-fungsi helper untuk memformat tampilan waktu menjadi lebih ramah dibaca
            if (!function_exists('format_tanggal_id')) {
                function format_tanggal_id($datetime_str) {
                    $nama_hari  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                    $nama_bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
                                'Juli','Agustus','September','Oktober','November','Desember'];

                    $dt = new DateTime($datetime_str, new DateTimeZone('Asia/Jakarta'));
                    $hari  = $nama_hari[(int)$dt->format('w')];
                    $tgl   = (int)$dt->format('d');
                    $bulan = $nama_bulan[(int)$dt->format('m')];
                    $tahun = $dt->format('Y');
                    $jam   = $dt->format('H:i');

                    return $hari . ', ' . $tgl . ' ' . $bulan . ' ' . $tahun . ' • ' . $jam . ' WIB';
                }
            }
            if (!function_exists('waktu_lalu_id')) {
                function waktu_lalu_id($datetime_str) {
                    $waktu_db  = new DateTime($datetime_str, new DateTimeZone('Asia/Jakarta'));
                    $sekarang  = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
                    $selisih   = $sekarang->getTimestamp() - $waktu_db->getTimestamp();

                    if ($selisih < 1)      return 'baru saja';
                    if ($selisih < 60)     return $selisih . ' detik yang lalu';
                    if ($selisih < 3600)   return floor($selisih / 60) . ' menit yang lalu';
                    if ($selisih < 86400)  return floor($selisih / 3600) . ' jam yang lalu';
                    if ($selisih < 2592000) return floor($selisih / 86400) . ' hari yang lalu';
                    if ($selisih < 31536000) return floor($selisih / 2592000) . ' bulan yang lalu';
                    return floor($selisih / 31536000) . ' tahun yang lalu';
                }
            }
            ?>
            <div class="card-body p-0 bg-white">
                <?php if (!empty($pending_pengajuan_list)): ?>
                    <div class="list-group list-group-flush">
                        <?php 
                        // Melakukan perulangan untuk setiap notifikasi yang belum diproses
                        foreach ($pending_pengajuan_list as $notif): ?>
                            <a href="javascript:void(0);" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-4 py-3 border-bottom btn-proses-notif" 
                               data-bs-toggle="modal" 
                               data-bs-target="#prosesModal" 
                               data-id="<?= $notif['id_alat'] ?>" 
                               data-alat-info="<?= htmlspecialchars((string) $notif['nama_alat'] . ' - ' . $notif['nama_pemilik']) ?>">
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 0.95rem;"><?= htmlspecialchars($notif['nama_alat']) ?></div>
                                    <div class="text-muted small mt-1"><i class="fas fa-building me-1"></i><?= htmlspecialchars($notif['nama_pemilik']) ?></div>
                                </div>
                                <div class="text-end">
                                    <?php if (!empty($notif['created_at'])): ?>
                                        <span class="badge bg-info text-dark mb-1" style="font-size: 0.72rem;"><?= format_tanggal_id($notif['created_at']) ?></span>
                                        <div class="small text-muted"><?= waktu_lalu_id($notif['created_at']) ?></div>
                                    <?php else: ?>
                                        <span class="badge bg-secondary text-white" style="font-size: 0.72rem;">Belum tersedia</span>
                                    <?php endif; ?>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="p-4 text-center text-muted">Belum ada pengajuan baru yang menunggu proses.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row g-4 mb-5">
    <!-- Card Jumlah Pengajuan -->
    <div class="col-md-4">
        <div class="card stat-card grad-primary">
            <div class="card-decoration"></div>
            <div class="card-body">
                <div>
                    <h5 class="card-title">Berkas Pengajuan</h5>
                    <p class="stat-number"><?= $jumlah_pengajuan ?></p>
                </div>
                <i class="fas fa-file-invoice stat-icon"></i>
            </div>
        </div>
    </div>
    
    <!-- Card Menunggu Validasi -->
    <div class="col-md-4">
        <div class="card stat-card grad-warning text-dark" style="background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);">
            <div class="card-decoration"></div>
            <div class="card-body">
                <div>
                    <h5 class="card-title">Tinjauan Validasi</h5>
                    <p class="stat-number"><?= $menunggu_validasi ?></p>
                </div>
                <i class="fas fa-clipboard-check stat-icon"></i>
            </div>
        </div>
    </div>

    <!-- Card Laporan Rekapitulasi -->
    <div class="col-md-4">
        <div class="card stat-card" style="background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);">
            <div class="card-decoration"></div>
            <div class="card-body">
                <div>
                    <h5 class="card-title">Arsip Rekap Laporan</h5>
                    <p class="stat-number"><?= $total_laporan ?></p>
                </div>
                <i class="fas fa-folder-open stat-icon"></i>
            </div>
        </div>
    </div>
    
    <!-- Card SKHP Terbit -->
    <div class="col-md-6">
        <div class="card stat-card grad-success">
             <div class="card-decoration" style="background: rgba(255,255,255,0.05); width: 200px; height: 200px;"></div>
            <div class="card-body">
                <div>
                    <h5 class="card-title">Total Sertifikat SKHP Beredar</h5>
                    <p class="stat-number"><?= $total_skhp ?></p>
                </div>
                <i class="fas fa-stamp stat-icon"></i>
            </div>
        </div>
    </div>
    
    <!-- Card Jumlah Pemohon -->
    <div class="col-md-6">
        <div class="card stat-card" style="background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);">
            <div class="card-decoration" style="background: rgba(255,255,255,0.05); width: 200px; height: 200px;"></div>
            <div class="card-body">
                <div>
                    <h5 class="card-title">Pengguna Terdaftar</h5>
                    <p class="stat-number"><?= $jumlah_pemohon ?></p>
                </div>
                <i class="fas fa-users stat-icon"></i>
            </div>
        </div>
    </div>
</div>

<!-- Bagian Grafik Statistik -->
<div class="row g-4 mb-5">
    <!-- Grafik SKHP per Bulan -->
    <div class="col-md-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                <h5 class="card-title mb-0 fw-bold"><i class="fas fa-chart-bar text-primary me-2"></i>Penerbitan SKHP per Bulan (<?= date('Y') ?>)</h5>
            </div>
            <div class="card-body p-4">
                <canvas id="chartBulan" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Grafik SKHP per Jenis Alat -->
    <div class="col-md-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom pt-4 pb-3 px-4">
                <h5 class="card-title mb-0 fw-bold"><i class="fas fa-chart-pie text-success me-2"></i>SKHP per Jenis Alat</h5>
            </div>
            <div class="card-body p-4 d-flex justify-content-center align-items-center">
                <canvas id="chartAlat" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Form Buat Surat Tugas Modal -->
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
                        <!-- Dropdown untuk memilih petugas yang akan diberi surat tugas -->
                        <select class="form-select" id="id_petugas" name="id_petugas[]" multiple="multiple" required data-placeholder="Ketik/Pilih Nama Petugas...">
                            <?php if(isset($petugas)): foreach ($petugas as $p): ?>
                                <option value="<?= $p['id_petugas'] ?>">
                                    <?= htmlspecialchars((string) $p['nama_petugas']) ?> - <?= htmlspecialchars((string) $p['jabatan']) ?>
                                </option>
                            <?php endforeach; endif; ?>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi library Select2 untuk dropdown petugas (jika tersedia)
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('#id_petugas').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#prosesModal'),
                width: '100%',
                allowClear: true
            });
        }
        
        // Mempersiapkan data pada modal ketika tombol "Proses Notifikasi" diklik
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

        // Tampilkan SWAL (SweetAlert) jika ada flash object session success/error dari controller
        <?php if ($this->session->flashdata('success')) : ?>
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '<?= $this->session->flashdata('success') ?>',
                    confirmButtonColor: '#10b981',
                    confirmButtonText: 'OKE'
                });
            }
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')) : ?>
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '<?= $this->session->flashdata('error') ?>',
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Tutup'
                });
            }
        <?php endif; ?>
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data dari PHP
        const dataBulan = <?= $chart_skhp_per_bulan ?? '[]' ?>;
        const labelAlat = <?= $chart_label_alat ?? '[]' ?>;
        const dataAlat = <?= $chart_data_alat ?? '[]' ?>;

        // Chart 1: SKHP per Bulan
        const ctxBulan = document.getElementById('chartBulan');
        if (ctxBulan) {
            new Chart(ctxBulan, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Jumlah SKHP Terbit',
                        data: dataBulan,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgb(37, 99, 235)',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        // Chart 2: SKHP per Jenis Alat
        const ctxAlat = document.getElementById('chartAlat');
        if (ctxAlat) {
            new Chart(ctxAlat, {
                type: 'doughnut',
                data: {
                    labels: labelAlat,
                    datasets: [{
                        data: dataAlat,
                        backgroundColor: [
                            '#3b82f6', '#10b981', '#f59e0b', '#ef4444', 
                            '#8b5cf6', '#06b6d4', '#f97316', '#64748b'
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 20, usePointStyle: true }
                        }
                    },
                    cutout: '65%'
                }
            });
        }
    });
</script>

<?php $this->load->view('layout/dash_footer'); // Memuat bagian footer dari layout ?>