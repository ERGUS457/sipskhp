<?php $this->load->view('layout/dash_header'); ?>

<style>
.stat-kup { border-radius: 16px; padding: 24px 28px; color: #fff; position: relative; overflow: hidden; transition: transform .3s, box-shadow .3s; }
.stat-kup:hover { transform: translateY(-6px); box-shadow: 0 16px 32px rgba(0,0,0,.15); }
.stat-kup .bg-deco { position:absolute; width:130px; height:130px; border-radius:50%; background:rgba(255,255,255,.1); top:-30px; right:-30px; }
.stat-kup .num { font-size: 2.5rem; font-weight: 800; line-height:1; }
.stat-kup .lbl { font-size: .88rem; opacity:.85; margin-bottom:6px; }
.bg-blue   { background: linear-gradient(135deg,#3b82f6,#1d4ed8); }
.bg-green  { background: linear-gradient(135deg,#10b981,#047857); }
.bg-purple { background: linear-gradient(135deg,#8b5cf6,#6d28d9); }
.bg-teal   { background: linear-gradient(135deg,#0ea5e9,#0369a1); }
.bg-danger { background: linear-gradient(135deg,#ef4444,#dc2626); }
</style>

<div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-users-cog text-primary me-2"></i>Laporan Aktivitas Pengguna</h4>
        <p class="text-muted mb-0">Monitoring seluruh aktivitas login dan logout pengguna sistem.</p>
    </div>
</div>

<!-- ===== FILTER PERIODE ===== -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3"><i class="fas fa-filter text-primary me-2"></i>Filter Periode</h5>
        <form method="GET" action="<?= site_url('log-aktivitas') ?>" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-select">
                    <option value="" <?= ($filter_bulan === '' || $filter_bulan === null) ? 'selected' : '' ?>>-- Semua Bulan --</option>
                    <?php 
                    $bulans = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
                    foreach($bulans as $num => $nama): 
                    ?>
                    <option value="<?= $num ?>" <?= ($filter_bulan === $num) ? 'selected' : '' ?>><?= $nama ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    <option value="" <?= ($filter_tahun === '' || $filter_tahun === null) ? 'selected' : '' ?>>-- Semua Tahun --</option>
                    <?php for($i=date('Y'); $i>=2020; $i--): ?>
                    <option value="<?= $i ?>" <?= ($filter_tahun == $i) ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-100 rounded-pill"><i class="fas fa-search me-1"></i> Tampilkan</button>
                <a href="<?= site_url('log-aktivitas') ?>" class="btn btn-outline-secondary w-50 rounded-pill"><i class="fas fa-sync-alt"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- ===== STATISTIK LOGIN PER LEVEL ===== -->
<div class="row g-3 mb-4">
    <?php
    $levels = ['Admin','Kepala UPT','Pemohon','Petugas'];
    $lvl_colors = ['Admin'=>'bg-danger','Kepala UPT'=>'bg-purple','Pemohon'=>'bg-green','Petugas'=>'bg-teal'];
    $lvl_icons  = ['Admin'=>'fa-user-shield','Kepala UPT'=>'fa-crown','Pemohon'=>'fa-building','Petugas'=>'fa-hard-hat'];
    foreach($levels as $lv):
        $s = $stats_login[$lv] ?? ['jumlah_login'=>0,'jumlah_pengguna'=>0];
    ?>
    <div class="col-6 col-md-3">
        <div class="stat-kup <?= $lvl_colors[$lv] ?? 'bg-blue' ?>" style="padding:16px 20px;">
            <div class="bg-deco"></div>
            <div class="lbl"><i class="fas <?= $lvl_icons[$lv] ?> me-1"></i><?= $lv ?></div>
            <div class="num" style="font-size:1.8rem"><?= $s['jumlah_login'] ?></div>
            <small style="opacity:.8"><?= $s['jumlah_pengguna'] ?> pengguna unik</small>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- ===== TABEL LOG AKTIVITAS ===== -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0"><i class="fas fa-list-alt text-primary me-2"></i>Riwayat Aktivitas</h5>
            <span class="badge bg-primary rounded-pill"><?= count($log_aktivitas) ?> aktivitas</span>
        </div>

        <!-- Filter Log -->
        <div class="row g-2 mb-3">
            <div class="col-md-3">
                <select id="filterLvl" class="form-select form-select-sm">
                    <option value="">-- Semua Level --</option>
                    <option value="Admin">Admin</option>
                    <option value="Kepala UPT">Kepala UPT</option>
                    <option value="Pemohon">Pemohon</option>
                    <option value="Petugas">Petugas</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterAksi" class="form-select form-select-sm">
                    <option value="">-- Semua Aksi --</option>
                    <option value="Login">Login</option>
                    <option value="Logout">Logout</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" id="filterCari" class="form-control form-control-sm" placeholder="Cari username...">
            </div>
        </div>

        <!-- Tabel Log -->
        <div class="table-responsive">
            <table class="table table-sm table-hover" style="font-size:.84rem" id="tabelLog">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Level</th>
                        <th>Aksi</th>
                        <th>Keterangan</th>
                        <th>IP Address</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(empty($log_aktivitas)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-3">Belum ada aktivitas pada periode ini.</td></tr>
                <?php else: ?>
                    <?php foreach($log_aktivitas as $i => $log): ?>
                    <tr class="log-row" data-level="<?= htmlspecialchars($log['level']) ?>" data-aksi="<?= htmlspecialchars($log['aksi']) ?>" data-user="<?= strtolower(htmlspecialchars($log['username'])) ?>">
                        <td class="text-muted"><?= $i+1 ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($log['username']) ?></td>
                        <td>
                            <?php
                            $badge = ['Admin'=>'danger','Kepala UPT'=>'warning','Pemohon'=>'success','Petugas'=>'info'];
                            $bc = $badge[$log['level']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $bc ?>"><?= $log['level'] ?></span>
                        </td>
                        <td>
                            <?php if($log['aksi']==='Login'): ?>
                                <span class="badge bg-success"><i class="fas fa-sign-in-alt me-1"></i>Login</span>
                            <?php elseif($log['aksi']==='Logout'): ?>
                                <span class="badge bg-secondary"><i class="fas fa-sign-out-alt me-1"></i>Logout</span>
                            <?php else: ?>
                                <span class="badge bg-primary"><?= htmlspecialchars($log['aksi']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small"><?= htmlspecialchars($log['keterangan'] ?? '-') ?></td>
                        <td class="text-muted small font-monospace"><?= htmlspecialchars($log['ip_address'] ?? '-') ?></td>
                        <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Filter tabel log aktivitas
    function filterLog() {
        var lvl  = $('#filterLvl').val().toLowerCase();
        var aksi = $('#filterAksi').val().toLowerCase();
        var cari = $('#filterCari').val().toLowerCase();
        $('.log-row').each(function() {
            var ok = true;
            if (lvl  && $(this).data('level').toLowerCase() !== lvl)  ok = false;
            if (aksi && $(this).data('aksi').toLowerCase()  !== aksi) ok = false;
            if (cari && $(this).data('user').indexOf(cari)  === -1)   ok = false;
            $(this).toggle(ok);
        });
    }
    $('#filterLvl, #filterAksi').on('change', filterLog);
    $('#filterCari').on('input', filterLog);
});
</script>

<?php $this->load->view('layout/dash_footer'); ?>
