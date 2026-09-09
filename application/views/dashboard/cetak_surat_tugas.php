<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Surat Tugas - <?= htmlspecialchars((string)$st['no_surat_tugas']) ?></title>
<style>
body { 
    font-family: "Times New Roman", Times, serif; 
    font-size: 12pt; 
    color: #000; 
    background: #e2e8f0; /* Gray background behind the paper */
    margin: 0; 
    padding: 2cm 0; 
    display: flex;
    justify-content: center;
}
.page { 
    width: 21cm; 
    min-height: 29.7cm; 
    background: white; 
    padding: 2cm; 
    box-sizing: border-box; 
    box-shadow: 0 15px 35px rgba(0,0,0,0.2); /* Nice drop shadow */
    border-radius: 4px; /* Slightly rounded edges for browser view */
}
@page { 
    size: A4 portrait; 
    margin: 1.5cm 2cm; 
}
@media print {
    body { background: white; padding: 0; display: block; }
    .page { width: 100%; margin: 0; padding: 0; box-shadow: none; border-radius: 0; min-height: auto; }
}

table { border-collapse: collapse; width: 100%; }
.kop-surat { border-bottom: 4px solid #000; margin-bottom: 2px; }
.kop-surat-2 { border-bottom: 1px solid #000; margin-bottom: 20px; }
.text-center { text-align: center; }
.fw-bold { font-weight: bold; }
</style>
</head>
<body>
<?php
// Helper: format tanggal Indonesia
function tglIndo($date_str) {
    $bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
              'Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($date_str);
    return date('d', $ts) . ' ' . $bulan[(int)date('m', $ts)] . ' ' . date('Y', $ts);
}
?>

<div class="page">
    <!-- KOP -->
    <table style="margin-bottom: 4px;">
        <tr>
            <td style="width: 80px; text-align: center; vertical-align: middle;">
                <img src="<?= base_url('asset/logo_kop_extracted.png') ?>" alt="Logo" style="width: 80px; height: auto;">
            </td>
            <td class="text-center" style="vertical-align: middle; line-height: 1.3; padding: 0 10px;">
                <div style="font-size: 12pt;">PEMERINTAH KOTA SINGKAWANG</div>
                <div style="font-size: 16pt;" class="fw-bold">DINAS PERDAGANGAN, PERINDUSTRIAN,<br>KOPERASI, DAN USAHA KECIL MENENGAH</div>
                <div style="font-size: 9pt; margin-top: 5px;">
                    Jalan Firdaus H. Rais No. 38 Singkawang, Kode Pos 79123<br>
                    Telepon : (0562) 631425 &nbsp;&nbsp;&nbsp; Faksimile : (0562) 631425<br>
                    Laman : disdaginkop.singkawangkota.go.id &nbsp;|&nbsp; Pos-el : daginkopukm@singkawangkota.go.id
                </div>
            </td>
            <td style="width: 80px;"></td>
        </tr>
    </table>
    <div class="kop-surat"></div>
    <div class="kop-surat-2"></div>

    <!-- JUDUL -->
    <div class="text-center fw-bold" style="font-size: 14pt; text-decoration: underline; letter-spacing: 1px; margin-bottom: 5px;">SURAT TUGAS</div>
    <div class="text-center" style="font-size: 12pt; margin-bottom: 25px;">Nomor : &nbsp; <?= htmlspecialchars((string)$st['no_surat_tugas']) ?></div>

    <!-- DASAR -->
    <table style="margin-bottom: 12px;">
        <tr>
            <td style="width: 12%; vertical-align: top;">Dasar</td>
            <td style="width: 3%; vertical-align: top;">:</td>
            <td style="width: 85%;">
                <table>
                    <tr><td style="width: 5%; vertical-align: top;">1.</td><td style="padding-bottom:5px; text-align: justify;">UU Nomor 02 Tahun 1981 tentang Metrologi Legal.</td></tr>
                    <tr><td style="vertical-align: top;">2.</td><td style="padding-bottom:5px; text-align: justify;">Permendag Nomor 24 Tahun 2024 tentang Kegiatan Tera dan Tera Ulang Alat Ukur, Alat Takar, Alat Timbang dan Alat Perlengkapan Metrologi Legal.</td></tr>
                    <tr><td style="vertical-align: top;">3.</td><td style="padding-bottom:5px; text-align: justify;">
                        Permohonan Tera/Kalibrasi dari <b><?= htmlspecialchars((string)$st['nama_pemilik']) ?></b>
                        untuk alat <b><?= htmlspecialchars((string)$st['nama_alat']) ?></b>
                        <?php if (!empty($st['merk'])): ?>(Merk: <?= htmlspecialchars((string)$st['merk']) ?>)<?php endif; ?>.
                    </td></tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- MEMERINTAHKAN -->
    <div class="text-center fw-bold" style="margin: 25px 0 15px;">MEMERINTAHKAN :</div>

    <!-- KEPADA -->
    <table style="margin-bottom: 15px;">
        <tr>
            <td style="width: 12%; vertical-align: top;">Kepada</td>
            <td style="width: 3%; vertical-align: top;">:</td>
            <td style="width: 85%;">
                <?php $no = 1; foreach ($st['data_petugas'] as $p): ?>
                <table style="margin-bottom: 10px;">
                    <tr>
                        <td style="width: 5%; vertical-align: top;"><?= $no++ ?>.</td>
                        <td>
                            <table style="border: 1px dashed #333;">
                                <tr>
                                    <td style="width: 25%; padding: 4px 10px;">Nama</td>
                                    <td style="width: 3%; padding: 4px;">:</td>
                                    <td style="padding: 4px 10px;" class="fw-bold"><?= htmlspecialchars((string)$p['nama_petugas']) ?></td>
                                </tr>
                                <tr>
                                    <td style="padding: 4px 10px;">Pangkat/Gol</td>
                                    <td style="padding: 4px;">:</td>
                                    <td style="padding: 4px 10px;"><?= htmlspecialchars((string)($p['pangkat'] ?? '-')) ?></td>
                                </tr>
                                <tr>
                                    <td style="padding: 4px 10px;"><?= strlen(preg_replace('/\D/', '', $p['nip'] ?? '')) >= 18 ? 'NI PPPK' : 'NIP' ?></td>
                                    <td style="padding: 4px;">:</td>
                                    <td style="padding: 4px 10px;"><?= htmlspecialchars((string)($p['nip'] ?? '-')) ?></td>
                                </tr>
                                <tr>
                                    <td style="padding: 4px 10px;">Jabatan</td>
                                    <td style="padding: 4px;">:</td>
                                    <td style="padding: 4px 10px;"><?= htmlspecialchars((string)$p['jabatan']) ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <?php endforeach; ?>
            </td>
        </tr>
    </table>

    <!-- UNTUK -->
    <table style="margin-bottom: 25px;">
        <tr>
            <td style="width: 12%; vertical-align: top;">Untuk</td>
            <td style="width: 3%; vertical-align: top;">:</td>
            <td style="width: 85%;">
                <table>
                    <tr>
                        <td style="width: 5%; vertical-align: top;">1.</td>
                        <td style="padding-bottom:6px; text-align: justify;">Kegiatan Tera / Tera Ulang <b><?= htmlspecialchars((string)$st['nama_alat']) ?></b>
                        <?php if (!empty($st['merk'])): ?>merek <b><?= htmlspecialchars((string)$st['merk']) ?></b><?php endif; ?>
                        di <?= htmlspecialchars((string)($st['alamat_usaha'] ?: 'lokasi pemohon')) ?>.</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">2.</td>
                        <td style="padding-bottom:6px;">Kegiatan dilaksanakan pada tanggal <b><?= tglIndo($st['tgl_tugas']) ?></b>.</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">3.</td>
                        <td style="padding-bottom:6px;">Melaksanakan tugas dengan disiplin dan penuh tanggung jawab.</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- TTD -->
    <table style="margin-top: 30px;">
        <tr>
            <td style="width: 50%;"></td>
            <td style="width: 50%; line-height: 1.5; padding-left: 20px;">
                Ditetapkan di Singkawang<br>
                Pada tanggal &nbsp;: <?= tglIndo($st['tgl_tugas']) ?><br>
                Kepala Dinas Perdagangan, Perindustrian, Koperasi<br>
                dan Usaha Kecil Menengah Kota Singkawang<br>
                <br><br><br><br><br>
                <b><u>YASMALIZAR, S.H.</u></b><br>
                Pembina Utama Muda (IV/c)<br>
                NIP. 196810161998031004
            </td>
        </tr>
    </table>
</div>

<script>
window.onload = function() {
    window.print();
}
</script>
</body>
</html>
