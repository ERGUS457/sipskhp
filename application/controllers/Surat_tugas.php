<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_tugas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cek autentikasi: pastikan pengguna sudah login sebelum mengakses controller ini
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    // Fungsi utama untuk menampilkan halaman daftar Surat Tugas
    public function index() {
        // Cek otorisasi: hanya Admin dan Kepala UPT yang diizinkan mengelola Surat Tugas
        if (!in_array($this->session->userdata('level'), ['Admin', 'Kepala UPT'])) {
            redirect('/');
        }

        $data['title'] = 'Kelola Surat Tugas';
        
        // Menyusun query untuk mengambil semua data surat tugas beserta detail alat, pemohon, dan file cerapan
        $this->db->select('surat_tugas.*, alat_uttp.nama_alat, alat_uttp.merk, alat_uttp.tipe_model, pemohon.nama_pemilik, cerapan_tera.file_cerapan');
        $this->db->from('surat_tugas');
        $this->db->join('alat_uttp', 'alat_uttp.id_alat = surat_tugas.id_alat', 'left');
        $this->db->join('pemohon', 'pemohon.id_pemohon = alat_uttp.id_pemohon', 'left');
        $this->db->join('cerapan_tera', 'cerapan_tera.id_surat_tugas = surat_tugas.id_surat_tugas', 'left');
        $this->db->order_by('surat_tugas.id_surat_tugas', 'DESC');
        $surat_tugas_data = $this->db->get()->result_array();

        // Melakukan iterasi pada setiap surat tugas untuk mengambil nama-nama petugas yang ditugaskan (disimpan dalam format JSON)
        foreach ($surat_tugas_data as &$st) {
            $ids = json_decode($st['id_petugas'], true); // Decode JSON array berisi id_petugas
            if (!is_array($ids)) $ids = [$st['id_petugas']]; // Jika bukan array, jadikan array
            if (!empty($ids)) {
                // Mengambil nama petugas dari tabel petugas berdasarkan id_petugas yang ada di surat tugas
                $this->db->where_in('id_petugas', $ids);
                $st_petugas = $this->db->get('petugas')->result_array();
                $names = array_column($st_petugas, 'nama_petugas'); // Ambil kolom nama saja
                $st['nama_petugas'] = implode(', ', $names); // Gabungkan menjadi string yang dipisahkan koma
                $st['petugas_array'] = $names;
            } else {
                $st['nama_petugas'] = 'Belum Ada';
                $st['petugas_array'] = [];
            }
        }
        
        $data['surat_tugas'] = $surat_tugas_data;
        // Memuat tampilan (view) daftar surat tugas
        $this->load->view('dashboard/surat_tugas_index', $data);
    }

    // Fungsi untuk memproses pembuatan surat tugas baru (dipanggil dari form modal)
    public function proses() {
        // Hanya Admin yang boleh memproses/membuat surat tugas
        if (!in_array($this->session->userdata('level'), ['Admin'])) {
            redirect('/');
        }

        // Menerima data inputan dari form POST
        $id_alat        = $this->input->post('id_alat');
        $no_surat_tugas = $this->input->post('no_surat_tugas');
        $tgl_tugas      = $this->input->post('tgl_tugas');
        $id_petugas     = $this->input->post('id_petugas');

        // Validasi: pastikan setidaknya satu petugas telah dipilih
        if (empty($id_petugas) || !is_array($id_petugas)) {
            $this->session->set_flashdata('error', 'Minimal satu petugas harus dipilih.');
            redirect('pengajuan');
        }

        // Menyusun data untuk disimpan ke dalam tabel surat_tugas
        $data = [
            'id_alat'       => $id_alat,
            'id_petugas'    => json_encode($id_petugas), // Simpan id_petugas dalam bentuk array JSON
            'no_surat_tugas'=> $no_surat_tugas,
            'tgl_tugas'     => $tgl_tugas,
            'status'        => 'Menunggu' // Set status awal ke 'Menunggu' (menunggu petugas bekerja)
        ];

        // Memasukkan data surat tugas ke database
        $this->db->insert('surat_tugas', $data);

        // Tandai pengajuan alat ini sebagai sudah dibaca / diproses admin agar hilang dari daftar notifikasi dashboard baru
        $this->db->where('id_alat', $id_alat);
        $this->db->update('alat_uttp', ['is_read_admin' => 1]);

        $this->session->set_flashdata('success', 'Surat Tugas Nomor '.$no_surat_tugas.' telah berhasil diterbitkan!');
        redirect('pengajuan'); // Redirect ke halaman pengajuan setelah berhasil
    }

    // ========================================================
    // Helper: ambil data surat tugas lengkap beserta data petugas
    // Digunakan secara internal oleh fungsi lain untuk mengambil detail
    // ========================================================
    private function get_st_data($id) {
        $this->db->select('surat_tugas.*, alat_uttp.nama_alat, alat_uttp.merk, alat_uttp.tipe_model, alat_uttp.nomor_seri, pemohon.nama_pemilik, pemohon.alamat_usaha');
        $this->db->from('surat_tugas');
        $this->db->join('alat_uttp', 'alat_uttp.id_alat = surat_tugas.id_alat', 'left');
        $this->db->join('pemohon', 'pemohon.id_pemohon = alat_uttp.id_pemohon', 'left');
        $this->db->where('surat_tugas.id_surat_tugas', $id);
        $st = $this->db->get()->row_array();

        if ($st) {
            $ids = json_decode($st['id_petugas'], true);
            if (!is_array($ids)) $ids = [$st['id_petugas']];
            if (!empty($ids)) {
                $this->db->where_in('id_petugas', $ids);
                $st['data_petugas'] = $this->db->get('petugas')->result_array(); // Mengambil detail lengkap semua petugas yang ditugaskan
            } else {
                $st['data_petugas'] = [];
            }
        }
        return $st;
    }

    // ========================================================
    // CETAK: Menyiapkan data dan memuat tampilan untuk dicetak/didownload
    // ========================================================
    public function cetak($id) {
        if (!in_array($this->session->userdata('level'), ['Admin', 'Kepala UPT'])) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak untuk mencetak!');
            redirect('/');
        }

        $data['st'] = $this->get_st_data($id);
        if (!$data['st']) show_404();

        // Menyusun format nama file jika akan disave sebagai PDF
        $filename = 'Surat_Tugas_' . preg_replace('/[\/\.\s]+/', '_', $data['st']['no_surat_tugas']) . '.pdf';
        
        $data['title'] = 'Cetak Surat Tugas';
        
        // Memuat view khusus cetak (yang berisi desain format surat resmi)
        $this->load->view('dashboard/cetak_surat_tugas', $data);
    }

    // ========================================================
    // Helper: Manipulasi XML DOCX — replace data di template
    // Catatan: Fungsi ini sepertinya peninggalan dari sistem generasi docx
    // ========================================================
    private function _replace_docx_data($xml, $st) {
        // Fungsi helper: buat XML run teks bersih (w:r > w:t)
        $mkRun = function($text, $bold = false) {
            $b = $bold ? '<w:b/><w:bCs/>' : '';
            $t = htmlspecialchars($text, ENT_XML1, 'UTF-8');
            return '<w:r><w:rPr><w:rFonts w:ascii="Arial" w:hAnsi="Arial" w:cs="Arial"/><w:sz w:val="24"/><w:szCs w:val="24"/>'.$b.'</w:rPr><w:t xml:space="preserve">'.$t.'</w:t></w:r>';
        };

        // --- 1. Ganti Nomor Surat ---
        // Template punya "800.1.11.1 / 32 / UPTD-08/ 2026" → replace dengan no dari DB
        $xml = $this->_replaceTextInRuns($xml, '800.1.11.1 / 32 / UPTD-08/ 2026', $st['no_surat_tugas']);
        $xml = $this->_replaceTextInRuns($xml, '800.1.11.1 / 82 / UPTD-08/ 2026', $st['no_surat_tugas']);

        // --- 2. Ganti Tanggal Penetapan ---
        $tgl_indo = $this->_tglIndo($st['tgl_tugas']);
        $xml = $this->_replaceTextInRuns($xml, '19 Januari 2026', $tgl_indo);
        $xml = $this->_replaceTextInRuns($xml, '6 Februari 2026', $tgl_indo);

        // --- 3. Ganti Dasar Surat item ke-3 (nama pemohon & alat) ---
        // Replace baris "Surat dari PT. Bintang Jasa Transkal..."
        $dasar3 = 'Permohonan Tera/Kalibrasi dari ' . $st['nama_pemilik'] . ' untuk alat ' . $st['nama_alat'] .
                  (!empty($st['merk']) ? ' (Merk: ' . $st['merk'] . ')' : '') . '.';
        $xml = $this->_replaceTextInRuns($xml, 'Surat dari PT. Bintang Jasa Transkal nomor: BJT/02/XI/2025 tanggal 25 Nopember 2025 perihal Permohonan Tera Dispenser.', $dasar3);
        // Hapus item ke-4 (ganti jadi kosong)
        $xml = $this->_replaceTextInRuns($xml, 'Surat dari Perusahan CENDANA tanggal 8 Desember 2025 perihal Permohonan Kalibrasi Timbangan Jembatan Elektronik.', '');
        // SPT kedua
        $xml = $this->_replaceTextInRuns($xml, 'Surat dari PT. HOTMIX PRO nomor: 001/HMP/II/ perihal Permohonan Tera Ulang Timbangan Jembatan Elektronik.', $dasar3);
        $xml = $this->_replaceTextInRuns($xml, 'Surat dari PT. Sinar Anugerah Singkawang tanggal 6 Februari 2026 perihal Permohonan Tera Timbangan Jembatan Elektronik.', '');

        // --- 4. Ganti kalimat "Untuk" ---
        $untuk1 = 'Kegiatan Tera/Tera Ulang ' . $st['nama_alat'] .
                  (!empty($st['merk']) ? ' merek ' . $st['merk'] : '') .
                  ' di ' . ($st['alamat_usaha'] ?: 'lokasi pemohon') . '.';
        $xml = $this->_replaceTextInRuns($xml, 'Kegiatan Tera Mesin Dispener SPBU Jl. Tani dan Kalibrasi Timbangan Jembatan Elektronik di Kota Singkawang.', $untuk1);
        $xml = $this->_replaceTextInRuns($xml, 'Kegiatan Tera/ Tera Ulang Timbangan Jembatan  PT. HOTMIX PRO dan  PT. Sinar Anugerah Singkawang.', $untuk1);

        $untuk2 = 'Kegiatan dilaksanakan pada tanggal ' . $tgl_indo . '.';
        $xml = $this->_replaceTextInRuns($xml, 'Kegiatan dilaksanakan selama 2 (Dua) Hari dari tanggal 19 – 20 Januari 2026.', $untuk2);
        $xml = $this->_replaceTextInRuns($xml, 'Kegiatan dilaksanakan selama 2 (Dua) Hari dari tanggal 9 – 10 Februari 2026.', $untuk2);

        // --- 5. Ganti data petugas (nama, pangkat, NIP, jabatan) ---
        // Template SPT pertama memiliki 7 petugas dengan data tetap
        $template_petugas = [
            ['nama' => 'Yasmalizar, S.H.',      'pangkat' => 'Pembina Utama Muda (IV/c)', 'nip_lbl' => 'NIP',     'nip' => '196810161998031004', 'jabatan' => 'Kepala Dinas'],
            ['nama' => 'Eri Kurniawan, S.Sos',  'pangkat' => 'Penata Tk.I / (III/d)',     'nip_lbl' => 'NIP',     'nip' => '198308302009031003', 'jabatan' => 'Kepala UPT Metrologi Legal'],
            ['nama' => 'Wiryamor, ST',           'pangkat' => 'Penata Tk.I / (III/d)',     'nip_lbl' => 'NIP',     'nip' => '197404302008031001', 'jabatan' => 'Pengelola Metrologi dan Perlindungan Konsumen'],
            ['nama' => 'Rudi Indratno',          'pangkat' => 'Penata / (III/c)',          'nip_lbl' => 'NIP',     'nip' => '196907021991031005', 'jabatan' => 'Penera Penyelia'],
            ['nama' => 'Bayu Yudhatama, ST',     'pangkat' => 'Penata Muda / IX',          'nip_lbl' => 'NI PPPK', 'nip' => '199302082023211013', 'jabatan' => 'Penera Ahli Pertama'],
            ['nama' => 'Darmaji',                'pangkat' => 'V',                          'nip_lbl' => 'NI PPPK', 'nip' => '198210112025211019', 'jabatan' => 'Pengadministrasi Perkantoran'],
            ['nama' => 'Purnando',               'pangkat' => 'V',                          'nip_lbl' => 'NI PPPK', 'nip' => '199909022025211060', 'jabatan' => 'Operator Layanan Operasional'],
        ];

        // Replace setiap petugas template dengan data dari DB (sesuai urutan index)
        foreach ($template_petugas as $i => $tp) {
            if (isset($st['data_petugas'][$i])) {
                $dp = $st['data_petugas'][$i];
                $nip_lbl = (strlen(preg_replace('/\D/', '', $dp['nip'])) >= 18) ? 'NI PPPK' : 'NIP';
                $xml = $this->_replaceTextInRuns($xml, $tp['nama'],    $dp['nama_petugas']);
                $xml = $this->_replaceTextInRuns($xml, $tp['pangkat'], $dp['pangkat'] ?? '-');
                $xml = $this->_replaceTextInRuns($xml, $tp['nip'],     $dp['nip']);
                $xml = $this->_replaceTextInRuns($xml, $tp['jabatan'], $dp['jabatan']);
            } else {
                // Petugas template lebih banyak dari DB → kosongkan
                $xml = $this->_replaceTextInRuns($xml, $tp['nama'],    '');
                $xml = $this->_replaceTextInRuns($xml, $tp['pangkat'], '');
                $xml = $this->_replaceTextInRuns($xml, $tp['nip'],     '');
                $xml = $this->_replaceTextInRuns($xml, $tp['jabatan'], '');
            }
        }

        // SPT kedua punya petugas berbeda — hapus saja duplikatnya dengan replace kosong
        $extra = ['Edy Chahyono, ST', 'Leo Andrian, A.Md.Kep', '19830310 201101 1 006', '198607032025211150', 'Pengawas Perdagangan Ahli Muda', 'Pengelola Layanan Operasional', 'Penata Tk.I  / (III/d)'];
        foreach ($extra as $e) {
            $xml = $this->_replaceTextInRuns($xml, $e, '');
        }

        return $xml;
    }

    // ========================================================
    // Ganti teks di dalam Word XML — tangani teks yang terpecah
    // ========================================================
    private function _replaceTextInRuns($xml, $search, $replace) {
        if (empty($search)) return $xml;

        // Strategi: gabungkan semua w:t dalam satu w:p, cari teks, kembalikan
        // Ini cara yang lebih andal dari simple str_replace di XML
        
        // Coba simple str_replace dulu
        $escaped_search = htmlspecialchars($search, ENT_XML1, 'UTF-8');
        $escaped_replace = htmlspecialchars($replace, ENT_XML1, 'UTF-8');
        
        if (strpos($xml, $escaped_search) !== false) {
            $xml = str_replace($escaped_search, $escaped_replace, $xml);
        }
        
        return $xml;
    }

    // ========================================================
    // Format tanggal Indonesia
    // ========================================================
    private function _tglIndo($date_str) {
        $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $ts = strtotime($date_str);
        return date('d', $ts) . ' ' . $bulan[(int)date('m', $ts)] . ' ' . date('Y', $ts);
    }

    // ========================================================
    // LIHAT: Read-only web view untuk Petugas (melihat detail surat tugas)
    // ========================================================
    public function lihat($id) {
        // Cek otorisasi: hanya Admin, Kepala UPT, dan Petugas yang boleh
        $level = $this->session->userdata('level');
        if (!in_array($level, ['Admin', 'Kepala UPT', 'Petugas'])) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini.');
            redirect('/');
            return;
        }
        $data['st'] = $this->get_st_data($id);
        if (!$data['st']) show_404();
        $data['title'] = 'Lihat Surat Tugas';
        $this->load->view('dashboard/lihat_surat_tugas', $data);
    }

    // ========================================================
    // JSON endpoint for popup modal (Petugas)
    // Fungsi ini menyediakan data detail dalam format JSON untuk dimuat melalui AJAX
    // ========================================================
    public function detail_json($id) {
        // Petugas, Admin, dan Kepala UPT boleh akses endpoint JSON ini
        $level = $this->session->userdata('level');
        if (!in_array($level, ['Admin', 'Kepala UPT', 'Petugas'])) {
            // Membersihkan output buffer untuk memastikan response murni JSON
            while (ob_get_level()) ob_end_clean();
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        $st = $this->get_st_data($id);
        if (!$st) {
            while (ob_get_level()) ob_end_clean();
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Not found']);
            exit;
        }

        // jsonify safe (memastikan semua string dikodekan dalam UTF-8 agar JSON_encode tidak gagal)
        array_walk_recursive($st, function (&$v) {
            if (is_string($v) && !mb_check_encoding($v, 'UTF-8')) {
                $v = mb_convert_encoding($v, 'UTF-8', 'auto');
            }
        });

        // Membersihkan output buffer lalu mengirim header dan data JSON
        while (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($st, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
