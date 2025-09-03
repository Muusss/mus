@extends('layouts.public')

@section('title', 'Hasil Peringkat Siswa Teladan')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    /* Container adjustments */
    .content-wrapper {
        padding: 20px 0;
    }
    
    /* Page Header */
    .page-header-custom {
        background: white;
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .page-header-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, var(--gold), var(--primary-color), var(--gold));
    }
    
    .header-icon {
        font-size: 3rem;
        color: var(--gold);
        margin-bottom: 15px;
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .header-title {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 10px;
    }
    
    .header-subtitle {
        color: #666;
        font-size: 1.1rem;
        margin-bottom: 20px;
    }
    
    .periode-badge {
        display: inline-block;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 600;
        box-shadow: 0 5px 15px rgba(255,107,53,0.3);
    }
    
    /* Filter Card */
    .filter-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    
    .filter-title {
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .filter-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }
    
    .filter-btn {
        padding: 8px 20px;
        border: 2px solid #e0e0e0;
        background: white;
        color: var(--dark-color);
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
        cursor: pointer;
        font-size: 0.95rem;
    }
    
    .filter-btn:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255,107,53,0.2);
    }
    
    .filter-btn.active {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border-color: transparent;
    }
    
    /* Winner Cards */
    .winner-section {
        margin-bottom: 40px;
    }
    
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
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .winner-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
    }
    
    .winner-card.gold::before { background: linear-gradient(90deg, var(--gold), #ffed4e); }
    .winner-card.silver::before { background: linear-gradient(90deg, var(--silver), #e8e8e8); }
    .winner-card.bronze::before { background: linear-gradient(90deg, var(--bronze), #e8a853); }
    
    .medal-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 2rem;
        opacity: 0.3;
    }
    
    .winner-card.gold .medal-icon { color: var(--gold); }
    .winner-card.silver .medal-icon { color: var(--silver); }
    .winner-card.bronze .medal-icon { color: var(--bronze); }
    
    .winner-rank {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.9rem;
        margin-bottom: 20px;
        letter-spacing: 1px;
    }
    
    .winner-card.gold .winner-rank { 
        background: rgba(255,215,0,0.2); 
        color: #b8860b;
    }
    .winner-card.silver .winner-rank { 
        background: rgba(192,192,192,0.2); 
        color: #708090;
    }
    .winner-card.bronze .winner-rank { 
        background: rgba(205,127,50,0.2); 
        color: #8b4513;
    }
    
    .winner-avatar {
        width: 100px;
        height: 100px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        position: relative;
    }
    
    .winner-card.gold .winner-avatar { 
        box-shadow: 0 0 0 5px rgba(255,215,0,0.3);
    }
    
    .winner-name {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 15px;
    }
    
    .winner-details {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .detail-item {
        background: #f8f9fa;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.9rem;
        color: #666;
        font-weight: 600;
    }
    
    .winner-score {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 15px;
        border-radius: 15px;
        margin-bottom: 15px;
    }
    
    .score-label {
        font-size: 0.85rem;
        opacity: 0.9;
        display: block;
        margin-bottom: 5px;
    }
    
    .score-value {
        font-size: 1.8rem;
        font-weight: 800;
        display: block;
    }
    
    .winner-badge {
        background: rgba(34,197,94,0.1);
        color: #16a34a;
        padding: 8px 15px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    /* Statistics Cards */
    .stats-section {
        margin-bottom: 40px;
    }
    
    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.3s;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
    }
    
    .stat-icon.blue { background: linear-gradient(135deg, #667eea, #764ba2); }
    .stat-icon.green { background: linear-gradient(135deg, #11998e, #38ef7d); }
    .stat-icon.orange { background: linear-gradient(135deg, #f2994a, #f2c94c); }
    .stat-icon.red { background: linear-gradient(135deg, #eb3349, #f45c43); }
    
    .stat-content {
        flex: 1;
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--dark-color);
        line-height: 1;
    }
    
    .stat-label {
        color: #666;
        font-size: 0.9rem;
        margin-top: 5px;
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
        padding: 20px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .table-header h4 {
        margin: 0;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .table-responsive {
        padding: 0;
    }
    
    .ranking-table {
        width: 100%;
        margin: 0;
    }
    
    .ranking-table thead {
        background: #f8f9fa;
    }
    
    .ranking-table thead th {
        font-weight: 700;
        color: var(--dark-color);
        padding: 15px;
        border: none;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .ranking-table tbody tr {
        transition: background 0.3s;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .ranking-table tbody tr:hover {
        background: rgba(255,107,53,0.05);
    }
    
    .ranking-table tbody tr.top-three {
        background: linear-gradient(90deg, rgba(255,215,0,0.05), rgba(255,255,255,0));
    }
    
    .ranking-table td {
        padding: 15px;
        vertical-align: middle;
    }
    
    /* Rank Badges */
    .rank-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.9rem;
    }
    
    .rank-badge.gold {
        background: linear-gradient(135deg, var(--gold), #ffed4e);
        color: #333;
    }
    
    .rank-badge.silver {
        background: linear-gradient(135deg, var(--silver), #e8e8e8);
        color: #333;
    }
    
    .rank-badge.bronze {
        background: linear-gradient(135deg, var(--bronze), #e8a853);
        color: white;
    }
    
    .rank-badge.default {
        background: #f0f0f0;
        color: #666;
    }
    
    .gender-badge {
        padding: 4px 10px;
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
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .score-display {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 5px 12px;
        border-radius: 10px;
        font-weight: 700;
    }
    
    .status-badge {
        padding: 5px 12px;
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
        background: rgba(245,158,11,0.2);
        color: #d97706;
    }
    
    .status-badge.top10 {
        background: rgba(59,130,246,0.2);
        color: #2563eb;
    }
    
    .status-badge.partisipan {
        background: #f0f0f0;
        color: #666;
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
        font-weight: 700;
    }
    
    .empty-state p {
        color: #666;
        margin-bottom: 30px;
    }
    
    /* DataTables Customization */
    .dataTables_wrapper {
        padding: 20px;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        border: 2px solid #e0e0e0;
        border-radius: 50px;
        padding: 8px 15px;
        margin-left: 10px;
        transition: all 0.3s;
    }
    
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(255,107,53,0.1);
    }
    
    .dataTables_wrapper .dataTables_length select {
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 5px 10px;
        margin: 0 5px;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #e0e0e0 !important;
        border-radius: 8px !important;
        margin: 0 2px !important;
        padding: 5px 12px !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark)) !important;
        color: white !important;
        border-color: transparent !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f8f9fa !important;
        border-color: var(--primary-color) !important;
        color: var(--primary-color) !important;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .header-title { font-size: 1.8rem; }
        .filter-buttons { justify-content: center; }
        .winner-details { flex-direction: column; align-items: center; }
        .table-header { flex-direction: column; gap: 10px; text-align: center; }
        
        /* Mobile table */
        .ranking-table thead { display: none; }
        .ranking-table, .ranking-table tbody, .ranking-table tr, .ranking-table td {
            display: block;
            width: 100%;
        }
        .ranking-table tbody tr {
            margin-bottom: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
        }
        .ranking-table tbody td {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border: none;
        }
        .ranking-table tbody td::before {
            content: attr(data-label);
            font-weight: 700;
            color: #666;
        }
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="container-fluid px-4">
        <!-- Page Header -->
        <div class="page-header-custom">
            <div class="header-icon">
                <i class="bi bi-trophy-fill"></i>
            </div>
            <h1 class="header-title">Hasil Peringkat Siswa Teladan</h1>
            <p class="header-subtitle">SDIT As Sunnah Cirebon - Tahun Ajaran {{ date('Y') }}/{{ date('Y') + 1 }}</p>
            @if($periodeAktif)
                <div class="periode-badge">
                    <i class="bi bi-calendar-check me-2"></i>{{ $periodeAktif->nama_periode }}
                </div>
            @endif
        </div>
        
        <!-- Filter Section -->
        <div class="filter-card">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h5 class="filter-title">
                        <i class="bi bi-funnel"></i> Filter Kelas
                    </h5>
                </div>
                <div class="col-md-8">
                    <div class="filter-buttons">
                        <button class="filter-btn {{ $kelasFilter == 'all' ? 'active' : '' }}" 
                                onclick="filterByKelas('all')">
                            <i class="bi bi-grid-3x3-gap me-1"></i> Semua Kelas
                        </button>
                        @foreach($kelasList as $kelas)
                            <button class="filter-btn {{ $kelasFilter == $kelas ? 'active' : '' }}" 
                                    onclick="filterByKelas('{{ $kelas }}')">
                                Kelas {{ $kelas }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        @if($nilaiAkhir && $nilaiAkhir->count() > 0)
            <!-- Top 3 Winners -->
            <div class="winner-section">
                <div class="row">
                    @php
                        $top3 = $nilaiAkhir->take(3);
                        $medals = ['gold', 'silver', 'bronze'];
                        $icons = ['trophy-fill', 'award-fill', 'award'];
                        $ranks = ['JUARA 1', 'JUARA 2', 'JUARA 3'];
                    @endphp
                    
                    @foreach($top3 as $index => $siswa)
                        <div class="col-lg-4 mb-4">
                            <div class="winner-card {{ $medals[$index] }}">
                                <div class="medal-icon">
                                    <i class="bi bi-{{ $icons[$index] }}"></i>
                                </div>
                                
                                <div class="winner-rank">{{ $ranks[$index] }}</div>
                                
                                <div class="winner-avatar">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                                
                                <h3 class="winner-name">{{ $siswa->alternatif->nama_siswa ?? '-' }}</h3>
                                
                                <div class="winner-details">
                                    <span class="detail-item">
                                        <i class="bi bi-card-text me-1"></i> {{ $siswa->alternatif->nis ?? '-' }}
                                    </span>
                                    <span class="detail-item">
                                        <i class="bi bi-building me-1"></i> {{ $siswa->alternatif->kelas ?? '-' }}
                                    </span>
                                </div>
                                
                                <div class="winner-score">
                                    <span class="score-label">Total Nilai</span>
                                    <span class="score-value">{{ number_format($siswa->total ?? 0, 4) }}</span>
                                </div>
                                
                                <div class="winner-badge">
                                    <i class="bi bi-star-fill me-1"></i>
                                    @if($kelasFilter && $kelasFilter !== 'all')
                                        Siswa Teladan Kelas {{ $kelasFilter }}
                                    @else
                                        Siswa Teladan Sekolah
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Statistics -->
            <div class="stats-section">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon blue">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $nilaiAkhir->count() }}</div>
                                <div class="stat-label">Total Siswa</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon green">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ number_format($nilaiAkhir->max('total') ?? 0, 4) }}</div>
                                <div class="stat-label">Nilai Tertinggi</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon orange">
                                <i class="bi bi-calculator"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ number_format($nilaiAkhir->avg('total') ?? 0, 4) }}</div>
                                <div class="stat-label">Nilai Rata-rata</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon red">
                                <i class="bi bi-graph-down-arrow"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-value">{{ number_format($nilaiAkhir->min('total') ?? 0, 4) }}</div>
                                <div class="stat-label">Nilai Terendah</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Ranking Table -->
            <div class="table-card">
                <div class="table-header">
                    <h4>
                        <i class="bi bi-list-ol"></i>
                        Tabel Peringkat Lengkap
                        @if($kelasFilter && $kelasFilter !== 'all')
                            - Kelas {{ $kelasFilter }}
                        @endif
                    </h4>
                </div>
                
                <div class="table-responsive">
                    <table id="rankingTable" class="ranking-table display">
                        <thead>
                            <tr>
                                <th width="80" class="text-center">Peringkat</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th width="80" class="text-center">L/P</th>
                                <th width="100" class="text-center">Kelas</th>
                                <th width="120" class="text-center">Total Nilai</th>
                                <th width="150" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nilaiAkhir as $row)
                                <tr class="{{ $row->peringkat_kelas <= 3 ? 'top-three' : '' }}">
                                    <td class="text-center" data-label="Peringkat">
                                        @if($row->peringkat_kelas == 1)
                                            <span class="rank-badge gold">
                                                <i class="bi bi-trophy-fill"></i> 1
                                            </span>
                                        @elseif($row->peringkat_kelas == 2)
                                            <span class="rank-badge silver">
                                                <i class="bi bi-award-fill"></i> 2
                                            </span>
                                        @elseif($row->peringkat_kelas == 3)
                                            <span class="rank-badge bronze">
                                                <i class="bi bi-award"></i> 3
                                            </span>
                                        @else
                                            <span class="rank-badge default">{{ $row->peringkat_kelas }}</span>
                                        @endif
                                    </td>
                                    <td data-label="NIS">{{ $row->alternatif->nis ?? '-' }}</td>
                                    <td data-label="Nama Siswa">
                                        <strong>{{ $row->alternatif->nama_siswa ?? '-' }}</strong>
                                        @if($row->peringkat_kelas == 1)
                                            <i class="bi bi-star-fill ms-2" style="color: var(--gold);"></i>
                                        @endif
                                    </td>
                                    <td class="text-center" data-label="L/P">
                                        @if($row->alternatif->jk == 'Lk')
                                            <span class="gender-badge male">
                                                <i class="bi bi-gender-male"></i> L
                                            </span>
                                        @else
                                            <span class="gender-badge female">
                                                <i class="bi bi-gender-female"></i> P
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center" data-label="Kelas">
                                        <span class="class-badge">{{ $row->alternatif->kelas ?? '-' }}</span>
                                    </td>
                                    <td class="text-center" data-label="Total Nilai">
                                        <span class="score-display">{{ number_format($row->total ?? 0, 4) }}</span>
                                    </td>
                                    <td class="text-center" data-label="Status">
                                        @if($row->peringkat_kelas == 1)
                                            <span class="status-badge teladan">
                                                <i class="bi bi-star-fill"></i> Siswa Teladan
                                            </span>
                                        @elseif($row->peringkat_kelas <= 3)
                                            <span class="status-badge nominasi">
                                                <i class="bi bi-award"></i> Nominasi
                                            </span>
                                        @elseif($row->peringkat_kelas <= 10)
                                            <span class="status-badge top10">
                                                <i class="bi bi-star"></i> 10 Besar
                                            </span>
                                        @else
                                            <span class="status-badge partisipan">Partisipan</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h3>Belum Ada Data Peringkat</h3>
                <p>Data peringkat siswa teladan belum tersedia untuk periode ini.</p>
                <a href="{{ url('/') }}" class="btn btn-primary btn-lg rounded-pill">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Beranda
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    function filterByKelas(kelas) {
        window.location.href = '{{ route("hasil.publik") }}?kelas=' + kelas;
    }
    
    $(document).ready(function() {
        @if(isset($nilaiAkhir) && $nilaiAkhir->count() > 0)
        $('#rankingTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'asc']],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                },
                zeroRecords: "Tidak ada data yang cocok",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(disaring dari _MAX_ total data)"
            }
        });
        @endif
    });
</script>
@endsection