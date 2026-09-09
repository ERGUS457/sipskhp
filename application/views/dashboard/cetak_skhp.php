<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>SKHP — <?= htmlspecialchars((string)($d['nama_alat'] ?? '')) ?> | <?= htmlspecialchars((string)($d['nama_pemilik'] ?? '')) ?></title>
<style>
/* =====================================================
   SKHP FORMAT RESMI — UPT Metrologi Legal
   Kota Singkawang — Replica Dokumen DOCX Asli
   ===================================================== */

@page {
    size: A4 portrait;
    margin: 0.5cm;
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: "Times New Roman", Times, serif;
    font-size: 12pt;
    color: #000;
    line-height: 1.15;
    <?php if (empty($is_pdf)): ?>
    background: #b0b5be;
    margin: 0;
    padding: 1.2cm 0;
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
    <?php else: ?>
    background: white;
    margin: 0;
    padding: 0;
    <?php endif; ?>
}

/* =================== HALAMAN A4 =================== */
.page {
    <?php if (empty($is_pdf)): ?>
    width: 210mm;
    height: 297mm;
    box-shadow: 0 10px 40px rgba(0,0,0,0.35);
    margin-bottom: 0.8cm;
    position: relative;
    overflow: hidden;
    <?php else: ?>
    width: auto;
    <?php endif; ?>
    background: white;
    padding: 0.8cm 1.8cm 1cm 1.8cm;
    box-sizing: border-box;
}

/* =================== WATERMARK TEKS BERULANG (LANDSCAPE) =================== */
.watermark-text {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 0;
    pointer-events: none;
    overflow: hidden;
    opacity: 0.10;
}
.watermark-text-inner {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    font-family: "Times New Roman", Times, serif;
    font-size: 8pt;
    color: #000;
    line-height: 1.25;
    letter-spacing: 1px;
    word-spacing: 4px;
    white-space: pre-wrap;
    user-select: none;
}

/* =================== WATERMARK LOGO TENGAH =================== */
.watermark-logo {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 13cm;
    height: auto;
    opacity: 0.09;
    z-index: 0;
    pointer-events: none;
    user-select: none;
}

/* =================== CONTENT (di atas watermark) =================== */
.content {
    position: relative;
    z-index: 1;
}

