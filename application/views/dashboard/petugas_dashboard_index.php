<?php $this->load->view('layout/petugas_header'); ?>

<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show mx-0 mb-3 rounded-3 shadow-sm" role="alert">
    <i class="fas fa-check-circle me-2"></i><?= $this->session->flashdata('success') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show mx-0 mb-3 rounded-3 shadow-sm" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i><?= $this->session->flashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Section Header -->
<div class="section-header">
    <h5><i class="fas fa-clipboard-list me-2"></i>Daftar Penugasan Saya</h5>
    <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="searchTask" placeholder="Cari nomor surat tugas...">
    </div>
</div>

<?php if (empty($surat_tugas)): ?>
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <p class="fw-semibold mb-1">Belum Ada Penugasan</p>
        <p class="mb-0" style="font-size:0.85rem;">Anda belum ditugaskan dalam surat tugas apapun saat ini.</p>
    </div>
<?php else: ?>

<div class="task-grid" id="taskGrid">
    <?php foreach ($surat_tugas as $st):
        $isSelesai = ($st['status'] === 'Selesai');
        $tglLabel  = date('d M Y', strtotime($st['tgl_tugas']));
        $today     = date('Y-m-d');
        $tglStr    = date('Y-m-d', strtotime($st['tgl_tugas']));
        if ($tglStr === $today)                                           $tglHuman = 'Hari Ini';
        elseif ($tglStr === date('Y-m-d', strtotime('-1 day')))          $tglHuman = 'Kemarin';
        else                                                              $tglHuman = $tglLabel;
    ?>
    <div class="task-card" data-no="<?= htmlspecialchars($st['no_surat_tugas']) ?>">
        <div class="task-card-header">
            <?php if ($isSelesai): ?>
                <span class="badge-status badge-selesai"><i class="fas fa-check-circle"></i> Selesai</span>
            <?php else: ?>
                <span style="font-size:0.75rem; color:#94a3b8;"><i class="fas fa-circle-dot me-1" style="font-size:0.6rem;"></i>Aktif</span>
            <?php endif; ?>
            <span class="task-date"><?= $tglHuman ?></span>
        </div>

        <div class="task-no"><?= htmlspecialchars((string)$st['no_surat_tugas']) ?></div>

        <div class="task-detail-row">
            <i class="fas fa-balance-scale"></i>
            <span><?= htmlspecialchars((string)$st['nama_alat']) ?><?= !empty($st['merk']) ? ' — ' . htmlspecialchars($st['merk']) : '' ?></span>
        </div>
        <div class="task-detail-row">
            <i class="fas fa-map-marker-alt"></i>
            <span><?= htmlspecialchars((string)($st['nama_pemilik'] ?: 'Lokasi tidak tersedia')) ?></span>
        </div>

        <div class="task-actions">
            <button class="btn-rincian"
                    onclick="bukaModal(<?= $st['id_surat_tugas'] ?>)">
                <i class="fas fa-info-circle me-1"></i> Rincian Penugasan
            </button>
            <a href="<?= site_url('petugas-dashboard/lihat/' . $st['id_surat_tugas']) ?>" target="_blank"
               style="display:block; text-align:center; background:#f8fafc; border:1px solid #e2e8f0; color:#1e40af; border-radius:8px; padding:8px 16px; font-size:0.82rem; font-weight:600; text-decoration:none; transition:background .2s;">
                <i class="fas fa-file-alt me-1"></i> Lihat Surat Tugas Resmi
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php endif; ?>


