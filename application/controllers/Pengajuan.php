<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuan extends CI_Controller {
    public function __construct() {
        parent::__construct();
        // Cek autentikasi dan otorisasi: hanya Admin dan Kepala UPT yang bisa mengakses
        if (!$this->session->userdata('logged_in') || !in_array($this->session->userdata('level'), ['Admin', 'Kepala UPT'])) {
            redirect('/');
        }
    }

    // Fungsi untuk menampilkan daftar pengajuan alat
    public function index() {
        $data['title'] = 'Daftar Pengajuan';

        // Jika user adalah Admin, tandai semua notifikasi/pengajuan baru sebagai sudah dibaca
        if ($this->session->userdata('level') === 'Admin') {
            $this->db->where('is_read_admin', 0);
            $this->db->update('alat_uttp', ['is_read_admin' => 1]);
        }
        
        // Menyusun query untuk mengambil data pengajuan beserta info pemohon, surat tugas, dan pengujian
        $this->db->select('alat_uttp.*, pemohon.nama_pemilik, pemohon.jenis_usaha, surat_tugas.id_surat_tugas, surat_tugas.status, pengujian.status_validasi, pengujian.hasil_uji');
        $this->db->from('alat_uttp');
        $this->db->join('pemohon', 'pemohon.id_pemohon = alat_uttp.id_pemohon', 'left'); // Relasi ke pemohon
        $this->db->join('surat_tugas', 'surat_tugas.id_alat = alat_uttp.id_alat', 'left'); // Relasi ke surat tugas
        $this->db->join('pengujian', 'pengujian.id_alat = alat_uttp.id_alat', 'left'); // Relasi ke data pengujian
        $this->db->order_by('alat_uttp.created_at', 'DESC'); // Urutkan dari pengajuan terbaru
        $data['pengajuan'] = $this->db->get()->result_array();
        
        // Mengambil daftar petugas untuk keperluan modal/form penugasan di tampilan (view)
        $data['petugas'] = $this->db->get('petugas')->result_array();
        
        // Memuat tampilan utama untuk daftar pengajuan
        $this->load->view('dashboard/pengajuan_index', $data);
    }

    // Fungsi untuk melihat detail dari satu pengajuan spesifik berdasarkan ID alat
    public function detail($id_alat)
    {
        $data['title'] = 'Detail Pengajuan Alat';
        
        // Mengambil data alat spesifik beserta profil pemohon pemiliknya dan status validasi
        $this->db->select('alat_uttp.*, pemohon.nama_pemilik, pemohon.jenis_usaha, pemohon.kontak_pemohon, pemohon.alamat_usaha, pengujian.status_validasi, pengujian.hasil_uji');
        $this->db->from('alat_uttp');
        $this->db->join('pemohon', 'pemohon.id_pemohon = alat_uttp.id_pemohon', 'left');
        $this->db->join('pengujian', 'pengujian.id_alat = alat_uttp.id_alat', 'left');
        $this->db->where('alat_uttp.id_alat', $id_alat);
        $alat = $this->db->get()->row_array();
        
        // Jika alat tidak ditemukan, kembalikan user ke halaman daftar pengajuan
        if (!$alat) {
            $this->session->set_flashdata('error', 'Data pengajuan / alat tidak ditemukan!');
            redirect('pengajuan');
        }
        
        $data['alat'] = $alat;
        // Kolom json detail_spesifik diurai menjadi array. Gunakan fallback array kosong apabila nilainya NULL/kosong
        $data['detail_alat'] = !empty($alat['detail_spesifik']) ? json_decode($alat['detail_spesifik'], true) : [];
        
        // Mengambil data surat tugas yang berkaitan dengan alat ini
        $st = $this->db->get_where('surat_tugas', ['id_alat' => $id_alat])->row_array();
        $data['surat_tugas'] = $st;

        // Jika ada surat tugas, ambil riwayat / data cerapan tera yang telah diupload petugas
        if ($st) {
            $this->db->select('cerapan_tera.*, petugas.nama_petugas as nama_petugas_upload');
            $this->db->from('cerapan_tera');
            $this->db->join('petugas', 'petugas.id_petugas = cerapan_tera.id_petugas', 'left'); // Mengambil nama petugas yang mengupload
            $this->db->where('cerapan_tera.id_surat_tugas', $st['id_surat_tugas']);
            $this->db->order_by('cerapan_tera.tgl_upload', 'DESC'); // Mengurutkan dari upload terbaru
            $data['cerapan'] = $this->db->get()->row_array();
        } else {
            $data['cerapan'] = null; // Tidak ada data cerapan jika belum ada surat tugas
        }
        
        // Mengambil daftar petugas untuk dropdown jika ingin membuat/mengedit surat tugas dari halaman detail
        $data['petugas'] = $this->db->get('petugas')->result_array();
        
        // Memuat tampilan detail pengajuan
        $this->load->view('dashboard/pengajuan_detail', $data);
    }
}
