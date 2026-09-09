<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Tugas <?= htmlspecialchars($st['no_surat_tugas']) ?> | E-TERA SKHP</title>
<?php
// Helper: format tanggal Indonesia
function tglIndo($date_str) {
    $bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
              'Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($date_str);
    return date('d', $ts) . ' ' . $bulan[(int)date('m', $ts)] . ' ' . date('Y', $ts);
}
?>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f0f2f5; margin: 0; }

        /* Toolbar */
        .doc-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 24px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .doc-toolbar a.btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f1f5f9;
            border: none;
            border-radius: 20px;
            padding: 7px 18px;
            font-size: 0.83rem;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            transition: background 0.2s;
        }
        .doc-toolbar a.btn-back:hover { background: #e2e8f0; }
        .doc-toolbar .toolbar-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1e293b;
        }
        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #1e40af, #0891b2);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 7px 18px;
            font-size: 0.83rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        /* Document */
        .doc-wrapper {
            padding: 32px 20px 60px;
            display: flex;
            justify-content: center;
        }
        .doc-page {
            background: white;
            width: 210mm;
            min-height: 297mm;
            padding: 20mm 20mm 20mm 25mm;
            box-shadow: 0 4px 30px rgba(0,0,0,0.12);
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
        }

        @media print {
            body { background: white; }
            .doc-toolbar { display: none; }
            .doc-wrapper { padding: 0; }
            .doc-page { box-shadow: none; margin: 0; width: 100%; padding: 15mm 20mm; }
        }
        @media (max-width: 768px) {
            .doc-page { width: 100%; padding: 12px; font-size: 10pt; }
        }
    </style>
</head>
<body>

<!-- Toolbar -->
<div class="doc-toolbar">
    <a href="<?= site_url('petugas-dashboard') ?>" class="btn-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
    </a>
    <div class="toolbar-title">📄 Surat Tugas Resmi</div>
    <button class="btn-print" onclick="window.print()">
        <i class="fas fa-print"></i> Cetak Dokumen
    </button>
</div>

