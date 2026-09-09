<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Sistem Penerbitan SKHP' ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome 6.5.1 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        /* Variabel Global Tema (Hijau Zamrud & Dark Mode) */
        :root {
            --color-primary: #065f46; 
            --color-secondary: #10b981; 
            --color-bg: #ffffff;
            --color-bg-alt: #f8f9fa;
            --color-bg-grad: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            --color-text: #333333;
            --color-text-muted: #6c757d;
            --color-card: #ffffff;
            --color-border: #ced4da;

            /* Khusus Navbar */
            --nav-bg: #ffffff;
            --nav-text: #495057;
            --nav-hover-bg: #f0fdf4;
            --nav-border: #e0e0e0;
        }

        [data-theme="dark"] {
            --color-primary: #34d399; 
            --color-secondary: #059669; 
            --color-bg: #111827; 
            --color-bg-alt: #1f2937;
            --color-bg-grad: linear-gradient(135deg, #111827 0%, #1f2937 100%);
            --color-text: #f9fafb;
            --color-text-muted: #d1d5db;
            --color-card: #1f2937;
            --color-border: #4b5563;

            /* Khusus Navbar */
            --nav-bg: #1f2937;
            --nav-text: #d1d5db;
            --nav-hover-bg: #374151;
            --nav-border: #4b5563;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--color-bg-alt);
            color: var(--color-text);
            padding-top: 80px; /* Memberi ruang untuk navbar fixed-top */
            transition: background-color 0.3s ease, color 0.3s ease;
            zoom: 0.8;
        }
        /* Responsive Font Size */
        html {
            font-size: 16px; /* Default untuk layar besar */
        }
        @media (max-width: 992px) { /* Tablet dan di bawahnya */
            html { font-size: 15px; }
        }
        @media (max-width: 768px) { /* Ponsel */
            html { font-size: 14px; }
        }
        /* Custom styles for Offcanvas Navbar */
        @media (max-width: 991.98px) {
            .offcanvas-body .navbar-nav .nav-item {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            .offcanvas-body .navbar-nav .nav-link {
                padding: 1rem;
                text-align: center;
                font-size: 1.1rem;
                border-radius: 0.5rem;
                transition: background-color 0.2s ease-in-out;
            }
            .offcanvas-body .navbar-nav .nav-link:hover,
            .offcanvas-body .navbar-nav .nav-link:focus {
                background-color: #e9ecef;
            }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
