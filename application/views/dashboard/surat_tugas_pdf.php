<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Tugas - <?= htmlspecialchars((string) $st['no_surat_tugas']) ?></title>
    <style>
        @page { margin: 1.5cm 2cm 2cm 2cm; }
        body {
            font-family: 'Times New Roman', Times, serif; /* Mengubah font menjadi Times New Roman untuk formalitas * /
            font-size: 12pt;
            line-height: 1.5; /* Spasi baris lebih bernafas dan realistis */
            color: #000;
        }
        .header-table {
            width: 100%;
            border-bottom: 3px solid black; /* Garis tebal bawah kop */
            padding-bottom: 8px;
            margin-bottom: 2px;
        }
        .header-table td { vertical-align: middle; }
        .logo-col { width: 17%; text-align: center; }
        .logo { width: 85px; height: auto; }
        .kop-text-col { width: 83%; text-align: center; }
        .kop-pemerintah { font-size: 14pt; font-family: Arial, Helvetica, sans-serif; }
        .kop-dinas { font-size: 17pt; font-weight: bold; font-family: Arial, Helvetica, sans-serif; }
        .kop-detail { font-size: 10pt; margin-top: 5px; font-family: Arial, Helvetica, sans-serif; }
        .border-thin { border-top: 1px solid black; margin-bottom: 25px; } /* Garis tipis ganda untuk kop */
        
        .title-surat {
            text-align: center;
            font-weight: bold;
            font-size: 13pt;
            text-decoration: underline;
            margin-bottom: 2px;
        }
        .nomor-surat { text-align: center; font-size: 11pt; margin-bottom: 20px; }
        
        .content-body { text-align: justify; }
        .table-data { width: 100%; margin-left: 15px; margin-bottom: 20px; margin-top: 10px; }
        .table-data td { vertical-align: top; padding: 3px 0; }
        .petugas-block { margin-bottom: 8px; }
        
        .footer-sig { width: 100%; margin-top: 25px; }
        .sig-box { width: 45%; float: right; text-align: center; }
    </style>
</head>
<body>
    <?php
        // Menggunakan Logo Kota Singkawang sesuai permintaan
        $path = FCPATH . 'asset/Logo_Kota_Singkawang.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $base64 = '';
        if(file_exists($path)) {
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $bulan_indo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $tgl_tugasParts = explode('-', $st['tgl_tugas']);
        $tgl_tugasIndo = $tgl_tugasParts[2] . ' ' . $bulan_indo[(int)$tgl_tugasParts[1]] . ' ' . $tgl_tugasParts[0];
    ?>

    <table class="header-table" cellspacing="0" cellpadding="0">
        <tr>
            <td class="logo-col">
                <?php if($base64): ?>
                    <img src="<?= $base64 ?>" class="logo">
                <?php endif; ?>
            </td>
            <td class="kop-text-col">
                <div class="kop-pemerintah">PEMERINTAH KOTA SINGKAWANG</div>
                <div class="kop-dinas">DINAS PERDAGANGAN, PERINDUSTRIAN, KOPERASI<br>DAN USAHA KECIL MENENGAH</div>
                <div class="kop-detail">
                    Alamat : Jalan Firdaus H. Rais No. 38 SINGKAWANG 79123<br>
                    Telepon : 0562-631425 Faks : (0562) 631425
                </div>
            </td>
        </tr>
    </table>
    <div class="border-thin"></div>

    <div class="title-surat">SURAT TUGAS LAPANGAN PENGUJIAN ALAT UTTP</div>
    <div class="nomor-surat">Nomor : <?= htmlspecialchars((string) $st['no_surat_tugas']) ?></div>

    <div class="content-body">
        <p>Berdasarkan Peraturan Perundang-undangan RI tentang Metrologi Legal dan menindaklanjuti permohonan pengajuan uji alat/tera, dengan ini Kepala Dinas Perdagangan, Perindustrian, Koperasi dan Usaha Kecil Menengah Kota Singkawang memberikan tugas kepada:</p>
        
        <table class="table-data" cellspacing="0" cellpadding="0">
            <?php 
            $no = 1;
            foreach($petugas_list as $ptgs): ?>
            <tr>
                <td style="width: 5%;"><?= $no++ ?>.</td>
                <td style="width: 25%;">Nama</td>
                <td style="width: 2%;">:</td>
                <td style="width: 68%;"><b><?= htmlspecialchars((string) $ptgs['nama_petugas']) ?></b></td>
            </tr>
            <tr>
                <td></td>
                <td>NIP</td>
                <td>:</td>
                <td><?= htmlspecialchars((string) $ptgs['nip']) ?></td>
            </tr>
            <tr class="petugas-block">
                <td></td>
                <td>Jabatan</td>
                <td>:</td>
                <td style="padding-bottom: 8px;"><?= htmlspecialchars((string) $ptgs['jabatan']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <p>Untuk melaksanakan tugas <b>Pengujian Lapangan / Tera Alat Ukur, Takar, Timbang dan Perlengkapannya (UTTP)</b> terhadap:</p>

        <table class="table-data" cellspacing="0" cellpadding="0">
            <tr>
                <td style="width: 30%;">Nama Pemilik Alat</td><td style="width: 2%;">:</td>
                <td><b><?= htmlspecialchars((string) $st['nama_pemilik']) ?></b></td>
            </tr>
            <tr>
                <td>Alamat Usaha / Lokasi</td><td>:</td>
                <td><?= htmlspecialchars((string) $st['alamat_usaha']) ?></td>
            </tr>
            <tr>
                <td>Jenis Alat UTTP</td><td>:</td>
                <td><?= htmlspecialchars((string) $st['nama_alat']) ?></td>
            </tr>
            <tr>
                <td>Merek / Tipe Model</td><td>:</td>
                <td><?= htmlspecialchars((string) $st['merk']) ?> / <?= htmlspecialchars((string) $st['tipe_model']) ?></td>
            </tr>
            <tr>
                <td>Nomor Seri</td><td>:</td>
                <td><?= htmlspecialchars((string) $st['nomor_seri']) ?></td>
            </tr>
        </table>

        <p style="margin-top: 15px;">Demikian Surat Tugas ini diberikan untuk dilaksanakan dengan penuh rasa tanggung jawab serta melaporkan hasil pelaksanaan tugas kepada atasan.</p>
    </div>

    <div class="footer-sig">
        <div class="sig-box">
            Singkawang, &nbsp;&nbsp;&nbsp;&nbsp; <?= $tgl_tugasIndo ?>.<br>
            Kepala Dinas Perdagangan, Perindustrian, Koperasi<br>
            Dan Usaha Kecil Menengah Kota Singkawang<br>
            <br><br><br><br>
            <b><u>Hj. ANTIN SUPRIHATIN, S.Sos.,M.Si</u></b><br>
            Pembina Utama Muda (IV/c)<br>
            NIP. 19710515 199203 2 007
        </div>
        <div style="clear:both;"></div>
    </div>
</body>
</html>
<?php $this->load->view('layout/dash_footer'); ?>