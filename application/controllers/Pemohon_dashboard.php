<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemohon_dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan masuk terlebih dahulu.');
            redirect('login');
        }

        if ($this->session->userdata('level') !== 'Pemohon') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
            redirect('/');
        }
    }

    public function index()
    {
        $data['title'] = 'Dashboard Pemohon';

        $id_user = $this->session->userdata('id_user');
        $pemohon = $this->db->get_where('pemohon', ['id_user' => $id_user])->row_array();
        
        if ($pemohon) {
            $this->db->select('alat_uttp.*, surat_tugas.status AS status_uji, surat_tugas.id_surat_tugas, surat_tugas.tgl_tugas, pengujian.status_validasi, pengujian.hasil_uji');
            $this->db->from('alat_uttp');
            $this->db->join('surat_tugas', 'surat_tugas.id_alat = alat_uttp.id_alat', 'left');
            $this->db->join('pengujian', 'pengujian.id_alat = alat_uttp.id_alat', 'left');
            $this->db->where('alat_uttp.id_pemohon', $pemohon['id_pemohon']);
            $data['pengajuan'] = $this->db->get()->result_array();
            $data['pemohon'] = $pemohon;
        } else {
            $data['pengajuan'] = [];
            $data['pemohon'] = [];
        }

        $this->load->view('dashboard/pemohon_dashboard', $data);
    }
    public function pengajuan() {
        $data['title'] = 'Data Pengajuan - SIAP SKHP TERA';
        $id_user = $this->session->userdata('id_user');
        $pemohon = $this->db->get_where('pemohon', ['id_user' => $id_user])->row_array();
        
        if ($pemohon) {
            $this->db->select('alat_uttp.*, surat_tugas.status AS status_uji, surat_tugas.id_surat_tugas, surat_tugas.tgl_tugas, pengujian.status_validasi, pengujian.hasil_uji');
            $this->db->from('alat_uttp');
            $this->db->join('surat_tugas', 'surat_tugas.id_alat = alat_uttp.id_alat', 'left');
            $this->db->join('pengujian', 'pengujian.id_alat = alat_uttp.id_alat', 'left');
            $this->db->where('alat_uttp.id_pemohon', $pemohon['id_pemohon']);
            $data['pengajuan'] = $this->db->get()->result_array();
        } else {
            $data['pengajuan'] = [];
        }
        $this->load->view('dashboard/pemohon_pengajuan', $data);
    }

    public function skhp() {
        $data['title'] = 'Cetak SKHP - SIAP SKHP TERA';
        $id_user = $this->session->userdata('id_user');
        $pemohon = $this->db->get_where('pemohon', ['id_user' => $id_user])->row_array();
        
        if ($pemohon) {
            $this->db->select('alat_uttp.*, surat_tugas.status AS status_uji, surat_tugas.id_surat_tugas, surat_tugas.tgl_tugas, pengujian.status_validasi, pengujian.hasil_uji');
            $this->db->from('alat_uttp');
            $this->db->join('surat_tugas', 'surat_tugas.id_alat = alat_uttp.id_alat', 'left');
            $this->db->join('pengujian', 'pengujian.id_alat = alat_uttp.id_alat', 'left');
            $this->db->where('alat_uttp.id_pemohon', $pemohon['id_pemohon']);
            // Hanya tampilkan alat yang sudah divalidasi dan siap cetak SKHP
            $this->db->where('pengujian.status_validasi', 'Tervalidasi');
            $data['pengajuan'] = $this->db->get()->result_array();
        } else {
            $data['pengajuan'] = [];
        }
        $this->load->view('dashboard/pemohon_skhp', $data);
    }

    public function profil() {
        $data['title'] = 'Profil Detail - SIAP SKHP TERA';
        $id_user = $this->session->userdata('id_user');
        
        $data['user'] = $this->db->get_where('user', ['id_user' => $id_user])->row_array();
        $data['pemohon'] = $this->db->get_where('pemohon', ['id_user' => $id_user])->row_array();
        
        $this->load->view('dashboard/pemohon_profil', $data);
    }
    
    public function update_profil() {
        $id_user = $this->session->userdata('id_user');
        $nama_pemilik = $this->input->post('nama_pemilik');
        $alamat_usaha = $this->input->post('alamat_usaha');
        $jenis_usaha = $this->input->post('jenis_usaha');
        $kontak_pemohon = $this->input->post('kontak_pemohon');

        $data_pemohon = [
            'nama_pemilik' => $nama_pemilik,
            'alamat_usaha' => $alamat_usaha,
            'jenis_usaha' => $jenis_usaha,
            'kontak_pemohon' => $kontak_pemohon
        ];
        
        $cek = $this->db->get_where('pemohon', ['id_user' => $id_user])->row_array();
        if ($cek) {
            $this->db->where('id_user', $id_user);
            $this->db->update('pemohon', $data_pemohon);
        } else {
            $data_pemohon['id_user'] = $id_user;
            $this->db->insert('pemohon', $data_pemohon);
        }

        // Proses Upload Foto Profil
        if (!empty($_FILES['foto_profil']['name']) && $_FILES['foto_profil']['error'] !== UPLOAD_ERR_NO_FILE) {
            // Validasi status error upload bawaan PHP
            if ($_FILES['foto_profil']['error'] !== UPLOAD_ERR_OK) {
                $this->session->set_flashdata('error', 'Terjadi kesalahan saat mengunggah foto profil. Silakan coba file lain.');
                redirect('pemohon-dashboard/profil');
                return;
            }

            // Validasi ukuran maksimal 2MB
            if ($_FILES['foto_profil']['size'] > 2097152) {
                $this->session->set_flashdata('error', 'Ukuran foto profil terlalu besar! Batas maksimal yang diperbolehkan adalah 2MB.');
                redirect('pemohon-dashboard/profil');
                return;
            }

            $tmp_file = $_FILES['foto_profil']['tmp_name'];
            $image_info = @getimagesize($tmp_file);
            $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            if (!$image_info || !in_array($image_info['mime'], $allowed_mimes)) {
                $this->session->set_flashdata('error', 'Format foto tidak didukung! Pastikan Anda mengunggah berkas foto berformat JPG, PNG, atau WEBP.');
                redirect('pemohon-dashboard/profil');
                return;
            }

            $ext = pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION);
            if (empty($ext)) $ext = 'jpg';
            $file_name = 'profil_' . $id_user . '_' . time() . '.' . strtolower($ext);
            $upload_dir = FCPATH . 'uploads/profil/';
            $saved_to_disk = false;

            // Jika folder lokal dapat ditulis (localhost/VPS), simpan ke file fisik
            if (!is_dir($upload_dir)) {
                @mkdir($upload_dir, 0777, true);
            }

            if (is_really_writable($upload_dir)) {
                if (@move_uploaded_file($tmp_file, $upload_dir . $file_name)) {
                    $saved_to_disk = true;
                    // Hapus file lama jika ada dan bukan data URI
                    $user = $this->db->get_where('user', ['id_user' => $id_user])->row_array();
                    if (!empty($user['foto_profil']) && strpos($user['foto_profil'], 'data:image') !== 0 && file_exists($upload_dir . $user['foto_profil'])) {
                        @unlink($upload_dir . $user['foto_profil']);
                    }
                    $this->db->where('id_user', $id_user);
                    $this->db->update('user', ['foto_profil' => $file_name]);
                }
            }

            // Jika sistem file bersifat Read-Only (Vercel Serverless), optimasi dan simpan ke Neon DB sebagai Data URI
            if (!$saved_to_disk) {
                $mime = $image_info['mime'];
                $image_data = false;

                // Kompresi dan optimasi foto profil menggunakan ekstensi GD jika tersedia
                if (extension_loaded('gd')) {
                    $src_img = null;
                    if ($mime === 'image/jpeg') $src_img = @imagecreatefromjpeg($tmp_file);
                    elseif ($mime === 'image/png') $src_img = @imagecreatefrompng($tmp_file);
                    elseif ($mime === 'image/webp') $src_img = @imagecreatefromwebp($tmp_file);
                    elseif ($mime === 'image/gif') $src_img = @imagecreatefromgif($tmp_file);

                    if ($src_img) {
                        $orig_w = imagesx($src_img);
                        $orig_h = imagesy($src_img);
                        $max_dim = 300; // Ukuran avatar optimal
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

                // Fallback raw base64 jika GD tidak tersedia / gagal
                if (!$image_data && file_exists($tmp_file)) {
                    $image_data = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($tmp_file));
                }

                if ($image_data) {
                    $this->db->where('id_user', $id_user);
                    $this->db->update('user', ['foto_profil' => $image_data]);
                }
            }
        }

        $this->session->set_flashdata('success', 'Profil berhasil diperbarui!');
        redirect('pemohon-dashboard/profil');
    }

    public function tambah_pengajuan() {
        $id_user = $this->session->userdata('id_user');
        $pemohon = $this->db->get_where('pemohon', ['id_user' => $id_user])->row_array();
        
        if (!$pemohon) {
            $this->session->set_flashdata('error', 'Silakan lengkapi profil Anda terlebih dahulu sebelum mengajukan tera.');
            redirect('pemohon-dashboard/profil');
            return;
        }

        if (!$this->db->field_exists('created_at', 'alat_uttp')) {
            $this->load->dbforge();
            $this->dbforge->add_column('alat_uttp', [
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => FALSE,
                    'default' => 'CURRENT_TIMESTAMP'
                ]
            ]);
        }

        $data = [
            'id_pemohon' => $pemohon['id_pemohon'],
            'nama_alat' => $this->input->post('nama_alat'),
            'merk' => $this->input->post('merk'),
            'kapasitas' => $this->input->post('kapasitas'),
            'jumlah' => $this->input->post('jumlah'),
            'tipe_model' => $this->input->post('tipe_model'),
            'nomor_seri' => $this->input->post('nomor_seri'),
            'buatan' => $this->input->post('buatan'),
            'created_at' => (new DateTime('now', new DateTimeZone('Asia/Jakarta')))->format('Y-m-d H:i:s'),
            // detail_spesifik json can be null for now, or you can build it if needed
        ];

        $this->db->insert('alat_uttp', $data);
        $this->session->set_flashdata('success', 'Pengajuan alat baru berhasil dikirim!');
        redirect('pemohon-dashboard/pengajuan');
    }
}
