<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPK Siswa Teladan - SDIT As Sunnah Cirebon</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="{{ asset('img/logo-yac.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo-yac.png') }}">
    <style>
        :root {
            --primary-color: #ff6b35;
            --primary-dark: #e55100;
            --primary-light: #ff8c5a;
            --secondary-color: #2c2c2c;
            --dark-color: #1a1a1a;
            --accent-green: #1cc88a;
            --logo-size: 150px; 
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }
        
        /* Animated Background */
        .bg-animation {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
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
            position: absolute;
            opacity: 0.1;
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
        
        /* Navigation */
        .nav-container {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 1000;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        
        .nav-btn {
            padding: 12px 30px;
            border: 2px solid white;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .nav-btn:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        
        .nav-btn.primary {
            background: rgba(255, 107, 53, 0.9);
            border-color: var(--primary-color);
        }
        
        .nav-btn.primary:hover {
            background: white;
            color: var(--primary-color);
        }
        
        .nav-btn i {
            font-size: 18px;
        }
        
        /* Main Content */
        .main-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            z-index: 1;
        }
        
        .content-box {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 60px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 800px;
            width: 100%;
            animation: slideUp 0.8s ease-out;
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
        
        .logo-container {
            margin-bottom: 30px;
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .logo{
            width: var(--logo-size);
            height: var(--logo-size);
            border-radius: 24px;
            padding: 16px;
            background: #fff;
            border: 3px solid var(--primary-color);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0,0,0,.1);
        }
        
        .logo img{
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: transparent;
        }
        
        .logo-fallback {
            font-size: 60px;
            color: var(--primary-color);
        }
        
        .title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 15px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .subtitle {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .school-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 20px;
        }
        
        .description {
            color: #777;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 40px;
            margin-bottom: 40px;
        }
        
        .action-card {
            background: linear-gradient(135deg, rgba(255,107,53,0.1), rgba(255,107,53,0.05));
            border-radius: 20px;
            padding: 25px;
            text-decoration: none;
            color: var(--dark-color);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }
        
        .action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(255,107,53,0.2);
            border-color: var(--primary-color);
        }
        
        .action-card:hover::before {
            transform: scaleX(1);
        }
        
        .action-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
        }
        
        .action-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--dark-color);
        }
        
        .action-desc {
            font-size: 0.9rem;
            color: #666;
            line-height: 1.4;
        }
        
        /* Features */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .feature {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 15px;
            transition: transform 0.3s ease;
        }
        
        .feature:hover {
            transform: translateY(-5px);
            background: white;
        }
        
        .feature i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .feature h4 {
            font-size: 1rem;
            color: var(--dark-color);
            margin-bottom: 5px;
        }
        
        .feature p {
            font-size: 0.85rem;
            color: #666;
        }
        
        /* Footer */
        .footer {
            background: rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: auto;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            :root{ --logo-size: 120px; }
            
            .nav-container {
                top: 10px;
                right: 10px;
                left: 10px;
                flex-direction: column;
                gap: 10px;
            }
            
            .nav-btn {
                padding: 10px 20px;
                font-size: 0.9rem;
                width: 100%;
                justify-content: center;
            }
            
            .content-box {
                padding: 40px 30px;
            }
            
            .title {
                font-size: 1.8rem;
            }
            
            .school-name {
                font-size: 1.4rem;
            }
            
            .logo {
                width: 120px;
                height: 120px;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
            
            .features {
                grid-template-columns: 1fr;
            }
        }
        
        /* Glow effect */
        .glow {
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes glow {
            from { box-shadow: 0 0 10px -10px var(--primary-color); }
            to { box-shadow: 0 0 20px 10px var(--primary-color); }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animation"></div>
    
    <!-- Floating Shapes -->
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
    
    <!-- Navigation -->
    <div class="nav-container">
        <a href="{{ route('informasi') }}" class="nav-btn">
            <i class="bi bi-info-circle"></i>
            <span>Informasi Sistem</span>
        </a>
        <a href="{{ route('hasil.publik') }}" class="nav-btn">
            <i class="bi bi-trophy"></i>
            <span>Hasil Peringkat</span>
        </a>
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="nav-btn">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="nav-btn">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Login</span>
                </a>
            @endauth
        @endif
    </div>
    
    <!-- Main Content -->
    <div class="main-container">
        <div class="content-box">
            <!-- Logo -->
            <div class="logo glow">
                <img src="{{ asset('img/logo-yac.png') }}" alt="Logo YAC">
            </div>
            
            <!-- Title -->
            <h1 class="title">SPK PENILAIAN SISWA TELADAN</h1>
            <h2 class="school-name">SDIT AS SUNNAH CIREBON</h2>
            
            <!-- Description -->
            <p class="description">
                Sistem penilaian siswa teladan menggunakan metode ROC (Rank Order Centroid) 
                dan SMART (Simple Multi Attribute Rating Technique) untuk menentukan siswa 
                berprestasi berdasarkan kriteria akademik, akhlak, dan keaktifan.
            </p>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <a href="{{ route('informasi') }}" class="action-card">
                    <div class="action-icon">
                        <i class="bi bi-book"></i>
                    </div>
                    <div class="action-title">Panduan Sistem</div>
                    <div class="action-desc">Pelajari cara kerja sistem penilaian</div>
                </a>
                
                <a href="{{ route('hasil.publik') }}" class="action-card">
                    <div class="action-icon">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <div class="action-title">Lihat Peringkat</div>
                    <div class="action-desc">Hasil peringkat siswa teladan</div>
                </a>
            </div>
            
            <!-- Features -->
            <div class="features">
                <div class="feature">
                    <i class="bi bi-calculator"></i>
                    <h4>ROC + SMART</h4>
                    <p>Metode akurat</p>
                </div>
                <div class="feature">
                    <i class="bi bi-graph-up"></i>
                    <h4>6 Kriteria</h4>
                    <p>Penilaian komprehensif</p>
                </div>
                <div class="feature">
                    <i class="bi bi-shield-check"></i>
                    <h4>Transparan</h4>
                    <p>Objektif & akuntabel</p>
                </div>
                <div class="feature">
                    <i class="bi bi-people"></i>
                    <h4>4 Kelas</h4>
                    <p>Kelas 6A-6D</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>&copy; {{ date('Y') }} SDIT As Sunnah Cirebon | Developed with Laravel {{ Illuminate\Foundation\Application::VERSION }}</p>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>