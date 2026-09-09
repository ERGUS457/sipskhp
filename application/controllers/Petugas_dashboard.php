<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petugas_dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('level') !== 'Petugas') {
            redirect('login');
        }
    }

    // -------------------------------------------------------
    private function _get_petugas() {
        $id_user = $this->session->userdata('id_user');
        return $this->db->get_where('petugas', ['id_user' => $id_user])->row_array();
    }

    // -------------------------------------------------------
    public function index() {
        $petugas = $this->_get_petugas();
        if (!$petugas) show_error("Data petugas tidak ditemukan. Silakan hubungi Admin.");

        $id = $petugas['id_petugas'];
        $data['petugas'] = $petugas;

        $this->db->select('st.*, a.nama_alat, a.merk, p.nama_pemilik, p.alamat_usaha');
        $this->db->from('surat_tugas st');
        $this->db->join('alat_uttp a', 'a.id_alat = st.id_alat', 'left');
        $this->db->join('pemohon p', 'p.id_pemohon = a.id_pemohon', 'left');
        // Gunakan escape() untuk mencegah SQL Injection pada interpolasi ke raw SQL
        $escaped_id = $this->db->escape('"' . $id . '"');
        $this->db->where("JSON_CONTAINS(st.id_petugas, $escaped_id)");
        $this->db->order_by('st.tgl_tugas', 'DESC');
        $data['surat_tugas'] = $this->db->get()->result_array();

        $this->load->view('dashboard/petugas_dashboard_index', $data);
    }

    // -------------------------------------------------------
    // Tampilan Resmi Surat Tugas (dokumen formal)
    // -------------------------------------------------------
    public function lihat($id) {
        $petugas = $this->_get_petugas();
        if (!$petugas) show_error("Data petugas tidak ditemukan.");

        $this->db->select('st.*, a.nama_alat, a.merk, a.tipe_model, a.nomor_seri, p.nama_pemilik, p.alamat_usaha');
        $this->db->from('surat_tugas st');
        $this->db->join('alat_uttp a', 'a.id_alat = st.id_alat', 'left');
        $this->db->join('pemohon p', 'p.id_pemohon = a.id_pemohon', 'left');
        $this->db->where('st.id_surat_tugas', $id);
        $st = $this->db->get()->row_array();
        if (!$st) show_404();

        // IDOR Protection: pastikan surat tugas ini memang ditugaskan ke petugas yang login
        $ids_st = json_decode($st['id_petugas'], true);
        if (!is_array($ids_st)) $ids_st = [$st['id_petugas']];
        if (!in_array($petugas['id_petugas'], $ids_st)) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke surat tugas ini.');
            redirect('petugas-dashboard');
            return;
        }

        $ids = json_decode($st['id_petugas'], true);
        if (!is_array($ids)) $ids = [$st['id_petugas']];
        if (!empty($ids)) {
            $this->db->where_in('id_petugas', $ids);
            $st['data_petugas'] = $this->db->get('petugas')->result_array();
        } else {
            $st['data_petugas'] = [];
        }

        $data['st']      = $st;
        $data['petugas'] = $petugas;
        $this->load->view('dashboard/petugas_lihat_st', $data);
    }

    // -------------------------------------------------------
    // AJAX: Detail Surat Tugas (JSON) – melewati CI3 output buffer
    // -------------------------------------------------------
    public function detail_st($id) {
        // Ambil data
        $this->db->select('st.*, a.nama_alat, a.merk, a.tipe_model, a.nomor_seri, p.nama_pemilik, p.alamat_usaha');
        $this->db->from('surat_tugas st');
        $this->db->join('alat_uttp a', 'a.id_alat = st.id_alat', 'left');
        $this->db->join('pemohon p', 'p.id_pemohon = a.id_pemohon', 'left');
        $this->db->where('st.id_surat_tugas', $id);
        $st = $this->db->get()->row_array();

        if (!$st) {
            $this->_json(['error' => 'Not found'], 404);
        }

        $ids = json_decode($st['id_petugas'], true);
        if (!is_array($ids)) $ids = [$st['id_petugas']];
        if (!empty($ids)) {
            $this->db->where_in('id_petugas', $ids);
            $st['data_petugas'] = $this->db->get('petugas')->result_array();
        } else {
            $st['data_petugas'] = [];
        }

        $this->_json($st);
    }

    // -------------------------------------------------------
    // Upload Foto Profil
    // -------------------------------------------------------
    public function upload_foto() {
        $petugas = $this->_get_petugas();
        if (!$petugas) redirect('petugas-dashboard');

        $config = [
            'upload_path'   => FCPATH . 'asset/foto_petugas/',
            'allowed_types' => 'jpg|jpeg|png|webp',
            'max_size'      => 2048,
            'file_name'     => 'petugas_' . $petugas['id_petugas'],
            'overwrite'     => TRUE,
        ];
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('foto')) {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
        } else {
            $info = $this->upload->data();
            $this->db->where('id_petugas', $petugas['id_petugas']);
            $this->db->update('petugas', ['foto' => $info['file_name']]);
            $this->session->set_flashdata('success', 'Foto profil berhasil diperbarui!');
        }
        redirect('petugas-dashboard');
    }

    // -------------------------------------------------------
    // Upload Cerapan Tera
    // -------------------------------------------------------
    public function upload_cerapan() {
        $petugas = $this->_get_petugas();
        if (!$petugas) redirect('petugas-dashboard');

        $id_st   = $this->input->post('id_surat_tugas');
        $catatan = $this->input->post('catatan');

        // Cek apakah status sudah Selesai
        $st = $this->db->get_where('surat_tugas', ['id_surat_tugas' => $id_st])->row_array();
        if ($st && $st['status'] === 'Selesai') {
            $this->session->set_flashdata('error', 'Gagal upload: Status pengujian surat tugas ini sudah Selesai. Anda tidak dapat mengunggah ulang cerapan.');
            redirect('petugas-dashboard');
            return;
        }

        // IDOR Protection: pastikan surat tugas ini memang ditugaskan ke petugas yang login
        if ($st) {
            $ids_st = json_decode($st['id_petugas'], true);
            if (!is_array($ids_st)) $ids_st = [$st['id_petugas']];
            if (!in_array($petugas['id_petugas'], $ids_st)) {
                $this->session->set_flashdata('error', 'Anda tidak memiliki hak untuk mengunggah cerapan pada surat tugas ini.');
                redirect('petugas-dashboard');
                return;
            }
        }

        $config = [
            'upload_path'   => FCPATH . 'asset/cerapan_tera/',
            'allowed_types' => 'pdf|xls|xlsx',
            'max_size'      => 5120, // 5MB
            'file_name'     => 'cerapan_' . $petugas['id_petugas'] . '_' . $id_st . '_' . time(),
        ];
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file_cerapan')) {
            $error_msg = $this->upload->display_errors('', '');
            if (strpos($error_msg, 'filetype you are attempting to upload is not allowed') !== false) {
                $error_msg = 'Ups! Format dokumen tidak didukung. Mohon pastikan Anda hanya mengunggah file berekstensi Excel (.xls/.xlsx) atau PDF.';
            } elseif (strpos($error_msg, 'larger than the permitted size') !== false) {
                $error_msg = 'Ukuran dokumen terlalu besar! Batas maksimal yang diperbolehkan adalah 5MB.';
            } elseif (strpos($error_msg, 'You did not select a file to upload') !== false) {
                $error_msg = 'Anda belum memilih dokumen apa pun untuk diunggah.';
            }
            $this->session->set_flashdata('error', '<strong>Gagal mengunggah cerapan:</strong> ' . $error_msg);
        } else {
            $info = $this->upload->data();
            $this->db->insert('cerapan_tera', [
                'id_surat_tugas' => $id_st,
                'id_petugas'     => $petugas['id_petugas'],
                'file_cerapan'   => $info['file_name'],
                'catatan'        => $catatan,
            ]);
            
            // Update status surat tugas menjadi Selesai
            $this->db->where('id_surat_tugas', $id_st);
            $this->db->update('surat_tugas', ['status' => 'Selesai']);
            
            $this->session->set_flashdata('success', 'Cerapan tera berhasil diunggah dan status uji selesai!');
        }
        redirect('petugas-dashboard');
    }

    // -------------------------------------------------------
    // Helper: output JSON bersih (bypass CI3 buffer)
    // -------------------------------------------------------
    private function _json($data, $code = 200) {
        while (ob_get_level()) ob_end_clean();
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
