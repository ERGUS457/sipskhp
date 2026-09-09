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
    </script>
</body>
</html>
