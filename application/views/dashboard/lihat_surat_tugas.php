<?php
// Helper: format tanggal Indonesia
function tglIndo($date_str) {
    $bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
              'Juli','Agustus','September','Oktober','November','Desember'];
    $ts = strtotime($date_str);
    return date('d', $ts) . ' ' . $bulan[(int)date('m', $ts)] . ' ' . date('Y', $ts);
}
?>
<?php $this->load->view('layout/dash_header'); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mb-3">
    <div>
        <h4 class="mb-1 fw-bold"><i class="fas fa-file-contract text-primary me-2"></i>Rincian Surat Tugas</h4>
        <small class="text-muted">Nomor: <b><?= htmlspecialchars((string)$st['no_surat_tugas']) ?></b></small>
    </div>
    <div class="d-flex gap-2 mt-2 mt-md-0">
        <?php if (in_array($this->session->userdata('level'), ['Admin', 'Kepala UPT'])): ?>
        <a href="<?= site_url('surat-tugas/cetak/' . $st['id_surat_tugas']) ?>" target="_blank"
           class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-file-word me-2"></i> Unduh .doc
        </a>
        <?php endif; ?>
        <a href="<?= site_url('surat-tugas') ?>" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">

        <!-- Preview Surat -->
        <div class="p-4 p-md-5" style="background:#f8f9fa;">
            <div class="mx-auto bg-white shadow-sm border rounded-3 p-4 p-md-5" style="max-width:800px; font-family:'Times New Roman',Times,serif; font-size:12pt; color:#000;">

                <!-- KOP -->
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

            </div>
        </div>
        <!-- End Preview -->

    </div>
</div>

<?php $this->load->view('layout/dash_footer'); ?>
