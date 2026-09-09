<?php $this->load->view('layout/header', ['title' => 'Pendaftaran Akun Baru - E-TERA SKHP']); ?>
<?php $this->load->view('layout/navbar'); ?>

<style>
    /* Styling Dasar Form Edukatif & Profesional */
    body {
        /* background-color diwariskan dari template */
    }
    .auth-container {
        padding-top: 100px;
        padding-bottom: 80px;
    }
    .auth-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        background-color: var(--color-card);
        border: 1px solid var(--color-border);
    }
    .auth-header {
        background: var(--color-bg-grad);
        color: var(--color-primary);
        padding: 30px 20px;
        text-align: center;
    }
    .auth-header i {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.9;
    }
    .auth-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .auth-subtitle {
        font-size: 1.05rem;
        font-weight: 300;
        opacity: 0.85;
    }
    .auth-body {
        padding: 40px;
        background-color: transparent;
    }
    .section-label {
        font-size: 1.25rem;
        color: var(--color-primary);
        font-weight: 700;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 10px;
        margin-bottom: 20px;
        margin-top: 10px;
    }
    .form-label {
        font-weight: 600;
        color: var(--color-text); /* Ini akan ambil dari var global text-main di header kita */
        font-size: 1.1rem;
        margin-bottom: 8px;
    }
    .form-control-lg {
        font-size: 1.1rem;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid var(--color-border);
        background-color: var(--color-bg);
        color: var(--text-main);
    }
    .form-control-lg:focus {
        border-color: var(--color-secondary);
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
    }
    .input-group-text {
        background-color: var(--color-bg-alt);
        color: var(--text-muted);
        border-radius: 8px;
        border: 1px solid var(--color-border);
        cursor: pointer;
    }
    .btn-auth {
        padding: 15px;
        font-size: 1.25rem;
        font-weight: 600;
        border-radius: 8px;
        background-color: var(--color-primary);
        color: white;
        border: none;
        box-shadow: 0 4px 10px rgba(6, 95, 70, 0.3);
        transition: transform 0.2s;
    }
    .btn-auth:hover {
        transform: translateY(-2px);
        background-color: var(--color-secondary);
        color: white;
    }
    .auth-footer {
        text-align: center;
        margin-top: 25px;
        font-size: 1.05rem;
    }
    .auth-footer a {
        font-weight: 600;
        color: var(--color-primary);
        text-decoration: none;
    }
    .auth-footer a:hover {
        text-decoration: underline;
    }
</style>

