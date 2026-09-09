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

/* ── KOP hanya tampil saat cetak ── */
#kopSurat { display: none; }

@media print {
    /* Hide the sidebar, header, no-print elements, and lokasi/tanggal section */
    .sidebar, .header, .no-print, #seksiLokasiTanggal {
        display: none !important;
    }

    /* Reset main wrapper to take full width without margin */
    .main-wrapper {
        margin-left: 0 !important;
        padding: 0 !important;
    }
    .content-area {
        padding: 0 !important;
    }

    /* Hide any direct siblings of printArea if any exist */
    .content-area > *:not(#printArea):not(.no-print) {
        display: none !important;
    }

    /* Reset printArea styling for clean print */
    #printArea {
        box-shadow: none !important;
        border: none !important;
        border-radius: 0 !important;
        background: white !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
    }
    #printArea .card-body {
        padding: 0 !important;
    }

    /* Format halaman A4 — margin lebih kecil supaya muat */
    @page {
        size: A4 portrait;
        margin: 1cm 1.5cm;
    }

    /* Font surat dinas */
    #printArea {
        font-family: "Times New Roman", Times, serif !important;
        font-size: 10pt !important;
        color: #000 !important;
    }

    /* Tampilkan KOP surat — lebih kompak */
    #kopSurat            { display: table !important; width: 100%; border-collapse: collapse; margin-bottom: 2px; }
    #kopSurat td         { padding: 0 !important; }
    #kopSurat img        { width: 60px !important; height: auto !important; }
    #kopGarisBawahTebal  { display: block !important; border-bottom: 3px solid #000; margin-bottom: 1px; }
    #kopGarisBawahTipis  { display: block !important; border-bottom: 1px solid #000; margin-bottom: 8px; }

    /* Judul resmi cetak */
    #judulCetak    { display: block !important; text-align: center; font-weight: bold; font-size: 11pt;
                     text-decoration: underline; letter-spacing: 1px; margin-bottom: 2px; }
    #subjudulCetak { display: block !important; text-align: center; font-size: 9.5pt; margin-bottom: 6px; }

    /* Sembunyikan judul web */
    #judulWeb { display: none !important; }

    /* Lokasi & tanggal surat resmi */
    #lokasiTanggalWeb    { display: none !important; }
    #lokasiTanggalSurat  { display: block !important; text-align: right;
                           font-family: "Times New Roman", Times, serif;
                           font-size: 10pt; margin-top: 6px; }

    /* Tanda tangan: sembunyikan versi web, tampilkan versi surat */
    #ttdWeb   { display: none !important; }
    #ttdSurat { display: table !important; width: 100%; border-collapse: collapse;
                font-family: "Times New Roman", Times, serif;
                font-size: 10pt; margin-top: 10px; }
    #ttdSurat td { line-height: 1.5 !important; }

    /* Pastikan tanda tangan tidak terpotong antar halaman */
    #seksiTandaTangan {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        margin-top: 8px !important;
        padding-top: 4px !important;
    }

    /* Hilangkan overflow pada tabel */
    .table-responsive { overflow: visible !important; }

    /* Tabel UTTP styling cetak — lebih kompak */
    .table { font-size: 9pt !important; margin-bottom: 0 !important; }
    .table th, .table td { border: 1px solid #000 !important; padding: 2px 4px !important; }
    .table thead { background-color: #ddd !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .table thead th { font-size: 8.5pt !important; }
}
</style>

<div class="d-flex justify-content-between align-items-center flex-wrap mb-4 no-print">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-file-invoice text-primary me-2"></i>Pembukuan Laporan</h4>
        <p class="text-muted mb-0">Rekapitulasi seluruh hasil pengujian alat UTTP yang telah divalidasi.</p>
    </div>
</div>

<div class="row g-3 mb-4 no-print">
    <div class="col-md-3">
        <div class="stat-kup bg-blue"><div class="bg-deco"></div>
            <div class="lbl"><i class="fas fa-file-invoice me-1"></i> Total Pengajuan</div>
            <div class="num"><?= $total_pengajuan ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-kup bg-teal"><div class="bg-deco"></div>
            <div class="lbl"><i class="fas fa-check-double me-1"></i> Selesai Uji</div>
            <div class="num"><?= $total_selesai ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-kup bg-green"><div class="bg-deco"></div>
            <div class="lbl"><i class="fas fa-stamp me-1"></i> Sudah Divalidasi</div>
            <div class="num"><?= $total_validasi ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-kup bg-purple"><div class="bg-deco"></div>
            <div class="lbl"><i class="fas fa-check-circle me-1"></i> Hasil Sah</div>
            <div class="num"><?= $total_sah ?></div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4 no-print">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3"><i class="fas fa-filter text-primary me-2"></i>Filter Laporan</h5>
        <form method="GET" action="<?= site_url('laporan') ?>" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Hari / Tanggal</label>
                <select name="hari" class="form-select">
                    <option value="">-- Semua Hari --</option>
                    <?php for($i=1; $i<=31; $i++): ?>
                    <option value="<?= $i ?>" <?= (isset($filter_hari) && $filter_hari == $i) ? 'selected' : '' ?>><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3">
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
            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-select">
                    <option value="" <?= ($filter_tahun === '' || $filter_tahun === null) ? 'selected' : '' ?>>-- Semua Tahun --</option>
                    <?php for($i=date('Y'); $i>=2020; $i--): ?>
                    <option value="<?= $i ?>" <?= ($filter_tahun == $i) ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary w-100 rounded-pill"><i class="fas fa-search me-1"></i> Tampilkan</button>
                <a href="<?= site_url('laporan') ?>" class="btn btn-outline-secondary w-50 rounded-pill"><i class="fas fa-sync-alt"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- ===== PANDUAN MEMBACA LAPORAN (no-print) ===== -->
<div class="card border-0 shadow-sm rounded-4 mb-4 no-print bg-light">
    <div class="card-header bg-transparent border-0 pt-3 pb-0 px-4 d-flex justify-content-between align-items-center" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#collapseGuide" aria-expanded="false" aria-controls="collapseGuide">
        <span class="fw-bold text-secondary small"><i class="fas fa-info-circle text-primary me-2"></i>Panduan Membaca Laporan & Prosentase</span>
        <span class="text-muted"><i class="fas fa-chevron-down" id="guideArrow"></i></span>
    </div>
    <div class="collapse" id="collapseGuide">
        <div class="card-body px-4 pb-4 pt-2">
            <div class="row g-3">
                <div class="col-md-6">
                    <h6 class="fw-bold text-dark mb-2" style="font-size:0.88rem;"><i class="fas fa-calendar-alt text-primary me-2"></i>Penjelasan Kolom Periode</h6>
                    <ul class="small text-muted ps-3 mb-0" style="line-height:1.6;">
                        <li class="mb-1"><strong>Jumlah Bulan Sebelumnya:</strong> Total unit UTTP yang diuji pada 1 bulan sebelum filter terpilih.</li>
                        <li class="mb-1"><strong>Jumlah Bulan Ini:</strong> Total unit UTTP yang diuji pada bulan filter terpilih (atau hari terpilih jika diset).</li>
                        <li class="mb-1"><strong>Jumlah Bulan Ini Tahun Sebelumnya:</strong> Total unit UTTP yang diuji pada bulan yang sama tahun lalu (sebagai pembanding).</li>
                        <li><strong>Jumlah S/D Bulan Ini:</strong> Akumulasi unit UTTP yang diuji sejak awal tahun berjalan s/d bulan terpilih (YTD).</li>
                    </ul>
                </div>
                <div class="col-md-6 border-start border-light-subtle">
                    <h6 class="fw-bold text-dark mb-2" style="font-size:0.88rem;"><i class="fas fa-percentage text-primary me-2"></i>Penjelasan Prosentase (%) & Arah Tren</h6>
                    <ul class="small text-muted ps-3 mb-0" style="line-height:1.6;">
                        <li class="mb-1"><strong>Angka % & Panah (misal: 150% ↑ / 75% ↓):</strong> Rasio pengujian Bulan Ini dengan Bulan yang sama tahun lalu. Panah menunjukkan arah tren naik (↑), turun (↓), atau stabil (=).</li>
                        <li class="mb-1"><strong>Baru:</strong> Menunjukkan ada pengujian di periode ini, tetapi tidak ada pengujian di tahun sebelumnya (menghindari error pembagian nol).</li>
                        <li class="mb-1"><strong>0%:</strong> Menunjukkan ada pengujian di tahun sebelumnya, tetapi belum ada pengujian di periode ini.</li>
                        <li><strong>Strip (-):</strong> Tidak ada pengujian di periode ini maupun tahun sebelumnya.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4" id="printArea">
    <div class="card-body p-4 p-md-5">

        <!-- ═══ KOP SURAT (hanya tampil saat cetak) ═══ -->
        <table id="kopSurat" style="display:none;">
            <tr>
                <td style="width:80px;text-align:center;vertical-align:middle;">
                    <img src="<?= base_url('asset/logo_kop_extracted.png') ?>" alt="Logo" style="width:75px;height:auto;">
                </td>
                <td style="text-align:center;vertical-align:middle;line-height:1.3;padding:0 10px;">
                    <div style="font-size:11pt;">PEMERINTAH KOTA SINGKAWANG</div>
                    <div style="font-size:15pt;font-weight:bold;">DINAS PERDAGANGAN, PERINDUSTRIAN,<br>KOPERASI, DAN USAHA KECIL MENENGAH</div>
                    <div style="font-size:8.5pt;margin-top:4px;">
                        Jalan Firdaus H. Rais No. 38 Singkawang, Kode Pos 79123<br>
                        Telepon : (0562) 631425 &nbsp;&nbsp;&nbsp; Faksimile : (0562) 631425<br>
                        Laman : disdaginkop.singkawangkota.go.id &nbsp;|&nbsp; Pos-el : daginkopukm@singkawangkota.go.id
                    </div>
                </td>
                <td style="width:80px;"></td>
            </tr>
        </table>
        <div id="kopGarisBawahTebal" style="display:none;"></div>
        <div id="kopGarisBawahTipis" style="display:none;"></div>

        <!-- ═══ Judul Cetak (hanya saat print) ═══ -->
        <span id="judulCetak" style="display:none;">REKAPITULASI JUMLAH ALAT UKUR, TAKAR, TIMBANG DAN PERLENGKAPANNYA (UTTP)</span>
        <span id="subjudulCetak" style="display:none;">UPT Metrologi Legal &mdash; <span id="subjudulPeriode"></span></span>

        <!-- ═══ Judul di layar web ═══ -->
        <div class="d-flex justify-content-between align-items-center mb-4" id="judulWeb">
            <div>
                <h6 class="fw-bold text-dark mb-1">JUMLAH ALAT UKUR, TAKAR, TIMBANG DAN PERLENGKAPANNYA (UTTP)</h6>
                <p class="text-muted small mb-0" id="bulanTahunLabel"></p>
            </div>
            <button class="btn btn-primary rounded-pill px-4 shadow-sm no-print" onclick="window.print()">
                <i class="fas fa-print me-2"></i> Cetak Laporan
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size: 0.85rem; border-color: #000;">
                <thead class="text-center align-middle" style="background-color: #eaeaea; font-weight: bold;">
                    <tr>
                        <th rowspan="2" style="width: 35%;">RINCIAN ALAT UTTP</th>
                        <th style="width: 13%;">JUMLAH<br>BULAN<br>SEBELUMNYA</th>
                        <th style="width: 13%;">JUMLAH<br>BULAN INI</th>
                        <th style="width: 13%;">JUMLAH S/D<br>BULAN INI</th>
                        <th style="width: 13%;">JUMLAH<br>BULAN INI<br>TAHUN<br>SEBELUMNYA</th>
                        <th style="width: 13%;">PROSENTASE</th>
                    </tr>
                    <tr>
                        <th><i>unit</i></th>
                        <th><i>unit</i></th>
                        <th><i>unit</i></th>
                        <th><i>unit</i></th>
                        <th><i>%</i></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-bold">I &nbsp;&nbsp;&nbsp;ALAT UKUR PANJANG</td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-panjang-ini">-</td>
                        <td class="text-end" id="val-panjang-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">II &nbsp;&nbsp;TIMBANGAN MEKANIK</td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <i>Sentisimal</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-tm_sentisimal-ini">-</td>
                        <td class="text-end" id="val-tm_sentisimal-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. <i>Meja</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-tm_meja-ini">-</td>
                        <td class="text-end" id="val-tm_meja-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. <i>Dacin</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-tm_dacin-ini">-</td>
                        <td class="text-end" id="val-tm_dacin-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. <i>Pegas</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-tm_pegas-ini">-</td>
                        <td class="text-end" id="val-tm_pegas-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5. <i>Bobot Ingsut</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-tm_bobot_ingsut-ini">-</td>
                        <td class="text-end" id="val-tm_bobot_ingsut-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6. <i>Neraca</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-tm_neraca-ini">-</td>
                        <td class="text-end" id="val-tm_neraca-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">III &nbsp;TIMBANGAN ELEKTRONIK</td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <i>Ketelitian Halus (kelas II)</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-te_halus-ini">-</td>
                        <td class="text-end" id="val-te_halus-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. <i>Ketelitian Sedang/Biasa (Kelas III/IIII)</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-te_sedang-ini">-</td>
                        <td class="text-end" id="val-te_sedang-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. <i>Jembatan</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-te_jembatan-ini">-</td>
                        <td class="text-end" id="val-te_jembatan-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. <i>Pengisian</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-te_pengisian-ini">-</td>
                        <td class="text-end" id="val-te_pengisian-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5. <i>Pencampuran</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-te_pencampuran-ini">-</td>
                        <td class="text-end" id="val-te_pencampuran-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">IV &nbsp;ALAT UKUR VOLUME</td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <i>Takaran Basah</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-v_takaran_basah-ini">-</td>
                        <td class="text-end" id="val-v_takaran_basah-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. <i>Bejana Ukur</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-v_bejana_ukur-ini">-</td>
                        <td class="text-end" id="val-v_bejana_ukur-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. <i>Pompa Ukur BBM</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-v_pompa_ukur_bbm-ini">-</td>
                        <td class="text-end" id="val-v_pompa_ukur_bbm-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4. <i>Gelas Ukur</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-v_gelas_ukur-ini">-</td>
                        <td class="text-end" id="val-v_gelas_ukur-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5. <i>Tangki Ukur</i></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a. Mobil</td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-v_tangki_mobil-ini">-</td>
                        <td class="text-end" id="val-v_tangki_mobil-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b. Silinder Datar</td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-v_tangki_datar-ini">-</td>
                        <td class="text-end" id="val-v_tangki_datar-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c. Silinder Tegak</td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-v_tangki_tegak-ini">-</td>
                        <td class="text-end" id="val-v_tangki_tegak-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d. Selain Silinder</td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-v_tangki_selain-ini">-</td>
                        <td class="text-end" id="val-v_tangki_selain-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6. <i>Meter Arus BBM</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-v_meter_arus-ini">-</td>
                        <td class="text-end" id="val-v_meter_arus-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;7. <i>Meter Air</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-v_meter_air-ini">-</td>
                        <td class="text-end" id="val-v_meter_air-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">V &nbsp;&nbsp;ALAT UKUR ENERGI LISTRIK</td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <i>Meter kWh</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-e_meter_kwh-ini">-</td>
                        <td class="text-end" id="val-e_meter_kwh-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. <i>Stasiun Pengisian Listrik Umum</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-e_splu-ini">-</td>
                        <td class="text-end" id="val-e_splu-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">VI &nbsp;PERLENGKAPAN</td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <i>Anak Timbangan Kelas M2/M3</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-p_m2_m3-ini">-</td>
                        <td class="text-end" id="val-p_m2_m3-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. <i>Anak Timbangan Kelas F2/M1</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-p_f2_m1-ini">-</td>
                        <td class="text-end" id="val-p_f2_m1-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">VII PENGUJIAN SAMPEL BDKT</td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <i>Jumlah Sampel Produk</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-s_produk-ini">-</td>
                        <td class="text-end" id="val-s_produk-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">VIII LAIN-LAIN</td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                        <td class="bg-light"></td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. <i>Alat Pencetak Kartu/Printer</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-l_printer-ini">-</td>
                        <td class="text-end" id="val-l_printer-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. <i>S K H P</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-l_skhp-ini">-</td>
                        <td class="text-end" id="val-l_skhp-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3. <i>Tabel Volume Tangki</i></td>
                        <td class="text-end">-</td>
                        <td class="text-end" id="val-l_tabel_volume-ini">-</td>
                        <td class="text-end" id="val-l_tabel_volume-sd">-</td>
                        <td class="text-end">-</td>
                        <td class="text-center">-</td>
                    </tr>
                    <tr style="border-top: 2px solid #000; border-bottom: 1px solid #000;">
                        <td class="fw-bold">JUMLAH ALAT UTTP</td>
                        <td class="text-end fw-bold" id="total-bulan-lalu">-</td>
                        <td class="text-end fw-bold" id="total-bulan-ini">-</td>
                        <td class="text-end fw-bold" id="total-sd">-</td>
                        <td class="text-end fw-bold" id="total-tahun-lalu">-</td>
                        <td class="text-center fw-bold" id="total-persen">-</td>
                    </tr>
                    <tr style="border-bottom: 2px solid #000;">
                        <td class="fw-bold">JUMLAH PEMILIK UTTP</td>
                        <td class="text-end" id="pemilik-bulan-lalu">-</td>
                        <td class="text-end" id="pemilik-bulan-ini">-</td>
                        <td class="text-end" id="pemilik-sd">-</td>
                        <td class="text-end" id="pemilik-tahun-lalu">-</td>
                        <td class="text-center" id="pemilik-persen">-</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ===== LOKASI & TANGGAL (Editable) ===== -->
        <div class="mt-4 pt-3 border-top" id="seksiLokasiTanggal">

            <!-- Tombol Edit (hanya tampil saat tidak cetak) -->
            <div class="d-flex justify-content-between align-items-center mb-2 no-print">
                <span class="fw-bold small text-muted"><i class="fas fa-map-marker-alt me-1"></i>Lokasi & Tanggal Laporan</span>
                <button class="btn btn-sm btn-outline-primary rounded-pill px-3" id="btnEditLokasi" onclick="toggleEditLokasi()">
                    <i class="fas fa-pen me-1"></i> Edit
                </button>
            </div>

            <!-- MODE TAMPIL (untuk cetak) -->
            <div id="viewLokasi">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">LOKASI</label>
                        <div class="form-control form-control-sm bg-light" id="dispLokasi">Singkawang</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted">TANGGAL LAPORAN</label>
                        <div class="form-control form-control-sm bg-light" id="tglLaporan"></div>
                    </div>
                </div>
            </div>

            <!-- MODE EDIT (hanya di layar) -->
            <div id="formEditLokasi" class="no-print" style="display:none;">
                <div class="card bg-light border-0 rounded-3 p-3 mt-2">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Lokasi</label>
                            <input type="text" id="inputLokasi" class="form-control form-control-sm" placeholder="Contoh: Singkawang">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Tanggal Laporan</label>
                            <input type="text" id="inputTglLaporan" class="form-control form-control-sm" placeholder="Contoh: 13 Mei 2026">
                        </div>
                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-success btn-sm rounded-pill px-4" onclick="simpanLokasi()"><i class="fas fa-save me-1"></i>Simpan</button>
                            <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="toggleEditLokasi()">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== TANDA TANGAN (Editable) ===== -->
        <div class="mt-3 pt-2 border-top" id="seksiTandaTangan">

            <!-- Tombol Edit Tanda Tangan -->
            <div class="d-flex justify-content-end mb-2 no-print">
                <button class="btn btn-sm btn-outline-primary rounded-pill px-3" id="btnEditTtd" onclick="toggleEditTtd()">
                    <i class="fas fa-pen me-1"></i> Edit Pejabat
                </button>
            </div>

            <!-- MODE TAMPIL WEB (disembunyikan saat cetak via CSS #ttdWeb) -->
            <div id="ttdWeb">
                <div class="row g-4 text-center">
                    <div class="col-6">
                        <p class="fw-bold small mb-1">Mengetahui,</p>
                        <p class="small text-muted mb-5" id="dispJabatanMengetahui">Kepala Dinas Perdagangan,<br>Perindustrian, Koperasi dan UMKM</p>
                        <br><br>
                        <p class="fw-bold mb-0" id="dispNamaMengetahui">YASMALIZAR, S.H.</p>
                        <p class="small text-muted" id="dispNipMengetahui">NIP. 196810161998031004</p>
                    </div>
                    <div class="col-6">
                        <p class="fw-bold small mb-1">Dilaporkan oleh,</p>
                        <p class="small text-muted mb-5" id="dispJabatanDilaporkan">Kepala UPT Metrologi Legal</p>
                        <br><br>
                        <p class="fw-bold mb-0" id="dispNamaDilaporkan"><?= htmlspecialchars($kepala_upt['nama'] ?? '-') ?></p>
                        <p class="small text-muted" id="dispNipDilaporkan"><?= htmlspecialchars($kepala_upt['nip'] ?? '-') ?></p>
                    </div>
                </div>
            </div>

            <!-- MODE TAMPIL SURAT RESMI (hanya saat cetak via CSS #ttdSurat) -->
            <div id="lokasiTanggalSurat" style="display:none;">
                <span id="suratLokasi">Singkawang</span>, <span id="suratTgl"></span>
            </div>
            <table id="ttdSurat" style="display:none; width:100%; border-collapse:collapse; font-family:'Times New Roman',serif; font-size:11pt; margin-top:24px;">
                <!-- Baris 1: Jabatan -->
                <tr>
                    <td style="width:50%; vertical-align:top; padding-right:20px; line-height:1.6;">
                        Mengetahui,<br>
                        <span id="suratJabatanMengetahui">Kepala Dinas Perdagangan, Perindustrian,<br>Koperasi dan UMKM Kota Singkawang</span>
                    </td>
                    <td style="width:50%; vertical-align:top; padding-left:20px; line-height:1.6;">
                        Dilaporkan oleh,<br>
                        <span id="suratJabatanDilaporkan">Kepala UPT Metrologi Legal</span>
                    </td>
                </tr>
                <!-- Baris 2: Spasi tanda tangan -->
                <tr>
                    <td style="height:60px;"></td>
                    <td style="height:60px;"></td>
                </tr>
                <!-- Baris 3: Nama & NIP (sejajar) -->
                <tr>
                    <td style="width:50%; vertical-align:top; padding-right:20px; line-height:1.6;">
                        <u><b id="suratNamaMengetahui">YASMALIZAR, S.H.</b></u><br>
                        <span id="suratNipMengetahui">NIP. 196810161998031004</span>
                    </td>
                    <td style="width:50%; vertical-align:top; padding-left:20px; line-height:1.6;">
                        <u><b id="suratNamaDilaporkan"><?= htmlspecialchars($kepala_upt['nama'] ?? '-') ?></b></u><br>
                        <span id="suratNipDilaporkan"><?= htmlspecialchars($kepala_upt['nip'] ?? '-') ?></span>
                    </td>
                </tr>
            </table>

            <!-- MODE EDIT Tanda Tangan (web only) -->
            <div id="formEditTtd" class="no-print" style="display:none;">
                <div class="card bg-light border-0 rounded-3 p-3 mt-2">
                    <div class="row g-3">
                        <div class="col-12"><h6 class="fw-bold text-primary mb-2"><i class="fas fa-user-tie me-1"></i>Kolom Kiri — Mengetahui</h6></div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Jabatan</label>
                            <input type="text" id="inputJabatanMengetahui" class="form-control form-control-sm" placeholder="Kepala Dinas Perdagangan, ...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Nama Lengkap</label>
                            <input type="text" id="inputNamaMengetahui" class="form-control form-control-sm" placeholder="YASMALIZAR, S.H.">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">NIP</label>
                            <input type="text" id="inputNipMengetahui" class="form-control form-control-sm" placeholder="196810161998031004">
                        </div>
                        <div class="col-12 mt-1"><hr class="my-1"><h6 class="fw-bold text-primary mb-2"><i class="fas fa-user-cog me-1"></i>Kolom Kanan — Dilaporkan oleh</h6></div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Jabatan</label>
                            <input type="text" id="inputJabatanDilaporkan" class="form-control form-control-sm" placeholder="Kepala UPT Metrologi Legal">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">Nama Lengkap</label>
                            <input type="text" id="inputNamaDilaporkan" class="form-control form-control-sm" placeholder="Nama Kepala UPT">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small">NIP</label>
                            <input type="text" id="inputNipDilaporkan" class="form-control form-control-sm" placeholder="NIP / NI PPPK">
                        </div>
                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-success btn-sm rounded-pill px-4" onclick="simpanTtd()"><i class="fas fa-save me-1"></i>Simpan</button>
                            <button class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="toggleEditTtd()">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
$(document).ready(function() { 
    // Set tanggal laporan berdasarkan filter atau saat ini
    var filterHari = <?= !empty($filter_hari) ? json_encode($filter_hari) : 'null' ?>;
    var filterBulan = <?= !empty($filter_bulan) ? json_encode($filter_bulan) : 'null' ?>;
    var filterTahun = <?= !empty($filter_tahun) ? json_encode($filter_tahun) : 'null' ?>;

    var bulanArr = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    var now = new Date();
    
    var h = filterHari ? filterHari : now.getDate();
    var b = filterBulan ? filterBulan - 1 : now.getMonth();
    var t = filterTahun ? filterTahun : now.getFullYear();

    var tglStr = h + ' ' + bulanArr[b] + ' ' + t;
    
    var filterLabel = '';
    if (filterHari && filterBulan && filterTahun) {
        filterLabel = 'TANGGAL: ' + h + ' ' + bulanArr[b].toUpperCase() + ' ' + t;
    } else if (filterBulan && filterTahun) {
        filterLabel = 'BULAN: ' + bulanArr[b].toUpperCase() + ' ' + t;
    } else if (filterBulan && !filterTahun) {
        filterLabel = 'BULAN: ' + bulanArr[b].toUpperCase() + ' (SEMUA TAHUN)';
    } else if (!filterBulan && filterTahun) {
        filterLabel = 'TAHUN: ' + t + ' (SEMUA BULAN)';
    } else {
        filterLabel = 'SEMUA PERIODE (SEPANJANG MASA)';
    }
    
    $('#bulanTahunLabel').text(filterLabel);

    // ── Lokasi & Tanggal: load dari localStorage atau gunakan default ────
    var savedLokasi = localStorage.getItem('laporan_lokasi') || 'Singkawang';
    var savedTgl    = localStorage.getItem('laporan_tgl')    || tglStr;
    
    // Clean up historical savedTgl that might contain location prefix
    if (savedTgl.startsWith(savedLokasi + ',')) {
        savedTgl = savedTgl.substring(savedLokasi.length + 1).trim();
    } else if (savedTgl.startsWith(savedLokasi)) {
        savedTgl = savedTgl.substring(savedLokasi.length).trim();
    }
    savedTgl = savedTgl.replace(/^[\s,]+/, '');

    $('#dispLokasi').text(savedLokasi);
    $('#tglLaporan').text(savedTgl);
    // Sync ke elemen surat resmi
    $('#suratLokasi').text(savedLokasi);
    $('#suratTgl').text(savedTgl);
    $('#subjudulPeriode').text($('#bulanTahunLabel').text());

    // Data rekap UTTP dari PHP
    var rekapIni = <?= json_encode($rekap_ini ?? []) ?>;
    var rekapLalu = <?= json_encode($rekap_lalu ?? []) ?>;
    var rekapSd = <?= json_encode($rekap_sd ?? []) ?>;
    var rekapTahunLalu = <?= json_encode($rekap_tahun_lalu ?? []) ?>;

    function getPercent(ini, thnLalu) {
        // Perhitungan: realisasi bulan ini dibanding bulan sama tahun lalu
        if (thnLalu > 0 && ini > 0) {
            var pct = ((ini / thnLalu) * 100).toFixed(1).replace('.', ',');
            var selisih = ini - thnLalu;
            var arah = selisih > 0 ? '\u2191' : (selisih < 0 ? '\u2193' : '=');
            return pct + '% ' + arah;
        } else if (thnLalu > 0 && ini === 0) {
            return '0%';
        } else if (ini > 0 && thnLalu === 0) {
            return 'Baru';
        }
        return '-';
    }

    var keys = [
        'panjang', 
        'tm_sentisimal', 'tm_meja', 'tm_dacin', 'tm_pegas', 'tm_bobot_ingsut', 'tm_neraca',
        'te_halus', 'te_sedang', 'te_jembatan', 'te_pengisian', 'te_pencampuran',
        'v_takaran_basah', 'v_bejana_ukur', 'v_pompa_ukur_bbm', 'v_gelas_ukur', 'v_tangki_mobil', 'v_tangki_datar', 'v_tangki_tegak', 'v_tangki_selain', 'v_meter_arus', 'v_meter_air',
        'e_meter_kwh', 'e_splu',
        'p_m2_m3', 'p_f2_m1',
        's_produk',
        'l_printer', 'l_skhp', 'l_tabel_volume'
    ];

    keys.forEach(function(k) {
        var valIni = rekapIni[k] || 0;
        var valLalu = rekapLalu[k] || 0;
        var valSd = rekapSd[k] || 0;
        var valTahunLalu = rekapTahunLalu[k] || 0;

        var elIni = $('#val-' + k + '-ini');
        if(elIni.length) {
            var tr = elIni.closest('tr');
            var persen = getPercent(valIni, valTahunLalu);

            if (valIni > 0 || valLalu > 0 || valSd > 0 || valTahunLalu > 0) {
                tr.find('td').eq(1).text(valLalu > 0 ? valLalu : '-');
                tr.find('td').eq(2).text(valIni > 0 ? valIni : '-');
                tr.find('td').eq(3).text(valSd > 0 ? valSd : '-');
                tr.find('td').eq(4).text(valTahunLalu > 0 ? valTahunLalu : '-');
                tr.find('td').eq(5).text(persen);
            }
        }
    });

    // Total summary
    var tIni = <?= $total_ini ?? 0 ?>;
    var tLalu = <?= $total_lalu ?? 0 ?>;
    var tSd = <?= $total_sd ?? 0 ?>;
    var tThnLalu = <?= $total_tahun_lalu ?? 0 ?>;

    var pIni = <?= $pemilik_ini ?? 0 ?>;
    var pLalu = <?= $pemilik_lalu ?? 0 ?>;
    var pSd = <?= $pemilik_sd ?? 0 ?>;
    var pThnLalu = <?= $pemilik_tahun_lalu ?? 0 ?>;

    $('#total-bulan-lalu').text(tLalu > 0 ? tLalu : '-');
    $('#total-bulan-ini').text(tIni > 0 ? tIni : '-');
    $('#total-sd').text(tSd > 0 ? tSd : '-');
    $('#total-tahun-lalu').text(tThnLalu > 0 ? tThnLalu : '-');
    $('#total-persen').text(getPercent(tIni, tThnLalu));
    
    $('#pemilik-bulan-lalu').text(pLalu > 0 ? pLalu : '-');
    $('#pemilik-bulan-ini').text(pIni > 0 ? pIni : '-');
    $('#pemilik-sd').text(pSd > 0 ? pSd : '-');
    $('#pemilik-tahun-lalu').text(pThnLalu > 0 ? pThnLalu : '-');
    $('#pemilik-persen').text(getPercent(pIni, pThnLalu));



    // ── Load tanda tangan dari localStorage ─────────────────────────
    var ttdData = JSON.parse(localStorage.getItem('laporan_ttd') || '{}');
    if (ttdData.jabatanMengetahui) { $('#dispJabatanMengetahui').html(ttdData.jabatanMengetahui); $('#suratJabatanMengetahui').html(ttdData.jabatanMengetahui); }
    if (ttdData.namaMengetahui)    { $('#dispNamaMengetahui').text(ttdData.namaMengetahui);    $('#suratNamaMengetahui').text(ttdData.namaMengetahui); }
    if (ttdData.nipMengetahui)     { $('#dispNipMengetahui').text(ttdData.nipMengetahui);      $('#suratNipMengetahui').text(ttdData.nipMengetahui); }
    if (ttdData.jabatanDilaporkan) { $('#dispJabatanDilaporkan').text(ttdData.jabatanDilaporkan); $('#suratJabatanDilaporkan').text(ttdData.jabatanDilaporkan); }
    if (ttdData.namaDilaporkan)    { $('#dispNamaDilaporkan').text(ttdData.namaDilaporkan);    $('#suratNamaDilaporkan').text(ttdData.namaDilaporkan); }
    if (ttdData.nipDilaporkan)     { $('#dispNipDilaporkan').text(ttdData.nipDilaporkan);      $('#suratNipDilaporkan').text(ttdData.nipDilaporkan); }

    // Toggle icon chevron panduan membaca
    $('#collapseGuide').on('shown.bs.collapse', function() {
        $('#guideArrow').removeClass('fa-chevron-down').addClass('fa-chevron-up');
    });
    $('#collapseGuide').on('hidden.bs.collapse', function() {
        $('#guideArrow').removeClass('fa-chevron-up').addClass('fa-chevron-down');
    });
});

// ═══════════════════════════════════════════════════════════════
// FUNGSI EDIT LOKASI & TANGGAL
// ═══════════════════════════════════════════════════════════════
function toggleEditLokasi() {
    var isEditing = $('#formEditLokasi').is(':visible');
    if (!isEditing) {
        // Isi input dengan nilai saat ini
        $('#inputLokasi').val($('#dispLokasi').text());
        $('#inputTglLaporan').val($('#tglLaporan').text());
        $('#formEditLokasi').slideDown(200);
        $('#btnEditLokasi').html('<i class="fas fa-times me-1"></i> Tutup');
    } else {
        $('#formEditLokasi').slideUp(200);
        $('#btnEditLokasi').html('<i class="fas fa-pen me-1"></i> Edit');
    }
}

function simpanLokasi() {
    var lok = $('#inputLokasi').val().trim();
    var tgl = $('#inputTglLaporan').val().trim();
    if (!lok) { alert('Lokasi tidak boleh kosong.'); return; }
    if (!tgl) { alert('Tanggal tidak boleh kosong.'); return; }

    // Clean up if the user typed the location prefix in the date field
    if (tgl.startsWith(lok + ',')) {
        tgl = tgl.substring(lok.length + 1).trim();
    } else if (tgl.startsWith(lok)) {
        tgl = tgl.substring(lok.length).trim();
    }
    tgl = tgl.replace(/^[\s,]+/, '');

    // Update UI web
    $('#dispLokasi').text(lok);
    $('#tglLaporan').text(tgl);
    // Sync elemen surat resmi
    $('#suratLokasi').text(lok);
    $('#suratTgl').text(tgl);
    // Simpan ke localStorage
    localStorage.setItem('laporan_lokasi', lok);
    localStorage.setItem('laporan_tgl', tgl);
    $('#formEditLokasi').slideUp(200);
    $('#btnEditLokasi').html('<i class="fas fa-pen me-1"></i> Edit');
    var toast = $('<div class="alert alert-success alert-dismissible py-2 px-3 position-fixed" style="bottom:20px;right:20px;z-index:9999;font-size:.85rem">'
        + '<i class="fas fa-check-circle me-1"></i> Lokasi & tanggal disimpan.'
        + '<button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button></div>');
    $('body').append(toast);
    setTimeout(function(){ toast.alert('close'); }, 2500);
}

// ═══════════════════════════════════════════════════════════════
// FUNGSI EDIT TANDA TANGAN
// ═══════════════════════════════════════════════════════════════
function toggleEditTtd() {
    var isEditing = $('#formEditTtd').is(':visible');
    if (!isEditing) {
        // Isi input dengan nilai saat ini
        $('#inputJabatanMengetahui').val($('#dispJabatanMengetahui').text().trim());
        $('#inputNamaMengetahui').val($('#dispNamaMengetahui').text().trim());
        $('#inputNipMengetahui').val($('#dispNipMengetahui').text().trim());
        $('#inputJabatanDilaporkan').val($('#dispJabatanDilaporkan').text().trim());
        $('#inputNamaDilaporkan').val($('#dispNamaDilaporkan').text().trim());
        $('#inputNipDilaporkan').val($('#dispNipDilaporkan').text().trim());
        $('#formEditTtd').slideDown(200);
        $('#btnEditTtd').html('<i class="fas fa-times me-1"></i> Tutup');
    } else {
        $('#formEditTtd').slideUp(200);
        $('#btnEditTtd').html('<i class="fas fa-pen me-1"></i> Edit Pejabat');
    }
}

function simpanTtd() {
    var jabM = $('#inputJabatanMengetahui').val().trim();
    var namM = $('#inputNamaMengetahui').val().trim();
    var nipM = $('#inputNipMengetahui').val().trim();
    var jabD = $('#inputJabatanDilaporkan').val().trim();
    var namD = $('#inputNamaDilaporkan').val().trim();
    var nipD = $('#inputNipDilaporkan').val().trim();

    if (!namM || !namD) { alert('Nama pejabat tidak boleh kosong.'); return; }

    // Update tampilan UI web
    $('#dispJabatanMengetahui').html(jabM.replace(/,/g, ',<br>'));
    $('#dispNamaMengetahui').text(namM);
    $('#dispNipMengetahui').text(nipM);
    $('#dispJabatanDilaporkan').text(jabD);
    $('#dispNamaDilaporkan').text(namD);
    $('#dispNipDilaporkan').text(nipD);

    // Sync ke elemen surat resmi
    $('#suratJabatanMengetahui').html(jabM.replace(/,/g, ',<br>'));
    $('#suratNamaMengetahui').text(namM);
    $('#suratNipMengetahui').text(nipM);
    $('#suratJabatanDilaporkan').text(jabD);
    $('#suratNamaDilaporkan').text(namD);
    $('#suratNipDilaporkan').text(nipD);

    // Simpan ke localStorage
    localStorage.setItem('laporan_ttd', JSON.stringify({
        jabatanMengetahui: jabM.replace(/,/g, ',<br>'),
        namaMengetahui:    namM,
        nipMengetahui:     nipM,
        jabatanDilaporkan: jabD,
        namaDilaporkan:    namD,
        nipDilaporkan:     nipD
    }));

    $('#formEditTtd').slideUp(200);
    $('#btnEditTtd').html('<i class="fas fa-pen me-1"></i> Edit Pejabat');
    var toast = $('<div class="alert alert-success alert-dismissible py-2 px-3 position-fixed" style="bottom:20px;right:20px;z-index:9999;font-size:.85rem">'
        + '<i class="fas fa-check-circle me-1"></i> Data pejabat disimpan.'
        + '<button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button></div>');
    $('body').append(toast);
    setTimeout(function(){ toast.alert('close'); }, 2500);
}
</script>

<?php $this->load->view('layout/dash_footer'); ?>