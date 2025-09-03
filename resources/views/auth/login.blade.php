<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk | SPK Siswa Teladan - SDIT As Sunnah Cirebon</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="{{ asset('img/logo-yac.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo-yac.png') }}">
    
    <style>
        :root {
            --primary: #ff6b35;
            --primary-dark: #e55100;
            --primary-light: #ff8c5a;
            --secondary: #764ba2;
            --accent: #667eea;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #1f2937;
            --light: #f9fafb;
            --ring: rgba(255, 107, 53, 0.2);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Animated Shapes */
        .shape {
            position: fixed;
            opacity: 0.1;
            z-index: 0;
        }
        
        .shape-1 {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 50%;
            top: 10%;
            left: 5%;
            animation: float 8s ease-in-out infinite;
        }
        
        .shape-2 {
            width: 180px;
            height: 180px;
            background: white;
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            bottom: 10%;
            right: 5%;
            animation: float 10s ease-in-out infinite reverse;
        }
        
        .shape-3 {
            width: 100px;
            height: 100px;
            background: white;
            transform: rotate(45deg);
            top: 50%;
            right: 10%;
            animation: float 6s ease-in-out infinite;
        }
        
        .shape-4 {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            bottom: 20%;
            left: 10%;
            animation: float 7s ease-in-out infinite reverse;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.1; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 0.15; }
        }
        
        /* Navigation */
        .nav-container {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 12px;
            z-index: 100;
        }
        
        .nav-btn {
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            color: white;
            text-decoration: none;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        
        .nav-btn:hover {
            background: white;
            color: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .nav-btn i {
            font-size: 1.1rem;
        }
        
        /* Main Container */
        .login-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
            position: relative;
            z-index: 10;
        }
        
        /* Login Card */
        .login-card {
            width: 100%;
            max-width: 480px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 50px 40px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.5);
            position: relative;
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Decorative gradient border */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--secondary), var(--accent));
            animation: gradientMove 3s ease infinite;
        }
        
        @keyframes gradientMove {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        /* Logo Section */
        .logo-section {
            text-align: center;
            margin-bottom: 35px;
        }
        
        .logo-wrapper {
            display: inline-block;
            position: relative;
            margin-bottom: 20px;
        }
        
        .logo-box {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 15px 35px rgba(255, 107, 53, 0.2);
            border: 3px solid var(--primary);
            position: relative;
            overflow: hidden;
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .logo-box img {
            width: 80%;
            height: 80%;
            object-fit: contain;
        }
        
        /* Glow effect */
        .logo-box::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: radial-gradient(circle, var(--primary) 0%, transparent 70%);
            opacity: 0;
            animation: glow 2s ease-in-out infinite;
        }
        
        @keyframes glow {
            50% { opacity: 0.3; }
        }
        
        .app-title {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }
        
        .app-subtitle {
            color: #6b7280;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .school-name {
            color: var(--dark);
            font-weight: 700;
            font-size: 0.95rem;
            margin-top: 5px;
        }
        
        /* Welcome Message */
        .welcome-message {
            background: linear-gradient(135deg, #f6f8fb, #fff);
            border-left: 4px solid var(--primary);
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 25px;
        }
        
        .welcome-message h2 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }
        
        .welcome-message p {
            color: #6b7280;
            font-size: 0.95rem;
            margin: 0;
        }
        
        /* Alert Messages */
        .alert-custom {
            border-radius: 12px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
            animation: slideDown 0.5s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            border-left: 4px solid var(--danger);
            color: #991b1b;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border-left: 4px solid var(--success);
            color: #166534;
        }
        
        /* Form Styling */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-label i {
            color: var(--primary);
            font-size: 1.1rem;
        }
        
        .input-group {
            position: relative;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            padding-left: 45px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--ring);
            transform: translateY(-1px);
        }
        
        .form-control::placeholder {
            color: #9ca3af;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }
        
        .form-control:focus ~ .input-icon {
            color: var(--primary);
        }
        
        /* Password Field */
        .password-wrapper {
            position: relative;
        }
        
        .password-wrapper .form-control {
            padding-right: 50px;
        }
        
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            padding: 5px;
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }
        
        .toggle-password:hover {
            color: var(--primary);
        }
        
        /* Remember & Forgot */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-check-input {
            width: 20px;
            height: 20px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .form-check-input:checked {
            background: var(--primary);
            border-color: var(--primary);
        }
        
        .form-check-label {
            color: #6b7280;
            cursor: pointer;
            user-select: none;
        }
        
        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        /* Submit Button */
        .btn-login {
            width: 100%;
            padding: 14px 20px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(255, 107, 53, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login i {
            font-size: 1.2rem;
        }
        
        /* Loading State */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }
        
        .spinner {
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            display: none;
        }
        
        .btn-login.loading .spinner {
            display: inline-block;
        }
        
        .btn-login.loading .btn-text {
            display: none;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Divider */
        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }
        
        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e7eb;
        }
        
        .divider span {
            background: white;
            padding: 0 15px;
            color: #9ca3af;
            position: relative;
            font-size: 0.9rem;
        }
        
        /* Quick Access */
        .quick-access {
            text-align: center;
        }
        
        .quick-access-title {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        .demo-accounts {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        
        .demo-btn {
            padding: 10px 15px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .demo-btn:hover {
            background: #e5e7eb;
            transform: translateY(-2px);
        }
        
        .demo-role {
            font-weight: 700;
            color: var(--dark);
            font-size: 0.9rem;
        }
        
        .demo-email {
            color: #9ca3af;
            font-size: 0.8rem;
            margin-top: 2px;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            padding: 20px;
            color: white;
            font-size: 0.95rem;
            position: relative;
            z-index: 10;
        }
        
        .footer a {
            color: white;
            text-decoration: none;
            font-weight: 600;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .nav-container {
                top: 10px;
                right: 10px;
                gap: 8px;
            }
            
            .nav-btn {
                padding: 8px 15px;
                font-size: 0.85rem;
            }
            
            .nav-btn span {
                display: none;
            }
            
            .login-card {
                padding: 35px 25px;
                border-radius: 25px;
            }
            
            .logo-box {
                width: 100px;
                height: 100px;
            }
            
            .app-title {
                font-size: 1.5rem;
            }
            
            .demo-accounts {
                grid-template-columns: 1fr;
            }
        }
        
        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        .toast-custom {
            background: white;
            border-radius: 12px;
            padding: 15px 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideInRight 0.3s ease;
            min-width: 300px;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .toast-success {
            border-left: 4px solid var(--success);
        }
        
        .toast-error {
            border-left: 4px solid var(--danger);
        }
        
        .toast-icon {
            font-size: 1.5rem;
        }
        
        .toast-success .toast-icon {
            color: var(--success);
        }
        
        .toast-error .toast-icon {
            color: var(--danger);
        }
    </style>
</head>
<body>
    <!-- Animated Background Shapes -->
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
    <div class="shape shape-4"></div>
    
    <!-- Navigation -->
    <div class="nav-container">
        <a href="{{ url('/') }}" class="nav-btn">
            <i class="bi bi-house-door"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('hasil.publik') }}" class="nav-btn">
            <i class="bi bi-trophy"></i>
            <span>Hasil</span>
        </a>
        <a href="{{ route('informasi') }}" class="nav-btn">
            <i class="bi bi-info-circle"></i>
            <span>Info</span>
        </a>
    </div>
    
    <!-- Main Login Container -->
    <main class="login-container">
        <div class="login-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo-wrapper">
                    <div class="logo-box">
                        <img src="{{ asset('img/logo-yac.png') }}" alt="Logo SDIT As Sunnah">
                    </div>
                </div>
                <h1 class="app-title">SPK Siswa Teladan</h1>
                <p class="app-subtitle">Sistem Pendukung Keputusan</p>
                <p class="school-name">SDIT As Sunnah Cirebon</p>
            </div>
            
            <!-- Welcome Message -->
            <div class="welcome-message">
                <h2>Selamat Datang! 👋</h2>
                <p>Silakan masuk untuk mengakses dashboard</p>
            </div>
            
            <!-- Session Status -->
            @if (session('status'))
                <div class="alert-custom alert-success">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('status') }}
                </div>
            @endif
            
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert-custom alert-danger">
                    <div class="fw-bold mb-2">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Terjadi kesalahan:
                    </div>
                    <ul class="mb-0 ps-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                
                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope"></i>
                        Email
                    </label>
                    <div class="input-group">
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control" 
                               value="{{ old('email') }}"
                               placeholder="nama@assunnah.sch.id"
                               required 
                               autofocus>
                        <i class="bi bi-envelope input-icon"></i>
                    </div>
                </div>
                
                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock"></i>
                        Kata Sandi
                    </label>
                    <div class="input-group password-wrapper">
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="form-control" 
                               placeholder="Masukkan kata sandi"
                               required>
                        <i class="bi bi-lock input-icon"></i>
                        <button type="button" class="toggle-password" aria-label="Toggle password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Remember & Forgot -->
                <div class="form-options">
                    <div class="form-check">
                        <input type="checkbox" 
                               id="remember" 
                               name="remember" 
                               class="form-check-input">
                        <label for="remember" class="form-check-label">
                            Ingat saya
                        </label>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn-login" id="loginBtn">
                    <span class="btn-text">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Masuk
                    </span>
                    <div class="spinner"></div>
                </button>
            </form>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <p>
            &copy; {{ date('Y') }} SDIT As Sunnah Cirebon &bull; 
            Powered by <a href="https://laravel.com" target="_blank">Laravel {{ Illuminate\Foundation\Application::VERSION }}</a>
        </p>
    </footer>
    
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Password Visibility
        const togglePassword = document.querySelector('.toggle-password');
        const passwordInput = document.getElementById('password');
        
        togglePassword?.addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            
            const icon = this.querySelector('i');
            icon.className = type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
        });
        
        // Form Submit Loading State
        const loginForm = document.getElementById('loginForm');
        const loginBtn = document.getElementById('loginBtn');
        
        loginForm?.addEventListener('submit', function(e) {
            // Add loading state
            loginBtn.classList.add('loading');
            loginBtn.disabled = true;
            
            // Show toast
            showToast('Memproses login...', 'info');
        });
        
        // Fill Demo Credentials
        function fillDemo(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            
            // Animate the fields
            document.getElementById('email').classList.add('animate__animated', 'animate__pulse');
            document.getElementById('password').classList.add('animate__animated', 'animate__pulse');
            
            setTimeout(() => {
                document.getElementById('email').classList.remove('animate__animated', 'animate__pulse');
                document.getElementById('password').classList.remove('animate__animated', 'animate__pulse');
            }, 1000);
            
            showToast('Kredensial demo telah diisi', 'success');
        }
        
        // Toast Notification
        function showToast(message, type = 'info') {
            const toastContainer = document.getElementById('toastContainer');
            
            const toast = document.createElement('div');
            toast.className = `toast-custom toast-${type}`;
            
            const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle';
            
            toast.innerHTML = `
                <i class="bi bi-${icon} toast-icon"></i>
                <div>
                    <strong>${type === 'success' ? 'Berhasil' : type === 'error' ? 'Error' : 'Info'}</strong>
                    <div>${message}</div>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
        
        // Add animation to form inputs on focus
        const formInputs = document.querySelectorAll('.form-control');
        formInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + D for demo admin
            if (e.altKey && e.key === 'd') {
                fillDemo('admin@assunnah.sch.id', 'admin123');
            }
            // Alt + W for demo wali
            if (e.altKey && e.key === 'w') {
                fillDemo('wali6a@assunnah.sch.id', 'wali123');
            }
        });
        
        // Add animation class for slide out
        const style = document.createElement('style');
        style.innerHTML = `
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
            
            @keyframes animate__pulse {
                from { transform: scale3d(1, 1, 1); }
                50% { transform: scale3d(1.05, 1.05, 1.05); }
                to { transform: scale3d(1, 1, 1); }
            }
            
            .animate__animated {
                animation-duration: 1s;
                animation-fill-mode: both;
            }
            
            .animate__pulse {
                animation-name: animate__pulse;
            }
        `;
        document.head.appendChild(style);
        
        // Welcome animation on page load
        window.addEventListener('load', function() {
            const loginCard = document.querySelector('.login-card');
            loginCard.style.animation = 'slideUp 0.6s ease-out';
            
            // Show welcome toast after a delay
            setTimeout(() => {
                const hour = new Date().getHours();
                let greeting = 'Selamat datang';
                
                if (hour < 12) greeting = 'Selamat pagi';
                else if (hour < 15) greeting = 'Selamat siang';
                else if (hour < 18) greeting = 'Selamat sore';
                else greeting = 'Selamat malam';
                
                showToast(`${greeting}! Silakan login untuk melanjutkan`, 'info');
            }, 1000);
        });
        
        // Check if there's a session message
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
    </script>
</body>
</html>