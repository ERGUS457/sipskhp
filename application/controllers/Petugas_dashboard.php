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

        if (empty($_FILES['foto']['name']) || $_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
            $this->session->set_flashdata('error', 'Anda belum memilih foto untuk diunggah.');
            redirect('petugas-dashboard');
            return;
        }

        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat mengunggah foto profil.');
            redirect('petugas-dashboard');
            return;
        }

        if ($_FILES['foto']['size'] > 2097152) {
            $this->session->set_flashdata('error', 'Ukuran foto terlalu besar! Batas maksimal adalah 2MB.');
            redirect('petugas-dashboard');
            return;
        }

        $tmp_file = $_FILES['foto']['tmp_name'];
        $image_info = @getimagesize($tmp_file);
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!$image_info || !in_array($image_info['mime'], $allowed_mimes)) {
            $this->session->set_flashdata('error', 'Format foto tidak didukung! Pastikan berkas berformat JPG, PNG, atau WEBP.');
            redirect('petugas-dashboard');
            return;
        }

        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        if (empty($ext)) $ext = 'jpg';
        $file_name = 'petugas_' . $petugas['id_petugas'] . '_' . time() . '.' . strtolower($ext);
        $upload_dir = FCPATH . 'asset/foto_petugas/';
        $saved_to_disk = false;

        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }

        if (is_really_writable($upload_dir)) {
            if (@move_uploaded_file($tmp_file, $upload_dir . $file_name)) {
                $saved_to_disk = true;
                if (!empty($petugas['foto']) && strpos($petugas['foto'], 'data:image') !== 0 && file_exists($upload_dir . $petugas['foto'])) {
                    @unlink($upload_dir . $petugas['foto']);
                }
                $this->db->where('id_petugas', $petugas['id_petugas']);
                $this->db->update('petugas', ['foto' => $file_name]);
            }
        }

        if (!$saved_to_disk) {
            $mime = $image_info['mime'];
            $image_data = false;

            if (extension_loaded('gd')) {
                $src_img = null;
                if ($mime === 'image/jpeg') $src_img = @imagecreatefromjpeg($tmp_file);
                elseif ($mime === 'image/png') $src_img = @imagecreatefrompng($tmp_file);
                elseif ($mime === 'image/webp') $src_img = @imagecreatefromwebp($tmp_file);
                elseif ($mime === 'image/gif') $src_img = @imagecreatefromgif($tmp_file);

                if ($src_img) {
                    $orig_w = imagesx($src_img);
                    $orig_h = imagesy($src_img);
                    $max_dim = 300;
                    if ($orig_w > $max_dim || $orig_h > $max_dim) {
                        $ratio = min($max_dim / $orig_w, $max_dim / $orig_h);
                        $new_w = (int)($orig_w * $ratio);
                        $new_h = (int)($orig_h * $ratio);
                    } else {
                        $new_w = $orig_w;
                        $new_h = $orig_h;
                    }

                    $dst_img = imagecreatetruecolor($new_w, $new_h);
                    if ($mime === 'image/png') {
                        imagealphablending($dst_img, false);
                        imagesavealpha($dst_img, true);
                        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $orig_w, $orig_h);
                        ob_start();
                        imagepng($dst_img, null, 7);
                        $raw = ob_get_clean();
                    } else {
                        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $orig_w, $orig_h);
                        ob_start();
                        imagejpeg($dst_img, null, 85);
                        $raw = ob_get_clean();
                        $mime = 'image/jpeg';
                    }
                    imagedestroy($src_img);
                    imagedestroy($dst_img);

                    if (!empty($raw)) {
                        $image_data = 'data:' . $mime . ';base64,' . base64_encode($raw);
                    }
                }
            }

            if (!$image_data && file_exists($tmp_file)) {
                $image_data = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($tmp_file));
            }

            if ($image_data) {
                $this->db->where('id_petugas', $petugas['id_petugas']);
                $this->db->update('petugas', ['foto' => $image_data]);
            }
        }

        $this->session->set_flashdata('success', 'Foto profil berhasil diperbarui!');
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

        if (empty($_FILES['file_cerapan']['name']) || $_FILES['file_cerapan']['error'] === UPLOAD_ERR_NO_FILE) {
            $this->session->set_flashdata('error', 'Anda belum memilih dokumen apa pun untuk diunggah.');
            redirect('petugas-dashboard');
            return;
        }

        if ($_FILES['file_cerapan']['error'] !== UPLOAD_ERR_OK) {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat mengunggah dokumen cerapan.');
            redirect('petugas-dashboard');
            return;
        }

        if ($_FILES['file_cerapan']['size'] > 5242880) {
            $this->session->set_flashdata('error', 'Ukuran dokumen terlalu besar! Batas maksimal yang diperbolehkan adalah 5MB.');
            redirect('petugas-dashboard');
            return;
        }

        $tmp_file = $_FILES['file_cerapan']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['file_cerapan']['name'], PATHINFO_EXTENSION));
        $allowed_exts = ['pdf', 'xls', 'xlsx'];

        if (!in_array($ext, $allowed_exts)) {
            $this->session->set_flashdata('error', 'Ups! Format dokumen tidak didukung. Mohon pastikan Anda hanya mengunggah file berekstensi Excel (.xls/.xlsx) atau PDF.');
            redirect('petugas-dashboard');
            return;
        }

        $file_name = 'cerapan_' . $petugas['id_petugas'] . '_' . $id_st . '_' . time() . '.' . $ext;
        $upload_dir = FCPATH . 'asset/cerapan_tera/';

        if (!is_dir($upload_dir)) {
            @mkdir($upload_dir, 0777, true);
        }

        // Simpan ke disk jika direktori dapat ditulis
        if (is_really_writable($upload_dir)) {
            @move_uploaded_file($tmp_file, $upload_dir . $file_name);
        }

        // Selalu simpan backup konten file di database Neon (kolom file_data) untuk keandalan serverless
        $file_raw = file_exists($tmp_file) ? file_get_contents($tmp_file) : (file_exists($upload_dir . $file_name) ? file_get_contents($upload_dir . $file_name) : '');
        $file_data = !empty($file_raw) ? base64_encode($file_raw) : null;

        $this->db->insert('cerapan_tera', [
            'id_surat_tugas' => $id_st,
            'id_petugas'     => $petugas['id_petugas'],
            'file_cerapan'   => $file_name,
            'catatan'        => $catatan,
            'file_data'      => $file_data,
        ]);
        
        // Update status surat tugas menjadi Selesai
        $this->db->where('id_surat_tugas', $id_st);
        $this->db->update('surat_tugas', ['status' => 'Selesai']);
        
        $this->session->set_flashdata('success', 'Cerapan tera berhasil diunggah dan status uji selesai!');
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
