    </div><!-- /.p-content -->

<footer class="p-footer">
    &copy; <?= date('Y') ?> UPT Metrologi Legal Kota Singkawang. All Rights Reserved.
</footer>

<script>
    $(document).on('click', '.btn-logout, a[href*="logout"]', function(e) {
        e.preventDefault();
        const logoutUrl = $(this).attr('href');
        Swal.fire({
            title: 'Konfirmasi Keluar',
            text: 'Apakah Anda yakin ingin keluar dari Portal Petugas?',
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
