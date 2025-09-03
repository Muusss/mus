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
        transition: max-height 0.3s ease;
    }
    
    .kriteria-item.active .kriteria-content {
        max-height: 600px;
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
                <h1 class="hero-title">Sistem Penilaian Siswa Teladan yang Transparan & Adil</h1>
                <p class="hero-subtitle">
                    SPK (Sistem Pendukung Keputusan) adalah teknologi pintar yang membantu sekolah memilih siswa teladan 
                    secara objektif. Tidak ada lagi penilaian 'katanya' atau 'kira-kira' – semua berdasarkan data terukur!
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
                <h3 class="benefit-title">📈 Objektif & Terukur</h3>
                <p class="benefit-desc">
                    Setiap nilai punya dasar yang jelas. Bebas dari unsur subjektivitas. 
                    Penilaian berdasarkan data real yang dapat diverifikasi.
                </p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="bi bi-eye"></i>
                </div>
                <h3 class="benefit-title">👁️ Transparan untuk Semua</h3>
                <p class="benefit-desc">
                    Bapak/Ibu bisa lihat detail penilaian anak. Kriteria terbuka untuk dipahami. 
                    Akses mudah kapan saja, dimana saja.
                </p>
            </div>
            
            <div class="benefit-card">
                <div class="benefit-icon">
                    <i class="bi bi-lightning-charge"></i>
                </div>
                <h3 class="benefit-title">⚡ Cepat & Akurat</h3>
                <p class="benefit-desc">
                    Hasil langsung terupdate setiap ada perubahan nilai. 
                    Perhitungan otomatis tanpa kesalahan manual.
                </p>
            </div>
        </div>
        
        <!-- Method Info Box -->
        <div class="method-box">
            <h4>
                <i class="bi bi-lightbulb-fill"></i>
                Fun Fact: Kami pakai kombinasi 2 metode canggih!
            </h4>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-start gap-3">
                        <span class="method-badge">ROC</span>
                        <div>
                            <strong>Rank Order Centroid</strong>
                            <p class="mb-0 text-muted">Menentukan bobot kepentingan setiap kriteria secara matematis berdasarkan prioritas sekolah</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-start gap-3">
                        <span class="method-badge">SMART</span>
                        <div>
                            <strong>Simple Multi Attribute Rating</strong>
                            <p class="mb-0 text-muted">Menghitung skor akhir dengan normalisasi nilai ke skala 0-1 untuk keadilan</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3 text-center">
                <strong>Hasilnya? Penilaian yang super adil! 🎯</strong>
            </div>
        </div>
        
        <!-- Kriteria Section -->
        <div class="kriteria-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="bi bi-clipboard-check"></i>
                </div>
                <h2 class="section-title">6 Kriteria Penilaian Siswa Teladan</h2>
                <p class="section-subtitle">
                    Kami menilai 6 aspek penting untuk menentukan siswa teladan. 
                    Klik setiap kriteria untuk detail lengkapnya:
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
                                <span class="kriteria-bobot">Bobot: 30%</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down kriteria-toggle"></i>
                    </div>
                    <div class="kriteria-content">
                        <div class="kriteria-content-inner">
                            <div class="kriteria-detail">
                                <div class="detail-box">
                                    <h4><i class="bi bi-question-circle"></i> Mengapa Penting?</h4>
                                    <p>Prestasi akademik menunjukkan kesungguhan belajar dan pemahaman materi pelajaran. Ini adalah fondasi utama pendidikan.</p>
                                </div>
                                <div class="detail-box">
                                    <h4><i class="bi bi-calculator"></i> Cara Mengukur</h4>
                                    <ul>
                                        <li><i class="bi bi-check-circle"></i> Rata-rata nilai semua mapel umum</li>
                                        <li><i class="bi bi-check-circle"></i> Skala penilaian 0-100</li>
                                        <li><i class="bi bi-check-circle"></i> Update setiap akhir semester</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="progress-visual">
                                <strong>Contoh Visual Nilai:</strong>
                                <div class="progress-bar-custom">
                                    <div class="progress-fill" style="width: 85%;">85/100</div>
                                </div>
                                <div class="progress-label">
                                    <span>Anak Bapak/Ibu: 85</span>
                                    <span>Rata-rata kelas: 78</span>
                                </div>
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
                                <span class="kriteria-bobot">Bobot: 25%</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down kriteria-toggle"></i>
                    </div>
                    <div class="kriteria-content">
                        <div class="kriteria-content-inner">
                            <div class="kriteria-detail">
                                <div class="detail-box">
                                    <h4><i class="bi bi-question-circle"></i> Mengapa Penting?</h4>
                                    <p>Membentuk karakter islami yang kuat sesuai visi sekolah. Menjadi bekal akhirat dan dunia.</p>
                                </div>
                                <div class="detail-box">
                                    <h4><i class="bi bi-book"></i> Mata Pelajaran</h4>
                                    <ul>
                                        <li><i class="bi bi-check-circle"></i> Al-Qur'an & Tajwid</li>
                                        <li><i class="bi bi-check-circle"></i> Hadits</li>
                                        <li><i class="bi bi-check-circle"></i> Fiqih</li>
                                        <li><i class="bi bi-check-circle"></i> Aqidah Akhlak</li>
                                    </ul>
                                </div>
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
                                    Akhlak & Sikap
                                </h3>
                                <span class="kriteria-bobot">Bobot: 20%</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down kriteria-toggle"></i>
                    </div>
                    <div class="kriteria-content">
                        <div class="kriteria-content-inner">
                            <div class="kriteria-detail">
                                <div class="detail-box">
                                    <h4><i class="bi bi-question-circle"></i> Mengapa Penting?</h4>
                                    <p>Siswa teladan bukan hanya pintar, tapi juga berakhlak mulia. Cerminan keberhasilan pendidikan karakter.</p>
                                </div>
                                <div class="detail-box">
                                    <h4><i class="bi bi-star"></i> Indikator Penilaian</h4>
                                    <ul>
                                        <li><i class="bi bi-check-circle"></i> Sopan santun</li>
                                        <li><i class="bi bi-check-circle"></i> Tanggung jawab</li>
                                        <li><i class="bi bi-check-circle"></i> Kerjasama</li>
                                        <li><i class="bi bi-check-circle"></i> Kejujuran</li>
                                    </ul>
                                </div>
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
                                <span class="kriteria-bobot">Bobot: 10%</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down kriteria-toggle"></i>
                    </div>
                    <div class="kriteria-content">
                        <div class="kriteria-content-inner">
                            <div class="kriteria-detail">
                                <div class="detail-box">
                                    <h4><i class="bi bi-trophy"></i> Level Pencapaian</h4>
                                    <ul>
                                        <li><i class="bi bi-star"></i> 1/2 Juz: Skor 25</li>
                                        <li><i class="bi bi-star"></i> 1 Juz: Skor 50</li>
                                        <li><i class="bi bi-star"></i> 2 Juz: Skor 75</li>
                                        <li><i class="bi bi-star"></i> >2 Juz: Skor 100</li>
                                    </ul>
                                </div>
                                <div class="detail-box">
                                    <h4><i class="bi bi-check2-all"></i> Metode Penilaian</h4>
                                    <p>Ujian lisan dengan tahsin yang baik. Penilaian oleh tim tahfidz sekolah.</p>
                                </div>
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
                                <span class="kriteria-bobot">Bobot: 10%</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down kriteria-toggle"></i>
                    </div>
                    <div class="kriteria-content">
                        <div class="kriteria-content-inner">
                            <div class="kriteria-detail">
                                <div class="detail-box">
                                    <h4><i class="bi bi-calculator"></i> Cara Hitung</h4>
                                    <p><code>Kehadiran = (Hari Masuk / Total Hari Efektif) × 100%</code></p>
                                </div>
                                <div class="detail-box">
                                    <h4><i class="bi bi-list-check"></i> Kategori</h4>
                                    <ul>
                                        <li><i class="bi bi-check-circle"></i> 96-100%: Sangat Baik</li>
                                        <li><i class="bi bi-check-circle"></i> 91-95%: Baik</li>
                                        <li><i class="bi bi-check-circle"></i> 86-90%: Cukup</li>
                                        <li><i class="bi bi-check-circle"></i> <85%: Perlu Perhatian</li>
                                    </ul>
                                </div>
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
                                <span class="kriteria-bobot">Bobot: 5%</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-down kriteria-toggle"></i>
                    </div>
                    <div class="kriteria-content">
                        <div class="kriteria-content-inner">
                            <div class="kriteria-detail">
                                <div class="detail-box">
                                    <h4><i class="bi bi-award"></i> Penilaian Berdasarkan</h4>
                                    <ul>
                                        <li><i class="bi bi-check-circle"></i> Keaktifan mengikuti kegiatan</li>
                                        <li><i class="bi bi-check-circle"></i> Prestasi/juara yang diraih</li>
                                        <li><i class="bi bi-check-circle"></i> Kontribusi dalam tim</li>
                                    </ul>
                                </div>
                                <div class="detail-box">
                                    <h4><i class="bi bi-flag"></i> Kegiatan Tersedia</h4>
                                    <p>Pramuka, Seni, Olahraga, Tahfidz, Robotik, dan lainnya</p>
                                </div>
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
                <p class="section-subtitle">Lihat gambaran umum prestasi siswa dalam bentuk grafik</p>
            </div>
            
            <div class="row">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <h4 class="chart-title">
                            <i class="bi bi-bar-chart"></i>
                            Top 10 Siswa Teladan
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
                                                {{ $student['score'] }}
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
                            Distribusi Nilai per Kriteria
                        </h4>
                        <div class="text-center">
                            <canvas id="pieChart" width="300" height="300"></canvas>
                            <p class="text-muted mt-3">
                                Grafik menunjukkan kontribusi setiap kriteria terhadap nilai akhir
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
                <h2 class="section-title">Cek Nilai Anak Anda</h2>
                <p class="section-subtitle">
                    Wali siswa dapat melihat rincian nilai untuk setiap kriteria penilaian
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
                        <i class="bi bi-file-earmark-pdf"></i> Download PDF
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
                <strong>Privasi Data:</strong> Informasi nilai bersifat rahasia dan hanya dapat diakses oleh wali siswa yang bersangkutan. 
                Data ini tidak untuk disebarluaskan atau dibandingkan dengan siswa lain di luar kepentingan evaluasi pribadi.
            </div>
        </div>
        
        <!-- FAQ Section -->
        <div class="faq-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="bi bi-question-circle"></i>
                </div>
                <h2 class="section-title">Panduan & Pertanyaan Umum</h2>
                <p class="section-subtitle">Temukan jawaban untuk pertanyaan yang sering diajukan</p>
            </div>
            
            <div class="faq-grid">
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="bi bi-patch-question"></i>
                        Apa itu bobot kriteria?
                    </div>
                    <div class="faq-answer">
                        Bobot adalah tingkat kepentingan setiap kriteria. Misal, nilai akademik (30%) 
                        lebih berpengaruh dibanding ekstrakurikuler (5%) dalam menentukan siswa teladan.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="bi bi-patch-question"></i>
                        Bagaimana cara ROC-SMART bekerja?
                    </div>
                    <div class="faq-answer">
                        ROC menentukan bobot berdasarkan prioritas, SMART menormalisasi nilai ke skala 0-1. 
                        Hasilnya adalah skor preferensi yang adil untuk semua siswa.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="bi bi-patch-question"></i>
                        Kapan data nilai diperbarui?
                    </div>
                    <div class="faq-answer">
                        Setiap akhir bulan untuk nilai harian dan akhir semester untuk nilai raport. 
                        Pembaruan dilakukan oleh wali kelas masing-masing.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="bi bi-patch-question"></i>
                        Bagaimana jika ada kesalahan data?
                    </div>
                    <div class="faq-answer">
                        Segera hubungi wali kelas untuk verifikasi dan koreksi data. 
                        Perubahan akan langsung terlihat setelah diperbarui.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="bi bi-patch-question"></i>
                        Mengapa skor anak saya berubah?
                    </div>
                    <div class="faq-answer">
                        Skor dapat berubah karena: (1) perkembangan nilai siswa, (2) perubahan nilai siswa lain 
                        yang mempengaruhi normalisasi, atau (3) penyesuaian bobot kriteria.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <i class="bi bi-patch-question"></i>
                        Bisakah melihat nilai siswa lain?
                    </div>
                    <div class="faq-answer">
                        Tidak. Setiap wali hanya dapat melihat nilai anak sendiri. 
                        Namun peringkat umum dapat dilihat tanpa detail nilai.
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Floating Action Button -->
    <div class="fab" onclick="window.location.href='{{ route('cek.nilai') }}'">
        <i class="bi bi-search"></i>
        <span class="fab-tooltip">Cek Nilai Anak Saya</span>
    </div>
    
    <!-- Tour Overlay (Hidden by default) -->
    <div class="tour-overlay" id="tourOverlay"></div>
    <div class="tour-tooltip" id="tourTooltip" style="display: none;">
        <h4>Selamat Datang!</h4>
        <p>Mari kami tunjukkan cara menggunakan sistem penilaian siswa teladan.</p>
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
        { element: '.search-box', text: 'Masukkan NISN atau nama anak Anda di sini' },
        { element: '.filter-group', text: 'Filter hasil berdasarkan kelas dan periode' },
        { element: '.view-toggle', text: 'Pilih mode tampilan yang Anda inginkan' },
        { element: '.action-buttons', text: 'Download atau cetak laporan nilai' }
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
        showToast('Anda dapat memulai tur kembali dari menu bantuan', 'info');
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
            { label: 'Nilai Umum', value: 30, color: '#ff6b35' },
            { label: 'Nilai Diniyah', value: 25, color: '#ff8c5a' },
            { label: 'Akhlak', value: 20, color: '#ffa374' },
            { label: 'Hafalan', value: 10, color: '#ffb891' },
            { label: 'Kehadiran', value: 10, color: '#ffcdb0' },
            { label: 'Ekskul', value: 5, color: '#ffe2cf' }
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