<!-- Document -->
<div class="doc-wrapper">
<div class="doc-page">

    <!-- KOP SURAT -->
    <table style="width:100%;border-collapse:collapse;margin-bottom:4px;">
        <tr>
            <td style="width:70px;vertical-align:middle;text-align:center;">
                <img src="<?= base_url('asset/logo_kop_extracted.png') ?>" alt="Logo" style="width:65px;height:auto;">
            </td>
            <td style="vertical-align:middle;text-align:center;line-height:1.3;padding:0 8px;">
                <div style="font-size:10pt;">PEMERINTAH KOTA SINGKAWANG</div>
                <div style="font-size:15pt;font-weight:bold;margin:2px 0;">DINAS PERDAGANGAN, PERINDUSTRIAN,<br>KOPERASI, DAN USAHA KECIL MENENGAH</div>
                <div style="font-size:8pt;">
                    Jalan Firdaus H. Rais No. 38 Singkawang, Kode Pos 79123<br>
                    Telepon : (0562) 631425 &nbsp; Faksimile : (0562) 631425<br>
                    Laman : disdaginkop.singkawangkota.go.id &nbsp;|&nbsp; Pos-el : daginkopukm@singkawangkota.go.id
                </div>
            </td>
            <td style="width:70px;"></td>
        </tr>
    </table>
    <div style="border-top:3px solid #000;margin-top:4px;"></div>
    <div style="border-top:1px solid #000;margin-top:2px;margin-bottom:12px;"></div>

    <!-- JUDUL -->
    <div style="text-align:center;font-size:13pt;font-weight:bold;text-decoration:underline;letter-spacing:1px;margin-bottom:2px;">SURAT TUGAS</div>
    <div style="text-align:center;font-size:11pt;margin-bottom:10px;">Nomor : &nbsp; <?= htmlspecialchars((string)$st['no_surat_tugas']) ?></div>

    <!-- DASAR -->
    <table style="width:100%;border-collapse:collapse;margin-bottom:6px;">
        <tr>
            <td style="width:13%;vertical-align:top;white-space:nowrap;font-size:11pt;padding:1px 3px;">Dasar</td>
            <td style="width:2%;vertical-align:top;font-size:11pt;padding:1px 3px;">:</td>
            <td style="width:85%;font-size:11pt;padding:1px 3px;">
                <table style="width:100%;border-collapse:collapse;">
                    <tr><td style="width:4%;vertical-align:top;">1.</td><td>UU Nomor 02 Tahun 1981 tentang Metrologi Legal.</td></tr>
                    <tr><td style="vertical-align:top;">2.</td><td>Permendag Nomor 24 Tahun 2024 tentang Kegiatan Tera dan Tera Ulang Alat Ukur, Alat Takar, Alat Timbang dan Alat Perlengkapan Metrologi Legal.</td></tr>
                    <tr><td style="vertical-align:top;">3.</td><td>
                        Permohonan Tera/Kalibrasi alat dari <b><?= htmlspecialchars((string)$st['nama_pemilik']) ?></b>
                        untuk alat <b><?= htmlspecialchars((string)$st['nama_alat']) ?></b>
                        <?php if (!empty($st['merk'])): ?>(Merk: <?= htmlspecialchars((string)$st['merk']) ?>)<?php endif; ?>.
                    </td></tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- MEMERINTAHKAN -->
    <div style="text-align:center;font-weight:bold;font-size:11pt;margin:10px 0 6px;">MEMERINTAHKAN :</div>

    <!-- KEPADA -->
    <table style="width:100%;border-collapse:collapse;margin-bottom:6px;">
        <tr>
            <td style="width:13%;vertical-align:top;white-space:nowrap;font-size:11pt;padding:1px 3px;">Kepada</td>
            <td style="width:2%;vertical-align:top;font-size:11pt;padding:1px 3px;">:</td>
            <td style="width:85%;font-size:11pt;padding:1px 3px;">
                <?php $no = 1; foreach ($st['data_petugas'] as $p): ?>
                <table style="width:100%;border-collapse:collapse;margin-bottom:4px;">
                    <tr>
                        <td style="width:5%;vertical-align:top;"><?= $no++ ?>.</td>
                        <td>
                            <table style="width:100%;border-collapse:collapse;border:1px dashed #666;">
                                <tr>
                                    <td style="width:24%;padding:2px 6px;white-space:nowrap;">Nama</td>
                                    <td style="width:3%;padding:2px 3px;">:</td>
                                    <td style="padding:2px 6px;font-weight:bold;"><?= htmlspecialchars((string)$p['nama_petugas']) ?></td>
                                </tr>
                                <tr>
                                    <td style="padding:2px 6px;">Pangkat/Gol</td>
                                    <td style="padding:2px 3px;">:</td>
                                    <td style="padding:2px 6px;"><?= htmlspecialchars((string)($p['pangkat'] ?? '-')) ?></td>
                                </tr>
                                <tr>
                                    <td style="padding:2px 6px;"><?= strlen(preg_replace('/\D/', '', $p['nip'] ?? '')) >= 18 ? 'NI PPPK' : 'NIP' ?></td>
                                    <td style="padding:2px 3px;">:</td>
                                    <td style="padding:2px 6px;"><?= htmlspecialchars((string)($p['nip'] ?? '-')) ?></td>
                                </tr>
                                <tr>
                                    <td style="padding:2px 6px;">Jabatan</td>
                                    <td style="padding:2px 3px;">:</td>
                                    <td style="padding:2px 6px;"><?= htmlspecialchars((string)$p['jabatan']) ?></td>
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
    <table style="width:100%;border-collapse:collapse;margin-bottom:10px;">
        <tr>
            <td style="width:13%;vertical-align:top;white-space:nowrap;font-size:11pt;padding:1px 3px;">Untuk</td>
            <td style="width:2%;vertical-align:top;font-size:11pt;padding:1px 3px;">:</td>
            <td style="width:85%;font-size:11pt;padding:1px 3px;">
                <table style="width:100%;border-collapse:collapse;">
                    <tr>
                        <td style="width:4%;vertical-align:top;">1.</td>
                        <td>Kegiatan Tera / Tera Ulang <b><?= htmlspecialchars((string)$st['nama_alat']) ?></b>
                        <?php if (!empty($st['merk'])): ?>merek <b><?= htmlspecialchars((string)$st['merk']) ?></b><?php endif; ?>
                        di <?= htmlspecialchars((string)($st['alamat_usaha'] ?: 'lokasi pemohon')) ?>.</td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top;">2.</td>
                        <td>Kegiatan dilaksanakan pada tanggal <b><?= tglIndo($st['tgl_tugas']) ?></b>.</td>
                    </tr>
                    <tr>
                        <td style="vertical-align:top;">3.</td>
                        <td>Melaksanakan tugas dengan disiplin dan penuh tanggung jawab.</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- TTD -->
    <table style="width:100%;border-collapse:collapse;margin-top:14px;">
        <tr>
            <td style="width:50%;"></td>
            <td style="width:50%;font-size:11pt;line-height:1.6;padding-left:10px;">
                Ditetapkan di Singkawang<br>
                Pada tanggal &nbsp;: <?= tglIndo($st['tgl_tugas']) ?><br>
                Kepala Dinas Perdagangan, Perindustrian, Koperasi<br>
                dan Usaha Kecil Menengah Kota Singkawang<br>
                <br><br><br><br>
                <b><u>YASMALIZAR, S.H.</u></b><br>
                Pembina Utama Muda (IV/c)<br>
                NIP. 196810161998031004
            </td>
        </tr>
    </table>

</div><!-- /.doc-page -->
</div><!-- /.doc-wrapper -->

</body>
</html>
