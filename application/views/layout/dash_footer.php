</main>
    </div>

                <!-- DataTables JS -->
                    <!-- Select2 JS -->
        
    <script>
        // Konfigurasi Global DataTables (Bahasa Indonesia)
        $.extend(true, $.fn.dataTable.defaults, { scrollX: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 s/d 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang ditemukan",
                emptyTable: "Tidak ada data yang tersedia pada tabel ini",
                paginate: {
                    first: "Awal",
                    previous: "Sebelumnya",
                    next: "Selanjutnya",
                    last: "Akhir"
                }
            }
        });

        // Global SweetAlert2 Konfirmasi Keluar (Logout)
        $(document).on('click', '.logout-btn, a[href*="logout"]', function(e) {
            e.preventDefault();
            const logoutUrl = $(this).attr('href');
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: 'Apakah Anda yakin ingin keluar dari sistem SIAP SKHP TERA?',
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

    <!-- Custom View Scripts -->
    
</body>
</html>
