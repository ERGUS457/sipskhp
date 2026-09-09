<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Pastikan pengguna sudah login
        if (!$this->session->userdata('logged_in')) redirect('login');
        
        // Batasi hak akses halaman laporan ini hanya untuk Kepala UPT dan Admin
        if (!in_array($this->session->userdata('level'), ['Kepala UPT', 'Admin'])) {
            $this->session->set_flashdata('error', 'Akses ditolak.');
            redirect('dashboard');
        }
    }

    // ============================================================
    // INDEX: Menampilkan halaman utama Pembukuan Laporan
    // ============================================================
    public function index() {
        $data['title'] = 'Pembukuan Laporan';

        // ─── Statistik Ringkasan (Kartu Atas) ───────────────────────────────
        // Total alat UTTP yang sudah pernah diajukan (punya surat tugas)
        $data['total_pengajuan'] = $this->db->count_all_results('surat_tugas');

        // Total surat tugas yang sudah selesai
        $data['total_selesai']   = $this->db->where('status', 'Selesai')->count_all_results('surat_tugas');

        // Total pengujian yang sudah tervalidasi
        $data['total_validasi']  = $this->db->where('status_validasi', 'Tervalidasi')->count_all_results('pengujian');

        // Total hasil uji Sah
        $data['total_sah']       = $this->db->where('hasil_uji', 'Sah')->count_all_results('pengujian');

        // ─── Filter Periode ──────────────────────────────────────────────────
        $hari  = $this->input->get('hari');
        $bulan = $this->input->get('bulan');
        $tahun = $this->input->get('tahun');

        // Jika load pertama (belum filter), default ke bulan & tahun saat ini
        // CI3 input->get() mengembalikan FALSE (bukan null) jika parameter tidak ada
        if ($this->input->get('tahun') === false && $this->input->get('bulan') === false) {
            $bulan = date('m');
            $tahun = date('Y');
        }

        // Hitung parameter penunjang jika filter terisi
        $bulan_lalu = null;
        if (!empty($bulan)) {
            $bulan_val = (int)$bulan;
            $bulan_lalu_val = $bulan_val - 1;
            if ($bulan_lalu_val == 0) {
                $bulan_lalu_val = 12;
            }
            $bulan_lalu = str_pad($bulan_lalu_val, 2, '0', STR_PAD_LEFT);
        }

        $tahun_lalu = null;
        if (!empty($tahun)) {
            $tahun_lalu = (int)$tahun - 1;
        }

        $data['filter_hari']  = $hari;
        $data['filter_bulan'] = $bulan;
        $data['filter_tahun'] = $tahun;

        // ─── Ambil seluruh pengujian Tervalidasi + data alat & pemohon ───────
        $this->db->select('
            pengujian.id_pengujian,
            pengujian.id_alat,
            pengujian.tgl_pengujian,
            pengujian.hasil_uji,
            pengujian.status_validasi,
            alat_uttp.nama_alat,
            alat_uttp.merk,
            alat_uttp.kapasitas,
            alat_uttp.jumlah,
            alat_uttp.tipe_model,
            alat_uttp.nomor_seri,
            alat_uttp.id_pemohon,
            pemohon.nama_pemilik,
            pemohon.jenis_usaha,
            pemohon.alamat_usaha
        ');
        $this->db->from('pengujian');
        $this->db->join('alat_uttp', 'alat_uttp.id_alat = pengujian.id_alat', 'left');
        $this->db->join('pemohon', 'pemohon.id_pemohon = alat_uttp.id_pemohon', 'left');
        $this->db->where('pengujian.status_validasi', 'Tervalidasi');
        $this->db->order_by('pengujian.tgl_pengujian', 'DESC');
        $all_laporan = $this->db->get()->result_array();

        // ─── Kelompokkan per periode ─────────────────────────────────────────
        // Inisialisasi array untuk menyimpan data sesuai dengan rentang waktunya
        $laporan_bulan_ini         = [];
        $laporan_bulan_lalu        = [];
        $laporan_sd_bulan_ini      = [];
        $laporan_bulan_ini_thn_lalu = [];

        // Melakukan perulangan untuk setiap data laporan
        foreach ($all_laporan as $row) {
            $tgl = $row['tgl_pengujian'];
            if (!$tgl) continue;

            // Memisahkan tanggal, bulan, dan tahun untuk keperluan filter
            $d = date('d', strtotime($tgl));
            $m = date('m', strtotime($tgl));
            $y = date('Y', strtotime($tgl));

            // Cek filter hari jika dispesifikasikan oleh pengguna
            $match_hari = empty($hari) || ($d == str_pad($hari, 2, '0', STR_PAD_LEFT));

            if (!empty($bulan) && !empty($tahun)) {
                // KASUS 1: Bulan & Tahun Spesifik (Normal Bulanan)
                
                // SD Bulan Ini (Januari s/d bulan terpilih di tahun terpilih)
                if ($y == $tahun && $m <= str_pad($bulan, 2, '0', STR_PAD_LEFT)) {
                    $laporan_sd_bulan_ini[] = $row;
                }
                
                // Bulan Ini
                if ($y == $tahun && $m == str_pad($bulan, 2, '0', STR_PAD_LEFT)) {
                    if ($match_hari) $laporan_bulan_ini[] = $row;
                }

                // Bulan Lalu
                $tahun_bulan_lalu = ($bulan == '01') ? $tahun - 1 : $tahun;
                if ($y == $tahun_bulan_lalu && $m == $bulan_lalu) {
                    if ($match_hari) $laporan_bulan_lalu[] = $row;
                }

                // Bulan Ini Tahun Lalu
                if ($y == $tahun_lalu && $m == str_pad($bulan, 2, '0', STR_PAD_LEFT)) {
                    if ($match_hari) $laporan_bulan_ini_thn_lalu[] = $row;
                }

            } elseif (empty($bulan) && !empty($tahun)) {
                // KASUS 2: Semua Bulan, Tahun Spesifik (Rekap Tahunan)
                if ($y == $tahun) {
                    $laporan_bulan_ini[] = $row;
                    $laporan_sd_bulan_ini[] = $row;
                }
                if ($y == $tahun_lalu) {
                    $laporan_bulan_lalu[] = $row;
                    $laporan_bulan_ini_thn_lalu[] = $row;
                }

            } elseif (!empty($bulan) && empty($tahun)) {
                // KASUS 3: Bulan Spesifik, Semua Tahun (Rekap bulanan lintas tahun)
                if ($m == str_pad($bulan, 2, '0', STR_PAD_LEFT)) {
                    if ($match_hari) $laporan_bulan_ini[] = $row;
                }
                if ($m == $bulan_lalu) {
                    if ($match_hari) $laporan_bulan_lalu[] = $row;
                }
                if ($m <= str_pad($bulan, 2, '0', STR_PAD_LEFT)) {
                    $laporan_sd_bulan_ini[] = $row;
                }

            } else {
                // KASUS 4: Semua Bulan & Semua Tahun (Total Sepanjang Masa)
                $laporan_bulan_ini[] = $row;
                $laporan_sd_bulan_ini[] = $row;
            }
        }

        $data['laporan'] = $laporan_bulan_ini;

        // ─── Rekap UTTP 4 periode ────────────────────────────────────────────
        $data['rekap_ini']        = $this->getRekapsUttp($laporan_bulan_ini);
        $data['rekap_lalu']       = $this->getRekapsUttp($laporan_bulan_lalu);
        $data['rekap_sd']         = $this->getRekapsUttp($laporan_sd_bulan_ini);
        $data['rekap_tahun_lalu'] = $this->getRekapsUttp($laporan_bulan_ini_thn_lalu);

        // ─── Rekap Pemilik 4 periode ─────────────────────────────────────────
        $data['pemilik_ini']        = count(array_unique(array_column($laporan_bulan_ini, 'id_pemohon')));
        $data['pemilik_lalu']       = count(array_unique(array_column($laporan_bulan_lalu, 'id_pemohon')));
        $data['pemilik_sd']         = count(array_unique(array_column($laporan_sd_bulan_ini, 'id_pemohon')));
        $data['pemilik_tahun_lalu'] = count(array_unique(array_column($laporan_bulan_ini_thn_lalu, 'id_pemohon')));

        // ─── Total Alat (dengan jumlah unit) 4 periode ──────────────────────
        $data['total_ini']        = $this->sumJumlah($laporan_bulan_ini);
        $data['total_lalu']       = $this->sumJumlah($laporan_bulan_lalu);
        $data['total_sd']         = $this->sumJumlah($laporan_sd_bulan_ini);
        $data['total_tahun_lalu'] = $this->sumJumlah($laporan_bulan_ini_thn_lalu);

        // ─── Kepala UPT (dari tabel petugas/user aktif) ─────────────────────
        $kepala = $this->db->select('petugas.nama_petugas, petugas.nip')
            ->from('petugas')
            ->join('user', 'user.id_user = petugas.id_user', 'left')
            ->where('user.level', 'Kepala UPT')
            ->limit(1)
            ->get()->row_array();

        $data['kepala_upt'] = $kepala ? [
            'nama' => $kepala['nama_petugas'],
            'nip'  => $kepala['nip']
        ] : [
            'nama' => 'YASMALIZAR, S.H.',
            'nip'  => '196810161998031004'
        ];

        // ─── Laporan Pengguna (Log Aktivitas) ────────────────────────────────
        $data['log_aktivitas'] = $this->getLogAktivitas($bulan, $tahun);
        $data['stats_login']   = $this->getStatsLogin($bulan, $tahun);

        $this->load->view('dashboard/laporan_index', $data);
    }

    // ─── Endpoint JSON untuk tab laporan pengguna ────────────────────────────
    // Endpoint ini diakses melalui AJAX (Datatables) untuk memuat log aktivitas tanpa reload halaman
    public function log_json() {
        // Ambil parameter pencarian/filter
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $level = $this->input->get('level');
        $aksi  = $this->input->get('aksi');

        // Menyusun query ke tabel log_aktivitas berdasarkan filter
        $this->db->select('id_log, username, level, aksi, keterangan, ip_address, created_at');
        $this->db->from('log_aktivitas');
        $this->db->where('MONTH(created_at)', (int)$bulan);
        $this->db->where('YEAR(created_at)', (int)$tahun);
        if (!empty($level)) $this->db->where('level', $level);
        if (!empty($aksi))  $this->db->where('aksi', $aksi);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(200); // Batasi maksimal 200 data terakhir
        $logs = $this->db->get()->result_array();

        while (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['data' => $logs], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ─── Ambil log aktivitas ─────────────────────────────────────────────────
    private function getLogAktivitas($bulan, $tahun) {
        $this->db->select('id_log, username, level, aksi, keterangan, ip_address, created_at');
        $this->db->from('log_aktivitas');
        $this->db->where('MONTH(created_at)', (int)$bulan);
        $this->db->where('YEAR(created_at)', (int)$tahun);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(100);
        return $this->db->get()->result_array();
    }

    // ─── Statistik login per level ───────────────────────────────────────────
    private function getStatsLogin($bulan, $tahun) {
        $this->db->select('level, COUNT(*) as jumlah_login, COUNT(DISTINCT username) as jumlah_pengguna');
        $this->db->from('log_aktivitas');
        $this->db->where('aksi', 'Login');
        $this->db->where('MONTH(created_at)', (int)$bulan);
        $this->db->where('YEAR(created_at)', (int)$tahun);
        $this->db->group_by('level');
        $rows = $this->db->get()->result_array();
        $stats = [];
        foreach ($rows as $r) {
            $stats[$r['level']] = $r;
        }
        return $stats;
    }

    // ─── Helper: Jumlah total unit (gunakan field `jumlah`) ─────────────────
    // Fungsi ini menjumlahkan total unit alat dari suatu kumpulan data laporan
    private function sumJumlah($laporan) {
        $total = 0;
        foreach ($laporan as $item) {
            $total += (int)($item['jumlah'] ?? 1); // Jika tidak ada nilai jumlah, default dihitung 1
        }
        return $total;
    }

    // ─── Helper: Rekap UTTP berdasarkan kategori nama_alat ──────────────────
    // Fungsi ini bertugas mendeteksi string nama alat (menggunakan pencarian teks stripos)
    // Kemudian memasukkannya ke dalam kategori dan sub-kategori UTTP yang sesuai
    private function getRekapsUttp($laporan) {
        $rekap = [
            'panjang'           => 0,
            'timbangan_mekanik' => 0,
            'tm_sentisimal'     => 0, 'tm_meja'     => 0, 'tm_dacin'     => 0,
            'tm_pegas'          => 0, 'tm_bobot_ingsut' => 0, 'tm_neraca' => 0,
            'timbangan_elektronik' => 0,
            'te_halus'          => 0, 'te_sedang'   => 0, 'te_jembatan'  => 0,
            'te_pengisian'      => 0, 'te_pencampuran' => 0,
            'volume'            => 0,
            'v_takaran_basah'   => 0, 'v_bejana_ukur'  => 0, 'v_pompa_ukur_bbm' => 0,
            'v_gelas_ukur'      => 0, 'v_tangki_mobil' => 0, 'v_tangki_datar'   => 0,
            'v_tangki_tegak'    => 0, 'v_tangki_selain'=> 0, 'v_meter_arus'     => 0,
            'v_meter_air'       => 0,
            'energi'            => 0,
            'e_meter_kwh'       => 0, 'e_splu'      => 0,
            'perlengkapan'      => 0,
            'p_m2_m3'           => 0, 'p_f2_m1'     => 0,
            'sampel'            => 0,
            's_produk'          => 0,
            'lain'              => 0,
            'l_printer'         => 0, 'l_skhp'      => 0, 'l_tabel_volume' => 0
        ];

        foreach ($laporan as $item) {
            $nama   = strtolower($item['nama_alat']);
            // Gunakan jumlah unit alat dari database (default 1 jika NULL/kosong)
            $jumlah = (int)($item['jumlah'] ?? 1);

            if (stripos($nama, 'panjang') !== false
                || stripos($nama, 'mistar') !== false
                || (stripos($nama, 'meter') !== false
                    && stripos($nama, 'air')     === false
                    && stripos($nama, 'listrik') === false
                    && stripos($nama, 'kwh')     === false
                    && stripos($nama, 'arus')    === false)) {
                $rekap['panjang'] += $jumlah;

            } elseif (stripos($nama, 'sentisimal') !== false) {
                $rekap['timbangan_mekanik'] += $jumlah;
                $rekap['tm_sentisimal']     += $jumlah;
            } elseif (stripos($nama, 'dacin') !== false) {
                $rekap['timbangan_mekanik'] += $jumlah;
                $rekap['tm_dacin']          += $jumlah;
            } elseif (stripos($nama, 'pegas') !== false) {
                $rekap['timbangan_mekanik'] += $jumlah;
                $rekap['tm_pegas']          += $jumlah;
            } elseif (stripos($nama, 'bobot ingsut') !== false || stripos($nama, 'bobot') !== false) {
                $rekap['timbangan_mekanik'] += $jumlah;
                $rekap['tm_bobot_ingsut']   += $jumlah;
            } elseif (stripos($nama, 'neraca') !== false) {
                $rekap['timbangan_mekanik'] += $jumlah;
                $rekap['tm_neraca']         += $jumlah;
            } elseif (stripos($nama, 'mekanik') !== false
                      || stripos($nama, 'meja') !== false
                      || (stripos($nama, 'timbangan') !== false
                          && stripos($nama, 'elektronik') === false
                          && stripos($nama, 'digital')    === false
                          && stripos($nama, 'jembatan')   === false
                          && stripos($nama, 'analitik')   === false)) {
                // Semua timbangan mekanik lain (termasuk Timbangan Lantai, Buah, Gantung, dst)
                $rekap['timbangan_mekanik'] += $jumlah;
                $rekap['tm_meja']           += $jumlah;

            } elseif (stripos($nama, 'jembatan') !== false || stripos($nama, 'truck') !== false || stripos($nama, 'truk') !== false) {
                $rekap['timbangan_elektronik'] += $jumlah;
                $rekap['te_jembatan']          += $jumlah;
            } elseif (stripos($nama, 'pengisian') !== false && stripos($nama, 'listrik') === false) {
                $rekap['timbangan_elektronik'] += $jumlah;
                $rekap['te_pengisian']         += $jumlah;
            } elseif (stripos($nama, 'pencampuran') !== false || stripos($nama, 'campur') !== false) {
                $rekap['timbangan_elektronik'] += $jumlah;
                $rekap['te_pencampuran']       += $jumlah;
            } elseif (stripos($nama, 'analitik') !== false
                      || stripos($nama, 'halus') !== false
                      || stripos($nama, 'kelas ii') !== false) {
                $rekap['timbangan_elektronik'] += $jumlah;
                $rekap['te_halus']             += $jumlah;
            } elseif (stripos($nama, 'elektronik') !== false
                      || stripos($nama, 'digital') !== false) {
                $rekap['timbangan_elektronik'] += $jumlah;
                $rekap['te_sedang']            += $jumlah;

            } elseif (stripos($nama, 'pompa ukur') !== false || stripos($nama, 'pompa') !== false) {
                $rekap['volume']            += $jumlah;
                $rekap['v_pompa_ukur_bbm']  += $jumlah;
            } elseif (stripos($nama, 'meter arus') !== false) {
                $rekap['volume']        += $jumlah;
                $rekap['v_meter_arus']  += $jumlah;
            } elseif (stripos($nama, 'meter air') !== false) {
                $rekap['volume']        += $jumlah;
                $rekap['v_meter_air']   += $jumlah;
            } elseif (stripos($nama, 'takaran') !== false) {
                $rekap['volume']            += $jumlah;
                $rekap['v_takaran_basah']   += $jumlah;
            } elseif (stripos($nama, 'bejana') !== false) {
                $rekap['volume']        += $jumlah;
                $rekap['v_bejana_ukur'] += $jumlah;
            } elseif (stripos($nama, 'gelas ukur') !== false) {
                $rekap['volume']        += $jumlah;
                $rekap['v_gelas_ukur']  += $jumlah;
            } elseif (stripos($nama, 'tangki mobil') !== false || stripos($nama, 'truk') !== false) {
                $rekap['volume']            += $jumlah;
                $rekap['v_tangki_mobil']    += $jumlah;
            } elseif (stripos($nama, 'silinder datar') !== false || stripos($nama, 'tangki datar') !== false) {
                $rekap['volume']        += $jumlah;
                $rekap['v_tangki_datar']    += $jumlah;
            } elseif (stripos($nama, 'silinder tegak') !== false || stripos($nama, 'tangki tegak') !== false) {
                $rekap['volume']            += $jumlah;
                $rekap['v_tangki_tegak']    += $jumlah;
            } elseif (stripos($nama, 'tangki') !== false) {
                $rekap['volume']            += $jumlah;
                $rekap['v_tangki_selain']   += $jumlah;
            } elseif (stripos($nama, 'volume') !== false || stripos($nama, 'liter') !== false) {
                $rekap['volume']            += $jumlah;
                $rekap['v_takaran_basah']   += $jumlah;

            } elseif (stripos($nama, 'splu') !== false || stripos($nama, 'stasiun pengisian listrik') !== false) {
                $rekap['energi']    += $jumlah;
                $rekap['e_splu']    += $jumlah;
            } elseif (stripos($nama, 'kwh') !== false || stripos($nama, 'listrik') !== false) {
                $rekap['energi']        += $jumlah;
                $rekap['e_meter_kwh']   += $jumlah;

            } elseif (stripos($nama, 'anak timbangan') !== false) {
                $rekap['perlengkapan'] += $jumlah;
                if (stripos($nama, 'f2') !== false || stripos($nama, 'm1') !== false) {
                    $rekap['p_f2_m1'] += $jumlah;
                } else {
                    $rekap['p_m2_m3'] += $jumlah;
                }
            } elseif (stripos($nama, 'kalibrator') !== false || stripos($nama, 'standar') !== false) {
                $rekap['perlengkapan'] += $jumlah;
                $rekap['p_m2_m3']     += $jumlah;

            } elseif (stripos($nama, 'sampel') !== false || stripos($nama, 'bdkt') !== false) {
                $rekap['sampel']    += $jumlah;
                $rekap['s_produk']  += $jumlah;

            } else {
                $rekap['lain'] += $jumlah;
                if (stripos($nama, 'printer') !== false || stripos($nama, 'cetak') !== false) {
                    $rekap['l_printer'] += $jumlah;
                } elseif (stripos($nama, 'skhp') !== false) {
                    $rekap['l_skhp'] += $jumlah;
                } elseif (stripos($nama, 'tabel') !== false) {
                    $rekap['l_tabel_volume'] += $jumlah;
                } else {
                    $rekap['l_skhp'] += $jumlah; // fallback
                }
            }
        }

        return $rekap;
    }
}
