<?php

$pengajuan_content = "<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pengajuan extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if (!\$this->session->userdata('logged_in') || !in_array(\$this->session->userdata('level'), ['Admin', 'Kepala UPT'])) {
            redirect('/');
        }
    }
    public function index() {
        \$data['title'] = 'Daftar Pengajuan';
        
        \$this->db->select('alat_uttp.*, pemohon.nama_pemilik, pemohon.jenis_usaha, surat_tugas.id_surat_tugas');
        \$this->db->from('alat_uttp');
        \$this->db->join('pemohon', 'pemohon.id_pemohon = alat_uttp.id_pemohon', 'left');
        \$this->db->join('surat_tugas', 'surat_tugas.id_alat = alat_uttp.id_alat', 'left');
        \$data['pengajuan'] = \$this->db->get()->result_array();
        
        \$data['petugas'] = \$this->db->get('petugas')->result_array();
        
        \$this->load->view('dashboard/pengajuan_index', \$data);
    }
}
";

$surat_tugas_content = "<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Surat_tugas extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if (!\$this->session->userdata('logged_in') || !in_array(\$this->session->userdata('level'), ['Admin', 'Kepala UPT'])) {
            redirect('/');
        }
    }
    public function index() {
        \$data['title'] = 'Kelola Surat Tugas';
        
        // Fetch surat_tugas with JOINs
        \$this->db->select('surat_tugas.*, alat_uttp.nama_alat, alat_uttp.merk, alat_uttp.tipe_model, pemohon.nama_pemilik');
        \$this->db->from('surat_tugas');
        \$this->db->join('alat_uttp', 'alat_uttp.id_alat = surat_tugas.id_alat', 'left');
        \$this->db->join('pemohon', 'pemohon.id_pemohon = alat_uttp.id_pemohon', 'left');
        \$surat_tugas_data = \$this->db->get()->result_array();

        // Process nama_petugas for JSON array support 
        // e.g. id_petugas might be [\"1\",\"2\"] or 1
        foreach (\$surat_tugas_data as &\$st) {
            \$ids = json_decode(\$st['id_petugas'], true);
            if (!is_array(\$ids)) {
                \$ids = [\$st['id_petugas']];
            }
            if (!empty(\$ids)) {
                \$this->db->where_in('id_petugas', \$ids);
                \$st_petugas = \$this->db->get('petugas')->result_array();
                \$names = array_column(\$st_petugas, 'nama_petugas');
                \$st['nama_petugas'] = implode(', ', \$names);
            } else {
                \$st['nama_petugas'] = 'Belum Ada';
            }
        }
        
        \$data['surat_tugas'] = \$surat_tugas_data;
        \$this->load->view('dashboard/surat_tugas_index', \$data);
    }
}
";

file_put_contents('application/controllers/Pengajuan.php', $pengajuan_content);
file_put_contents('application/controllers/Surat_tugas.php', $surat_tugas_content);

echo "Fixed Pengajuan and Surat_tugas controllers.";
