<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_aktivitas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) redirect('login');

        // Hanya Admin yang boleh mengakses
        if ($this->session->userdata('level') !== 'Admin') {
            $this->session->set_flashdata('error', 'Akses ditolak.');
            redirect('dashboard');
        }
    }

    public function index() {
        $data['title'] = 'Laporan Aktivitas Pengguna';

        // Filter bulan & tahun
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        // Default ke bulan & tahun saat ini jika belum difilter
        // CI3 input->get() mengembalikan FALSE (bukan null) jika parameter tidak ada
        if ($bulan === false && $tahun === false) {
            $bulan = date('m');
            $tahun = date('Y');
        }

        $data['filter_bulan'] = $bulan;
        $data['filter_tahun'] = $tahun;

        // Ambil log aktivitas
        $data['log_aktivitas'] = $this->getLogAktivitas($bulan, $tahun);
        $data['stats_login']   = $this->getStatsLogin($bulan, $tahun);

        $this->load->view('dashboard/log_aktivitas', $data);
    }

    // ─── Ambil log aktivitas ─────────────────────────────────────────────────
    private function getLogAktivitas($bulan, $tahun) {
        $this->db->select('id_log, username, level, aksi, keterangan, ip_address, created_at');
        $this->db->from('log_aktivitas');
        if (!empty($bulan)) $this->db->where('MONTH(created_at)', (int)$bulan);
        if (!empty($tahun)) $this->db->where('YEAR(created_at)', (int)$tahun);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(200);
        return $this->db->get()->result_array();
    }

    // ─── Statistik login per level ───────────────────────────────────────────
    private function getStatsLogin($bulan, $tahun) {
        $this->db->select('level, COUNT(*) as jumlah_login, COUNT(DISTINCT username) as jumlah_pengguna');
        $this->db->from('log_aktivitas');
        $this->db->where('aksi', 'Login');
        if (!empty($bulan)) $this->db->where('MONTH(created_at)', (int)$bulan);
        if (!empty($tahun)) $this->db->where('YEAR(created_at)', (int)$tahun);
        $this->db->group_by('level');
        $rows = $this->db->get()->result_array();
        $stats = [];
        foreach ($rows as $r) {
            $stats[$r['level']] = $r;
        }
        return $stats;
    }
}
