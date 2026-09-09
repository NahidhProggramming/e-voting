<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Voting OSIM - Darul Lughah Wal Karomah')</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS Styling -->
    <style>
        :root {
            --ev-primary: #0f5132;
            --ev-primary-hover: #146c43;
            --ev-primary-light: #d1e7dd;
            --ev-secondary: #f8f9fa;
            --ev-dark: #212529;
            --ev-muted: #6c757d;
            --ev-bg: #f4f6f9;
            --ev-font: 'Plus Jakarta Sans', sans-serif;
            --ev-card-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            --ev-card-shadow-hover: 0 15px 40px rgba(15, 81, 50, 0.12);
            --ev-border-radius: 16px;
        }

        body {
            font-family: var(--ev-font);
            background-color: var(--ev-bg);
            color: var(--ev-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        .navbar-custom {
            background-color: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .navbar-custom .navbar-brand {
            font-weight: 700;
            color: var(--ev-primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary-custom {
            background-color: var(--ev-primary);
            border-color: var(--ev-primary);
            color: #ffffff;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover, .btn-primary-custom:focus {
            background-color: var(--ev-primary-hover);
            border-color: var(--ev-primary-hover);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(15, 81, 50, 0.2);
        }

        .btn-secondary-custom {
            background-color: #ffffff;
            border-color: rgba(0, 0, 0, 0.1);
            color: var(--ev-dark);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background-color: var(--ev-secondary);
            border-color: rgba(0, 0, 0, 0.15);
            color: var(--ev-dark);
        }

        .card-custom {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: var(--ev-border-radius);
            box-shadow: var(--ev-card-shadow);
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            overflow: hidden;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: var(--ev-card-shadow-hover);
            border-color: var(--ev-primary-light);
        }

        .text-primary-custom {
            color: var(--ev-primary) !important;
        }

        .bg-primary-custom {
            background-color: var(--ev-primary) !important;
        }

        .badge-number {
            background-color: var(--ev-primary);
            color: #ffffff;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.25rem;
            box-shadow: 0 4px 10px rgba(15, 81, 50, 0.3);
        }

        footer {
            margin-top: auto;
            background-color: #ffffff;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding: 20px 0;
            color: var(--ev-muted);
            font-size: 0.9rem;
            text-align: center;
        }

        /* Form styling */
        .form-control-custom {
            border-radius: 10px;
            padding: 12px 16px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .form-control-custom:focus {
            border-color: var(--ev-primary);
            box-shadow: 0 0 0 0.25rem rgba(15, 81, 50, 0.15);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Madrasah Diniyah Darul Lughah Wal Karomah. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
