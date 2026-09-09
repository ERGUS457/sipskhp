<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petugas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || !in_array($this->session->userdata('level'), ['Admin'])) {
            redirect('/');
        }
    }

    public function index() {
        $data['title'] = 'Manajemen Petugas';
        $this->db->select('petugas.*, user.username, user.email');
        $this->db->from('petugas');
        $this->db->join('user', 'user.id_user = petugas.id_user', 'left');
        $data['petugas'] = $this->db->get()->result_array();
        $this->load->view('dashboard/petugas_index', $data);
    }

    public function simpan() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.username]|alpha_numeric', [
            'required'      => 'Username wajib diisi.',
            'is_unique'     => 'Username sudah digunakan.',
            'alpha_numeric' => 'Username hanya boleh huruf dan angka.'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]', [
            'required'   => 'Password wajib diisi.',
            'min_length' => 'Password minimal 8 karakter.'
        ]);
        $this->form_validation->set_rules('nip', 'NIP', 'required|is_unique[petugas.nip]', [
            'required'  => 'NIP wajib diisi.',
            'is_unique' => 'NIP sudah terdaftar.'
        ]);
        $this->form_validation->set_rules('nama_petugas', 'Nama Petugas', 'required', [
            'required' => 'Nama petugas wajib diisi.'
        ]);

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors(' ', ' | '));
            redirect('petugas');
            return;
        }

        $this->db->trans_start();

        // 1. Buat Akun User
        $user_data = [
            'username' => $this->input->post('username'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'email'    => $this->input->post('username') . '@petugas.local', // Email dummy agar unique
            'level'    => 'Petugas'
        ];
        $this->db->insert('user', $user_data);
        $id_user = $this->db->insert_id();

        // 2. Simpan Data Petugas
        $data = [
            'id_petugas'   => $this->input->post('nip'),
            'id_user'      => $id_user,
            'nama_petugas' => $this->input->post('nama_petugas'),
            'nip'          => $this->input->post('nip'),
            'pangkat'      => $this->input->post('pangkat'),
            'jabatan'      => $this->input->post('jabatan'),
        ];
        $this->db->insert('petugas', $data);
        
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal menambahkan petugas dan akun.');
        } else {
            $this->session->set_flashdata('success', 'Data petugas & akun login berhasil ditambahkan.');
        }
        redirect('petugas');
    }

    public function update($id = null) {
        if ($id === null) {
            $id = $this->input->post('id');
        }
        
        $petugas = $this->db->get_where('petugas', ['id_petugas' => $id])->row_array();
        if (!$petugas) show_404();

        $this->db->trans_start();

        $username = $this->input->post('username');
        $password = $this->input->post('password');
        
        $data = [
            'id_petugas'   => $this->input->post('nip'),
            'nama_petugas' => $this->input->post('nama_petugas'),
            'nip'          => $this->input->post('nip'),
            'pangkat'      => $this->input->post('pangkat'),
            'jabatan'      => $this->input->post('jabatan'),
        ];

        // Kelola Akun User
        if ($petugas['id_user']) {
            $user_update = [
                'username' => $username,
                'email'    => $username . '@petugas.local'
            ];
            if (!empty($password)) {
                $user_update['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            $this->db->where('id_user', $petugas['id_user']);
            $this->db->update('user', $user_update);
        } else {
            // Jika sebelumnya belum ada akun — password wajib diisi, tidak ada default
            if (empty($password)) {
                $this->session->set_flashdata('error', 'Password wajib diisi untuk petugas yang belum memiliki akun.');
                redirect('petugas');
                return;
            }
            $user_data = [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'email'    => $username . '@petugas.local',
                'level'    => 'Petugas'
            ];
            $this->db->insert('user', $user_data);
            $data['id_user'] = $this->db->insert_id();
        }

        $this->db->where('id_petugas', $id);
        $this->db->update('petugas', $data);
        
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal memperbarui data petugas.');
        } else {
            $this->session->set_flashdata('success', 'Data petugas berhasil diperbarui.');
        }
        redirect('petugas');
    }

    public function hapus($id) {
        $petugas = $this->db->get_where('petugas', ['id_petugas' => $id])->row_array();
        
        $this->db->trans_start();
        if ($petugas && $petugas['id_user']) {
            $this->db->where('id_user', $petugas['id_user']);
            $this->db->delete('user');
        }
        $this->db->where('id_petugas', $id);
        $this->db->delete('petugas');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal menghapus data petugas.');
        } else {
            $this->session->set_flashdata('success', 'Data petugas beserta akun aplikasinya berhasil dihapus.');
        }
        redirect('petugas');
    }

    public function get_json($id) {
        $row = $this->db->get_where('petugas', ['id_petugas' => $id])->row_array();
        echo json_encode($row);
    }
}
