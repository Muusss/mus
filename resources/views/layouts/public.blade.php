<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SPK Siswa Teladan') - SDIT As Sunnah</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo-yac.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo-yac.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <style>
        :root {
            --primary-color: #ff6b35;
            --primary-dark: #e55100;
            --primary-light: #ff8c5a;
            --secondary-color: #2c2c2c;
            --dark-color: #1a1a1a;
            --gold: #ffd700;
            --silver: #c0c0c0;
            --bronze: #cd7f32;
        }
        
        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding-top: 80px;
        }
        
        /* Animated Background */
        .bg-animation {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -2;
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Floating shapes */
        .shape {
            position: fixed;
            opacity: 0.08;
            z-index: -1;
        }
        
        .shape-1 {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            top: 10%;
            left: 10%;
            animation: float 6s ease-in-out infinite;
        }
        
        .shape-2 {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            bottom: 10%;
            right: 10%;
            animation: float 8s ease-in-out infinite reverse;
        }
        
        .shape-3 {
            width: 60px;
            height: 60px;
            background: white;
            transform: rotate(45deg);
            top: 50%;
            right: 5%;
            animation: float 7s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        /* Navigation Bar */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 10px 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 15px;
            font-weight: 700;
            color: var(--primary-color) !important;
            text-decoration: none;
        }
        
        .navbar-brand:hover {
            color: var(--primary-dark) !important;
        }
        
        .navbar-logo {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 10px;
            padding: 8px;
            border: 2px solid var(--primary-color);
            transition: all 0.3s;
        }
        
        .navbar-brand:hover .navbar-logo {
            transform: rotate(-5deg) scale(1.05);
        }
        
        .navbar-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }
        
        .brand-title {
            font-size: 1.1rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .brand-subtitle {
            font-size: 0.85rem;
            color: #666;
            font-weight: 600;
        }
        
        /* Navigation Menu */
        .nav-menu {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .nav-link-custom {
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--dark-color);
            border: 2px solid transparent;
        }
        
        .nav-link-custom:hover {
            background: #f8f9fa;
            color: var(--primary-color);
            transform: translateY(-2px);
        }
        
        .nav-link-custom.active {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
        }
        
        .nav-link-custom i {
            font-size: 1.1rem;
        }
        
        .btn-login {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        
        /* Mobile Menu Toggle */
        .navbar-toggler {
            border: none;
            padding: 4px 8px;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 107, 53, 0.8)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* Mobile Menu */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: white;
                margin-top: 15px;
                padding: 20px;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }
            
            .nav-menu {
                flex-direction: column;
                width: 100%;
            }
            
            .nav-link-custom {
                width: 100%;
                justify-content: center;
                margin-bottom: 10px;
            }
            
            .brand-text {
                display: none;
            }
        }
        
        /* Page Header */
        .page-header {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }
        
        .page-subtitle {
            color: #666;
            font-size: 1.1rem;
        }
        
        .badge-periode {
            background: var(--primary-color);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            display: inline-block;
            margin-top: 15px;
        }
        
        /* Content */
        .main-content {
            position: relative;
            z-index: 1;
            min-height: calc(100vh - 80px);
        }
        
        /* Footer */
        .footer-custom {
            background: rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            text-align: center;
            padding: 30px 20px;
            margin-top: 50px;
        }
        
        .footer-custom a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }
        
        .footer-custom a:hover {
            opacity: 0.8;
        }
        
        /* Alert Messages */
        .alert-custom {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
            animation: slideDown 0.5s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-success {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            color: white;
        }
        
        .alert-warning {
            background: linear-gradient(135deg, #f2994a, #f2c94c);
            color: white;
        }
        
        .alert-error,
        .alert-danger {
            background: linear-gradient(135deg, #eb3349, #f45c43);
            color: white;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-title { font-size: 1.8rem; }
            .navbar-custom { padding: 8px 0; }
            .navbar-logo { width: 40px; height: 40px; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation"></div>
    
    <!-- Floating Shapes -->
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ url('/') }}">
                <div class="navbar-logo">
                    <img src="{{ asset('img/logo-yac.png') }}" alt="Logo YAC">
                </div>
                <div class="brand-text">
                    <span class="brand-title">SPK Siswa Teladan</span>
                    <span class="brand-subtitle">SDIT As Sunnah Cirebon</span>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="nav-menu ms-auto">
                    <a href="{{ url('/') }}" class="nav-link-custom {{ request()->is('/') ? 'active' : '' }}">
                        <i class="bi bi-house"></i>
                        <span>Beranda</span>
                    </a>
                    
                    <a href="{{ route('informasi') }}" class="nav-link-custom {{ request()->routeIs('informasi') ? 'active' : '' }}">
                        <i class="bi bi-info-circle"></i>
                        <span>Informasi</span>
                    </a>
                    
                    <a href="{{ route('hasil.publik') }}" class="nav-link-custom {{ request()->routeIs('hasil.publik') ? 'active' : '' }}">
                        <i class="bi bi-trophy"></i>
                        <span>Peringkat</span>
                    </a>
                    
                    <a href="{{ route('cek.nilai') }}" class="nav-link-custom {{ request()->routeIs('cek.nilai*') ? 'active' : '' }}">
                        <i class="bi bi-search"></i>
                        <span>Cek Nilai</span>
                    </a>
                    
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-login">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-login">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid px-4">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-custom alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                    <i class="bi bi-x-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                    <i class="bi bi-x-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
        
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="footer-custom">
        <div class="container">
            <p class="mb-2">
                <strong>SDIT As Sunnah Cirebon</strong><br>
                Jl. Pendidikan No. 123, Cirebon
            </p>
            <p class="mb-0">
                &copy; {{ date('Y') }} SPK Siswa Teladan | Developed with Laravel {{ Illuminate\Foundation\Application::VERSION }}
            </p>
        </div>
    </footer>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert-custom');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
    
    @yield('scripts')
</body>
</html>