<?php
$template = 'SPT TERA TERA ULANG.docx';
$zip = new ZipArchive;
$zip->open($template);
$expectedXml = $zip->getFromName('word/document.xml');
$zip->close();


// Need mock CI instance to test replace_docx_data.
// Instead of full CI, just extract the method.
class Transformer {
    public function _replace_docx_data($xml, $st) {
        $dasar3 = 'Permohonan Tera/Kalibrasi dari ' . $st['nama_pemilik'] . ' untuk alat ' . $st['nama_alat'] .
                  (!empty($st['merk']) ? ' (Merk: ' . $st['merk'] . ')' : '') . '.';
        $xml = $this->_replaceTextInRuns($xml, 'Surat dari PT. Bintang Jasa Transkal nomor: BJT/02/XI/2025 tanggal 25 Nopember 2025 perihal Permohonan Tera Dispenser.', $dasar3);
        $xml = $this->_replaceTextInRuns($xml, 'Surat dari Perusahan CENDANA tanggal 8 Desember 2025 perihal Permohonan Kalibrasi Timbangan Jembatan Elektronik.', '');
        $xml = $this->_replaceTextInRuns($xml, 'Surat dari PT. HOTMIX PRO nomor: 001/HMP/II/ perihal Permohonan Tera Ulang Timbangan Jembatan Elektronik.', $dasar3);
        $xml = $this->_replaceTextInRuns($xml, 'Surat dari PT. Sinar Anugerah Singkawang tanggal 6 Februari 2026 perihal Permohonan Tera Timbangan Jembatan Elektronik.', '');

        $untuk1 = 'Kegiatan Tera/Tera Ulang ' . $st['nama_alat'] .
                  (!empty($st['merk']) ? ' merek ' . $st['merk'] : '') .
                  ' di ' . ($st['alamat_usaha'] ?: 'lokasi pemohon') . '.';
        $xml = $this->_replaceTextInRuns($xml, 'Kegiatan Tera Mesin Dispener SPBU Jl. Tani dan Kalibrasi Timbangan Jembatan Elektronik di Kota Singkawang.', $untuk1);
        $xml = $this->_replaceTextInRuns($xml, 'Kegiatan Tera/ Tera Ulang Timbangan Jembatan  PT. HOTMIX PRO dan  PT. Sinar Anugerah Singkawang.', $untuk1);

        $xml = $this->_replaceTextInRuns($xml, '800.1.11.1 / 32 / UPTD-08/ 2026', $st['no_surat_tugas']);
        $xml = $this->_replaceTextInRuns($xml, '800.1.11.1 / 82 / UPTD-08/ 2026', $st['no_surat_tugas']);

        $tgl_indo = '13 April 2026';
        $xml = $this->_replaceTextInRuns($xml, '19 Januari 2026', $tgl_indo);
        $xml = $this->_replaceTextInRuns($xml, '6 Februari 2026', $tgl_indo);

        $untuk2 = 'Kegiatan dilaksanakan pada tanggal ' . $tgl_indo . '.';
        $xml = $this->_replaceTextInRuns($xml, 'Kegiatan dilaksanakan selama 2 (Dua) Hari dari tanggal 19 – 20 Januari 2026.', $untuk2);
        $xml = $this->_replaceTextInRuns($xml, 'Kegiatan dilaksanakan selama 2 (Dua) Hari dari tanggal 9 – 10 Februari 2026.', $untuk2);

        $template_petugas = [
            ['nama' => 'Yasmalizar, S.H.',      'pangkat' => 'Pembina Utama Muda (IV/c)', 'nip_lbl' => 'NIP',     'nip' => '196810161998031004', 'jabatan' => 'Kepala Dinas'],
            ['nama' => 'Eri Kurniawan, S.Sos',  'pangkat' => 'Penata Tk.I / (III/d)',     'nip_lbl' => 'NIP',     'nip' => '198308302009031003', 'jabatan' => 'Kepala UPT Metrologi Legal'],
            ['nama' => 'Wiryamor, ST',           'pangkat' => 'Penata Tk.I / (III/d)',     'nip_lbl' => 'NIP',     'nip' => '197404302008031001', 'jabatan' => 'Pengelola Metrologi dan Perlindungan Konsumen'],
            ['nama' => 'Rudi Indratno',          'pangkat' => 'Penata / (III/c)',          'nip_lbl' => 'NIP',     'nip' => '196907021991031005', 'jabatan' => 'Penera Penyelia'],
            ['nama' => 'Bayu Yudhatama, ST',     'pangkat' => 'Penata Muda / IX',          'nip_lbl' => 'NI PPPK', 'nip' => '199302082023211013', 'jabatan' => 'Penera Ahli Pertama'],
            ['nama' => 'Darmaji',                'pangkat' => 'V',                          'nip_lbl' => 'NI PPPK', 'nip' => '198210112025211019', 'jabatan' => 'Pengadministrasi Perkantoran'],
            ['nama' => 'Purnando',               'pangkat' => 'V',                          'nip_lbl' => 'NI PPPK', 'nip' => '199909022025211060', 'jabatan' => 'Operator Layanan Operasional'],
        ];

        foreach ($template_petugas as $i => $tp) {
            if (isset($st['data_petugas'][$i])) {
                $dp = $st['data_petugas'][$i];
                $xml = $this->_replaceTextInRuns($xml, $tp['nama'],    $dp['nama_petugas']);
                $xml = $this->_replaceTextInRuns($xml, $tp['pangkat'], $dp['pangkat'] ?? '-');
                $xml = $this->_replaceTextInRuns($xml, $tp['nip'],     $dp['nip']);
                $xml = $this->_replaceTextInRuns($xml, $tp['jabatan'], $dp['jabatan']);
            } else {
                $xml = $this->_replaceTextInRuns($xml, $tp['nama'],    '');
                $xml = $this->_replaceTextInRuns($xml, $tp['pangkat'], '');
                $xml = $this->_replaceTextInRuns($xml, $tp['nip'],     '');
                $xml = $this->_replaceTextInRuns($xml, $tp['jabatan'], '');
            }
        }

        $extra = ['Edy Chahyono, ST', 'Leo Andrian, A.Md.Kep', '19830310 201101 1 006', '198607032025211150', 'Pengawas Perdagangan Ahli Muda', 'Pengelola Layanan Operasional', 'Penata Tk.I  / (III/d)'];
        foreach ($extra as $e) {
            $xml = $this->_replaceTextInRuns($xml, $e, '');
        }

        return $xml;
    }