<div class="container auth-container">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-8">
            <div class="card auth-card">
                
                <div class="auth-header">
                    <i class="fas fa-user-plus"></i>
                    <h2 class="auth-title">Pendaftaran Akun Baru</h2>
                    <p class="auth-subtitle">Sistem Pelayanan Pengujian Tera & Tera Ulang</p>
                </div>

                <div class="auth-body px-md-5">

                    <!-- Menampilkan pesan info (CodeIgniter 3 syntax) -->
                    <?php if ($this->session->flashdata('info')) : ?>
                        <div class="alert alert-info d-flex align-items-center" role="alert" style="font-size: 1.05rem;">
                            <i class="fas fa-info-circle me-2"></i>
                            <div><?= $this->session->flashdata('info') ?></div>
                        </div>
                    <?php endif; ?>

                    <!-- Menampilkan pesan error umum -->
                    <?php if ($this->session->flashdata('error')) : ?>
                        <div class="alert alert-danger d-flex align-items-center" role="alert" style="font-size: 1.05rem;">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <div><?= $this->session->flashdata('error') ?></div>
                        </div>
                    <?php endif; ?>

                    <!-- Menampilkan pesan error validasi Form CodeIgniter 3 -->
                    <?php if (validation_errors()) : ?>
                        <div class="alert alert-warning" role="alert" style="font-size: 1.05rem;">
                            <strong>Perhatian:</strong><br>
                            <?= validation_errors() ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('register') ?>" method="post">
                        <!-- Pada CI3 CSRF form disisipkan otomatis jika aktif, atau pakai form_open() -->
                        
                        <h5 class="section-label"><i class="fas fa-id-card me-2"></i> 1. Informasi Akun Login</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label">Alamat Email Lengkap <span class="text-danger">*</span></label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="contoh@email.com" value="<?= set_value('email') ?>" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="username" class="form-label">Username (Nama Pengguna) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" id="username" name="username" placeholder="Untuk login nantinya" value="<?= set_value('username') ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label">Kata Sandi (Password) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Minimal 8 Karakter" required>
                                    <button class="btn btn-outline-secondary input-group-text toggle-btn" type="button" data-target="password" style="border-radius: 0 8px 8px 0;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="password_confirm" class="form-label">Ulangi Kata Sandi <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-lg" id="password_confirm" name="password_confirm" placeholder="Ketik ulang password" required>
                                    <button class="btn btn-outline-secondary input-group-text toggle-btn" type="button" data-target="password_confirm" style="border-radius: 0 8px 8px 0;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <h5 class="section-label mt-4"><i class="fas fa-building me-2"></i> 2. Profil Pemohon / Instansi</h5>

                        <div class="mb-4">
                            <label for="nama_pemilik" class="form-label">Nama Instansi / Pemilik <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="nama_pemilik" name="nama_pemilik" placeholder="Sesuai KTP" required value="<?= set_value('nama_pemilik') ?>">
                        </div>

                        <div class="mb-4">
                            <label for="jenis_usaha" class="form-label">Jenis Tempat Usaha <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-lg" id="jenis_usaha" name="jenis_usaha" placeholder="Contoh: SPBU, Toko Emas, Pasar Tradisional" required value="<?= set_value('jenis_usaha') ?>">
                        </div>

                        <div class="mb-4">
                            <label for="kontak_pemohon" class="form-label">Nomor Handphone (WhatsApp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone-alt text-muted"></i></span>
                                <input type="tel" class="form-control form-control-lg" id="kontak_pemohon" name="kontak_pemohon" placeholder="08123xxxxxxx" required value="<?= set_value('kontak_pemohon') ?>" maxlength="15" oninput="validatePhone(this)">
                            </div>
                            <div id="phone-feedback" class="mt-1" style="font-size: 0.92rem;"></div>
                            <small class="text-muted">Format yang diterima: <code>08xxx</code>, <code>+62xxx</code>, atau <code>628xxx</code> (10–15 digit)</small>
                        </div>

                        <div class="mb-4 pb-3 border-bottom">
                            <label for="alamat_usaha" class="form-label">Alamat Lengkap Perusahaan / Toko <span class="text-danger">*</span></label>
                            <textarea class="form-control form-control-lg" id="alamat_usaha" name="alamat_usaha" rows="3" placeholder="Nama Jalan, RT/RW, Kelurahan, Kecamatan" required><?= set_value('alamat_usaha') ?></textarea>
                        </div>

                        <!-- CAPTCHA Verifikasi -->
                        <h5 class="section-label mt-4"><i class="fas fa-shield-alt me-2"></i> 3. Verifikasi Keamanan</h5>
                        <div class="mb-4">
                            <label class="form-label">Verifikasi bahwa Anda bukan robot: <span class="text-danger">*</span></label>
                            
                            <div class="recaptcha-fake d-flex align-items-center justify-content-between p-3 rounded" style="max-width: 304px; background: #f9f9f9; border: 1px solid #d3d3d3; box-shadow: 0px 0px 4px 1px rgba(0,0,0,0.08); cursor: pointer;" onclick="toggleCaptcha(this)">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="captcha-checkbox d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: #fff; border: 2px solid #c1c1c1; border-radius: 2px; transition: 0.2s;">
                                    </div>
                                    <span style="font-size: 14px; font-family: Roboto, helvetica, arial, sans-serif; font-weight: 400; color: #222;">I'm not a robot</span>
                                </div>
                                <div class="text-center" style="opacity: 0.8; line-height: 1.2;">
                                    <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" alt="reCAPTCHA logo" style="width: 32px; height: 32px;">
                                    <div style="font-size: 10px; color: #555; margin-top: 4px;">reCAPTCHA</div>
                                    <div style="font-size: 8px; color: #555;">Privacy - Terms</div>
                                </div>
                            </div>
                            <!-- Hidden input agar form tidak dikirim sebelum di "centang" -->
                            <input type="checkbox" name="captcha" id="captcha_hidden" required style="opacity: 0; position: absolute; z-index: -1;">
                            
                            <script>
                                function toggleCaptcha(el) {
                                    const checkbox = el.querySelector('.captcha-checkbox');
                                    const hiddenInput = document.getElementById('captcha_hidden');
                                    
                                    if (hiddenInput.checked) return; // Sudah di centang
                                    
                                    // Animasi loading singkat
                                    checkbox.style.border = '2px solid transparent';
                                    checkbox.innerHTML = '<i class="fas fa-spinner fa-spin text-primary" style="font-size: 18px;"></i>';
                                    
                                    setTimeout(() => {
                                        checkbox.innerHTML = '<i class="fas fa-check text-success" style="font-size: 20px;"></i>';
                                        hiddenInput.checked = true;
                                    }, 1000);
                                }
                            </script>

                        </div>

                        <!-- Kotak Centang Persetujuan -->
                        <div class="form-check mb-4 d-flex align-items-center">
                            <input class="form-check-input flex-shrink-0" type="checkbox" id="termsCheck" name="termsCheck" style="width: 1.5em; height: 1.5em; margin-top: 0; cursor: pointer;" required>
                            <label class="form-check-label text-muted ms-3" for="termsCheck" style="font-size: 1.05rem; cursor: pointer; line-height: 1.5;">
                                Saya menjamin seluruh data instansi yang saya berikan adalah <strong>benar</strong> dan dapat dipertanggungjawabkan secara hukum. <span class="text-danger">*</span>
                            </label>
                        </div>

                        <div class="d-grid mt-2">
                            <button type="submit" class="btn btn-auth">
                                <i class="fas fa-check-circle me-2"></i> Selesaikan Pendaftaran
                            </button>
                        </div>

                        <div class="auth-footer">
                            <p class="text-muted">Sudah mendaftar sebelumnya? <br><a href="<?= site_url('welcome/login') ?>">Masuk ke Akun Anda di sini</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert script tidak perlu dipanggil ulang dari internet kalau sudah ada di footer. Namun sesuai permintaan tidak apa ada, hanya dihapus pemanggil duplicate -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logika untuk menampilkan hide/show password
        const toggleButtons = document.querySelectorAll('.toggle-btn');
        toggleButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetInput = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (targetInput.type === 'password') {
                    targetInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    targetInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // [Saran 2] Validasi format nomor HP real-time
        window.validatePhone = function(input) {
            const val   = input.value.trim();
            const regex = /^(08|\+62|628)[0-9]{7,12}$/;
            const fb    = document.getElementById('phone-feedback');

            if (val === '') {
                fb.innerHTML = '';
                input.classList.remove('is-valid', 'is-invalid');
                return;
            }

            if (regex.test(val) && val.length >= 10 && val.length <= 15) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                fb.innerHTML = '<span class="text-success"><i class="fas fa-check-circle me-1"></i>Format nomor HP valid.</span>';
            } else {
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');
                fb.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle me-1"></i>Format tidak valid. Gunakan: 08xxx, +62xxx, atau 628xxx (10–15 digit).</span>';
            }
        };

        // Validasi Form Kustom sebelum Submit
        const registerForm = document.querySelector('form');
        const submitBtn = document.querySelector('.btn-auth');

        submitBtn.addEventListener('click', function(e) {
            // Definisikan kolom-kolom wajib isi
            const fieldsMap = [
                { id: 'email', name: 'Alamat Email' },
                { id: 'username', name: 'Username (Nama Pengguna)' },
                { id: 'password', name: 'Kata Sandi' },
                { id: 'password_confirm', name: 'Konfirmasi Kata Sandi' },
                { id: 'nama_pemilik', name: 'Nama Lengkap Pemilik' },
                { id: 'jenis_usaha', name: 'Jenis Tempat Usaha' },
                { id: 'kontak_pemohon', name: 'Nomor Handphone' },
                { id: 'alamat_usaha', name: 'Alamat Lengkap Usaha' },
                { id: 'captcha', name: 'Jawaban CAPTCHA' }
            ];

            let fieldKosongArray = [];

            // Mengecek setiap field apakah kosong
            fieldsMap.forEach(function(field) {
                let el = document.getElementById(field.id);
                if (!el.value.trim()) {
                    fieldKosongArray.push(field.name);
                }
            });

            // Jika ada field kosong, tahan pengiriman dan munculkan pesan
            if (fieldKosongArray.length > 0) {
                e.preventDefault(); 
                
                // Menyusun pesan 
                let listTeks = fieldKosongArray.map(item => `<li>${item}</li>`).join('');
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Mohon Lengkapi Data Anda',
                    html: `Masih ada kolom yang belum diisi:<ul style="text-align: left; margin-top: 10px; padding-left: 20px;">${listTeks}</ul> <p style="margin-top:15px; font-size: 0.95rem;">Silakan lengkapi terlebih dahulu untuk memproses pendaftaran.</p>`,
                    confirmButtonColor: '#065f46',
                    confirmButtonText: 'Baik, Akan Saya Lengkapi'
                });
            } else {
                // Tambahan: cek jika password kurang dari syarat atau tak identik
                let pass = document.getElementById('password').value;
                let passConf = document.getElementById('password_confirm').value;

                if (pass.length < 8) {
                    e.preventDefault();
                    Swal.fire({ icon: 'error', title: 'Kata Sandi Terlalu Pendek', text: 'Mohon gunakan minimal 8 karakter demi keamanan akun Anda.', confirmButtonColor: '#065f46' });
                } else if (pass !== passConf) {
                    e.preventDefault();
                    Swal.fire({ icon: 'error', title: 'Kata Sandi Tidak Sama', text: 'Pastikan ulangi kata sandi persis seperti yang Anda masukkan sebelumnya.', confirmButtonColor: '#065f46' });
                } else {
                    // [Saran 2] Cek format nomor HP sebelum submit
                    const phoneVal   = document.getElementById('kontak_pemohon').value.trim();
                    const phoneRegex = /^(08|\+62|628)[0-9]{7,12}$/;
                    if (!phoneRegex.test(phoneVal) || phoneVal.length < 10 || phoneVal.length > 15) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Nomor HP Tidak Valid',
                            html: 'Format nomor HP tidak sesuai.<br>Gunakan: <b>08xxx</b>, <b>+62xxx</b>, atau <b>628xxx</b> (10–15 digit).',
                            confirmButtonColor: '#065f46'
                        });
                    } else {
                        // Cek kotak persetujuan
                        const terms = document.getElementById('termsCheck');
                        if (!terms.checked) {
                            e.preventDefault();
                            Swal.fire({
                                icon: 'warning',
                                title: 'Persetujuan Diperlukan',
                                text: 'Anda harus mencentang kotak pernyataan penjaminan data sebelum melanjutkan pendaftaran.',
                                confirmButtonColor: '#065f46'
                            });
                        }
                    }
                }
            }
        });
    });
</script>

<?php $this->load->view('layout/footer'); ?>
