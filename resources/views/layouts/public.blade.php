<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPK Siswa Teladan') - SDIT As Sunnah</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo-yac.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo-yac.png') }}">
    
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
        
        /* Navigation Bar */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 15px 0;
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
        }
        
        .navbar-logo {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 10px;
            padding: 8px;
            border: 2px solid var(--primary-color);
        }
        
        .navbar-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .btn-home {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-home:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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
        
        /* Filter Card */
        .filter-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .filter-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        
        .filter-btn {
            padding: 10px 20px;
            border: 2px solid var(--primary-color);
            background: white;
            color: var(--primary-color);
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .filter-btn:hover,
        .filter-btn.active {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,107,53,0.3);
        }
        
        /* Winner Cards */
        .winner-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
            height: 100%;
            transition: transform 0.3s;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .winner-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .winner-card.gold { border-top: 5px solid var(--gold); }
        .winner-card.silver { border-top: 5px solid var(--silver); }
        .winner-card.bronze { border-top: 5px solid var(--bronze); }
        
        .winner-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 2rem;
        }
        
        .winner-card.gold .winner-badge { color: var(--gold); }
        .winner-card.silver .winner-badge { color: var(--silver); }
        .winner-card.bronze .winner-badge { color: var(--bronze); }
        
        .winner-rank {
            font-size: 1.2rem;
            font-weight: 800;
            margin-bottom: 20px;
        }
        
        .winner-card.gold .winner-rank { color: var(--gold); }
        .winner-card.silver .winner-rank { color: var(--silver); }
        .winner-card.bronze .winner-rank { color: var(--bronze); }
        
        .winner-photo {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            position: relative;
        }
        
        .photo-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }
        
        .winner-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 15px;
        }
        
        .winner-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 20px;
        }
        
        .info-item {
            color: #666;
            font-size: 0.95rem;
        }
        
        .winner-score {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 15px;
        }
        
        .score-label {
            display: block;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .score-value {
            display: block;
            font-size: 1.8rem;
            font-weight: 800;
        }
        
        .winner-status {
            background: rgba(255,107,53,0.1);
            color: var(--primary-color);
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        /* Statistics Cards */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }
        
        .stat-icon.blue { background: linear-gradient(135deg, #667eea, #764ba2); }
        .stat-icon.green { background: linear-gradient(135deg, #11998e, #38ef7d); }
        .stat-icon.orange { background: linear-gradient(135deg, #f2994a, #f2c94c); }
        .stat-icon.red { background: linear-gradient(135deg, #eb3349, #f45c43); }
        
        .stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark-color);
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Table Card */
        .table-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .table-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 20px 30px;
        }
        
        .table-header h4 {
            margin: 0;
            font-weight: 700;
        }
        
        .ranking-table {
            width: 100%;
            margin: 0;
        }
        
        .ranking-table thead th {
            background: #f8f9fa;
            font-weight: 700;
            color: var(--dark-color);
            padding: 15px;
            border: none;
        }
        
        .ranking-table tbody tr {
            transition: background 0.3s;
        }
        
        .ranking-table tbody tr:hover {
            background: rgba(255,107,53,0.05);
        }
        
        .ranking-table tbody tr.top-three {
            background: rgba(255,215,0,0.05);
        }
        
        .ranking-table td {
            padding: 15px;
            vertical-align: middle;
            border-top: 1px solid #eee;
        }
        
        /* Badges */
        .rank-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.95rem;
        }
        
        .rank-badge.gold {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            color: #333;
        }
        
        .rank-badge.silver {
            background: linear-gradient(135deg, #c0c0c0, #e8e8e8);
            color: #333;
        }
        
        .rank-badge.bronze {
            background: linear-gradient(135deg, #cd7f32, #e8a853);
            color: white;
        }
        
        .rank-badge.default {
            background: #f0f0f0;
            color: #666;
        }
        
        .gender-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .gender-badge.male {
            background: rgba(54,162,235,0.1);
            color: #36a2eb;
        }
        
        .gender-badge.female {
            background: rgba(255,99,132,0.1);
            color: #ff6384;
        }
        
        .class-badge {
            background: var(--primary-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .score-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 8px 15px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
        }
        
        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .status-badge.teladan {
            background: linear-gradient(135deg, #11998e, #38ef7d);
            color: white;
        }
        
        .status-badge.nominasi {
            background: rgba(52,152,219,0.1);
            color: #3498db;
        }
        
        .status-badge.top10 {
            background: rgba(155,89,182,0.1);
            color: #9b59b6;
        }
        
        .status-badge.partisipan {
            background: #f0f0f0;
            color: #666;
        }
        
        /* Chart Card */
        .chart-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        /* Empty State */
        .empty-state {
            background: white;
            border-radius: 20px;
            padding: 80px 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .empty-state i {
            font-size: 5rem;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            color: var(--dark-color);
            margin-bottom: 10px;
        }
        
        .empty-state p {
            color: #666;
            margin-bottom: 30px;
        }
        
        .btn-back {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-back:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-title { font-size: 1.8rem; }
            .filter-buttons { justify-content: center; }
            .stat-card { flex-direction: column; text-align: center; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ url('/') }}">
                <div class="navbar-logo">
                    <img src="{{ asset('img/logo-yac.png') }}" alt="Logo YAC">
                </div>
                <span>SPK Siswa Teladan</span>
            </a>
            
            <div class="ms-auto">
                <a href="{{ url('/') }}" class="btn btn-home">
                    <i class="bi bi-house-door"></i> Beranda
                </a>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>