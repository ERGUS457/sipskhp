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

        if (!empty($_FILES['foto_profil']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/profil/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['max_size'] = 2048;
            $config['file_name'] = 'profil_' . $id_user . '_' . time();

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto_profil')) {
                $uploadData = $this->upload->data();
                $foto_profil = $uploadData['file_name'];
                
                $user = $this->db->get_where('user', ['id_user' => $id_user])->row_array();
                if (!empty($user['foto_profil']) && file_exists(FCPATH . 'uploads/profil/' . $user['foto_profil'])) {
                    unlink(FCPATH . 'uploads/profil/' . $user['foto_profil']);
                }

                $this->db->where('id_user', $id_user);
                $this->db->update('user', ['foto_profil' => $foto_profil]);
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                redirect('pemohon-dashboard/profil');
                return;
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
