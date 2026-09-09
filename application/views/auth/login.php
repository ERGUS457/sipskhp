<?php $this->load->view('layout/header', ['title' => 'Halaman Masuk - E-TERA SKHP']); ?>
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
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); /* Memberi efek mengambang yang mewah */
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
        padding: 40px 35px;
        background-color: transparent;
    }
    .form-label {
        font-weight: 600;
        color: var(--text-main);
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
        padding: 14px;
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
        <div class="col-md-7 col-lg-5">
            <div class="card auth-card">
                <!-- Header Card dengan Warna Biru Resmi -->
                <div class="auth-header">
                    <i class="fas fa-user-circle"></i>
                    <h2 class="auth-title">Halaman Masuk</h2>
                    <p class="auth-subtitle">Sistem Penerbitan SKHP Kota Singkawang</p>
                </div>

                <div class="auth-body">
                    <!-- Menampilkan pesan error (jika ada) -->
                    <?php if ($this->session->flashdata('error')) : ?>
                        <div class="alert alert-danger d-flex align-items-center mb-4" role="alert" style="font-size: 1rem; border-radius: 8px;">
                            <i class="fas fa-exclamation-circle fs-4 me-3 flex-shrink-0"></i>
                            <div><?= $this->session->flashdata('error') ?></div>
                        </div>
                    <?php endif; ?>

                    <!-- Menampilkan pesan info (jika ada) -->
                    <?php if ($this->session->flashdata('info')) : ?>
                        <div class="alert alert-info d-flex align-items-center mb-4" role="alert" style="font-size: 1rem; border-radius: 8px;">
                            <i class="fas fa-info-circle fs-4 me-3 flex-shrink-0"></i>
                            <div><?= $this->session->flashdata('info') ?></div>
                        </div>
                    <?php endif; ?>


                    <form action="<?= site_url('login') ?>" method="post">
                        
                        <div class="mb-4">
                            <label for="login" class="form-label">Username atau Email Anda <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope text-muted"></i></span>
                                <input type="text" class="form-control form-control-lg" id="login" name="login" placeholder="Masukkan Username/Email" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="password" class="form-label mb-0">Kata Sandi (Password) <span class="text-danger">*</span></label>
                                <a href="javascript:void(0);" onclick="fiturLupaSandi()" class="text-decoration-none" style="font-size: 0.95rem; font-weight: 600;">Lupa Sandi?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Masukkan Kata Sandi" required>
                                <button class="btn btn-outline-secondary input-group-text" type="button" id="togglePassword" style="border-radius: 0 8px 8px 0;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-auth" id="btnLogin">
                                <i class="fas fa-sign-in-alt me-2"></i> Masuk Sekarang
                            </button>
                        </div>

                        <div class="auth-footer">
                            <p class="text-muted">Belum memiliki akun e-Tera? <br> <a href="<?= site_url('register') ?>">Daftar Baru di sini</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>

<!-- Script untuk SweetAlert & Toggle Password -->
<script>
    // Cek apakah ada flashdata 'success' dari session (untuk registrasi atau logout)
    <?php if ($this->session->flashdata('success')) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: <?= json_encode($this->session->flashdata('success')) ?>,
            showConfirmButton: false,
            timer: 2500
        });
    <?php endif; ?>

    // Cek apakah ada flashdata 'error' dari session (gagal login dengan pesan jelas)
    <?php if ($this->session->flashdata('error')) : ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal Masuk',
            html: <?= json_encode($this->session->flashdata('error')) ?>,
            confirmButtonColor: '#ef4444',
            confirmButtonText: '<i class="fas fa-redo me-1"></i> Coba Lagi'
        });
    <?php endif; ?>

    // Cek apakah ada flashdata 'info' dari session
    <?php if ($this->session->flashdata('info')) : ?>
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            html: <?= json_encode($this->session->flashdata('info')) ?>,
            confirmButtonColor: '#0ea5e9',
            confirmButtonText: 'Mengerti'
        });
    <?php endif; ?>

    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');
        const loginInput = document.querySelector('#login');
        const btnLogin = document.querySelector('#btnLogin');
        const icon = togglePassword ? togglePassword.querySelector('i') : null;

        // Fitur Hide/Show Password
        if (togglePassword && passwordInput && icon) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        }

        // Validasi Form Kustom dengan SweetAlert
        if (btnLogin && loginInput && passwordInput) {
            btnLogin.addEventListener('click', function(e) {
                const loginVal = loginInput.value.trim();
                const passVal = passwordInput.value.trim();

                if (loginVal === '' || passVal === '') {
                    e.preventDefault(); // Hentikan pengiriman form default
                    
                    let title = 'Kolom Belum Lengkap';
                    let pesan = 'Harap lengkapi Username/Email dan Kata Sandi Anda!';

                    if (loginVal === '' && passVal === '') {
                        title = 'Data Masuk Belum Diisi';
                        pesan = 'Silakan masukkan <b>Username atau Email</b> serta <b>Kata Sandi</b> Anda.';
                    } else if (loginVal === '') {
                        title = 'Username / Email Kosong';
                        pesan = 'Silakan masukkan <b>Username atau Alamat Email</b> Anda terlebih dahulu.';
                        loginInput.focus();
                    } else if (passVal === '') {
                        title = 'Kata Sandi Kosong';
                        pesan = 'Silakan masukkan <b>Kata Sandi (Password)</b> Anda terlebih dahulu.';
                        passwordInput.focus();
                    }

                    Swal.fire({
                        icon: 'warning',
                        title: title,
                        html: pesan,
                        confirmButtonColor: '#065f46',
                        confirmButtonText: 'Baik, Saya Lengkapi'
                    });
                }
            });
        }
    });

    // Fitur SweetAlert Lupa Kata Sandi
    function fiturLupaSandi() {
        Swal.fire({
            title: 'Lupa Kata Sandi?',
            html: '<p class="text-muted mb-3">Masukkan alamat Email Anda yang terdaftar pada akun E-Tera. Tautan pemulihan akan kami kirimkan ke email tersebut.</p>',
            input: 'email',
            inputPlaceholder: 'contoh@email.com',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-paper-plane me-1"></i> Kirim Akses',
            cancelButtonText: 'Batal',
            validationMessage: 'Format Email tidak valid!',
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                // Simulasi Loading Kirim Email
                Swal.fire({
                    title: 'Mengirim Permintaan...',
                    html: 'Sedang menghubungi server.',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                    },
                    timer: 1500
                }).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terkirim!',
                        html: `Instruksi pemulihan sandi telah dikirim ke <strong>${result.value}</strong>.<br><br><i>Silakan cek Kotak Masuk atau folder Spam Anda.</i>`,
                        confirmButtonColor: '#10b981'
                    });
                });
            }
        });
    }
</script>
