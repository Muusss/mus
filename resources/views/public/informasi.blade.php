@extends('layouts.public')

@section('title', 'Informasi Sistem Penilaian')

@php
    use Illuminate\Support\Str;
@endphp

@section('styles')
<style>
    /* Custom styles untuk halaman informasi */
    .info-hero {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 80px 0;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }
    
    .info-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
    }
    
    .hero-title {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 20px;
        animation: fadeInUp 0.8s ease;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .hero-subtitle {
        font-size: 1.3rem;
        opacity: 0.95;
        margin-bottom: 30px;
        animation: fadeInUp 0.8s ease 0.2s both;
    }
    
    .hero-tagline {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 12px 25px;
        border-radius: 50px;
        font-weight: 600;
        backdrop-filter: blur(10px);
        margin-bottom: 20px;
        animation: fadeInUp 0.8s ease 0.4s both;
    }
    
    /* Benefit Cards */
    .benefit-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-top: 40px;
    }
    
    .benefit-card {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .benefit-card::before {
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
    
    .benefit-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .benefit-card:hover::before {
        transform: scaleX(1);
    }
    
    .benefit-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
        margin-bottom: 20px;
    }
    
    .benefit-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 10px;
    }
    
    .benefit-desc {
        color: #666;
        line-height: 1.6;
    }
    
    /* Method Info Box */
    .method-box {
        background: linear-gradient(135deg, #f6f8fb, #fff);
        border-left: 4px solid var(--primary-color);
        border-radius: 15px;
        padding: 25px;
        margin: 30px 0;
    }
    
    .method-box h4 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .method-badge {
        display: inline-block;
        background: var(--primary-color);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    /* Kriteria Section */
    .kriteria-section {
        background: white;
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .section-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .section-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        margin: 0 auto 20px;
    }
    
    .section-title {
        font-size: 2rem;
        font-weight: 800;
        color: var(--dark-color);
        margin-bottom: 10px;
    }
    
    .section-subtitle {
        color: #666;
        font-size: 1.1rem;
    }
    
    /* Kriteria Accordion */
    .kriteria-accordion {
        margin-top: 30px;
    }
    
    .kriteria-item {
        background: #f8f9fa;
        border-radius: 15px;
        margin-bottom: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .kriteria-item.active {
        background: white;
        box-shadow: 0 10px 30px rgba(255, 107, 53, 0.1);
    }
    
    .kriteria-header {
        padding: 25px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background 0.3s ease;
    }
    
    .kriteria-header:hover {
        background: rgba(255, 107, 53, 0.05);
    }
    
    .kriteria-header-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .kriteria-icon {
        width: 50px;
        height: 50px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .kriteria-info h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 5px;
    }
    
    .kriteria-code {
        display: inline-block;
        background: var(--primary-color);
        color: white;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-right: 10px;
    }
    
    .kriteria-bobot {
        display: inline-block;
        background: #e3f2fd;
        color: #1976d2;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .kriteria-toggle {
        font-size: 1.5rem;
        color: #999;
        transition: transform 0.3s ease;
    }
    
    .kriteria-item.active .kriteria-toggle {
        transform: rotate(180deg);
        color: var(--primary-color);
    }
    
    .kriteria-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.5s ease;
    }
    
    .kriteria-item.active .kriteria-content {
        max-height: 2000px;
    }
    
    .kriteria-content-inner {
        padding: 0 25px 25px;
    }
    
    .kriteria-detail {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .detail-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
    }
    
    .detail-box h4 {
        color: var(--dark-color);
        font-weight: 600;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .detail-box p {
        color: #666;
        line-height: 1.6;
        margin: 0;
    }
    
    .detail-box ul {
        list-style: none;
        padding: 0;
        margin: 10px 0 0;
    }
    
    .detail-box li {
        padding: 5px 0;
        color: #666;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .detail-box li i {
        color: var(--accent-green);
        font-size: 0.9rem;
    }
    
    /* Subkriteria Table */
    .subkriteria-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        margin: 20px 0;
    }
    
    .subkriteria-table table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .subkriteria-table th {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        color: white;
        padding: 15px;
        text-align: left;
        font-weight: 600;
    }
    
    .subkriteria-table td {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .subkriteria-table tr:last-child td {
        border-bottom: none;
    }
    
    .subkriteria-table tr:hover {
        background: #f8f9fa;
    }
    
    .score-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .score-1 { background: #ffebee; color: #c62828; }
    .score-2 { background: #fff3e0; color: #e65100; }
    .score-3 { background: #e8f5e9; color: #2e7d32; }
    .score-4 { background: #e3f2fd; color: #1565c0; }
    
    /* Progress Visual */
    .progress-visual {
        background: white;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
        margin-top: 15px;
    }
    
    .progress-bar-custom {
        background: #f0f0f0;
        height: 30px;
        border-radius: 15px;
        overflow: hidden;
        position: relative;
        margin: 10px 0;
    }
    
    .progress-fill {
        background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
        height: 100%;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 10px;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        transition: width 1s ease;
    }
    
    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-top: 5px;
        font-size: 0.85rem;
    }
    
    /* Visualization Section */
    .viz-section {
        background: linear-gradient(135deg, #f6f8fb, #fff);
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 30px;
    }
    
    .chart-container {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
    }
    
    .chart-title {
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    /* Search & Filter Section */
    .search-section {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 2px solid var(--primary-color);
        margin-bottom: 30px;
    }
    
    .search-box {
        position: relative;
        margin-bottom: 30px;
    }
    
    .search-input {
        width: 100%;
        padding: 15px 50px 15px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 50px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }
    
    .search-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
        outline: none;
    }
    
    .search-btn {
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        background: var(--primary-color);
        color: white;
        border: none;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .search-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-50%) scale(1.1);
    }
    
    /* Filter Options */
    .filter-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .filter-item label {
        display: block;
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 8px;
    }
    
    .filter-select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }
    
    .filter-select:focus {
        border-color: var(--primary-color);
        outline: none;
    }
    
    /* View Mode Toggle */
    .view-toggle {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 30px;
    }
    
    .toggle-btn {
        padding: 12px 25px;
        border: 2px solid #e0e0e0;
        background: white;
        border-radius: 50px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .toggle-btn:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }
    
    .toggle-btn.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .action-btn {
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .action-btn.primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
    }
    
    .action-btn.secondary {
        background: #f0f0f0;
        color: var(--dark-color);
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    /* FAQ Section */
    .faq-section {
        background: white;
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .faq-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 25px;
        margin-top: 30px;
    }
    
    .faq-item {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 25px;
        transition: all 0.3s ease;
    }
    
    .faq-item:hover {
        background: white;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .faq-question {
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .faq-question i {
        color: var(--primary-color);
    }
    
    .faq-answer {
        color: #666;
        line-height: 1.6;
    }
    
    /* Onboarding Tour */
    .tour-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 9998;
        display: none;
    }
    
    .tour-tooltip {
        position: absolute;
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        max-width: 350px;
        z-index: 9999;
    }
    
    .tour-tooltip h4 {
        color: var(--primary-color);
        margin-bottom: 10px;
    }
    
    .tour-tooltip p {
        color: #666;
        margin-bottom: 15px;
    }
    
    .tour-buttons {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }
    
    .tour-btn {
        padding: 8px 20px;
        border-radius: 25px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .tour-btn.next {
        background: var(--primary-color);
        color: white;
    }
    
    .tour-btn.skip {
        background: #f0f0f0;
        color: #666;
    }
    
    /* Floating Action Button */
    .fab {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 10px 30px rgba(255, 107, 53, 0.3);
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 1000;
    }
    
    .fab:hover {
        transform: scale(1.1) rotate(90deg);
        box-shadow: 0 15px 40px rgba(255, 107, 53, 0.4);
    }
    
    .fab-tooltip {
        position: absolute;
        right: 70px;
        top: 50%;
        transform: translateY(-50%);
        background: var(--dark-color);
        color: white;
        padding: 8px 15px;
        border-radius: 8px;
        white-space: nowrap;
        font-size: 0.9rem;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    
    .fab:hover .fab-tooltip {
        opacity: 1;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .benefit-cards {
            grid-template-columns: 1fr;
        }
        
        .kriteria-detail {
            grid-template-columns: 1fr;
        }
        
        .filter-group {
            grid-template-columns: 1fr;
        }
        
        .faq-grid {
            grid-template-columns: 1fr;
        }
        
        .view-toggle {
            flex-direction: column;
        }
        
        .toggle-btn {
            width: 100%;
            justify-content: center;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-btn {
            width: 100%;
            justify-content: center;
        }
        
        .subkriteria-table {
            font-size: 0.85rem;
        }
        
        .subkriteria-table th,
        .subkriteria-table td {
            padding: 10px;
        }
    }
    
    /* Loading State */
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }
    
    /* Toast Notifications */
    .toast {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        background: white;
        padding: 15px 25px;
        border-radius: 50px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 10000;
        animation: slideUp 0.3s ease;
    }
    
    .toast.success {
        border-left: 4px solid #4caf50;
    }
    
    .toast.error {
        border-left: 4px solid #f44336;
    }
    
    .toast.info {
        border-left: 4px solid #2196f3;
    }
    
    @keyframes slideUp {
        from {
            transform: translateX(-50%) translateY(100px);
            opacity: 0;
        }
        to {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Hero Section -->
    <div class="info-hero">
        <div class="container">
            <div class="hero-content text-center">
                <div class="hero-tagline">
                    <i class="bi bi-shield-check me-2"></i>
                    Transparansi • Objektif • Akuntabel
                </div>
                <h1 class="hero-title">Sistem Penilaian Siswa Teladan Berbasis Keputusan Multikriteria</h1>
                <p class="hero-subtitle">
                    Sistem Pendukung Keputusan (SPK) merupakan implementasi teknologi informasi yang dirancang untuk 
                    membantu proses pemilihan siswa teladan secara objektif dan terukur. Sistem ini mengintegrasikan 
                    pendekatan ilmiah dalam evaluasi prestasi siswa berdasarkan data empiris yang terverifikasi.
                </p>
            </div>
        </div>
    </div>
    
    <div class="container">
        <!-- Benefit Cards -->
        <div class="benefit-cards">
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <h3 class="benefit-title">Objektif dan Terukur</h3>
                <p class="benefit-desc">
                    Setiap aspek penilaian didasarkan pada indikator yang jelas dan terverifikasi. 
                    Sistem ini mengeliminasi unsur subjektivitas melalui standardisasi rubrik penilaian 
                    yang konsisten untuk seluruh siswa.
                </p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="bi bi-eye"></i>
                </div>
                <h3 class="benefit-title">Transparansi Akademik</h3>
                <p class="benefit-desc">
                    Wali siswa memiliki akses penuh terhadap detail penilaian anaknya. 
                    Kriteria dan metodologi penilaian terbuka untuk dipelajari, 
                    memastikan akuntabilitas proses evaluasi.
                </p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="bi bi-lightning-charge"></i>
                </div>
                <h3 class="benefit-title">Efisiensi Komputasi</h3>
                <p class="benefit-desc">
                    Hasil penilaian diperbarui secara real-time dengan perhitungan 
                    matematis otomatis yang mengeliminasi kemungkinan kesalahan manual 
                    dalam proses kalkulasi.
                </p>
            </div>
        </div>
        
        <!-- Method Info Box -->
        <div class="method-box">
            <h4>
                <i class="bi bi-lightbulb-fill"></i>
                Metodologi Ilmiah: Integrasi ROC dan SMART
            </h4>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-start gap-3">
                        <span class="method-badge">ROC</span>
                        <div>
                            <strong>Rank Order Centroid</strong>
                            <p class="mb-0 text-muted">
                                Metode pembobotan kriteria yang menggunakan pendekatan matematis untuk 
                                menentukan tingkat kepentingan relatif setiap kriteria berdasarkan 
                                prioritas institusional yang telah ditetapkan oleh manajemen sekolah.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-start gap-3">
                        <span class="method-badge">SMART</span>
                        <div>
                            <strong>Simple Multi Attribute Rating Technique</strong>
                            <p class="mb-0 text-muted">
                                Teknik evaluasi multiatribut yang melakukan normalisasi nilai ke dalam 
                                skala uniform 0-1, memastikan komparabilitas antar kriteria dengan 
                                satuan pengukuran yang berbeda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3 text-center">
                <strong>Hasil: Sistem penilaian yang adil, objektif, dan berbasis bukti empiris</strong>
            </div>
        </div>
        
        <!-- Kriteria Section -->
        <div class="kriteria-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="bi bi-clipboard-check"></i>
                </div>
                <h2 class="section-title">Enam Kriteria Penilaian Komprehensif</h2>
                <p class="section-subtitle">
                    Sistem evaluasi ini menggunakan enam kriteria fundamental yang mencakup aspek 
                    akademik, spiritual, dan pengembangan karakter. Klik setiap kriteria untuk 
                    melihat rubrik penilaian detail.
                </p>
            </div>
            
            <div class="kriteria-accordion">
                <!-- Kriteria 1: Nilai Raport Umum -->
                <div class="kriteria-item active">
                    <div class="kriteria-header" onclick="toggleKriteria(this)">
                        <div class="kriteria-header-left">
                            <div class="kriteria-icon">📚</div>
                            <div class="kriteria-info">
                                <h3>
                                    <span class="kriteria-code">C1</span>
                                    Nilai Raport Umum
                                </h3>
                                <span class="kriteria-bobot">Bobot: 40.83%</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down kriteria-toggle"></i>
                    </div>
                    <div class="kriteria-content">
                        <div class="kriteria-content-inner">
                            <div class="kriteria-detail">
                                <div class="detail-box">
                                    <h4><i class="bi bi-question-circle"></i> Signifikansi Kriteria</h4>
                                    <p>
                                        Nilai Raport Umum merupakan kriteria dengan bobot tertinggi, 
                                        mencerminkan filosofi institusional bahwa prestasi akademik 
                                        adalah fondasi utama pendidikan formal. Kriteria ini menilai 
                                        kemampuan kognitif siswa dalam menguasai mata pelajaran umum.
                                    </p>
                                </div>
                                <div class="detail-box">
                                    <h4><i class="bi bi-book"></i> Cakupan Mata Pelajaran</h4>
                                    <ul>
                                        <li><i class="bi bi-check-circle"></i> Matematika</li>
                                        <li><i class="bi bi-check-circle"></i> Ilmu Pengetahuan Alam</li>
                                        <li><i class="bi bi-check-circle"></i> Ilmu Pengetahuan Sosial</li>
                                        <li><i class="bi bi-check-circle"></i> Bahasa Indonesia</li>
                                        <li><i class="bi bi-check-circle"></i> Bahasa Inggris</li>
                                        <li><i class="bi bi-check-circle"></i> Pendidikan Kewarganegaraan</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="subkriteria-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Rentang Nilai</th>
                                            <th>Kategori</th>
                                            <th>Skor</th>
                                            <th>Deskripsi Pencapaian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>&lt; 80</strong></td>
                                            <td>Perlu Peningkatan</td>
                                            <td><span class="score-badge score-1">1</span></td>
                                            <td>
                                                Siswa memerlukan bimbingan intensif dan perbaikan signifikan 
                                                dalam proses pembelajaran. Kesulitan dalam memahami konsep dasar 
                                                mata pelajaran membutuhkan perhatian khusus dari guru.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>80-85</strong></td>
                                            <td>Cukup Baik</td>
                                            <td><span class="score-badge score-2">2</span></td>
                                            <td>
                                                Pencapaian akademik pada tingkat cukup baik. Siswa telah memenuhi 
                                                standar minimal kompetensi, namun perlu mengembangkan kemampuan 
                                                analisis dan aplikasi pengetahuan dalam konteks yang lebih kompleks.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>86-90</strong></td>
                                            <td>Baik</td>
                                            <td><span class="score-badge score-3">3</span></td>
                                            <td>
                                                Prestasi akademik yang baik dan konsisten. Siswa menunjukkan 
                                                penguasaan materi yang solid, kemampuan berpikir kritis yang 
                                                berkembang, dan aktif dalam diskusi kelas.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>&gt; 90</strong></td>
                                            <td>Sangat Baik</td>
                                            <td><span class="score-badge score-4">4</span></td>
                                            <td>
                                                Prestasi akademik yang unggul. Siswa mampu melakukan analisis 
                                                mendalam, sintesis informasi dari berbagai sumber, dan menghasilkan 
                                                pemikiran orisinal. Berpotensi menjadi pemimpin akademik.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Kriteria 2: Nilai Raport Diniyah -->
                <div class="kriteria-item">
                    <div class="kriteria-header" onclick="toggleKriteria(this)">
                        <div class="kriteria-header-left">
                            <div class="kriteria-icon">🕌</div>
                            <div class="kriteria-info">
                                <h3>
                                    <span class="kriteria-code">C2</span>
                                    Nilai Raport Diniyah
                                </h3>
                                <span class="kriteria-bobot">Bobot: 24.17%</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down kriteria-toggle"></i>
                    </div>
                    <div class="kriteria-content">
                        <div class="kriteria-content-inner">
                            <div class="kriteria-detail">
                                <div class="detail-box">
                                    <h4><i class="bi bi-question-circle"></i> Signifikansi Kriteria</h4>
                                    <p>
                                        Menduduki posisi kedua dalam hierarki pembobotan, mencerminkan 
                                        identitas SDIT As Sunnah sebagai sekolah Islam terpadu yang 
                                        menempatkan pendidikan agama sebagai pilar penting dalam 
                                        pembentukan karakter siswa.
                                    </p>
                                </div>
                                <div class="detail-box">
                                    <h4><i class="bi bi-book"></i> Mata Pelajaran Diniyah</h4>
                                    <ul>
                                        <li><i class="bi bi-check-circle"></i> Al-Qur'an dan Tajwid</li>
                                        <li><i class="bi bi-check-circle"></i> Hadits</li>
                                        <li><i class="bi bi-check-circle"></i> Fiqih</li>
                                        <li><i class="bi bi-check-circle"></i> Aqidah Akhlak</li>
                                        <li><i class="bi bi-check-circle"></i> Sejarah Islam</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="subkriteria-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Rentang Nilai</th>
                                            <th>Kategori</th>
                                            <th>Skor</th>
                                            <th>Deskripsi Pencapaian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>&lt; 85</strong></td>
                                            <td>Perlu Pendalaman</td>
                                            <td><span class="score-badge score-1">1</span></td>
                                            <td>
                                                Pemahaman agama yang masih dasar dan memerlukan pendalaman intensif. 
                                                Kesulitan dalam membaca Al-Qur'an dengan tajwid yang benar, 
                                                pemahaman hadits terbatas, dan penguasaan fiqih ibadah belum memadai.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>85-90</strong></td>
                                            <td>Cukup</td>
                                            <td><span class="score-badge score-2">2</span></td>
                                            <td>
                                                Pemahaman diniyah pada tingkat cukup yang memenuhi standar minimal. 
                                                Dapat membaca Al-Qur'an dengan tajwid cukup baik, memahami hadits pokok, 
                                                dan menguasai fiqih ibadah dasar.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>91-95</strong></td>
                                            <td>Baik</td>
                                            <td><span class="score-badge score-3">3</span></td>
                                            <td>
                                                Penguasaan materi diniyah yang solid. Mampu menjelaskan konsep keislaman 
                                                dengan baik, mengaitkan pembelajaran dengan realitas kehidupan, 
                                                dan menunjukkan adab Islami dalam pergaulan sehari-hari.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>&gt; 95</strong></td>
                                            <td>Sangat Baik</td>
                                            <td><span class="score-badge score-4">4</span></td>
                                            <td>
                                                Pemahaman diniyah yang mendalam dan luar biasa. Mampu melakukan 
                                                analisis komparatif antar madzhab, memahami konteks historis 
                                                hukum Islam, dan menjadi rujukan dalam masalah keagamaan.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Kriteria 3: Akhlak & Sikap -->
                <div class="kriteria-item">
                    <div class="kriteria-header" onclick="toggleKriteria(this)">
                        <div class="kriteria-header-left">
                            <div class="kriteria-icon">🤝</div>
                            <div class="kriteria-info">
                                <h3>
                                    <span class="kriteria-code">C3</span>
                                    Akhlak
                                </h3>
                                <span class="kriteria-bobot">Bobot: 15.83%</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down kriteria-toggle"></i>
                    </div>
                    <div class="kriteria-content">
                        <div class="kriteria-content-inner">
                            <div class="kriteria-detail">
                                <div class="detail-box">
                                    <h4><i class="bi bi-question-circle"></i> Signifikansi Kriteria</h4>
                                    <p>
                                        Komponen vital yang mencerminkan aspek afektif dan psikomotorik 
                                        siswa dalam kehidupan sehari-hari. Penilaian memerlukan observasi 
                                        kontinyu dan komprehensif dari guru mata pelajaran, wali kelas, 
                                        guru BK, dan masukan teman sebaya.
                                    </p>
                                </div>
                                <div class="detail-box">
                                    <h4><i class="bi bi-star"></i> Lima Aspek Fundamental</h4>
                                    <ul>
                                        <li><i class="bi bi-check-circle"></i> Sopan Santun</li>
                                        <li><i class="bi bi-check-circle"></i> Tanggung Jawab</li>
                                        <li><i class="bi bi-check-circle"></i> Kerjasama</li>
                                        <li><i class="bi bi-check-circle"></i> Kejujuran</li>
                                        <li><i class="bi bi-check-circle"></i> Kepedulian Sosial</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="subkriteria-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Kategori</th>
                                            <th>Skor</th>
                                            <th>Indikator Perilaku</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Kurang Baik</strong></td>
                                            <td><span class="score-badge score-1">1</span></td>
                                            <td>
                                                Sering melanggar tata tertib sekolah, menunjukkan sikap tidak sopan, 
                                                memiliki catatan ketidakjujuran seperti menyontek atau berbohong, 
                                                dan kurang peduli terhadap lingkungan. Memerlukan pembinaan karakter intensif.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Cukup Baik</strong></td>
                                            <td><span class="score-badge score-2">2</span></td>
                                            <td>
                                                Sesekali melakukan pelanggaran ringan, namun menunjukkan kesadaran 
                                                akan kesalahan dan bersedia memperbaiki diri. Sopan santun cukup baik 
                                                meskipun belum konsisten, tanggung jawab mulai berkembang.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Baik</strong></td>
                                            <td><span class="score-badge score-3">3</span></td>
                                            <td>
                                                Konsisten mematuhi aturan sekolah, menunjukkan sopan santun yang baik, 
                                                bertanggung jawab terhadap tugas, dapat bekerjasama dalam tim, 
                                                dan menunjukkan kejujuran. Menjadi contoh positif bagi teman-teman.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Sangat Baik</strong></td>
                                            <td><span class="score-badge score-4">4</span></td>
                                            <td>
                                                Menjadi teladan dan agen perubahan positif. Menunjukkan inisiatif membantu, 
                                                integritas tinggi bahkan tanpa pengawasan, mampu menyelesaikan konflik 
                                                dengan bijaksana, dan aktif dalam kegiatan sosial kemanusiaan.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Kriteria 4: Hafalan Al-Qur'an -->
                <div class="kriteria-item">
                    <div class="kriteria-header" onclick="toggleKriteria(this)">
                        <div class="kriteria-header-left">
                            <div class="kriteria-icon">📖</div>
                            <div class="kriteria-info">
                                <h3>
                                    <span class="kriteria-code">C4</span>
                                    Hafalan Al-Qur'an
                                </h3>
                                <span class="kriteria-bobot">Bobot: 10.28%</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down kriteria-toggle"></i>
                    </div>
                    <div class="kriteria-content">
                        <div class="kriteria-content-inner">
                            <div class="kriteria-detail">
                                <div class="detail-box">
                                    <h4><i class="bi bi-question-circle"></i> Signifikansi Kriteria</h4>
                                    <p>
                                        Mencerminkan komitmen SDIT As Sunnah dalam mewujudkan generasi Qur'ani. 
                                        Program tahfidz merupakan bagian integral dari kurikulum untuk membentuk 
                                        kedekatan emosional dan spiritual siswa dengan kitab suci.
                                    </p>
                                </div>
                                <div class="detail-box">
                                    <h4><i class="bi bi-check2-all"></i> Metode Evaluasi</h4>
                                    <p>
                                        Penilaian melalui ujian lisan berkala oleh tim tahfidz bersertifikat. 
                                        Evaluasi mencakup kelancaran, tajwid, dan tahsin (keindahan bacaan) 
                                        menggunakan sistem tasmi' (menyimak) tanpa mushaf.
                                    </p>
                                </div>
                            </div>
                            
                            <div class="subkriteria-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Capaian Hafalan</th>
                                            <th>Skor</th>
                                            <th>Deskripsi Pencapaian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>½ Juz</strong></td>
                                            <td><span class="score-badge score-1">1</span></td>
                                            <td>
                                                Tahap fondasi hafalan (sekitar 10 halaman), umumnya Juz 30 bagian pertama. 
                                                Siswa dalam tahap membangun teknik menghafal efektif dan membiasakan 
                                                lidah dengan bacaan Arab yang fasih.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>1 Juz</strong></td>
                                            <td><span class="score-badge score-2">2</span></td>
                                            <td>
                                                Menyelesaikan hafalan Juz 30 penuh. Menunjukkan komitmen serius terhadap 
                                                program tahfidz, memiliki rutinitas menghafal terbentuk, mampu muraja'ah, 
                                                dan peningkatan kualitas bacaan.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>2 Juz</strong></td>
                                            <td><span class="score-badge score-3">3</span></td>
                                            <td>
                                                Pencapaian signifikan (Juz 30 dan 29). Disiplin tinggi dalam menjaga hafalan, 
                                                teknik menghafal efektif, mampu menghubungkan ayat dalam konteks luas. 
                                                Sering menjadi imam shalat atau memimpin tadarus.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>&gt; 2 Juz</strong></td>
                                            <td><span class="score-badge score-4">4</span></td>
                                            <td>
                                                Pencapaian istimewa untuk tingkat SD. Hafalan sempurna dengan pemahaman 
                                                asbabun nuzul, mampu menjelaskan kandungan ayat, menerapkan nilai Al-Qur'an. 
                                                Menjadi motivator program tahfidz dan mewakili sekolah dalam kompetisi.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Kriteria 5: Kehadiran -->
                <div class="kriteria-item">
                    <div class="kriteria-header" onclick="toggleKriteria(this)">
                        <div class="kriteria-header-left">
                            <div class="kriteria-icon">✅</div>
                            <div class="kriteria-info">
                                <h3>
                                    <span class="kriteria-code">C5</span>
                                    Kehadiran
                                </h3>
                                <span class="kriteria-bobot">Bobot: 6.11%</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down kriteria-toggle"></i>
                    </div>
                    <div class="kriteria-content">
                        <div class="kriteria-content-inner">
                            <div class="kriteria-detail">
                                <div class="detail-box">
                                    <h4><i class="bi bi-question-circle"></i> Signifikansi Kriteria</h4>
                                    <p>
                                        Indikator fundamental kedisiplinan dan komitmen siswa terhadap proses 
                                        pendidikan. Kehadiran adalah prasyarat mutlak untuk pencapaian akademik 
                                        dan non-akademik, manifestasi dari motivasi belajar intrinsik.
                                    </p>
                                </div>
                                <div class="detail-box">
                                    <h4><i class="bi bi-calculator"></i> Formula Perhitungan</h4>
                                    <p>
                                        <code>Kehadiran = (Jumlah Hari Masuk ÷ Total Hari Efektif) × 100%</code>
                                    </p>
                                    <p class="mt-2">
                                        Sistem pencatatan menggunakan kombinasi metode manual dan digital, 
                                        mencatat kategori ketidakhadiran (sakit, izin, alpa) dan durasi keterlambatan.
                                    </p>
                                </div>
                            </div>
                            
                            <div class="subkriteria-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Persentase</th>
                                            <th>Kategori</th>
                                            <th>Skor</th>
                                            <th>Analisis Kedisiplinan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>&lt; 60%</strong></td>
                                            <td>Kritis</td>
                                            <td><span class="score-badge score-1">1</span></td>
                                            <td>
                                                Masalah serius dalam kedisiplinan. Kehilangan lebih dari 40% waktu 
                                                pembelajaran berdampak signifikan pada pencapaian. Memerlukan home visit, 
                                                konseling intensif, dan kerjasama dengan orang tua.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>60-75%</strong></td>
                                            <td>Mengkhawatirkan</td>
                                            <td><span class="score-badge score-2">2</span></td>
                                            <td>
                                                Tingkat absensi mengkhawatirkan namun dapat diperbaiki. Kehilangan 
                                                seperempat hingga setengah waktu pembelajaran. Perlu kontrak kehadiran 
                                                dengan orang tua dan program remedial.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>76-90%</strong></td>
                                            <td>Baik</td>
                                            <td><span class="score-badge score-3">3</span></td>
                                            <td>
                                                Kedisiplinan memenuhi standar sekolah. Ketidakhadiran dalam batas wajar 
                                                dengan keterangan valid. Menunjukkan tanggung jawab dengan berusaha 
                                                mengejar materi yang tertinggal.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>&gt; 90%</strong></td>
                                            <td>Sangat Baik</td>
                                            <td><span class="score-badge score-4">4</span></td>
                                            <td>
                                                Kedisiplinan sangat baik dan komitmen tinggi. Hampir tidak pernah absen, 
                                                selalu hadir tepat waktu, proaktif menanyakan tugas saat terpaksa tidak hadir. 
                                                Berkorelasi positif dengan prestasi akademik.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Kriteria 6: Ekstrakurikuler -->
                <div class="kriteria-item">
                    <div class="kriteria-header" onclick="toggleKriteria(this)">
                        <div class="kriteria-header-left">
                            <div class="kriteria-icon">🏆</div>
                            <div class="kriteria-info">
                                <h3>
                                    <span class="kriteria-code">C6</span>
                                    Ekstrakurikuler
                                </h3>
                                <span class="kriteria-bobot">Bobot: 2.78%</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down kriteria-toggle"></i>
                    </div>
                    <div class="kriteria-content">
                        <div class="kriteria-content-inner">
                            <div class="kriteria-detail">
                                <div class="detail-box">
                                    <h4><i class="bi bi-question-circle"></i> Signifikansi Kriteria</h4>
                                    <p>
                                        Pengembangan karakter holistik melalui eksplorasi bakat, minat, 
                                        dan soft skills di luar pembelajaran regular. Bobot relatif kecil 
                                        mengindikasikan sifat komplementer terhadap prestasi akademik.
                                    </p>
                                </div>
                                <div class="detail-box">
                                    <h4><i class="bi bi-flag"></i> Kategori Kegiatan</h4>
                                    <ul>
                                        <li><i class="bi bi-check-circle"></i> Keagamaan (Tahfidz, Kaligrafi, Marawis)</li>
                                        <li><i class="bi bi-check-circle"></i> Olahraga (Futsal, Basket, Pencak Silat)</li>
                                        <li><i class="bi bi-check-circle"></i> Seni Budaya (Paduan Suara, Tari, Teater)</li>
                                        <li><i class="bi bi-check-circle"></i> Sains Teknologi (Robotik, Sains Club)</li>
                                        <li><i class="bi bi-check-circle"></i> Kepramukaan (Wajib)</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="subkriteria-table">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Status Partisipasi</th>
                                            <th>Skor</th>
                                            <th>Indikator Perkembangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Tidak Mengikuti</strong></td>
                                            <td><span class="score-badge score-1">1</span></td>
                                            <td>
                                                Tidak terlibat dalam kegiatan ekstrakurikuler di luar Pramuka wajib, 
                                                atau tidak mengikuti Pramuka sama sekali. Kehilangan kesempatan 
                                                pengembangan keterampilan sosial dan kepemimpinan.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tidak Berkembang</strong></td>
                                            <td><span class="score-badge score-2">2</span></td>
                                            <td>
                                                Partisipasi pasif dengan kehadiran di bawah 75%. Tidak menunjukkan 
                                                perkembangan skill signifikan, hadir karena kewajiban tanpa motivasi internal. 
                                                Perlu evaluasi kesesuaian minat.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Berkembang</strong></td>
                                            <td><span class="score-badge score-3">3</span></td>
                                            <td>
                                                Partisipasi aktif dengan kehadiran di atas 75%. Progress terukur dalam 
                                                keterampilan, kontribusi konsisten dalam tim, berani tampil dalam pentas 
                                                internal, menunjukkan sportivitas dan kerjasama yang baik.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Sangat Berkembang</strong></td>
                                            <td><span class="score-badge score-4">4</span></td>
                                            <td>
                                                Dedikasi luar biasa dengan kehadiran hampir sempurna (>90%). Motor penggerak 
                                                dalam kelompok, meraih prestasi tingkat antar sekolah atau lebih tinggi. 
                                                Berhasil mengintegrasikan pembelajaran ekstrakurikuler ke kehidupan sehari-hari.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Visualization Section -->
        <div class="viz-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="bi bi-graph-up"></i>
                </div>
                <h2 class="section-title">Visualisasi Data Penilaian</h2>
                <p class="section-subtitle">Representasi grafis prestasi siswa berdasarkan data aktual</p>
            </div>
            
            <div class="row">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <h4 class="chart-title">
                            <i class="bi bi-bar-chart"></i>
                            Peringkat 10 Besar Siswa Teladan
                        </h4>
                        <div class="chart-bars">
                            @php
                                $topStudents = [
                                    ['name' => 'Ahmad Faaris', 'score' => 95.2],
                                    ['name' => 'Nabilah', 'score' => 93.8],
                                    ['name' => 'M. Rayyan', 'score' => 91.5],
                                    ['name' => 'Khansa S.', 'score' => 89.7],
                                    ['name' => 'Zainab L.', 'score' => 88.3],
                                ];
                            @endphp
                            @foreach($topStudents as $student)
                                <div class="d-flex align-items-center mb-3">
                                    <div style="width: 120px; font-weight: 600;">{{ $student['name'] }}</div>
                                    <div class="flex-grow-1">
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $student['score'] }}%; background: linear-gradient(90deg, var(--primary-color), var(--primary-light));"
                                                 aria-valuenow="{{ $student['score'] }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ $student['score'] }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="chart-container">
                        <h4 class="chart-title">
                            <i class="bi bi-pie-chart"></i>
                            Distribusi Bobot Kriteria
                        </h4>
                        <div class="text-center">
                            <canvas id="pieChart" width="300" height="300"></canvas>
                            <p class="text-muted mt-3">
                                Visualisasi proporsi kontribusi setiap kriteria terhadap nilai akhir
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Search & Filter Section -->
        <div class="search-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="bi bi-search"></i>
                </div>
                <h2 class="section-title">Akses Informasi Nilai Siswa</h2>
                <p class="section-subtitle">
                    Wali siswa dapat mengakses rincian komprehensif nilai untuk setiap kriteria penilaian
                </p>
            </div>
            
            <form action="{{ route('cek.nilai') }}" method="GET">
                <div class="search-box">
                    <input type="text" class="search-input" name="search" 
                           placeholder="Masukkan NISN atau nama lengkap siswa..." required>
                    <button type="submit" class="search-btn">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                
                <div class="filter-group">
                    <div class="filter-item">
                        <label>Kelas</label>
                        <select class="filter-select" name="kelas">
                            <option value="">Semua Kelas</option>
                            <option value="6A">Kelas 6A</option>
                            <option value="6B">Kelas 6B</option>
                            <option value="6C">Kelas 6C</option>
                            <option value="6D">Kelas 6D</option>
                        </select>
                    </div>
                    
                    <div class="filter-item">
                        <label>Semester</label>
                        <select class="filter-select" name="semester">
                            <option value="">Semester Aktif</option>
                            <option value="ganjil">Ganjil</option>
                            <option value="genap">Genap</option>
                        </select>
                    </div>
                    
                    <div class="filter-item">
                        <label>Tahun Ajaran</label>
                        <select class="filter-select" name="tahun">
                            <option value="">{{ date('Y') }}/{{ date('Y') + 1 }}</option>
                            <option value="2024">2024/2025</option>
                            <option value="2023">2023/2024</option>
                        </select>
                    </div>
                </div>
                
                <div class="view-toggle">
                    <button type="button" class="toggle-btn active" onclick="setViewMode('detail')">
                        <i class="bi bi-list-ul"></i> Detail Skor
                    </button>
                    <button type="button" class="toggle-btn" onclick="setViewMode('ranking')">
                        <i class="bi bi-trophy"></i> Peringkat
                    </button>
                    <button type="button" class="toggle-btn" onclick="setViewMode('progress')">
                        <i class="bi bi-graph-up-arrow"></i> Progres
                    </button>
                </div>
                
                <div class="action-buttons">
                    <button type="button" class="action-btn primary">
                        <i class="bi bi-file-earmark-pdf"></i> Unduh PDF
                    </button>
                    <button type="button" class="action-btn secondary">
                        <i class="bi bi-printer"></i> Cetak Laporan
                    </button>
                    <button type="button" class="action-btn secondary">
                        <i class="bi bi-envelope"></i> Email Hasil
                    </button>
                </div>
            </form>
            
            <div class="alert alert-info mt-4">
                <i class="bi bi-shield-lock me-2"></i>
                <strong>Kebijakan Privasi Data:</strong> Informasi nilai bersifat rahasia dan hanya dapat diakses 
                oleh wali siswa yang bersangkutan sesuai dengan regulasi perlindungan data pribadi. 
                Data ini tidak untuk disebarluaskan atau dibandingkan dengan siswa lain di luar kepentingan evaluasi akademik.
            </div>
        </div>
        
        <!-- FAQ Section -->
        <div class="faq-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="bi bi-question-circle"></i>
                </div>
                <h2 class="section-title">Panduan dan Pertanyaan Umum</h2>
                <p class="section-subtitle">Informasi komprehensif mengenai sistem penilaian</p>
            </div>
            
            <div class="faq-grid">
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="bi bi-patch-question"></i>
                        Apa yang dimaksud dengan bobot kriteria?
                    </div>
                    <div class="faq-answer">
                        Bobot kriteria merupakan nilai kepentingan relatif setiap kriteria dalam sistem evaluasi. 
                        Sebagai contoh, nilai raport umum dengan bobot 40.83% memiliki pengaruh lebih signifikan 
                        dibandingkan ekstrakurikuler (2.78%) dalam penentuan siswa teladan.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="bi bi-patch-question"></i>
                        Bagaimana mekanisme kerja metode ROC-SMART?
                    </div>
                    <div class="faq-answer">
                        ROC (Rank Order Centroid) menentukan bobot kriteria berdasarkan urutan prioritas institusional, 
                        sementara SMART (Simple Multi Attribute Rating Technique) menormalisasi nilai heterogen 
                        ke dalam skala uniform 0-1, menghasilkan skor preferensi yang adil dan komparatif.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="bi bi-patch-question"></i>
                        Kapan periode pembaruan data nilai?
                    </div>
                    <div class="faq-answer">
                        Pembaruan data dilakukan secara berkala: nilai harian diperbarui setiap akhir bulan, 
                        sementara nilai raport diperbarui setiap akhir semester. Pembaruan dilakukan oleh 
                        wali kelas masing-masing melalui sistem informasi sekolah.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="bi bi-patch-question"></i>
                        Prosedur koreksi data yang keliru?
                    </div>
                    <div class="faq-answer">
                        Apabila terdapat kesalahan data, wali siswa dapat mengajukan verifikasi kepada wali kelas 
                        dengan menyertakan bukti pendukung. Perubahan yang telah diverifikasi akan langsung 
                        terintegrasi dalam sistem secara real-time.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="bi bi-patch-question"></i>
                        Mengapa skor siswa dapat berubah?
                    </div>
                    <div class="faq-answer">
                        Perubahan skor dapat disebabkan oleh: (1) perkembangan nilai individual siswa, 
                        (2) perubahan nilai siswa lain yang mempengaruhi proses normalisasi, 
                        atau (3) penyesuaian bobot kriteria berdasarkan kebijakan sekolah.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="bi bi-patch-question"></i>
                        Apakah nilai siswa lain dapat diakses?
                    </div>
                    <div class="faq-answer">
                        Sesuai dengan prinsip kerahasiaan akademik, setiap wali hanya memiliki akses 
                        terhadap nilai anaknya sendiri. Informasi peringkat umum dapat diakses 
                        tanpa menampilkan detail nilai individual siswa lain.
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Floating Action Button -->
    <div class="fab" onclick="window.location.href='{{ route('cek.nilai') }}'">
        <i class="bi bi-search"></i>
        <span class="fab-tooltip">Akses Nilai Siswa</span>
    </div>
    
    <!-- Tour Overlay (Hidden by default) -->
    <div class="tour-overlay" id="tourOverlay"></div>
    <div class="tour-tooltip" id="tourTooltip" style="display: none;">
        <h4>Selamat Datang</h4>
        <p>Kami akan memandu Anda dalam menggunakan sistem penilaian siswa teladan.</p>
        <div class="tour-buttons">
            <button class="tour-btn skip" onclick="skipTour()">Lewati</button>
            <button class="tour-btn next" onclick="nextTourStep()">Lanjut</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle Kriteria Accordion
    function toggleKriteria(element) {
        const item = element.parentElement;
        const wasActive = item.classList.contains('active');
        
        // Close all items
        document.querySelectorAll('.kriteria-item').forEach(el => {
            el.classList.remove('active');
        });
        
        // Open clicked item if it wasn't active
        if (!wasActive) {
            item.classList.add('active');
        }
    }
    
    // View Mode Toggle
    function setViewMode(mode) {
        document.querySelectorAll('.toggle-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.closest('.toggle-btn').classList.add('active');
        
        // Here you would handle the actual view mode change
        showToast('Mode tampilan: ' + mode, 'info');
    }
    
    // Toast Notification
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    // Tour Functions
    let tourStep = 0;
    const tourSteps = [
        { element: '.search-box', text: 'Masukkan NISN atau nama siswa pada kolom pencarian' },
        { element: '.filter-group', text: 'Gunakan filter untuk mempersempit hasil pencarian' },
        { element: '.view-toggle', text: 'Pilih mode tampilan sesuai kebutuhan' },
        { element: '.action-buttons', text: 'Unduh atau cetak laporan penilaian' }
    ];
    
    function startTour() {
        const isFirstVisit = !localStorage.getItem('tourCompleted');
        if (isFirstVisit) {
            document.getElementById('tourOverlay').style.display = 'block';
            document.getElementById('tourTooltip').style.display = 'block';
            showTourStep(0);
        }
    }
    
    function showTourStep(step) {
        if (step >= tourSteps.length) {
            endTour();
            return;
        }
        
        const currentStep = tourSteps[step];
        const element = document.querySelector(currentStep.element);
        const tooltip = document.getElementById('tourTooltip');
        
        if (element) {
            const rect = element.getBoundingClientRect();
            tooltip.style.top = rect.bottom + 10 + 'px';
            tooltip.style.left = rect.left + 'px';
            tooltip.querySelector('p').textContent = currentStep.text;
        }
        
        tourStep = step;
    }
    
    function nextTourStep() {
        showTourStep(tourStep + 1);
    }
    
    function skipTour() {
        endTour();
    }
    
    function endTour() {
        document.getElementById('tourOverlay').style.display = 'none';
        document.getElementById('tourTooltip').style.display = 'none';
        localStorage.setItem('tourCompleted', 'true');
        showToast('Tur dapat diakses kembali melalui menu bantuan', 'info');
    }
    
    // Simple Pie Chart Drawing
    function drawPieChart() {
        const canvas = document.getElementById('pieChart');
        if (!canvas) return;
        
        const ctx = canvas.getContext('2d');
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = 100;
        
        const data = [
            { label: 'Nilai Umum', value: 40.83, color: '#ff6b35' },
            { label: 'Nilai Diniyah', value: 24.17, color: '#ff8c5a' },
            { label: 'Akhlak', value: 15.83, color: '#ffa374' },
            { label: 'Hafalan', value: 10.28, color: '#ffb891' },
            { label: 'Kehadiran', value: 6.11, color: '#ffcdb0' },
            { label: 'Ekskul', value: 2.78, color: '#ffe2cf' }
        ];
        
        let currentAngle = -Math.PI / 2;
        
        data.forEach(segment => {
            const sliceAngle = (segment.value / 100) * 2 * Math.PI;
            
            // Draw slice
            ctx.beginPath();
            ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
            ctx.lineTo(centerX, centerY);
            ctx.fillStyle = segment.color;
            ctx.fill();
            
            // Draw label
            const labelX = centerX + Math.cos(currentAngle + sliceAngle / 2) * (radius * 0.7);
            const labelY = centerY + Math.sin(currentAngle + sliceAngle / 2) * (radius * 0.7);
            
            ctx.fillStyle = 'white';
            ctx.font = 'bold 12px Arial';
            ctx.textAlign = 'center';
            ctx.fillText(segment.value + '%', labelX, labelY);
            
            currentAngle += sliceAngle;
        });
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Draw pie chart
        drawPieChart();
        
        // Start tour for first-time visitors
        // startTour(); // Uncomment to enable tour
        
        // Animate progress bars on scroll
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'none';
                    setTimeout(() => {
                        entry.target.style.animation = '';
                    }, 10);
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.progress-fill').forEach(el => {
            observer.observe(el);
        });
    });
    
    // Handle form submission
    document.querySelector('form')?.addEventListener('submit', function(e) {
        const searchInput = this.querySelector('input[name="search"]');
        if (!searchInput.value.trim()) {
            e.preventDefault();
            showToast('Mohon masukkan NISN atau nama siswa', 'error');
            searchInput.focus();
        }
    });
</script>
@endsection