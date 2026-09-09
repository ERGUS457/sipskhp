<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Memeriksa apakah pengguna sudah login, jika belum maka akan diarahkan ke halaman login
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('error', 'Silakan masuk terlebih dahulu.');
            redirect('login');
        }

        // Cek level akses pengguna: Hanya Admin dan Kepala UPT yang diizinkan mengakses dashboard utama
        $level = $this->session->userdata('level');
        if (!in_array($level, ['Admin', 'Kepala UPT'])) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
            redirect('/');
        }
    }

    // Fungsi index untuk menampilkan halaman utama dashboard
    public function index()
    {
        $data['title'] = 'Dashboard Utama';
        
        // Mengambil berbagai data statistik dari database untuk ditampilkan di dashboard
        $data['jumlah_pengajuan'] = $this->db->count_all('alat_uttp'); // Total pengajuan alat UTTP
        $data['menunggu_validasi'] = $this->db->where('status', 'Selesai')->count_all_results('surat_tugas'); // Total surat tugas yang selesai dan menunggu validasi
        $data['total_laporan'] = $this->db->where('status_validasi', 'Tervalidasi')->count_all_results('pengujian'); // Total laporan pengujian yang sudah tervalidasi
        $data['total_skhp'] = $this->db->count_all('skhp'); // Total SKHP yang telah diterbitkan
        $data['jumlah_pemohon'] = $this->db->count_all('pemohon'); // Total pengguna dengan peran pemohon

        // Data Grafik 1: SKHP per bulan (Tahun Berjalan)
        $tahun_ini = date('Y');
        $this->db->select('MONTH(tgl_terbit) as bulan, COUNT(*) as jumlah');
        $this->db->from('skhp');
        $this->db->where('YEAR(tgl_terbit)', $tahun_ini);
        $this->db->group_by('MONTH(tgl_terbit)');
        $skhp_per_bulan = $this->db->get()->result_array();
        
        $chart_bulan = array_fill(1, 12, 0);
        foreach ($skhp_per_bulan as $row) {
            $chart_bulan[(int)$row['bulan']] = (int)$row['jumlah'];
        }
        $data['chart_skhp_per_bulan'] = json_encode(array_values($chart_bulan));

        // Data Grafik 2: SKHP per jenis alat
        $this->db->select('alat_uttp.nama_alat, COUNT(skhp.id_skhp) as jumlah');
        $this->db->from('skhp');
        $this->db->join('pengujian', 'pengujian.id_pengujian = skhp.id_pengujian');
        $this->db->join('alat_uttp', 'alat_uttp.id_alat = pengujian.id_alat');
        $this->db->group_by('alat_uttp.nama_alat');
        $chart_alat = $this->db->get()->result_array();
        
        $label_alat = [];
        $data_alat = [];
        foreach ($chart_alat as $row) {
            $label_alat[] = $row['nama_alat'];
            $data_alat[] = (int)$row['jumlah'];
        }
        $data['chart_label_alat'] = json_encode($label_alat);
        $data['chart_data_alat'] = json_encode($data_alat);


        $data['pending_pengajuan'] = 0;
        $data['pending_pengajuan_list'] = [];
        
        // Khusus untuk Admin, ambil data pengajuan yang belum dibaca/diproses
        if ($this->session->userdata('level') === 'Admin') {
            // Mengecek apakah kolom created_at tersedia di tabel alat_uttp
            $hasCreatedAt = $this->db->field_exists('created_at', 'alat_uttp');

            // Menyusun query untuk mengambil daftar 5 pengajuan terbaru yang belum dibaca (is_read_admin = 0)
            $this->db->select('alat_uttp.id_alat, alat_uttp.nama_alat, pemohon.nama_pemilik' . ($hasCreatedAt ? ', alat_uttp.created_at' : ''));
            $this->db->from('alat_uttp');
            $this->db->join('pemohon', 'pemohon.id_pemohon = alat_uttp.id_pemohon', 'left'); // Menggabungkan dengan tabel pemohon untuk mendapatkan nama pemilik
            $this->db->where('alat_uttp.is_read_admin', 0);
            $this->db->order_by($hasCreatedAt ? 'alat_uttp.created_at' : 'alat_uttp.id_alat', 'DESC');
            $this->db->limit(5); // Membatasi data hanya 5
            $data['pending_pengajuan_list'] = $this->db->get()->result_array();

            // Menghitung total seluruh pengajuan yang belum dibaca
            $this->db->from('alat_uttp');
            $this->db->where('alat_uttp.is_read_admin', 0);
            $data['pending_pengajuan'] = $this->db->count_all_results();
            
            // Ambil data petugas untuk mengisi pilihan (dropdown) pada Form Surat Tugas
            $data['petugas'] = $this->db->get('petugas')->result_array();
        }

        // Memuat tampilan (view) dashboard dengan membawa data yang telah disiapkan
        $this->load->view('dashboard/dashboard', $data);
    }
}
