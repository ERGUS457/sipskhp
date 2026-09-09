<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelola_pemohon extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Cek login: jika user belum login, akan diarahkan ke halaman login
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan masuk terlebih dahulu.');
            redirect('login');
        }

        // Proteksi Otorisasi Admin: hanya Admin dan Kepala UPT yang bisa melihat daftar pemohon
        $level = $this->session->userdata('level');
        if (!in_array($level, ['Admin', 'Kepala UPT'])) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
            redirect('/');
        }
    }

    // Fungsi utama untuk menampilkan daftar seluruh profil pemohon
    public function index()
    {
        $data['title'] = 'Kelola Profil Pemohon';
        
        // Menarik data pemohon dan menggabungkannya dengan tabel user untuk mengambil alamat email dan username
        $this->db->select('pemohon.*, user.email, user.username');
        $this->db->from('pemohon');
        $this->db->join('user', 'user.id_user = pemohon.id_user', 'left');
        $data['pemohon'] = $this->db->get()->result_array();

        // Memuat tampilan tabel daftar pemohon
        $this->load->view('dashboard/pemohon_index', $data);
    }

    // Fungsi untuk menampilkan detail spesifik dari satu profil pemohon beserta riwayat pengajuan alatnya
    public function detail($id_pemohon)
    {
        $data['title'] = 'Detail Data Pemohon';
        
        // Tarik data spesifik pemohon dari database berdasarkan id pemohon yang dipilih
        $this->db->select('pemohon.*, user.email, user.username');
        $this->db->from('pemohon');
        $this->db->join('user', 'user.id_user = pemohon.id_user', 'left');
        $this->db->where('pemohon.id_pemohon', $id_pemohon);
        $pemohon = $this->db->get()->row_array();
        
        // Jika data pemohon tidak ditemukan, kembalikan ke halaman daftar
        if (!$pemohon) {
            $this->session->set_flashdata('error', 'Profil pemohon tidak ditemukan di sistem.');
            redirect('kelola-pemohon');
        }
        $data['pemohon'] = $pemohon;
        
        // Tarik daftar riwayat alat yg didaftarkan oleh pemohon ini (semua alat yang pernah diajukan)
        $this->db->where('id_pemohon', $id_pemohon);
        $data['riwayat_alat'] = $this->db->get('alat_uttp')->result_array();

        // Memuat tampilan halaman detail pemohon
        $this->load->view('dashboard/pemohon_detail', $data);
    }

    // Fungsi untuk mengubah ID Pemohon oleh Admin
    public function update_id($old_id)
    {
        $new_id = $this->input->post('new_id');

        // Cek apakah ID baru sudah ada (kecuali untuk ID saat ini)
        if ($new_id != $old_id) {
            $exists = $this->db->get_where('pemohon', ['id_pemohon' => $new_id])->num_rows();
            if ($exists > 0) {
                $this->session->set_flashdata('error', 'Gagal: ID ' . $new_id . ' sudah digunakan oleh pemohon lain.');
                redirect('kelola-pemohon/detail/' . $old_id);
                return;
            }

            // Update ID (ON UPDATE CASCADE akan menangani tabel terkait seperti alat_uttp otomatis)
            $this->db->where('id_pemohon', $old_id);
            if ($this->db->update('pemohon', ['id_pemohon' => $new_id])) {
                $this->session->set_flashdata('success', 'ID Pemohon berhasil diperbarui.');
                redirect('kelola-pemohon/detail/' . $new_id);
            } else {
                $this->session->set_flashdata('error', 'Terjadi kesalahan saat memperbarui ID.');
                redirect('kelola-pemohon/detail/' . $old_id);
            }
        } else {
            // ID tidak berubah
            redirect('kelola-pemohon/detail/' . $old_id);
        }
    }
}
