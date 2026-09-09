<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		// Default route mengarah ke Halaman Landing (Beranda)
		$this->load->view('home/landing');
	}

	public function login()
	{
		
		if ($this->input->method() === 'post') {
			$login = $this->input->post('login');
			$password = $this->input->post('password');

			// Mencari user berdasarkan username ATAU email di tabel 'user'
			$this->db->group_start();
			$this->db->where('username', $login);
			$this->db->or_where('email', $login);
			$this->db->group_end();
			$user = $this->db->get('user')->row();

			if ($user) {
				// Cek kecocokan password menggunakan fungsi hash
				if (password_verify($password, $user->password)) {

					// [Verifikasi email dinonaktifkan]

					// Pendaftaran variabel session setelah sukses
					$session_data = array(
						'id_user'   => $user->id_user,
						'username'  => $user->username,
						'email'     => $user->email,
						'level'     => $user->level,
						'logged_in' => TRUE
					);
					$this->session->set_userdata($session_data);

					// Catat log aktivitas login
					$this->db->insert('log_aktivitas', [
						'id_user'    => $user->id_user,
						'username'   => $user->username,
						'level'      => $user->level,
						'aksi'       => 'Login',
						'keterangan' => 'Login berhasil ke sistem SIAP SKHP TERA',
						'ip_address' => $this->input->ip_address(),
						'user_agent' => $this->input->user_agent(),
						'created_at' => date('Y-m-d H:i:s')
					]);

					if ($user->level === 'Pemohon') {
						$this->session->set_flashdata('success', 'Berhasil masuk! Selamat datang, ' . $user->username . '.');
						redirect('pemohon-dashboard');
					} elseif ($user->level === 'Admin' || $user->level === 'Kepala UPT') {
						$this->session->set_flashdata('success', 'Selamat Bertugas, ' . $user->username . '!');
						// Arahkan ke Controller Dashboard
						redirect('dashboard');
					} elseif ($user->level === 'Petugas') {
						$this->session->set_flashdata('success', 'Selamat Bertugas, ' . $user->username . '!');
						redirect('petugas-dashboard');
					} else {
						// Level lainnya
						redirect('petugas-dashboard');
					}
				} else {
					$this->session->set_flashdata('error', 'Kata Sandi yang Anda masukkan salah.');
					redirect('login');
				}
			} else {
				$this->session->set_flashdata('error', 'Akun tidak ditemukan. Pastikan Username atau Email Anda benar.');
				redirect('login');
			}
		}

		// Tampilkan Form View jika tidak ada request POST
		$this->load->view('auth/login');
	}

	public function logout()
	{
		// Catat log aktivitas logout sebelum session dihancurkan
		if ($this->session->userdata('logged_in')) {
			$this->db->insert('log_aktivitas', [
				'id_user'    => $this->session->userdata('id_user'),
				'username'   => $this->session->userdata('username'),
				'level'      => $this->session->userdata('level'),
				'aksi'       => 'Logout',
				'keterangan' => 'Keluar dari sistem SIAP SKHP TERA',
				'ip_address' => $this->input->ip_address(),
				'user_agent' => $this->input->user_agent(),
				'created_at' => date('Y-m-d H:i:s')
			]);
		}
		$this->session->sess_destroy();
		redirect('login');
	}

	public function petugas()
	{
		if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
		$this->load->view('dashboard/petugas');
	}

	public function register()
	{
		if ($this->input->method() === 'post') {
			$this->load->library('form_validation');
			
			// -------------------------------------------------------
			// Validasi User
			// -------------------------------------------------------
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[user.email]',
				array('is_unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau login.')
			);
			$this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.username]|alpha_numeric',
				array(
					'is_unique'     => 'Username ini sudah dipakai. Silakan pilih username lain.',
					'alpha_numeric' => 'Username hanya boleh terdiri dari huruf dan angka.'
				)
			);
			$this->form_validation->set_rules('password', 'Kata Sandi', 'required|min_length[8]');
			$this->form_validation->set_rules('password_confirm', 'Konfirmasi Kata Sandi', 'required|matches[password]');
			
			// -------------------------------------------------------
			// Validasi Pemohon
			// -------------------------------------------------------
			$this->form_validation->set_rules('nama_pemilik', 'Nama Pemilik', 'required');
			$this->form_validation->set_rules('jenis_usaha', 'Jenis Tempat Usaha', 'required');

			// [Saran 2] Validasi format nomor HP Indonesia
			$this->form_validation->set_rules(
				'kontak_pemohon',
				'Nomor Handphone',
				'required|min_length[10]|max_length[15]|regex_match[/^(08|\+62|628)[0-9]{7,12}$/]',
				[
					'required'    => 'Nomor Handphone wajib diisi.',
					'min_length'  => 'Nomor HP minimal 10 digit.',
					'max_length'  => 'Nomor HP maksimal 15 digit.',
					'regex_match' => 'Format nomor HP tidak valid. Gunakan format: 08xxx, +62xxx, atau 628xxx.',
				]
			);

			$this->form_validation->set_rules('alamat_usaha', 'Alamat Lengkap Usaha', 'required');

			if ($this->form_validation->run() === FALSE) {
				// Jika gagal validasi, form view akan menampilkan validation_errors()
				$this->load->view('auth/register');
			} else {

				// -------------------------------------------------------
				// [Saran 5] Generate id_pemohon dengan format prefix PMH-XXXXXXXX
				// -------------------------------------------------------
				do {
					$angka_random = rand(10000000, 99999999);
					$id_pemohon   = 'PMH-' . $angka_random;
					$exists = $this->db->get_where('pemohon', ['id_pemohon' => $id_pemohon])->num_rows();
				} while ($exists > 0);

				// Simpan Data User (langsung aktif tanpa verifikasi email)
				$user_data = array(
					'username' => $this->input->post('username'),
					'email'    => $this->input->post('email'),
					'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
					'level'    => 'Pemohon',
				);
				$this->db->insert('user', $user_data);
				$id_user = $this->db->insert_id();

				// Simpan Data Pemohon
				$pemohon_data = array(
					'id_pemohon'     => $id_pemohon,
					'id_user'        => $id_user,
					'nama_pemilik'   => $this->input->post('nama_pemilik'),
					'jenis_usaha'    => $this->input->post('jenis_usaha'),
					'kontak_pemohon' => $this->input->post('kontak_pemohon'),
					'alamat_usaha'   => $this->input->post('alamat_usaha')
				);
				$this->db->insert('pemohon', $pemohon_data);

				$this->session->set_flashdata('success',
					'<strong>Pendaftaran Berhasil!</strong> Akun Anda telah dibuat dengan ID Pemohon: <strong>' . $id_pemohon . '</strong>. Silakan login.'
				);
				redirect('login');
			}
		} else {
			$this->load->view('auth/register');
		}
	}

	// -------------------------------------------------------
	// [Saran 4] Fungsi verifikasi email via token
	// -------------------------------------------------------
	public function verify_email($token = NULL)
	{
		if (empty($token)) {
			$this->session->set_flashdata('error', 'Token verifikasi tidak valid.');
			redirect('login');
			return;
		}

		// Cari user berdasarkan token
		$user = $this->db->get_where('user', [
			'verification_token' => $token,
			'email_verified'     => 0
		])->row();

		if ($user) {
			// Token valid — aktifkan akun dan hapus token
			$this->db->where('id_user', $user->id_user);
			$this->db->update('user', [
				'email_verified'     => 1,
				'verification_token' => NULL
			]);

			$this->session->set_flashdata('success',
				'<strong>Email berhasil diverifikasi!</strong> Akun Anda kini aktif. Silakan login.'
			);
			redirect('login');
		} else {
			// Token tidak ditemukan atau sudah dipakai
			$this->session->set_flashdata('error',
				'Link verifikasi tidak valid atau sudah pernah digunakan. Silakan login untuk mendapatkan link baru.'
			);
			redirect('login');
		}
	}
}
