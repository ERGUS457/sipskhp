<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Cek login
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan masuk terlebih dahulu.');
            redirect('login');
        }

        // Proteksi Otorisasi Admin
        $level = $this->session->userdata('level');
        if (!in_array($level, ['Admin', 'Kepala UPT'])) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
            redirect('/');
        }
    }

    public function index()
    {
        $data['title'] = 'Kelola Pengguna Sistem';
        
        // Menarik semua data user dari tabel 'user'
        $data['users'] = $this->db->get('user')->result_array();

        $this->load->view('dashboard/users_index', $data);
    }

    // Mengambil data user spesifik untuk ditampilkan di dalam Modal Edit (AJAX)
    public function edit($id)
    {
        $user = $this->db->get_where('user', ['id_user' => $id])->row_array();
        if ($user) {
            // Kita keluarkan atribut sensitif
            unset($user['password']);
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($user));
        } else {
            return $this->output
                ->set_status_header(404)
                ->set_output(json_encode(['status' => 'error', 'message' => 'User tidak ditemukan']));
        }
    }

    // Menyimpan perubahan data dari Modal Edit
    public function update($id)
    {
        // Hanya Admin yang boleh mengubah level user (mencegah privilege escalation oleh Kepala UPT)
        $current_level = $this->session->userdata('level');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric', [
            'required'      => 'Username wajib diisi.',
            'alpha_numeric' => 'Username hanya boleh huruf dan angka.'
        ]);
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email', [
            'required'    => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.'
        ]);
        $this->form_validation->set_rules('level', 'Level', 'required|in_list[Admin,Kepala UPT,Petugas,Pemohon]', [
            'required' => 'Level wajib dipilih.',
            'in_list'  => 'Level tidak valid.'
        ]);

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors(' ', ' | '));
            redirect('users');
            return;
        }

        // Pastikan user yang diupdate ada di database
        $target_user = $this->db->get_where('user', ['id_user' => $id])->row_array();
        if (!$target_user) {
            $this->session->set_flashdata('error', 'Pengguna tidak ditemukan.');
            redirect('users');
            return;
        }

        $update_data = [
            'username' => $this->input->post('username'),
            'email'    => $this->input->post('email'),
        ];

        // Hanya Admin yang boleh mengubah level — mencegah privilege escalation
        if ($current_level === 'Admin') {
            $update_data['level'] = $this->input->post('level');
        }

        // Jika password diisi, enkripsi ulang
        $password = $this->input->post('password');
        if (!empty($password)) {
            $update_data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->db->where('id_user', $id);
        $this->db->update('user', $update_data);

        $this->session->set_flashdata('success', 'Data profil pengguna terkait telah berhasil diperbarui!');
        redirect('users');
    }

    // Menghapus akun secara permanen (melalui AJAX SweetAlert)
    public function delete($id)
    {
        // Proteksi agar Admin yang login tidak menghapus dirinya sendiri
        if ($id == $this->session->userdata('id_user')) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Anda tidak bisa menghapus akun Anda sendiri!']));
        }

        $this->db->where('id_user', $id);
        $result = $this->db->delete('user');

        if ($result) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'message' => 'Akun tersebut telah dihapus secara permanen.']));
        } else {
            return $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'Terjadi masalah pada sinkronisasi server.']));
        }
    }

    // Menampilkan Form Tambah Pengguna Baru (dialihkan ke Modal)
    public function tambah()
    {
        $this->session->set_flashdata('open_tambah_modal', TRUE);
        redirect('users');
    }

    // Menyimpan data Pengguna Baru dari Form
    public function simpan()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.username]|alpha_numeric', [
            'required' => 'Username wajib diisi.',
            'is_unique' => 'Username ini sudah terdaftar.',
            'alpha_numeric' => 'Username hanya boleh terdiri dari huruf dan angka.'
        ]);
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[user.email]', [
            'required' => 'Email wajib diisi.',
            'valid_email' => 'Format email tidak valid.',
            'is_unique' => 'Email ini sudah terdaftar.'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]', [
            'required' => 'Password wajib diisi.',
            'min_length' => 'Password minimal harus 8 karakter.'
        ]);
        $this->form_validation->set_rules('level', 'Level Pengguna', 'required|in_list[Admin,Kepala UPT,Petugas,Pemohon]', [
            'required' => 'Pilih level pengguna terlebih dahulu.',
            'in_list'  => 'Level pengguna tidak valid.'
        ]);

        // Validasi tambahan jika level Petugas
        $level_post = $this->input->post('level');
        if ($level_post === 'Petugas') {
            $this->form_validation->set_rules('nip', 'NIP', 'required|is_unique[petugas.nip]', [
                'required'  => 'NIP wajib diisi untuk akun Petugas.',
                'is_unique' => 'NIP ini sudah terdaftar di sistem.'
            ]);
            $this->form_validation->set_rules('nama_petugas', 'Nama Petugas', 'required', [
                'required' => 'Nama lengkap petugas wajib diisi.'
            ]);
        }

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('validation_errors', validation_errors());
            $this->session->set_flashdata('old_username', $this->input->post('username'));
            $this->session->set_flashdata('old_email', $this->input->post('email'));
            $this->session->set_flashdata('old_level', $this->input->post('level'));
            $this->session->set_flashdata('old_nip', $this->input->post('nip'));
            $this->session->set_flashdata('old_nama_petugas', $this->input->post('nama_petugas'));
            $this->session->set_flashdata('old_pangkat', $this->input->post('pangkat'));
            $this->session->set_flashdata('old_jabatan', $this->input->post('jabatan'));
            $this->session->set_flashdata('open_tambah_modal', TRUE);
            redirect('users');
        } else {
            $level = $this->input->post('level');

            $this->db->trans_start();

            $user_data = [
                'username' => $this->input->post('username'),
                'email'    => $this->input->post('email'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'level'    => $level
            ];
            $this->db->insert('user', $user_data);
            $id_user = $this->db->insert_id();

            // Jika level Petugas, otomatis buat juga data di tabel petugas
            if ($level === 'Petugas') {
                $nip = $this->input->post('nip');
                $petugas_data = [
                    'id_petugas'   => $nip,
                    'id_user'      => $id_user,
                    'nama_petugas' => $this->input->post('nama_petugas'),
                    'nip'          => $nip,
                    'pangkat'      => $this->input->post('pangkat'),
                    'jabatan'      => $this->input->post('jabatan'),
                ];
                $this->db->insert('petugas', $petugas_data);
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('error', 'Gagal menyimpan data pengguna. Silakan coba lagi.');
            } else {
                $this->session->set_flashdata('success', 'Pengguna baru berhasil ditambahkan!');
            }
            redirect('users');
        }
    }
}