/* =================== KOP SURAT =================== */
.kop-table { width: 100%; border-collapse: collapse; margin-bottom: 2px; }
.kop-logo { width: 70px; text-align: center; vertical-align: middle; padding: 0; }
.kop-logo img { width: 62px; height: auto; }
.kop-text {
    text-align: center;
    vertical-align: middle;
    padding: 0 4px;
    line-height: 1.15;
}
.kop-instansi { font-size: 11pt; letter-spacing: 0.3px; }
.kop-dinas {
    font-size: 13.5pt;
    font-weight: bold;
    letter-spacing: 0.2px;
    margin: 1px 0;
    line-height: 1.2;
}
.kop-alamat { font-size: 8pt; line-height: 1.3; }
.kop-line-1 { border-bottom: 3px solid #000; margin-top: 3px; }
.kop-line-2 { border-bottom: 1px solid #000; margin-top: 1.5px; margin-bottom: 8px; }

/* =================== JUDUL & NOMOR =================== */
.title-area { position: relative; margin-bottom: 8px; }
.skhp-title {
    text-align: center;
    font-size: 13pt;
    font-weight: bold;
    text-decoration: underline;
    letter-spacing: 1.5px;
    margin-bottom: 1px;
}
.skhp-nomor {
    text-align: center;
    font-size: 10.5pt;
    margin-bottom: 0;
}

/* Nomor Daftar - kotak kanan atas */
.nodaftar-box {
    position: absolute;
    top: 0;
    right: 0;
    border: 1.5px solid #000;
    padding: 4px 10px;
    font-size: 10pt;
    text-align: center;
    line-height: 1.4;
    background: rgba(255,255,255,0.85);
}
.nodaftar-box .nd-label { font-weight: bold; }

/* =================== DATA TABLE =================== */
.data-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 0;
}
.data-table td {
    padding: 1.5px 3px;
    vertical-align: top;
    font-size: 10.5pt;
    line-height: 1.2;
}
.data-table .col-label { width: 30%; font-weight: bold; }
.data-table .col-sep { width: 2%; text-align: center; }
.data-table .col-value { }
.label-en {
    font-style: italic;
    font-weight: normal;
    font-size: 9pt;
    color: #000;
}

/* =================== GARIS PEMISAH =================== */
.separator-line {
    border: none;
    border-top: 1px solid #000;
    margin: 6px 0;
}

/* =================== TANDA TANGAN =================== */
.ttd-area {
    width: 100%;
    margin-top: 4px;
}
.ttd-area td {
    vertical-align: top;
    font-size: 10.5pt;
    line-height: 1.4;
}
.ttd-kiri { width: 45%; }
.ttd-kanan { width: 55%; text-align: left; padding-left: 40px; }

/* =================== CATATAN =================== */
.catatan-box {
    margin-top: 6px;
    font-size: 9pt;
    line-height: 1.35;
    text-align: justify;
}
.catatan-title {
    font-weight: bold;
    text-decoration: underline;
    font-size: 9.5pt;
    margin-bottom: 2px;
}
.catatan-list {
    margin: 0;
    padding-left: 14px;
}
.catatan-list li {
    margin-bottom: 1px;
}

/* =================== PRINT =================== */
@media print {
    html, body {
        background: white !important;
        padding: 0 !important;
        margin: 0 !important;
        width: auto !important;
        min-height: auto !important;
    }
    .page {
        width: auto !important;
        min-height: auto !important;
        margin: 0 !important;
        padding: 0.5cm 0.7cm !important;
        box-shadow: none !important;
    }
    .no-print { display: none !important; }
    .watermark-text { opacity: 0.08 !important; }
    .watermark-logo { opacity: 0.07 !important; }
}

/* =================== TOMBOL CETAK =================== */
.print-toolbar {
    text-align: center;
    margin-bottom: 12px;
}
.btn-cetak {
    background: linear-gradient(135deg, #1e6b3a, #228B22);
    color: #fff;
    border: none;
    padding: 10px 38px;
    border-radius: 30px;
    font-size: 12pt;
    font-family: 'Segoe UI', sans-serif;
    cursor: pointer;
    font-weight: bold;
    box-shadow: 0 4px 15px rgba(30,107,58,0.35);
    transition: all 0.2s;
}
.btn-cetak:hover {
    background: linear-gradient(135deg, #228B22, #2da44e);
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(30,107,58,0.45);
}
</style>
</head>
<body>
<?php
// ============================================
// HELPER FUNCTIONS
// ============================================
function tglLongIndo($date_str) {
    if (empty($date_str)) return '-';
    $bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
              'Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($date_str);
    if (!$ts) return '-';
    return date('d', $ts) . ' ' . $bulan[(int)date('m', $ts)] . ' ' . date('Y', $ts);
}

function tglBulanTahun($date_str) {
    if (empty($date_str)) return '-';
    $bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
              'Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($date_str);
    if (!$ts) return '-';
    return $bulan[(int)date('m', $ts)] . ' ' . date('Y', $ts);
}

function getBase64Image($path) {
    if (file_exists($path)) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        return 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    return '';
}

// Convert Logo to base64 for Dompdf to ensure they always load
$logo_metrologi = !empty($is_pdf) ? getBase64Image(FCPATH . 'asset/Logo Metrologi.png') : base_url('asset/Logo Metrologi.png');
$logo_singkawang = !empty($is_pdf) ? getBase64Image(FCPATH . 'asset/Logo_Kota_Singkawang.png') : base_url('asset/Logo_Kota_Singkawang.png');

// Pastikan data tersedia
$d = isset($d) ? $d : [];

// ============================================
// Variabel Data
// ============================================
$no_skhp   = !empty($skhp['no_surat']) ? $skhp['no_surat'] : sprintf('B / 500.2.3.15 / %04d / UPTD-08 / %s', $d['id_pengujian'] ?? 0, date('Y', strtotime($d['tgl_pengujian'] ?? 'now')));
$no_daftar = !empty($skhp['no_daftar']) ? $skhp['no_daftar'] : sprintf('%04d / %s / %s', $d['id_pengujian'] ?? 0, date('m', strtotime($d['tgl_pengujian'] ?? 'now')), date('Y', strtotime($d['tgl_pengujian'] ?? 'now')));

$masa_berlaku = !empty($skhp['masa_berlaku']) ? tglLongIndo($skhp['masa_berlaku']) : (!empty($d['tgl_pengujian']) ? tglLongIndo(date('Y-m-d', strtotime($d['tgl_pengujian'] . ' +1 year'))) : '-');
$tgl_terbit   = !empty($skhp['tgl_terbit']) ? $skhp['tgl_terbit'] : ($d['tgl_pengujian'] ?? date('Y-m-d'));
$tahun_tera   = date('Y', strtotime($d['tgl_pengujian'] ?? 'now'));

$nama_kadis = !empty($skhp['nama_kepala_dinas']) ? $skhp['nama_kepala_dinas'] : 'YASMALIZAR, S.H.';
$nip_kadis  = !empty($skhp['nip_pejabat']) ? $skhp['nip_pejabat'] : '196810161998031004';

$data_petugas = isset($petugas_list) ? $petugas_list : [];

// Detail spesifik
$detail = !empty($d['detail_spesifik']) ? json_decode($d['detail_spesifik'], true) : [];
$kapasitas_nominal = '';
if (!empty($detail['kapasitas_nominal'])) {
    $kapasitas_nominal = $detail['kapasitas_nominal'];
} elseif (!empty($d['kapasitas'])) {
    $kapasitas_nominal = $d['kapasitas'];
} else {
    $kapasitas_nominal = '-';
}

// Deteksi jenis alat SPBU
$is_spbu = (stripos($d['nama_alat'] ?? '', 'pompa') !== false || stripos($d['nama_alat'] ?? '', 'spbu') !== false || stripos($d['nama_alat'] ?? '', 'bbm') !== false || stripos($d['nama_alat'] ?? '', 'dispenser') !== false);

$no_pulau     = !empty($detail['no_pulau']) ? $detail['no_pulau'] : (!empty($detail['nomor_pulau']) ? $detail['nomor_pulau'] : '');
$no_nozel     = !empty($detail['no_nozel']) ? $detail['no_nozel'] : (!empty($detail['jumlah_nozzle']) ? $detail['jumlah_nozzle'] : '');
$jenis_bbm    = !empty($detail['jenis_bbm']) ? $detail['jenis_bbm'] : (!empty($detail['daftar_produk_bbm']) ? $detail['daftar_produk_bbm'] : '');
$kecepatan    = !empty($detail['kecepatan_alir']) ? $detail['kecepatan_alir'] : '';
$kelas_ketelitian = !empty($detail['kelas_ketelitian']) ? $detail['kelas_ketelitian'] : '';

// Generate watermark text — landscape, jarak lebih dekat
$wm_line = 'upt metrologi legal  ';
$wm_block = '';
for ($i = 0; $i < 200; $i++) {
    $wm_block .= str_repeat($wm_line, 12) . "\n";
}
?>

<?php if (empty($is_pdf)): ?>
<!-- TOMBOL CETAK -->
<div class="no-print print-toolbar">
    <button class="btn-cetak" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>

    <?php if (!empty($semua_petugas) && count($semua_petugas) > 1): ?>
    <div style="margin-top:10px; font-family:'Segoe UI',sans-serif; font-size:11pt;">
        <label style="font-weight:600; color:#444; margin-right:8px;">
            👤 Petugas Pelaksana:
        </label>
        <select id="dropdownPetugas"
            style="padding:6px 14px; border-radius:20px; border:1.5px solid #16a34a; font-size:10.5pt; color:#333; cursor:pointer; outline:none; background:#f0fdf4;">
            <?php foreach ($semua_petugas as $p): ?>
            <option value="<?= htmlspecialchars($p['id_petugas']) ?>"
                <?= ($petugas_id_dipilih == $p['id_petugas'] || (empty($petugas_id_dipilih) && $p === $semua_petugas[0])) ? 'selected' : '' ?>>
                <?= htmlspecialchars($p['nama_petugas']) ?> — <?= htmlspecialchars($p['jabatan']) ?>
            </option>
            <?php endforeach; ?>
        </select>
        <button onclick="gantiPetugas()"
            style="margin-left:8px; padding:6px 18px; border-radius:20px; background:#16a34a; color:#fff; border:none; font-size:10pt; font-weight:600; cursor:pointer;">
            Terapkan
        </button>
    </div>
    <?php endif; ?>
</div>
<script>
function gantiPetugas() {
    var petugasId = document.getElementById('dropdownPetugas').value;
    var url = new URL(window.location.href);
    url.searchParams.set('petugas_id', petugasId);
    window.location.href = url.toString();
}
</script>
<?php endif; ?>

<div class="page">

    <!-- =================== WATERMARK TEKS BERULANG =================== -->
    <?php if (empty($is_pdf)): ?>
    <div class="watermark-text">
        <div class="watermark-text-inner"><?= $wm_block ?></div>
    </div>
    <?php endif; ?>

    <!-- =================== WATERMARK LOGO TENGAH =================== -->
    <img class="watermark-logo" src="<?= $logo_metrologi ?>" alt="">

    <!-- =================== KONTEN UTAMA =================== -->
    <div class="content">

        <!-- KOP SURAT -->
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    <img src="<?= $logo_singkawang ?>" alt="Logo">
                </td>
                <td class="kop-text">
                    <div class="kop-instansi">PEMERINTAH KOTA SINGKAWANG</div>
                    <div class="kop-dinas">DINAS PERDAGANGAN, PERINDUSTRIAN, KOPERASI<br>DAN USAHA KECIL MENENGAH</div>
                    <div class="kop-alamat">
                        Alamat : Jalan Firdaus H. Rais No. 38 SINGKAWANG 79123<br>
                        Telepon : 0562-631425 Faks : (0562) 631425<br>
                        Laman : disdaginkop.singkawangkota.go.id &nbsp; Pos-el : daginkopukm@singkawangkota.go.id
                    </div>
                </td>
            </tr>
        </table>
        <div class="kop-line-1"></div>
        <div class="kop-line-2"></div>

        <!-- JUDUL + NOMOR DAFTAR -->
        <div class="title-area">
            <div class="skhp-title">SURAT KETERANGAN HASIL PENGUJIAN</div>
            <div class="skhp-nomor">Nomor &nbsp;: &nbsp;<?= htmlspecialchars($no_skhp) ?></div>

            <!-- Nomor Daftar (kotak kanan atas) -->
            <div class="nodaftar-box">
                <div class="nd-label">Nomor Daftar :</div>
                <div><?= htmlspecialchars($no_daftar) ?></div>
            </div>
        </div>

        <!-- DATA UTAMA -->
        <table class="data-table">
            <!-- Nama Alat Ukur -->
            <tr>
                <td class="col-label">Nama Alat Ukur<br><span class="label-en">Measuring instrument</span></td>
                <td class="col-sep">:</td>
                <td class="col-value"><?= htmlspecialchars((string)($d['nama_alat'] ?? '-')) ?></td>
            </tr>
            <!-- P e m i l i k -->
            <tr>
                <td class="col-label">P e m i l i k<br><span class="label-en">Owner</span></td>
                <td class="col-sep">:</td>
                <td class="col-value"><?= htmlspecialchars((string)($d['nama_pemilik'] ?? '-')) ?><br><span style="font-size:10pt;"><?= htmlspecialchars((string)($d['alamat_usaha'] ?? '')) ?></span></td>
            </tr>
            <!-- Merek -->
            <tr>
                <td class="col-label">Merek<br><span class="label-en">Trade Mark</span></td>
                <td class="col-sep">:</td>
                <td class="col-value"><?= htmlspecialchars((string)($d['merk'] ?? '-')) ?></td>
            </tr>
            <!-- Nomor Seri -->
            <tr>
                <td class="col-label">Nomor Seri<br><span class="label-en">Serial Number</span></td>
                <td class="col-sep">:</td>
                <td class="col-value"><?= htmlspecialchars((string)($d['nomor_seri'] ?? '-')) ?></td>
            </tr>
            <!-- Model / Tipe -->
            <tr>
                <td class="col-label">Model / Tipe<br><span class="label-en">Model /Type</span></td>
                <td class="col-sep">:</td>
                <td class="col-value"><?= htmlspecialchars((string)($d['tipe_model'] ?? '-')) ?></td>
            </tr>

            <?php if ($is_spbu && !empty($no_pulau)): ?>
            <!-- No. Pulau - No. Mesin (SPBU) -->
            <tr>
                <td class="col-label">No. Pulau - No. Mesin</td>
                <td class="col-sep">:</td>
                <td class="col-value"><?= htmlspecialchars($no_pulau) ?></td>
            </tr>
            <?php endif; ?>

            <?php if ($is_spbu && !empty($no_nozel)): ?>
            <!-- No. Nozel - Jenis BBM -->
            <tr>
                <td class="col-label">No. Nozel - Jenis BBM</td>
                <td class="col-sep">:</td>
                <td class="col-value"><?= !empty($jenis_bbm) ? htmlspecialchars($jenis_bbm) : htmlspecialchars($no_nozel) ?></td>
            </tr>
            <?php endif; ?>

            <!-- Dibuat Oleh -->
            <tr>
                <td class="col-label">Dibuat Oleh<br><span class="label-en">Manufactured by</span></td>
                <td class="col-sep">:</td>
                <td class="col-value"><?= htmlspecialchars((string)($d['buatan'] ?? '-')) ?></td>
            </tr>

            <?php if ($is_spbu && !empty($kecepatan)): ?>
            <!-- Kecepatan Alir (SPBU) -->
            <tr>
                <td class="col-label">Kecepatan Alir</td>
                <td class="col-sep">:</td>
                <td class="col-value"><?= htmlspecialchars($kecepatan) ?></td>
            </tr>
            <?php endif; ?>

            <?php if (!$is_spbu): ?>
            <!-- Kapasitas Nominal -->
            <tr>
                <td class="col-label">Kapasitas Nominal<br><span class="label-en">Nominal Capacity</span></td>
                <td class="col-sep">:</td>
                <td class="col-value"><?= htmlspecialchars($kapasitas_nominal) ?></td>
            </tr>
            <?php endif; ?>

            <?php if (!empty($kelas_ketelitian)): ?>
            <tr>
                <td class="col-label">Kelas Ketelitian<br><span class="label-en">Accuracy Class</span></td>
                <td class="col-sep">:</td>
                <td class="col-value"><?= htmlspecialchars($kelas_ketelitian) ?></td>
            </tr>
            <?php endif; ?>

            <!-- Motode -->
            <tr>
                <td class="col-label">Motode<br><span class="label-en">Methode</span></td>
                <td class="col-sep">:</td>
                <td class="col-value"><?= htmlspecialchars((string)($d['metode_uji'] ?? 'Membandingkan langsung dengan standard')) ?></td>
            </tr>
            <!-- Standard / Telusuran -->
            <tr>
                <td class="col-label">Standard / Telusuran<br><span class="label-en">Standard / Treceablility</span></td>
                <td class="col-sep">:</td>
                <td class="col-value"><?= htmlspecialchars((string)($d['standar_uji'] ?? '-')) ?></td>
            </tr>
            <!-- Suhu Dasar -->
            <tr>
                <td class="col-label">Suhu Dasar<br><span class="label-en">Temp. Refrence</span></td>
                <td class="col-sep">:</td>
                <td class="col-value"><?= !empty($d['suhu_dasar']) ? htmlspecialchars($d['suhu_dasar']) . ' °C' : '-' ?></td>
            </tr>
            <!-- Dilaksanakan Oleh -->
            <tr>
                <td class="col-label">Dilaksanakan Oleh<br><span class="label-en">Peformed by</span></td>
                <td class="col-sep">:</td>
                <td class="col-value">
                    <?php if (!empty($data_petugas)): ?>
                        <?php $pt = $data_petugas[0]; // Hanya tampilkan petugas pertama (ketua/utama) ?>
                        <?= htmlspecialchars($pt['nama_petugas']) ?> / NIP <?= htmlspecialchars($pt['nip']) ?> / <?= htmlspecialchars($pt['jabatan']) ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
            <!-- Pada Tanggal -->
            <tr>
                <td class="col-label">Pada Tanggal<br><span class="label-en">D a t e</span></td>
                <td class="col-sep">:</td>
                <td class="col-value"><strong><?= !empty($d['tgl_pengujian']) ? tglLongIndo($d['tgl_pengujian']) : '-' ?></strong></td>
            </tr>
            <!-- H a s i l -->
            <tr>
                <td class="col-label" style="padding-top:4px;"><strong>H &nbsp;a &nbsp;s &nbsp;i &nbsp;l</strong><br><span class="label-en">Result</span></td>
                <td class="col-sep" style="padding-top:4px;">:</td>
                <td class="col-value" style="padding-top:4px;">
                    <?php if (($d['hasil_uji'] ?? '') === 'Sah'): ?>
                        1. &nbsp;&nbsp;Disahkan untuk : <strong>TERA TAHUN <?= $tahun_tera ?></strong><br>
                        2. &nbsp;&nbsp;Disahkan berdasarkan Undang-Undang RI Nomor 2 Tahun 1981 tentang <strong>Metrologi Legal.</strong>
                    <?php else: ?>
                        1. &nbsp;&nbsp;<strong>DIBATALKAN</strong> — Tidak Memenuhi Syarat Metrologi Legal<br>
                        2. &nbsp;&nbsp;Berdasarkan Undang-Undang RI Nomor 2 Tahun 1981 tentang <strong>Metrologi Legal.</strong>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <!-- GARIS PEMISAH -->
        <hr class="separator-line">

        <!-- TANDA TANGAN -->
        <table class="ttd-area" cellspacing="0" cellpadding="0">
            <tr>
                <td class="ttd-kiri"></td>
                <td class="ttd-kanan">
                    Singkawang, &nbsp;&nbsp;&nbsp;&nbsp;<?= tglBulanTahun($tgl_terbit) ?>.<br><br>
                    Kepala Dinas Perdagangan, Perindustrian, Koperasi<br>
                    Dan Usaha Kecil Menengah Kota Singkawang<br>
                    <br><br><br><br>
                    <strong><u><?= htmlspecialchars($nama_kadis) ?></u></strong><br>
                    Pembina Utama Muda (IV/c<br>
                    NIP <?= htmlspecialchars($nip_kadis) ?>
                </td>
            </tr>
        </table>

        <!-- CATATAN -->
        <div class="catatan-box">
            <div class="catatan-title">CATATAN :</div>
            <ol class="catatan-list">
                <li>
                    <?php if ($is_spbu): ?>
                        Pompa Ukur BBM tersebut di atas disarankan untuk ditera ulang kembali pada tanggal : <strong style="color: red;"><?= $masa_berlaku ?></strong>
                    <?php else: ?>
                        Surat Keterangan Hasil Pengujian ini berlaku sampai tanggal : <strong style="color: red;"><?= $masa_berlaku ?></strong>
                    <?php endif; ?>
                </li>
                <li>Telah terpasang tanda tera sah SP6 pada bagian yang dapat mempengaruhi hasil pengukuran;</li>
                <li>Surat Keterangan Hasil Pengujian ini tidak berlaku apabila Tanda Teranya rusak/putus atau pada alat ukur tersebut dilakukan perbaikan / perubahan yang mempengaruhi penunjukkan / menyimpang dari nilai seharusnya atau yang diizinkan ( UUML Pasal 25 huruf c, d dan e ).</li>
            </ol>
        </div>

    </div><!-- /content -->

</div><!-- /page -->

<?php if (empty($is_pdf)): ?>
<script>
window.onload = function() { window.print(); }
</script>
<?php endif; ?>
</body>
</html>
