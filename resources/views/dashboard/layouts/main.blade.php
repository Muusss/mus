<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SPK SMART | {{ $title ?? 'Dashboard' }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 80px;
            --primary-color: #4e73df;
            --primary-dark: #2e59d9;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #2c3e50;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--light-color);
            color: #333;
            min-height: 100vh;
        }

        /* Layout Container */
        .app-container {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* Mobile Sidebar Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* Mobile Sidebar Behavior */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-wrapper {
                margin-left: 0 !important;
            }
        }

        .sidebar .logo-section {
            padding: 1.5rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            min-height: 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .logo-container .logo-img{
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 8px;
            display: block;
        }

        /* Perbesar logo saat sidebar collapse */
        .sidebar.collapsed .logo-section { min-height: 88px; }
        .sidebar.collapsed .logo-container { width: 68px; height: 68px; }

        .sidebar .logo-section .logo-container {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            padding: 5px;
        }

        .sidebar .logo-section .logo-text {
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            margin: 0;
            transition: opacity 0.3s;
            white-space: nowrap;
        }

        .sidebar.collapsed .logo-section .logo-text {
            display: none;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-menu .menu-header {
            color: rgba(255,255,255,0.5);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 0 1.5rem;
            margin: 1rem 0 0.5rem;
            transition: opacity 0.3s;
        }

        .sidebar.collapsed .menu-header {
            display: none;
        }

        .sidebar-menu .nav-item {
            margin: 0.25rem 0;
        }

        .sidebar-menu .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            text-decoration: none;
            position: relative;
        }

        .sidebar-menu .nav-link:hover {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-left-color: white;
        }

        .sidebar-menu .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.15);
            border-left-color: white;
        }

        .sidebar-menu .nav-link i {
            font-size: 1.1rem;
            width: 30px;
            text-align: center;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .sidebar-menu .nav-link span {
            white-space: nowrap;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }

        /* Tooltip for collapsed sidebar */
        .sidebar.collapsed .nav-link:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 100%;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            white-space: nowrap;
            font-size: 0.875rem;
            margin-left: 10px;
            z-index: 1000;
        }

        /* Main Content Wrapper */
        .main-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-wrapper.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Top Bar */
        .topbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .topbar .toggle-sidebar {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--secondary-color);
            cursor: pointer;
            transition: color 0.3s;
            padding: 0.5rem;
        }

        .topbar .toggle-sidebar:hover {
            color: var(--primary-color);
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: inline-block;
            }
            
            .desktop-menu-toggle {
                display: none;
            }
        }

        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .topbar .user-info .user-name {
            display: none;
        }

        @media (min-width: 576px) {
            .topbar .user-info .user-name {
                display: inline;
            }
        }

        .topbar .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Content Area */
        .content-area {
            padding: 1.5rem;
            flex: 1;
        }

        @media (max-width: 576px) {
            .content-area {
                padding: 1rem;
            }
        }

        /* Cards Responsive */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            border-left: 4px solid var(--primary-color);
            margin-bottom: 1rem;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        /* Tables Responsive */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .custom-table {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        @media (max-width: 576px) {
            .custom-table {
                padding: 1rem;
                border-radius: 10px;
            }
        }

        /* DataTables Responsive Override */
        table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control:before {
            background-color: var(--primary-color);
        }

        /* Footer Responsive */
        .footer {
            background: white;
            padding: 1.5rem 2rem;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
            margin-top: auto;
        }

        @media (max-width: 768px) {
            .footer {
                padding: 1rem;
            }
            
            .footer-content {
                flex-direction: column;
                text-align: center;
            }
            
            .footer-left,
            .footer-right {
                width: 100%;
                margin-bottom: 1rem;
            }
            
            .footer-links {
                flex-wrap: wrap;
                justify-content: center;
            }
        }

        /* Utility Classes */
        .text-truncate-mobile {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        @media (min-width: 576px) {
            .text-truncate-mobile {
                max-width: none;
            }
        }

        /* Fix Bootstrap Modal on Mobile */
        .modal-dialog {
            margin: 1rem;
        }

        @media (max-width: 576px) {
            .modal-dialog {
                margin: 0.5rem;
            }
            
            .modal-content {
                border-radius: 10px;
            }
        }

        /* Loading Spinner */
        .spinner-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 200px;
        }
    </style>
    @yield('css')
</head>
<body>
    <div class="app-container">
        <!-- Sidebar Overlay for Mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
        
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="logo-section">
                <div class="logo-container">
                    <img src="{{ asset('logo-yac.png') }}" alt="Logo YAC" class="logo-img">
                </div>
                <div class="logo-text">SPK SDIT As Sunnah</div>
            </div>
            
            <div class="sidebar-menu">
                <div class="menu-header">Menu Utama</div>
                
                <div class="nav-item">
                    <a href="{{ route('dashboard') }}" 
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       data-tooltip="Dashboard">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
                
                <div class="menu-header">Data Master</div>
                
                @if(auth()->user()->role === 'admin')
                <div class="nav-item">
                    <a href="{{ route('users') }}" class="nav-link {{ request()->routeIs('users*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>Data User</span>
                    </a>
                </div>
                @endif

                <div class="nav-item">
                    <a href="{{ route('alternatif') }}" 
                       class="nav-link {{ request()->routeIs('alternatif*') ? 'active' : '' }}"
                       data-tooltip="Data Siswa">
                        <i class="bi bi-people"></i>
                        <span>Data Siswa</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('kriteria') }}" 
                       class="nav-link {{ request()->routeIs('kriteria*') ? 'active' : '' }}"
                       data-tooltip="Kriteria">
                        <i class="bi bi-list-check"></i>
                        <span>Kriteria</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="{{ route('subkriteria') }}" 
                       class="nav-link {{ request()->routeIs('subkriteria*') ? 'active' : '' }}"
                       data-tooltip="Sub Kriteria">
                        <i class="bi bi-diagram-3"></i>
                        <span>Sub Kriteria</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('periode') }}" 
                       class="nav-link {{ request()->routeIs('periode*') ? 'active' : '' }}"
                       data-tooltip="Periode">
                        <i class="bi bi-calendar-week"></i>
                        <span>Periode Semester</span>
                    </a>
                </div>
                
                <div class="menu-header">SMART</div>
                
                <div class="nav-item">
                    <a href="{{ route('penilaian') }}" 
                       class="nav-link {{ request()->routeIs('penilaian*') ? 'active' : '' }}"
                       data-tooltip="Penilaian">
                        <i class="bi bi-pencil-square"></i>
                        <span>Penilaian</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="{{ route('perhitungan') }}" 
                       class="nav-link {{ request()->routeIs('perhitungan*') ? 'active' : '' }}"
                       data-tooltip="Perhitungan">
                        <i class="bi bi-calculator"></i>
                        <span>Perhitungan</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="{{ route('hasil-akhir') }}" 
                       class="nav-link {{ request()->routeIs('hasil-akhir*') ? 'active' : '' }}"
                       data-tooltip="Hasil Akhir">
                        <i class="bi bi-trophy"></i>
                        <span>Hasil Akhir</span>
                    </a>
                </div>
                
                <div class="menu-header">Pengaturan</div>
                
                <div class="nav-item">
                    <a href="{{ route('profile.edit') }}" 
                       class="nav-link {{ request()->routeIs('profile*') ? 'active' : '' }}"
                       data-tooltip="Profile">
                        <i class="bi bi-person-circle"></i>
                        <span>Profile</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link w-100 text-start" data-tooltip="Logout">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content Wrapper -->
        <div class="main-wrapper" id="mainContent">
            <!-- Top Bar -->
            <div class="topbar">
                <button class="toggle-sidebar desktop-menu-toggle" onclick="toggleSidebarDesktop()">
                    <i class="bi bi-list"></i>
                </button>
                
                <button class="toggle-sidebar mobile-menu-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="user-info">
                    <span class="user-name">
                        Hi, <strong>{{ auth()->user()->name }}</strong>
                        @if(auth()->user()->role === 'wali_kelas')
                            <span class="badge bg-info ms-2">Wali {{ auth()->user()->kelas }}</span>
                        @elseif(auth()->user()->role === 'admin')
                            <span class="badge bg-danger ms-2">Admin</span>
                        @endif
                    </span>
                    <div class="user-avatar">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
            
            <!-- Content Area -->
            <div class="content-area">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="footer">
                <div class="footer-content">
                    <div class="text-center">
                        <strong>© {{ date('Y') }} SPK SDIT As Sunnah Cirebon</strong>
                        <br>
                        <small class="text-muted">
                            Metode ROC & SMART | Laravel v{{ Illuminate\Foundation\Application::VERSION }}
                        </small>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <script>
        // Toggle Sidebar Desktop
        function toggleSidebarDesktop() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('mainContent').classList.toggle('expanded');
            
            // Save state to localStorage
            const isCollapsed = document.getElementById('sidebar').classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }
        
        // Toggle Sidebar Mobile
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
        
        // Load sidebar state
        document.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed && window.innerWidth > 768) {
                document.getElementById('sidebar').classList.add('collapsed');
                document.getElementById('mainContent').classList.add('expanded');
            }
        });
        
        // Initialize DataTables globally with responsive
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const menuToggle = document.querySelector('.mobile-menu-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !menuToggle.contains(event.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
        
        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth > 768) {
                    document.getElementById('sidebar').classList.remove('show');
                    document.getElementById('sidebarOverlay').classList.remove('show');
                }
            }, 250);
        });
    </script>
    @yield('js')
</body>
</html>