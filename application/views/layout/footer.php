<footer class="bg-dark text-white text-center py-4 mt-auto">
    <div class="container">
        <p class="mb-0">
            &copy; <?= date('Y') ?> UPT Metrologi Legal Kota Singkawang. All Rights Reserved.
        </p>
    </div>
</footer>

    <!-- Bootstrap JS Bundle 5.3.3 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 JS (Required for other views) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- jQuery (Required for other views) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        // Init global theme
        const savedThemeGlobal = localStorage.getItem('theme');
        if (savedThemeGlobal) {
            document.documentElement.setAttribute('data-theme', savedThemeGlobal);
        }

        // Global SweetAlert2 Konfirmasi Keluar (Logout)
        $(document).on('click', 'a[href*="logout"]', function(e) {
            e.preventDefault();
            const logoutUrl = $(this).attr('href');
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: 'Apakah Anda yakin ingin keluar dari akun Anda?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-sign-out-alt me-1"></i> Ya, Keluar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = logoutUrl;
                }
            });
        });
    </script>

    <!-- Global SweetAlert Flashdata -->
    <?php if ($this->session->flashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= addslashes((string)$this->session->flashdata('success')) ?>',
            timer: 2500,
            showConfirmButton: true,
            confirmButtonColor: '#065f46'
        });
    </script>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Perhatian / Terjadi Kesalahan',
            text: '<?= addslashes((string)$this->session->flashdata('error')) ?>',
            confirmButtonColor: '#ef4444'
        });
    </script>
    <?php endif; ?>
</body>
</html>
