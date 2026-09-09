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
			$login = trim((string)$this->input->post('login'));
			$password = (string)$this->input->post('password');

			// Validasi input kosong dengan pesan informatif
			if ($login === '' && $password === '') {
				$this->session->set_flashdata('error', 'Silakan masukkan <strong>Username/Email</strong> dan <strong>Kata Sandi</strong> Anda.');
				redirect('login');
			} elseif ($login === '') {
				$this->session->set_flashdata('error', 'Silakan masukkan <strong>Username atau Alamat Email</strong> Anda.');
				redirect('login');
			} elseif ($password === '') {
				$this->session->set_flashdata('error', 'Silakan masukkan <strong>Kata Sandi (Password)</strong> Anda.');
				redirect('login');
			}

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
					$this->session->set_flashdata('error', '<strong>Kata Sandi salah!</strong> Periksa kembali huruf besar/kecil (Caps Lock) dan pastikan kata sandi yang Anda ketik sudah sesuai.');
					redirect('login');
				}
			} else {
				$this->session->set_flashdata('error', '<strong>Akun tidak ditemukan!</strong> Username atau Email "<strong>' . htmlspecialchars($login) . '</strong>" belum terdaftar di sistem. Silakan periksa kembali atau lakukan pendaftaran akun baru.');
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

			// Konfigurasi pesan default form validation berbahasa Indonesia
			$this->form_validation->set_message('required', '{field} wajib diisi.');
			$this->form_validation->set_message('valid_email', 'Format {field} tidak valid (contoh: user@domain.com).');
			$this->form_validation->set_message('is_unique', '{field} ini sudah terdaftar di sistem.');
			$this->form_validation->set_message('min_length', '{field} minimal {param} karakter.');
			$this->form_validation->set_message('max_length', '{field} maksimal {param} karakter.');
			$this->form_validation->set_message('matches', '{field} tidak sesuai dengan kolom {param}.');
			$this->form_validation->set_message('alpha_numeric', '{field} hanya boleh berisi huruf dan angka.');
			$this->form_validation->set_message('regex_match', 'Format {field} tidak sesuai ketentuan.');
			
			// -------------------------------------------------------
			// Validasi User
			// -------------------------------------------------------
			$this->form_validation->set_rules(
				'email',
				'Alamat Email',
				'required|valid_email|is_unique[user.email]',
				[
					'required'    => 'Alamat Email wajib diisi.',
					'valid_email' => 'Format Alamat Email tidak valid.',
					'is_unique'   => 'Alamat Email ini sudah terdaftar. Silakan gunakan email lain atau langsung masuk ke akun Anda.'
				]
			);
			$this->form_validation->set_rules(
				'username',
				'Username',
				'required|alpha_numeric|min_length[4]|max_length[30]|is_unique[user.username]',
				[
					'required'      => 'Username wajib diisi.',
					'alpha_numeric' => 'Username hanya boleh terdiri dari huruf dan angka tanpa spasi atau simbol.',
					'min_length'    => 'Username minimal 4 karakter.',
					'max_length'    => 'Username maksimal 30 karakter.',
					'is_unique'     => 'Username ini sudah dipakai oleh pengguna lain. Silakan gunakan username lain.'
				]
			);
			$this->form_validation->set_rules(
				'password',
				'Kata Sandi',
				'required|min_length[8]',
				[
					'required'   => 'Kata Sandi wajib diisi.',
					'min_length' => 'Kata Sandi minimal 8 karakter demi keamanan akun Anda.'
				]
			);
			$this->form_validation->set_rules(
				'password_confirm',
				'Konfirmasi Kata Sandi',
				'required|matches[password]',
				[
					'required' => 'Konfirmasi Kata Sandi wajib diisi.',
					'matches'  => 'Konfirmasi Kata Sandi tidak cocok dengan Kata Sandi yang dimasukkan.'
				]
			);
			
			// -------------------------------------------------------
			// Validasi Pemohon
			// -------------------------------------------------------
			$this->form_validation->set_rules(
				'nama_pemilik',
				'Nama Instansi / Pemilik',
				'required',
				['required' => 'Nama Instansi / Pemilik wajib diisi sesuai KTP/dokumen resmi.']
			);
			$this->form_validation->set_rules(
				'jenis_usaha',
				'Jenis Tempat Usaha',
				'required',
				['required' => 'Jenis Tempat Usaha wajib diisi.']
			);

			// Validasi format nomor HP Indonesia (Dikirim sebagai ARRAY agar karakter pipe '|' tidak dipecah oleh CI3)
			$this->form_validation->set_rules(
				'kontak_pemohon',
				'Nomor Handphone',
				[
					'required',
					'min_length[10]',
					'max_length[15]',
					'regex_match[/^(08|628|\+62)[0-9]{7,12}$/]'
				],
				[
					'required'    => 'Nomor Handphone (WhatsApp) wajib diisi.',
					'min_length'  => 'Nomor Handphone minimal 10 digit angka.',
					'max_length'  => 'Nomor Handphone maksimal 15 digit angka.',
					'regex_match' => 'Format Nomor Handphone tidak valid. Gunakan format yang benar diawali 08, 628, atau +62 (contoh: 081234567890).'
				]
			);

			$this->form_validation->set_rules(
				'alamat_usaha',
				'Alamat Lengkap Usaha',
				'required',
				['required' => 'Alamat Lengkap Perusahaan / Toko wajib diisi.']
			);

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

	// -------------------------------------------------------
	// Endpoint Streaming Cerapan Tera (Support Serverless & Local)
	// -------------------------------------------------------
	public function cerapan($filename = '')
	{
		$filename = basename($filename);
		if (empty($filename)) {
			show_404();
			return;
		}

		$local_path = FCPATH . 'asset/cerapan_tera/' . $filename;
		if (file_exists($local_path)) {
			$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
			$mime = 'application/octet-stream';
			if ($ext === 'pdf') $mime = 'application/pdf';
			elseif ($ext === 'xls') $mime = 'application/vnd.ms-excel';
			elseif ($ext === 'xlsx') $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

			header('Content-Type: ' . $mime);
			header('Content-Disposition: inline; filename="' . $filename . '"');
			header('Content-Length: ' . filesize($local_path));
			readfile($local_path);
			exit;
		}

		// Cari di database cerapan_tera jika file fisik tidak ada di container serverless
		$row = $this->db->get_where('cerapan_tera', ['file_cerapan' => $filename])->row_array();
		if ($row && !empty($row['file_data'])) {
			$data = base64_decode($row['file_data']);
			$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
			$mime = 'application/octet-stream';
			if ($ext === 'pdf') $mime = 'application/pdf';
			elseif ($ext === 'xls') $mime = 'application/vnd.ms-excel';
			elseif ($ext === 'xlsx') $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

			header('Content-Type: ' . $mime);
			header('Content-Disposition: inline; filename="' . $filename . '"');
			header('Content-Length: ' . strlen($data));
			echo $data;
			exit;
		}

		show_404();
	}

	// -------------------------------------------------------
	// Endpoint Streaming Foto Profil (Support Serverless & Local)
	// -------------------------------------------------------
	public function uploads_profil($filename = '')
	{
		$filename = basename($filename);
		if (empty($filename)) {
			show_404();
			return;
		}

		$local_path = FCPATH . 'uploads/profil/' . $filename;
		if (file_exists($local_path)) {
			$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
			$mime = 'image/jpeg';
			if ($ext === 'png') $mime = 'image/png';
			elseif ($ext === 'webp') $mime = 'image/webp';
			elseif ($ext === 'gif') $mime = 'image/gif';

			header('Content-Type: ' . $mime);
			header('Content-Length: ' . filesize($local_path));
			header('Cache-Control: public, max-age=86400');
			readfile($local_path);
			exit;
		}

		show_404();
	}
}
