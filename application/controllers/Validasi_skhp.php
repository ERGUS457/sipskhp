<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Validasi_skhp extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Cek autentikasi: pastikan pengguna sudah login sebelum mengakses
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
        // Cek otorisasi khusus: Halaman ini benar-benar dikhususkan untuk Kepala UPT
        if ($this->session->userdata('level') !== 'Kepala UPT') {
            $this->session->set_flashdata('error', 'Akses ditolak. Halaman ini hanya untuk Kepala UPT.');
            redirect('dashboard');
        }
    }

    // ============================================================
    // INDEX: Daftar surat tugas yang sudah selesai (menunggu validasi)
    // Menampilkan halaman dimana Kepala UPT bisa melihat hasil kerja petugas
    // ============================================================
    public function index() {
        $data['title'] = 'Otorisasi Hasil Uji';

        // Query untuk mengambil data surat tugas yang sudah ditandai 'Selesai' oleh petugas
        // beserta dengan data alat, pemohon, dan file cerapan yang telah diupload
        $this->db->select('
            surat_tugas.id_surat_tugas,
            surat_tugas.no_surat_tugas,
            surat_tugas.tgl_tugas,
            surat_tugas.status,
            alat_uttp.id_alat,
            alat_uttp.nama_alat,
            alat_uttp.merk,
            alat_uttp.tipe_model,
            alat_uttp.kapasitas,
            alat_uttp.jumlah,
            pemohon.nama_pemilik,
            pemohon.jenis_usaha,
            cerapan_tera.file_cerapan,
            cerapan_tera.catatan,
            cerapan_tera.tgl_upload,
            petugas.nama_petugas AS nama_petugas_upload,
            pengujian.id_pengujian,
            pengujian.status_validasi
        ');
        $this->db->from('surat_tugas');
        $this->db->join('alat_uttp', 'alat_uttp.id_alat = surat_tugas.id_alat', 'left');
        $this->db->join('pemohon', 'pemohon.id_pemohon = alat_uttp.id_pemohon', 'left');
        $this->db->join('cerapan_tera', 'cerapan_tera.id_surat_tugas = surat_tugas.id_surat_tugas', 'left');
        $this->db->join('petugas', 'petugas.id_petugas = cerapan_tera.id_petugas', 'left');
        $this->db->join('pengujian', 'pengujian.id_alat = alat_uttp.id_alat', 'left');
        $this->db->where('surat_tugas.status', 'Selesai'); // Filter hanya yang sudah selesai
        $this->db->order_by('surat_tugas.id_surat_tugas', 'DESC');
        $data['list'] = $this->db->get()->result_array();

        // Menghitung statistik untuk ditampilkan di bagian atas halaman (badge/summary)
        $data['total_selesai']   = count($data['list']);
        $data['total_validated'] = count(array_filter($data['list'], function($r){ return $r['status_validasi'] === 'Tervalidasi'; }));
        $data['total_pending']   = $data['total_selesai'] - $data['total_validated'];

        // Memuat tampilan utama halaman validasi SKHP
        $this->load->view('dashboard/validasi_skhp_index', $data);
    }

    // ============================================================
    // VALIDASI: Kepala UPT menyetujui hasil uji & mencatat data pengujian
    // ============================================================
    public function validasi() {
        // Mencegah akses langsung via URL (harus melalui method POST dari form)
        if ($this->input->method() !== 'post') redirect('validasi-skhp');

        // Menerima inputan form validasi
        $id_alat       = $this->input->post('id_alat');
        $id_surat_tugas = $this->input->post('id_surat_tugas');
        $hasil_uji     = $this->input->post('hasil_uji');
        $tgl_pengujian = $this->input->post('tgl_pengujian');
        $metode_uji    = $this->input->post('metode_uji');
        $standar_uji   = $this->input->post('standar_uji');

        if (empty($id_alat) || empty($id_surat_tugas)) {
            $this->session->set_flashdata('error', 'Data tidak valid.');
            redirect('validasi-skhp');
        }

        // Cek apakah sudah ada data pengujian sebelumnya untuk alat ini
        $existing = $this->db->get_where('pengujian', ['id_alat' => $id_alat])->row_array();

        // Menyusun data pengujian (hasil akhir) yang akan disimpan ke database
        $pengujian_data = [
            'id_alat'          => $id_alat,
            'tgl_pengujian'    => $tgl_pengujian,
            'metode_uji'       => $metode_uji ?: 'Tera / Tera Ulang', // Default value jika kosong
            'standar_uji'      => $standar_uji ?: 'SNI / Permendag No. 24 Tahun 2024',
            'suhu_dasar'       => $this->input->post('suhu_dasar') ?: '20 °C',
            'hasil_uji'        => $hasil_uji, // 'Sah' atau 'Batal'
            'status_validasi'  => 'Tervalidasi', // Menandai bahwa hasil sudah divalidasi Kepala UPT
        ];

        // Jika sudah ada record, cukup update; jika belum, maka insert baru
        if ($existing) {
            $this->db->where('id_alat', $id_alat);
            $this->db->update('pengujian', $pengujian_data);
        } else {
            $this->db->insert('pengujian', $pengujian_data);
        }

        $this->session->set_flashdata('success', 'Hasil uji berhasil divalidasi oleh Kepala UPT. Pengajuan siap diterbitkan SKHP.');
        redirect('validasi-skhp');
    }

    // ============================================================
    // TOLAK: Kepala UPT mengembalikan hasil uji ke Petugas
    // Dipanggil saat dokumen cerapan dinilai kurang lengkap/ada kesalahan
    // ============================================================
    public function tolak() {
        if ($this->input->method() !== 'post') redirect('validasi-skhp');

        $id_surat_tugas = $this->input->post('id_surat_tugas');
        $catatan_tolak  = $this->input->post('catatan_tolak');

        if (empty($id_surat_tugas)) {
            $this->session->set_flashdata('error', 'Data tidak valid.');
            redirect('validasi-skhp');
        }

        // Reset status surat tugas dari 'Selesai' menjadi 'Menunggu' agar petugas bisa memproses ulang
        $this->db->where('id_surat_tugas', $id_surat_tugas);
        $this->db->update('surat_tugas', ['status' => 'Menunggu']);

        // Menghapus data cerapan lama yang salah
        $cerapan = $this->db->get_where('cerapan_tera', ['id_surat_tugas' => $id_surat_tugas])->row_array();
        if ($cerapan) {
            // Hapus file fisik dari server jika ada
            $file_path = FCPATH . 'asset/cerapan_tera/' . $cerapan['file_cerapan'];
            if (file_exists($file_path)) @unlink($file_path);
            
            // Hapus record dari database
            $this->db->where('id_surat_tugas', $id_surat_tugas);
            $this->db->delete('cerapan_tera');
        }

        // Set pesan notifikasi agar tampil di halaman (disertai catatan alasan penolakan)
        $this->session->set_flashdata('error', 'Hasil uji dikembalikan. Petugas diminta mengunggah ulang cerapan dengan perbaikan. Catatan: ' . htmlspecialchars($catatan_tolak));
        redirect('validasi-skhp');
    }
}
