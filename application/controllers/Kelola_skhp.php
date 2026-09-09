<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelola_skhp extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cek autentikasi: hanya pengguna yang sudah login yang bisa mengakses
        if (!$this->session->userdata('logged_in')) {
            redirect('/');
        }
    }

    // ============================================================
    // INDEX: Menampilkan daftar SKHP yang siap atau sudah diterbitkan
    // ============================================================
    public function index() {
        // Cek otorisasi: hanya Admin dan Kepala UPT yang berhak mengelola SKHP
        if (!in_array($this->session->userdata('level'), ['Admin', 'Kepala UPT'])) {
            redirect('/');
        }
        $data['title'] = 'Penerbitan SKHP';

        // Mengambil daftar pengujian yang statusnya sudah 'Tervalidasi' (artinya SKHP siap dicetak/diterbitkan)
        $this->db->select('
            pengujian.*,
            alat_uttp.id_alat, alat_uttp.nama_alat, alat_uttp.merk, alat_uttp.tipe_model,
            alat_uttp.kapasitas, alat_uttp.nomor_seri, alat_uttp.buatan, alat_uttp.jumlah,
            pemohon.nama_pemilik, pemohon.jenis_usaha, pemohon.alamat_usaha, pemohon.kontak_pemohon,
            surat_tugas.no_surat_tugas, surat_tugas.tgl_tugas
        ');
        $this->db->from('pengujian');
        // Melakukan relasi (join) untuk mengambil data pelengkap dari tabel lain
        $this->db->join('alat_uttp', 'alat_uttp.id_alat = pengujian.id_alat', 'left');
        $this->db->join('pemohon', 'pemohon.id_pemohon = alat_uttp.id_pemohon', 'left');
        $this->db->join('surat_tugas', 'surat_tugas.id_alat = alat_uttp.id_alat', 'left');
        $this->db->where('pengujian.status_validasi', 'Tervalidasi'); // Filter hanya yang sudah tervalidasi
        $this->db->order_by('pengujian.tgl_pengujian', 'DESC'); // Mengurutkan dari tanggal pengujian terbaru
        $data['list'] = $this->db->get()->result_array();

        // Memuat tampilan daftar SKHP
        $this->load->view('dashboard/kelola_skhp_index', $data);
    }

    // ============================================================
    // CETAK: Menyiapkan data dan render halaman SKHP untuk dicetak
    // Format akan disesuaikan dengan dokumen resmi UPT Metrologi Legal
    // ============================================================
    public function cetak($id_alat) {
        // Cek otorisasi: Admin, Kepala UPT, dan Pemohon diizinkan mencetak/mendownload SKHP
        $level = $this->session->userdata('level');
        if (!in_array($level, ['Admin', 'Kepala UPT', 'Pemohon'])) {
            redirect('/');
        }

        // IDOR Protection: Jika role Pemohon, pastikan alat ini miliknya
        if ($level === 'Pemohon') {
            $id_user = $this->session->userdata('id_user');
            $pemohon = $this->db->get_where('pemohon', ['id_user' => $id_user])->row_array();
            if (!$pemohon) { redirect('pemohon-dashboard'); return; }

            $alat = $this->db->get_where('alat_uttp', ['id_alat' => $id_alat, 'id_pemohon' => $pemohon['id_pemohon']])->row_array();
            if (!$alat) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke SKHP ini.');
                redirect('pemohon-dashboard/skhp');
                return;
            }
        }

        // =============================================
        // 1. Ambil data utama: pengujian, alat, pemohon, dan surat tugas
        // =============================================
        $this->db->select('
            pengujian.*,
            alat_uttp.id_alat, alat_uttp.nama_alat, alat_uttp.merk, alat_uttp.tipe_model,
            alat_uttp.kapasitas, alat_uttp.nomor_seri, alat_uttp.buatan, alat_uttp.jumlah,
            alat_uttp.detail_spesifik,
            pemohon.nama_pemilik, pemohon.jenis_usaha, pemohon.alamat_usaha, pemohon.kontak_pemohon,
            surat_tugas.no_surat_tugas, surat_tugas.tgl_tugas, surat_tugas.id_petugas AS st_id_petugas
        ');
        $this->db->from('pengujian');
        $this->db->join('alat_uttp', 'alat_uttp.id_alat = pengujian.id_alat', 'left');
        $this->db->join('pemohon', 'pemohon.id_pemohon = alat_uttp.id_pemohon', 'left');
        $this->db->join('surat_tugas', 'surat_tugas.id_alat = alat_uttp.id_alat', 'left');
        $this->db->where('pengujian.id_alat', $id_alat);
        $this->db->where('pengujian.status_validasi', 'Tervalidasi');
        $data['d'] = $this->db->get()->row_array();

        // Jika data pengujian untuk alat tersebut tidak ditemukan atau belum divalidasi
        if (!$data['d']) {
            $this->session->set_flashdata('error', 'Data SKHP tidak ditemukan atau belum divalidasi.');
            redirect('kelola-skhp');
        }

        // =============================================
        // 2. Ambil atau buat record SKHP di tabel 'skhp'
        // =============================================
        $data['skhp'] = $this->db->get_where('skhp', [
            'id_pengujian' => $data['d']['id_pengujian']
        ])->row_array();

        // Jika SKHP belum pernah digenerate (record kosong), buat record SKHP otomatis sekarang
        if (empty($data['skhp'])) {
            // Ambil petugas pertama dari surat tugas sebagai default id_petugas
            $default_petugas_id = 1; // fallback jika surat tugas belum ada
            $st_tmp = $this->db->select('id_petugas')->get_where('surat_tugas', ['id_alat' => $id_alat])->row_array();
            if ($st_tmp && !empty($st_tmp['id_petugas'])) {
                $ids_tmp = json_decode($st_tmp['id_petugas'], true);
                if (is_array($ids_tmp) && !empty($ids_tmp)) {
                    $default_petugas_id = $ids_tmp[0];
                } else {
                    $default_petugas_id = $st_tmp['id_petugas'];
                }
            }

            $skhp_data = [
                'id_pengujian'     => $data['d']['id_pengujian'],
                'id_petugas'       => $default_petugas_id,
                'no_surat'         => sprintf('B / 500.2.3.15 / %04d / UPTD-08 / %s', $data['d']['id_pengujian'], date('Y', strtotime($data['d']['tgl_pengujian']))),
                'nama_kepala_dinas'=> 'YASMALIZAR, S.H.',
                'masa_berlaku'     => date('Y-m-d', strtotime($data['d']['tgl_pengujian'] . ' +1 year')),
                'tgl_terbit'       => $data['d']['tgl_pengujian'],
                'no_daftar'        => sprintf('%04d / %s / %s', $data['d']['id_pengujian'], date('m', strtotime($data['d']['tgl_pengujian'])), date('Y', strtotime($data['d']['tgl_pengujian']))),
                'nip_pejabat'      => '196810161998031004',
            ];
            $this->db->insert('skhp', $skhp_data);
            $data['skhp'] = $skhp_data;
            $data['skhp']['id_skhp'] = $this->db->insert_id();

            // =============================================
            // KIRIM NOTIFIKASI
            // =============================================
            $email = '';
            $user = $this->db->query('SELECT "user".email FROM pemohon JOIN "user" ON "user".id_user = pemohon.id_user WHERE pemohon.id_pemohon = (SELECT id_pemohon FROM alat_uttp WHERE id_alat = ?)', [$id_alat])->row_array();
            if ($user) {
                $email = $user['email'];
            }
            $this->_kirim_notifikasi($email, $data['d']['kontak_pemohon'], $data['d']['nama_pemilik'], $data['d']['nama_alat'], $skhp_data['no_surat']);
        }

        // =============================================
        // 3. Tentukan petugas yang akan tampil di SKHP
        // =============================================
        $data['petugas_list']       = [];
        $data['semua_petugas']      = [];
        $data['petugas_id_dipilih'] = '';

        // Ambil semua petugas yang ada di surat tugas (untuk dropdown admin)
        $st_id_petugas = $data['d']['st_id_petugas'] ?? '';
        if (!empty($st_id_petugas)) {
            $ids = json_decode($st_id_petugas, true);
            if (!is_array($ids)) $ids = [$st_id_petugas];
            if (!empty($ids)) {
                $this->db->where_in('id_petugas', $ids);
                $data['semua_petugas'] = $this->db->get('petugas')->result_array();
            }
        }

        // Cek apakah admin sedang memilih petugas baru via GET param
        $petugas_id_dipilih = $this->input->get('petugas_id');

        if (!empty($petugas_id_dipilih) && in_array($level, ['Admin', 'Kepala UPT'])) {
            // Admin memilih petugas → simpan ke tabel skhp agar berlaku permanen
            if (!empty($data['skhp']['id_skhp'])) {
                $this->db->where('id_skhp', $data['skhp']['id_skhp']);
                $this->db->update('skhp', ['id_petugas' => $petugas_id_dipilih]);
                $data['skhp']['id_petugas'] = $petugas_id_dipilih;
            }
            $data['petugas_id_dipilih'] = $petugas_id_dipilih;
        } else {
            // Gunakan id_petugas yang sudah tersimpan di tabel skhp
            $petugas_id_dipilih = $data['skhp']['id_petugas'] ?? '';
            $data['petugas_id_dipilih'] = $petugas_id_dipilih;
        }

        // Tampilkan petugas sesuai id yang tersimpan di skhp
        if (!empty($petugas_id_dipilih) && !empty($data['semua_petugas'])) {
            $filtered = array_values(array_filter($data['semua_petugas'], function($p) use ($petugas_id_dipilih) {
                return $p['id_petugas'] == $petugas_id_dipilih;
            }));
            $data['petugas_list'] = !empty($filtered) ? $filtered : [$data['semua_petugas'][0]];
        } elseif (!empty($data['semua_petugas'])) {
            // Fallback: tampilkan petugas pertama jika belum pernah dipilih
            $data['petugas_list'] = [$data['semua_petugas'][0]];
        }

        // =============================================
        // 4. Ambil dan gabungkan detail spesifik alat (Timbangan atau SPBU)
        // =============================================
        // Cek apakah alat ini merupakan timbangan (punya data di tabel alat_timbangan)
        $timbangan = $this->db->get_where('alat_timbangan', ['id_alat' => $id_alat])->row_array();
        if ($timbangan) {
            $detail = !empty($data['d']['detail_spesifik']) ? json_decode($data['d']['detail_spesifik'], true) : [];
            if (empty($detail)) $detail = [];
            // Menyalin properti kapasitas dan ketelitian timbangan ke dalam JSON detail
            if (!empty($timbangan['kapasitas_nominal'])) $detail['kapasitas_nominal'] = $timbangan['kapasitas_nominal'];
            if (!empty($timbangan['kelas_ketelitian'])) $detail['kelas_ketelitian'] = $timbangan['kelas_ketelitian'];
            $data['d']['detail_spesifik'] = json_encode($detail);
        }

        // Cek apakah alat ini merupakan SPBU (punya data di tabel alat_spbu)
        $spbu = $this->db->get_where('alat_spbu', ['id_alat' => $id_alat])->row_array();
        if ($spbu) {
            $detail = !empty($data['d']['detail_spesifik']) ? json_decode($data['d']['detail_spesifik'], true) : [];
            if (empty($detail)) $detail = [];
            // Menyalin spesifikasi SPBU (nozzle, kecepatan aliran, dsb) ke dalam JSON detail
            if (!empty($spbu['nomor_pulau'])) $detail['nomor_pulau'] = $spbu['nomor_pulau'];
            if (!empty($spbu['kecepatan_alir'])) $detail['kecepatan_alir'] = $spbu['kecepatan_alir'];
            if (!empty($spbu['jumlah_nozzle'])) $detail['jumlah_nozzle'] = $spbu['jumlah_nozzle'];
            if (!empty($spbu['daftar_produk_bbm'])) $detail['daftar_produk_bbm'] = $spbu['daftar_produk_bbm'];
            $data['d']['detail_spesifik'] = json_encode($detail);
        }

        // Memuat tampilan (view) untuk halaman cetak SKHP resmi
        $this->load->view('dashboard/cetak_skhp', $data);
    }

    // ============================================================
    // EXPORT PDF: Generate SKHP dalam format PDF
    // ============================================================
    public function export_pdf($id_alat) {
        $level = $this->session->userdata('level');
        if (!in_array($level, ['Admin', 'Kepala UPT', 'Pemohon'])) {
            redirect('/');
        }

        // IDOR Protection: Jika role Pemohon, pastikan alat ini miliknya
        if ($level === 'Pemohon') {
            $id_user = $this->session->userdata('id_user');
            $pemohon = $this->db->get_where('pemohon', ['id_user' => $id_user])->row_array();
            if (!$pemohon) { redirect('pemohon-dashboard'); return; }

            $alat = $this->db->get_where('alat_uttp', ['id_alat' => $id_alat, 'id_pemohon' => $pemohon['id_pemohon']])->row_array();
            if (!$alat) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke SKHP ini.');
                redirect('pemohon-dashboard/skhp');
                return;
            }
        }

        // Ambil data (sama seperti metode cetak)
        $this->db->select('
            pengujian.*,
            alat_uttp.id_alat, alat_uttp.nama_alat, alat_uttp.merk, alat_uttp.tipe_model,
            alat_uttp.kapasitas, alat_uttp.nomor_seri, alat_uttp.buatan, alat_uttp.jumlah,
            alat_uttp.detail_spesifik,
            pemohon.nama_pemilik, pemohon.jenis_usaha, pemohon.alamat_usaha, pemohon.kontak_pemohon,
            surat_tugas.no_surat_tugas, surat_tugas.tgl_tugas, surat_tugas.id_petugas AS st_id_petugas
        ');
        $this->db->from('pengujian');
        $this->db->join('alat_uttp', 'alat_uttp.id_alat = pengujian.id_alat', 'left');
        $this->db->join('pemohon', 'pemohon.id_pemohon = alat_uttp.id_pemohon', 'left');
        $this->db->join('surat_tugas', 'surat_tugas.id_alat = alat_uttp.id_alat', 'left');
        $this->db->where('pengujian.id_alat', $id_alat);
        $this->db->where('pengujian.status_validasi', 'Tervalidasi');
        $data['d'] = $this->db->get()->row_array();

        if (!$data['d']) {
            $this->session->set_flashdata('error', 'Data SKHP tidak ditemukan.');
            redirect('kelola-skhp');
        }

        $data['skhp'] = $this->db->get_where('skhp', ['id_pengujian' => $data['d']['id_pengujian']])->row_array();

        $data['petugas_list'] = [];
        $data['semua_petugas'] = [];

        $st_id_petugas = $data['d']['st_id_petugas'] ?? '';
        if (!empty($st_id_petugas)) {
            $ids = json_decode($st_id_petugas, true);
            if (!is_array($ids)) $ids = [$st_id_petugas];
            if (!empty($ids)) {
                $this->db->where_in('id_petugas', $ids);
                $data['semua_petugas'] = $this->db->get('petugas')->result_array();
            }
        }

        // Gunakan id_petugas yang tersimpan di skhp (ditetapkan oleh admin)
        $petugas_id_tersimpan = $data['skhp']['id_petugas'] ?? '';
        if (!empty($petugas_id_tersimpan) && !empty($data['semua_petugas'])) {
            $filtered = array_values(array_filter($data['semua_petugas'], function($p) use ($petugas_id_tersimpan) {
                return $p['id_petugas'] == $petugas_id_tersimpan;
            }));
            $data['petugas_list'] = !empty($filtered) ? $filtered : [$data['semua_petugas'][0]];
        } elseif (!empty($data['semua_petugas'])) {
            $data['petugas_list'] = [$data['semua_petugas'][0]];
        }

        $timbangan = $this->db->get_where('alat_timbangan', ['id_alat' => $id_alat])->row_array();
        if ($timbangan) {
            $detail = !empty($data['d']['detail_spesifik']) ? json_decode($data['d']['detail_spesifik'], true) : [];
            if (empty($detail)) $detail = [];
            if (!empty($timbangan['kapasitas_nominal'])) $detail['kapasitas_nominal'] = $timbangan['kapasitas_nominal'];
            if (!empty($timbangan['kelas_ketelitian'])) $detail['kelas_ketelitian'] = $timbangan['kelas_ketelitian'];
            $data['d']['detail_spesifik'] = json_encode($detail);
        }

        $spbu = $this->db->get_where('alat_spbu', ['id_alat' => $id_alat])->row_array();
        if ($spbu) {
            $detail = !empty($data['d']['detail_spesifik']) ? json_decode($data['d']['detail_spesifik'], true) : [];
            if (empty($detail)) $detail = [];
            if (!empty($spbu['nomor_pulau'])) $detail['nomor_pulau'] = $spbu['nomor_pulau'];
            if (!empty($spbu['kecepatan_alir'])) $detail['kecepatan_alir'] = $spbu['kecepatan_alir'];
            if (!empty($spbu['jumlah_nozzle'])) $detail['jumlah_nozzle'] = $spbu['jumlah_nozzle'];
            if (!empty($spbu['daftar_produk_bbm'])) $detail['daftar_produk_bbm'] = $spbu['daftar_produk_bbm'];
            $data['d']['detail_spesifik'] = json_encode($detail);
        }

        // Set flag is_pdf agar view tahu ini untuk render PDF (bukan view HTML biasa)
        $data['is_pdf'] = true;
        
        // Render HTML
        $html = $this->load->view('dashboard/cetak_skhp', $data, TRUE);

        // Load Dompdf
        require_once FCPATH . 'vendor/autoload.php';
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Output PDF
        $dompdf->stream("SKHP_{$data['d']['nama_alat']}_{$data['d']['nomor_seri']}.pdf", array("Attachment" => true));
    }

    // ============================================================
    // AJAX: Ambil daftar petugas per alat (untuk dropdown modal pilih petugas)
    // ============================================================
    public function get_petugas_json($id_alat) {
        if (!$this->session->userdata('logged_in')) {
            echo json_encode([]); exit;
        }
        $st = $this->db->select('id_petugas')->get_where('surat_tugas', ['id_alat' => $id_alat])->row_array();
        if (!$st || empty($st['id_petugas'])) {
            echo json_encode([]); exit;
        }
        $ids = json_decode($st['id_petugas'], true);
        if (!is_array($ids)) $ids = [$st['id_petugas']];

        $this->db->where_in('id_petugas', $ids);
        $petugas = $this->db->select('id_petugas, nama_petugas, nip, jabatan')->get('petugas')->result_array();

        while (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($petugas, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ============================================================
    // NOTIFIKASI EMAIL & WA
    // ============================================================
    private function _kirim_notifikasi($email_tujuan, $no_wa, $nama_pemohon, $nama_alat, $no_skhp) {
        // 1. Kirim Email
        if (!empty($email_tujuan)) {
            $this->load->library('email');
            
            $this->email->from('no-reply@upt-metrologi.go.id', 'UPT Metrologi Legal');
            $this->email->to($email_tujuan);
            
            $this->email->subject('Sertifikat SKHP Telah Diterbitkan - ' . $nama_alat);
            
            $pesan_email = "
                <h3>Halo, {$nama_pemohon}</h3>
                <p>Kami informasikan bahwa Sertifikat SKHP (Surat Keterangan Hasil Pengujian) untuk alat Anda telah selesai diproses dan diterbitkan.</p>
                <ul>
                    <li><strong>Nama Alat:</strong> {$nama_alat}</li>
                    <li><strong>Nomor Surat SKHP:</strong> {$no_skhp}</li>
                </ul>
                <p>Anda dapat mengunduh atau mencetak sertifikat tersebut langsung dari dalam sistem SIPSKHP TERA.</p>
                <br>
                <p>Terima kasih,<br>UPT Metrologi Legal</p>
            ";
            $this->email->message($pesan_email);
            
            // Kirim email (mengabaikan error untuk mencegah terhentinya proses)
            @$this->email->send();
        }

        // 2. Kirim WhatsApp (Placeholder/Template)
        if (!empty($no_wa)) {
            // Contoh menggunakan Fonnte API:
            /*
            $token = 'YOUR_API_TOKEN';
            $pesan_wa = "Halo *{$nama_pemohon}*,\n\nSKHP untuk alat *{$nama_alat}* Anda (No: {$no_skhp}) telah diterbitkan. Silakan cek sistem SIPSKHP TERA untuk mengunduhnya.\n\nTerima kasih,\nUPT Metrologi Legal";
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.fonnte.com/send',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array(
                'target' => $no_wa,
                'message' => $pesan_wa, 
              ),
              CURLOPT_HTTPHEADER => array(
                "Authorization: $token"
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            */
        }
    }
}