<!-- ============================================================ -->
<!-- MODAL: Detail Surat Tugas                                    -->
<!-- ============================================================ -->
<div id="modalST" style="display:none; position:fixed; inset:0; z-index:3000; background:rgba(0,0,0,0.55); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; border-radius:18px; width:100%; max-width:560px; max-height:90vh; overflow-y:auto; box-shadow:0 25px 60px rgba(0,0,0,0.25); animation: slideUp .25s ease;">
        <!-- Modal Header -->
        <div style="background:linear-gradient(135deg, #1e40af, #0891b2); color:white; padding:20px 24px; border-radius:18px 18px 0 0; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <div style="font-size:0.75rem; opacity:0.8; margin-bottom:2px;"><i class="fas fa-file-contract me-1"></i> SURAT TUGAS</div>
                <div id="m-no-st" style="font-size:1.05rem; font-weight:700;"></div>
            </div>
            <button onclick="tutupModal()" style="background:rgba(255,255,255,0.2); border:none; color:white; width:32px; height:32px; border-radius:50%; font-size:1rem; cursor:pointer; display:flex; align-items:center; justify-content:center;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div style="padding:24px;" id="m-body">
            <div id="m-loading" style="text-align:center; padding:30px; color:#94a3b8;">
                <i class="fas fa-spinner fa-spin fa-2x mb-3" style="display:block;"></i> Memuat data…
            </div>
            <div id="m-content" style="display:none;">
                <!-- Informasi Alat & Pemohon -->
                <div style="background:#f8fafc; border-radius:12px; padding:16px; margin-bottom:16px;">
                    <div style="font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin-bottom:10px;">Informasi Alat & Pemohon</div>
                    <div id="m-alat" style="margin-bottom:0;"></div>
                </div>
                <!-- Tanggal -->
                <div style="background:#f8fafc; border-radius:12px; padding:16px; margin-bottom:16px;">
                    <div style="font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin-bottom:10px;">Jadwal Pelaksanaan</div>
                    <div id="m-jadwal"></div>
                </div>
                <!-- Petugas Ditugaskan -->
                <div style="background:#f8fafc; border-radius:12px; padding:16px; margin-bottom:16px;">
                    <div style="font-size:0.7rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin-bottom:10px;">Petugas yang Ditugaskan</div>
                    <div id="m-petugas"></div>
                </div>
                <!-- Status -->
                <div id="m-status-wrap" style="text-align:center; margin-bottom:14px;"></div>
                <!-- Upload Cerapan Button -->
                <button id="m-btn-cerapan" onclick="bukaCerapan()" 
                        style="width:100%; background:linear-gradient(135deg,#d97706,#f59e0b); color:white; border:none; border-radius:10px; padding:10px; font-weight:600; font-size:0.88rem; cursor:pointer; display:none;">
                    <i class="fas fa-cloud-upload-alt me-2"></i> Upload Cerapan Tera
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL: Upload Foto Profil                                    -->
<!-- ============================================================ -->
<div id="modalFoto" style="display:none; position:fixed; inset:0; z-index:3001; background:rgba(0,0,0,0.6); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; border-radius:18px; width:100%; max-width:420px; box-shadow:0 25px 60px rgba(0,0,0,0.25); animation: slideUp .25s ease;">
        <div style="padding:20px 24px 16px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
            <strong style="font-size:1rem;"><i class="fas fa-camera text-primary me-2"></i> Ganti Foto Profil</strong>
            <button onclick="tutupFoto()" style="background:#f1f5f9; border:none; width:30px; height:30px; border-radius:50%; cursor:pointer;">✕</button>
        </div>
        <div style="padding:24px;">
            <form action="<?= site_url('petugas-dashboard/upload_foto') ?>" method="post" enctype="multipart/form-data">
                <div style="background:#f8fafc; border:2px dashed #cbd5e1; border-radius:12px; padding:24px; text-align:center; margin-bottom:16px; cursor:pointer;" onclick="document.getElementById('fotoInput').click()">
                    <i class="fas fa-cloud-upload-alt" style="font-size:2rem; color:#94a3b8; display:block; margin-bottom:8px;"></i>
                    <div style="font-size:0.85rem; color:#64748b;">Klik untuk pilih foto <br><small style="color:#94a3b8;">JPG, PNG, WebP — Maks 2MB</small></div>
                    <input type="file" id="fotoInput" name="foto" accept="image/*" style="display:none;" onchange="previewFoto(this)">
                </div>
                <img id="fotoPreview" src="" style="display:none; width:100%; border-radius:10px; margin-bottom:16px; max-height:220px; object-fit:cover;">
                <button type="submit" style="width:100%; background:linear-gradient(135deg,#1d4ed8,#0891b2); color:white; border:none; border-radius:10px; padding:11px; font-weight:600; font-size:0.9rem; cursor:pointer;">
                    <i class="fas fa-save me-1"></i> Simpan Foto
                </button>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes slideUp {
    from { transform: translateY(30px); opacity: 0; }
    to   { transform: translateY(0);   opacity: 1; }
}
.modal-row {
    display: flex;
    gap: 8px;
    align-items: flex-start;
    margin-bottom: 8px;
    font-size: 0.85rem;
}
.modal-row i { color: #94a3b8; width: 16px; flex-shrink: 0; margin-top: 2px; }
.modal-row strong { color: #1e293b; }
.modal-row span { color: #475569; }
</style>

<!-- ============================================================ -->
<!-- FORM : Upload Cerapan Tera                                   -->
<!-- ============================================================ -->
<div id="modalCerapan" style="display:none; position:fixed; inset:0; z-index:3002; background:rgba(0,0,0,0.6); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; border-radius:18px; width:100%; max-width:460px; box-shadow:0 25px 60px rgba(0,0,0,0.25); animation: slideUp .25s ease;">
        <div style="padding:20px 24px 16px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
            <strong style="font-size:1rem;"><i class="fas fa-cloud-upload-alt me-2" style="color:#d97706;"></i> Upload Cerapan Tera</strong>
            <button onclick="tutupCerapan()" style="background:#f1f5f9; border:none; width:30px; height:30px; border-radius:50%; cursor:pointer;">✕</button>
        </div>
        <div style="padding:24px;">
            <form id="formCerapan" action="<?= site_url('petugas-dashboard/upload_cerapan') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id_surat_tugas" id="cerapan-st-id" value="">
                <div style="margin-bottom:14px; font-size:0.82rem; background:#fef3c7; border-radius:10px; padding:10px 14px; color:#92400e;">
                    <i class="fas fa-info-circle me-1"></i> Unggah file cerapan tera (Excel, PDF) maksimal 5MB.
                </div>
                <div style="background:#f8fafc; border:2px dashed #cbd5e1; border-radius:12px; padding:20px; text-align:center; margin-bottom:14px; cursor:pointer;" onclick="document.getElementById('cerapanInput').click()">
                    <i class="fas fa-file-upload" style="font-size:1.8rem; color:#d97706; display:block; margin-bottom:8px;"></i>
                    <div style="font-size:0.83rem; color:#64748b;" id="cerapan-label">Klik untuk pilih file cerapan<br><small style="color:#94a3b8;">XLS, XLSX, PDF — Maks 5MB</small></div>
                    <input type="file" id="cerapanInput" name="file_cerapan" accept=".pdf,.xls,.xlsx" style="display:none;" onchange="labelCerapan(this)">
                </div>
                <div style="margin-bottom:14px;">
                    <label style="font-size:0.82rem; font-weight:600; color:#475569; display:block; margin-bottom:4px;">Catatan (opsional)</label>
                    <textarea name="catatan" rows="2" style="width:100%; border:1px solid #e2e8f0; border-radius:8px; padding:8px 12px; font-size:0.83rem; resize:none; outline:none;" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>
                <button type="submit" style="width:100%; background:linear-gradient(135deg,#d97706,#f59e0b); color:white; border:none; border-radius:10px; padding:11px; font-weight:600; font-size:0.9rem; cursor:pointer;">
                    <i class="fas fa-save me-1"></i> Kirim Cerapan
                </button>
            </form>
        </div>
    </div>
</div>

<script>
const detailUrl = '<?= site_url("petugas-dashboard/detail_st") ?>/';

// ===== MODAL SURAT TUGAS =====
function bukaModal(id) {
    _currentStId = id;
    const modal = document.getElementById('modalST');
    modal.style.display = 'flex';
    document.getElementById('m-loading').style.display = '';
    document.getElementById('m-content').style.display  = 'none';

    fetch(detailUrl + id, { credentials: 'same-origin' })
        .then(r => r.json())
        .then(d => {
            document.getElementById('m-no-st').textContent = d.no_surat_tugas || '-';

            // Alat & Pemohon
            document.getElementById('m-alat').innerHTML = `
                <div class="modal-row"><i class="fas fa-balance-scale"></i><div><strong>${d.nama_alat || '-'}</strong>${d.merk ? '<br><span>Merek: '+d.merk+'</span>' : ''}${d.tipe_model ? '<br><span>Tipe: '+d.tipe_model+'</span>' : ''}${d.nomor_seri ? '<br><span>No. Seri: '+d.nomor_seri+'</span>' : ''}</div></div>
                <div class="modal-row"><i class="fas fa-building"></i><div><strong>${d.nama_pemilik || '-'}</strong>${d.alamat_usaha ? '<br><span>'+d.alamat_usaha+'</span>' : ''}</div></div>
            `;

            // Jadwal
            const tgl = d.tgl_tugas ? new Date(d.tgl_tugas).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'}) : '-';
            document.getElementById('m-jadwal').innerHTML = `
                <div class="modal-row"><i class="far fa-calendar-alt"></i><span>${tgl}</span></div>
            `;

            // Petugas
            let petList = '';
            if (d.data_petugas && d.data_petugas.length) {
                d.data_petugas.forEach(p => {
                    petList += `<div class="modal-row"><i class="fas fa-user-tie"></i><div><strong>${p.nama_petugas}</strong><br><span>${p.jabatan}${p.nip ? ' — NIP. '+p.nip : ''}</span></div></div>`;
                });
            } else {
                petList = '<span style="color:#94a3b8;font-size:0.83rem;">Belum ada petugas</span>';
            }
            document.getElementById('m-petugas').innerHTML = petList;

            // Status badge
            const isSelesai = d.status === 'Selesai';
            document.getElementById('m-status-wrap').innerHTML = `
                <span style="display:inline-flex; align-items:center; gap:6px; padding:6px 18px; border-radius:20px; font-size:0.83rem; font-weight:600;
                    background:${isSelesai ? '#dcfce7' : '#fef3c7'}; color:${isSelesai ? '#16a34a' : '#d97706'};">
                    <i class="fas fa-${isSelesai ? 'check-circle' : 'hourglass-half'}"></i>
                    ${isSelesai ? 'Pengujian Selesai' : 'Sedang Di Lapangan'}
                </span>
            `;

            document.getElementById('m-loading').style.display = 'none';
            document.getElementById('m-content').style.display = '';
            
            // Sembunyikan tombol upload jika status sudah selesai
            if (isSelesai) {
                document.getElementById('m-btn-cerapan').style.display = 'none';
            } else {
                document.getElementById('m-btn-cerapan').style.display = '';
            }
        })
        .catch(err => {
            document.getElementById('m-loading').innerHTML = '<span style="color:red">Gagal memuat data: ' + err.message + '</span>';
        });
}
function tutupModal() {
    document.getElementById('modalST').style.display = 'none';
}
document.getElementById('modalST').addEventListener('click', function(e) {
    if (e.target === this) tutupModal();
});

// ===== MODAL FOTO =====
function bukaFoto()  { document.getElementById('modalFoto').style.display = 'flex'; }
function tutupFoto() { document.getElementById('modalFoto').style.display = 'none'; }
document.getElementById('modalFoto').addEventListener('click', function(e) {
    if (e.target === this) tutupFoto();
});
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.getElementById('fotoPreview');
            img.src = e.target.result;
            img.style.display = '';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Search
document.getElementById('searchTask').addEventListener('keyup', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.task-card').forEach(card => {
        const no = (card.dataset.no || '').toLowerCase();
        card.style.display = no.includes(q) ? '' : 'none';
    });
});

// ===== MODAL CERAPAN =====
let _currentStId = null;
function bukaCerapan() {
    document.getElementById('cerapan-st-id').value = _currentStId;
    document.getElementById('modalCerapan').style.display = 'flex';
}
function tutupCerapan() { document.getElementById('modalCerapan').style.display = 'none'; }
document.getElementById('modalCerapan').addEventListener('click', function(e) {
    if (e.target === this) tutupCerapan();
});
function labelCerapan(input) {
    if (input.files && input.files[0]) {
        document.getElementById('cerapan-label').innerHTML = '<strong>' + input.files[0].name + '</strong><br><small style="color:#94a3b8;">Siap dikirim</small>';
    }
}
</script>

<?php $this->load->view('layout/petugas_footer'); ?>