    private function _replaceTextInRuns($xml, $search, $replace) {
        if (empty($search)) return $xml;
        $escaped_search = htmlspecialchars($search, ENT_XML1, 'UTF-8');
        $escaped_replace = htmlspecialchars($replace, ENT_XML1, 'UTF-8');
        if (strpos($xml, $escaped_search) !== false) {
            $xml = str_replace($escaped_search, $escaped_replace, $xml);
        }
        return $xml;
    }
}

$tf = new Transformer();
$st = [
    'nama_pemilik' => 'Test Pemohon',
    'nama_alat' => 'Test Alat',
    'merk' => 'Test Merk',
    'alamat_usaha' => 'Test Alamat',
    'no_surat_tugas' => 'TEST/001/2026',
    'data_petugas' => [
        ['nama_petugas' => 'Petugas 1', 'pangkat' => 'Gol A', 'nip' => '111', 'jabatan' => 'Jab 1']
    ]
];
$newXml = $tf->_replace_docx_data($expectedXml, $st);

$doc = new DOMDocument();
libxml_use_internal_errors(true);
if (!$doc->loadXML($newXml)) {
    echo "XML is invalid!\n";
    foreach(libxml_get_errors() as $e) {
        echo $e->message . "\n";
    }
} else {
    echo "XML is valid.\n";
    if ($newXml === $expectedXml) {
        echo "No changes were made to XML!\n";
    } else {
        echo "Changes were made to XML successfully.\n";
    }
}
