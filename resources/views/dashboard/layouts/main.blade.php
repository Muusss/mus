<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SPK SMART | {{ $title ?? 'Dashboard' }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo-yac.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo-yac.png') }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Custom CSS dengan Tema Hitam-Orange -->
    <style>
    /* ===== Tokens / Variables ===== */
    :root {
        --sidebar-width: 250px;
        --sidebar-collapsed-width: 80px;

        --primary-color: #ff6b35;
        --primary-dark: #e55100;
        --primary-light: #ff8c5a;

        --secondary-color: #2c2c2c;
        --dark-color: #1a1a1a;
        --darker-color: #0d0d0d;

        --success-color: #1cc88a;
        --info-color: #36b9cc;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;

        --light-color: #f8f9fc;
        --text-light: #ffffff;
        --text-muted: #858796;

        --radius-lg: 15px;
        --radius-md: 12px;
        --radius-sm: 10px;

        --shadow-1: 0 5px 15px rgba(0,0,0,0.08);
        --shadow-2: 0 10px 25px rgba(0,0,0,0.15);

        --ring: 0 0 0 .2rem rgba(255,107,53,.25);
    }

    /* ===== Base ===== */
    * { box-sizing: border-box; }
    html, body { height: 100%; }
    body {
        margin: 0;
        font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background-color: var(--light-color);
        color: #333;
        line-height: 1.5;
    }

    /* ===== Layout ===== */
    .app-container { display: flex; min-height: 100vh; position: relative; }
    .main-wrapper {
        flex: 1;
        margin-left: var(--sidebar-width);
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        transition: margin-left .3s ease;
    }
    .main-wrapper.expanded { margin-left: var(--sidebar-collapsed-width); }

    /* ===== Sidebar ===== */
    .sidebar {
        position: fixed; inset: 0 auto 0 0;
        height: 100vh; width: var(--sidebar-width);
        background: linear-gradient(180deg, var(--dark-color) 0%, var(--darker-color) 100%);
        box-shadow: 0 0 20px rgba(0,0,0,.3);
        z-index: 1000;
        overflow: hidden auto;
        transition: width .3s ease, transform .3s ease;
    }
    .sidebar.collapsed { width: var(--sidebar-collapsed-width); }
    .sidebar-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,.5); z-index: 999;
    }
    .sidebar-overlay.show { display: block; }

    /* Mobile behavior */
    @media (max-width: 768px) {
        .sidebar { transform: translateX(-100%); }
        .sidebar.show { transform: translateX(0); }
        .main-wrapper { margin-left: 0 !important; }
    }

    /* Logo section */
    .logo-section {
        padding: 1.5rem;
        min-height: 80px;
        display: grid; place-items: center;
        text-align: center;
        border-bottom: 1px solid rgba(255,255,255,.1);
        background: var(--primary-color);
    }
    .logo-container {
        width: 60px; height: 60px; padding: 5px;
        background: #fff; border-radius: 10px;
        display: grid; place-items: center;
        box-shadow: 0 3px 10px rgba(0,0,0,.2);
        margin-bottom: 10px;
    }
    .logo-img { width: 100%; height: 100%; object-fit: contain; display: block; }
    .logo-text { color: #fff; font-weight: 700; font-size: .9rem; white-space: nowrap; }
    .sidebar.collapsed .logo-text { display: none; }
    .sidebar.collapsed .logo-section { min-height: 88px; }
    .sidebar.collapsed .logo-container { width: 68px; height: 68px; }

    /* Menu */
    .sidebar-menu { padding: 1rem 0; }
    .menu-header {
        color: rgba(255,255,255,0.5);
        font-size: .75rem; font-weight: 700; text-transform: uppercase;
        padding: 0 1.5rem; margin: 1rem 0 .5rem;
    }
    .sidebar.collapsed .menu-header { display: none; }

    .nav-item { margin: .25rem 0; }
    .nav-link {
        display: flex; align-items: center; gap: 10px;
        color: rgba(255,255,255,.8);
        padding: .75rem 1.5rem; text-decoration: none;
        border-left: 3px solid transparent; position: relative;
        transition: background-color .2s ease, color .2s ease, border-color .2s ease;
    }
    .nav-link i {
        font-size: 1.1rem; width: 30px; text-align: center; flex-shrink: 0;
        color: var(--primary-light);
    }
    .nav-link:hover {
        color: #fff; background-color: rgba(255,107,53,.2); border-left-color: var(--primary-color);
    }
    .nav-link.active {
        color: #fff; background-color: rgba(255,107,53,.3); border-left-color: var(--primary-color);
    }
    .sidebar.collapsed .nav-link span { display: none; }
    .sidebar.collapsed .nav-link i { margin-right: 0; }

    /* Tooltip on collapsed */
    .sidebar.collapsed .nav-link:hover::after {
        content: attr(data-tooltip);
        position: absolute; left: 100%; top: 50%; transform: translateY(-50%);
        background: var(--dark-color); color: #fff;
        border: 1px solid var(--primary-color);
        padding: 5px 10px; border-radius: 6px; white-space: nowrap;
        margin-left: 10px; z-index: 1000;
    }

    /* ===== Topbar ===== */
    .topbar {
        position: sticky; top: 0; z-index: 999;
        background: #fff; border-bottom: 3px solid var(--primary-color);
        box-shadow: 0 2px 10px rgba(0,0,0,.1);
        padding: 1rem 1.5rem;
        display: flex; justify-content: space-between; align-items: center;
    }
    .toggle-sidebar { background: none; border: 0; font-size: 1.25rem; color: var(--secondary-color); cursor: pointer; padding: .5rem; }
    .toggle-sidebar:hover { color: var(--primary-color); }
    .mobile-menu-toggle { display: none; }
    @media (max-width: 768px) {
        .mobile-menu-toggle { display: inline-block; }
        .desktop-menu-toggle { display: none; }
    }
    .user-info { display: flex; align-items: center; gap: 1rem; }
    .user-name { display: none; }
    @media (min-width: 576px) { .user-name { display: inline; } }
    .user-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        display: grid; place-items: center; font-weight: 700;
        color: #fff; background: var(--primary-color);
    }

    /* ===== Content ===== */
    .content-area { padding: 1.5rem; flex: 1; background: #f5f5f5; }
    @media (max-width: 576px) { .content-area { padding: 1rem; } }

    /* ===== Cards ===== */
    .stat-card, .custom-table {
        background: #fff; border-radius: var(--radius-lg);
        box-shadow: var(--shadow-1); padding: 1.5rem;
    }
    .stat-card { border-left: 4px solid var(--primary-color); margin-bottom: 1rem; transition: transform .3s, box-shadow .3s; }
    .stat-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-2); }
    .stat-card .icon { font-size: 2rem; color: var(--primary-color); opacity: .8; }
    .stat-card.primary  { border-left-color: var(--primary-color); }
    .stat-card.success  { border-left-color: var(--success-color); }
    .stat-card.warning  { border-left-color: var(--warning-color); }
    .stat-card.info     { border-left-color: var(--info-color); }

    /* ===== Tables (DataTables) ===== */
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    table.dataTable thead { background: var(--dark-color); color: #fff; }
    table.dataTable thead th { border-bottom: 2px solid var(--primary-color); }
    table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control:before,
    table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control:before {
        background-color: var(--primary-color);
    }

    /* ===== Buttons ===== */
    .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }
    .btn-primary:hover { background-color: var(--primary-dark); border-color: var(--primary-dark); }
    .btn-outline-primary { color: var(--primary-color); border-color: var(--primary-color); }
    .btn-outline-primary:hover { background-color: var(--primary-color); border-color: var(--primary-color); color: #fff; }

    /* ===== Badges / Alerts ===== */
    .badge.bg-primary   { background-color: var(--primary-color) !important; }
    .badge.bg-secondary { background-color: var(--secondary-color) !important; }
    .badge.bg-dark      { background-color: var(--dark-color) !important; }

    .alert-primary {
        background-color: rgba(255,107,53,.1);
        border-color: var(--primary-color);
        color: var(--primary-dark);
        border-radius: var(--radius-sm);
    }

    /* ===== Footer ===== */
    .footer {
        margin-top: auto;
        background: var(--dark-color);
        color: #fff;
        padding: 1.5rem 2rem;
        border-top: 3px solid var(--primary-color);
    }
    @media (max-width: 768px) {
        .footer { padding: 1rem; }
        .footer-content { display: flex; flex-direction: column; text-align: center; }
    }

    /* ===== Forms / Focus ===== */
    .form-control:focus, .form-select:focus { border-color: var(--primary-color); box-shadow: var(--ring); outline: none; }

    /* ===== Progress & SweetAlert ===== */
    .progress-bar { background-color: var(--primary-color); }
    .swal2-confirm { background-color: var(--primary-color) !important; border-color: var(--primary-color) !important; }
    .swal2-confirm:hover { background-color: var(--primary-dark) !important; border-color: var(--primary-dark) !important; }

    /* ===== Scrollbar ===== */
    ::-webkit-scrollbar { width: 10px; height: 10px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: var(--primary-color); border-radius: 5px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--primary-dark); }
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
                    {{-- Pakai logo YAC langsung --}}
                    <img
                        src="{{ asset('img/logo-yac.png') }}"
                        alt="Logo YAC"
                        class="logo-img"
                        onerror="this.outerHTML='<i class=&quot;bi bi-mortarboard-fill&quot; style=&quot;font-size:2rem;color:var(--primary-color);&quot;></i>';"
                    >
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
                    <a href="{{ route('users') }}" class="nav-link {{ request()->routeIs('users*') ? 'active' : '' }}"
                       data-tooltip="Data User">
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
                            <span class="badge bg-dark ms-2">Admin</span>
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
                        <small style="color: var(--primary-light);">
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
