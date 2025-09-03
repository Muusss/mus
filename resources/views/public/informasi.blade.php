@extends('layouts.public')

@section('title', 'Informasi Sistem Penilaian')

@section('styles')
<style>
    /* Custom styles untuk halaman informasi */
    .info-hero {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 60px 0;
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
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 15px;
    }
    
    .hero-subtitle {
        font-size: 1.2rem;
        opacity: 0.95;
        margin-bottom: 20px;
    }
    
    .hero-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }
    
    .hero-buttons {
        margin-top: 30px;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .hero-btn {
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }
    
    .hero-btn.primary {
        background: white;
        color: var(--primary-color);
    }
    
    .hero-btn.secondary {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        backdrop-filter: blur(10px);
    }
    
    .hero-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
    
    /* Content sections */
    .info-section {
        background: white;
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .section-icon {
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
    
    .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 20px;
    }
    
    .section-lead {
        color: #666;
        font-size: 1.1rem;
        margin-bottom: 25px;
        line-height: 1.6;
    }
    
    /* Kriteria cards */
    .kriteria-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    
    .kriteria-card {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 25px;
        border-left: 4px solid var(--primary-color);
        transition: transform 0.3s;
    }
    
    .kriteria-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .kriteria-code {
        display: inline-block;
        background: var(--primary-color);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 10px;
    }
    
    .kriteria-name {
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 10px;
        font-size: 1.1rem;
    }
    
    .kriteria-desc {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    
    /* Process steps */
    .process-steps {
        display: flex;
        flex-direction: column;
        gap: 25px;
        margin-top: 30px;
    }
    
    .process-step {
        display: flex;
        gap: 20px;
        align-items: start;
    }
    
    .step-number {
        flex-shrink: 0;
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
    }
    
    .step-content h4 {
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 10px;
    }
    
    .step-content p {
        color: #666;
        line-height: 1.6;
    }
    
    /* Search form */
    .search-section {
        background: linear-gradient(135deg, #f6f8fb, #fff);
        border: 2px solid var(--primary-color);
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 8px;
        display: block;
    }
    
    .form-label .required {
        color: var(--primary-color);
    }
    
    .form-control {
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 1rem;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.1);
    }
    
    .form-help {
        font-size: 0.9rem;
        color: #666;
        margin-top: 5px;
    }
    
    .btn-search {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border: none;
        padding: 12px 40px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    
    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(255, 107, 53, 0.3);
    }
    
    .privacy-note {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 15px 20px;
        border-radius: 10px;
        margin-top: 20px;
    }
    
    .privacy-note i {
        color: #ff9800;
        margin-right: 10px;
    }
    
    /* FAQ Accordion */
    .faq-accordion {
        margin-top: 30px;
    }
    
    .faq-item {
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 15px;
        overflow: hidden;
    }
    
    .faq-question {
        padding: 20px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        color: var(--dark-color);
        transition: background 0.3s;
    }
    
    .faq-question:hover {
        background: #f0f0f0;
    }
    
    .faq-question i {
        transition: transform 0.3s;
    }
    
    .faq-item.active .faq-question i {
        transform: rotate(180deg);
    }
    
    .faq-answer {
        padding: 0 20px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
    }
    
    .faq-item.active .faq-answer {
        padding: 0 20px 20px;
        max-height: 500px;
    }
    
    /* Policy section */
    .policy-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 30px;
    }
    
    .policy-card {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 15px;
        text-align: center;
    }
    
    .policy-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #60a5fa, #3b82f6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin: 0 auto 15px;
    }
    
    .policy-title {
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 10px;
    }
    
    .policy-desc {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    
    /* Footer info */
    .footer-info {
        background: linear-gradient(135deg, var(--dark-color), #2c2c2c);
        color: white;
        padding: 40px;
        border-radius: 20px;
        margin-top: 40px;
        text-align: center;
    }
    
    .school-info h3 {
        font-weight: 700;
        margin-bottom: 20px;
    }
    
    .contact-info {
        display: flex;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
        margin-top: 20px;
    }
    
    .contact-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .quick-links {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 30px;
        padding-top: 30px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .quick-links a {
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: opacity 0.3s;
    }
    
    .quick-links a:hover {
        opacity: 0.8;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 1.8rem;
        }
        
        .info-section {
            padding: 25px;
        }
        
        .section-title {
            font-size: 1.5rem;
        }
        
        .kriteria-grid {
            grid-template-columns: 1fr;
        }
        
        .process-step {
            flex-direction: column;
            text-align: center;
        }
        
        .contact-info {
            flex-direction: column;
            align-items: center;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-0">
    <!-- Hero Section -->
    <div class="info-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Informasi Sistem Penilaian Siswa Teladan</h1>
                <p class="hero-subtitle">Transparansi • Objektif • Akuntabel</p>
                @if($periodeAktif ?? null)
                <span class="hero-badge">
                    <i class="bi bi-calendar-check"></i> {{ $periodeAktif->nama_periode ?? 'Periode Aktif' }}
                </span>
                @endif
                <div class="hero-buttons">
                    <a href="{{ route('hasil.publik') }}" class="hero-btn primary">
                        <i class="bi bi-trophy"></i> Lihat Hasil Peringkat
                    </a>
                    <a href="#panduan" class="hero-btn secondary">
                        <i class="bi bi-book"></i> Panduan Penggunaan
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        <!-- Tentang Sistem -->
        <div class="info-section">
            <div class="section-icon">
                <i class="bi bi-info-circle"></i>
            </div>
            <h2 class="section-title">Tentang Sistem Penilaian</h2>
            
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <h3 class="h5 fw-bold mb-3">Apa itu Sistem Pendukung Keputusan?</h3>
                    <p class="text-muted">
                        Sistem Pendukung Keputusan (SPK) merupakan sistem berbasis teknologi yang membantu sekolah dalam menentukan siswa teladan secara objektif dan transparan. Dengan SPK ini, setiap wali siswa dapat memantau perkembangan dan pencapaian putra-putrinya melalui penilaian yang terukur dan adil.
                    </p>
                    <div class="mt-3">
                        <h4 class="h6 fw-bold">Manfaat untuk Wali Siswa:</h4>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-check-circle text-success me-2"></i> Transparansi penuh dalam proses penilaian</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i> Akses mudah ke data nilai dan peringkat</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i> Pemahaman jelas tentang kriteria penilaian</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i> Monitoring perkembangan anak secara berkala</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-6 mb-4">
                    <h3 class="h5 fw-bold mb-3">Metodologi Penilaian</h3>
                    <p class="text-muted mb-3">
                        Sistem kami menggunakan kombinasi dua metode ilmiah yang telah teruji:
                    </p>
                    <div class="alert alert-light border-start border-4 border-primary">
                        <h5 class="alert-heading h6">1. Metode ROC (Rank Order Centroid)</h5>
                        <p class="mb-0 small">Digunakan untuk menentukan bobot kepentingan setiap kriteria berdasarkan prioritas yang ditetapkan sekolah. Metode ini memastikan kriteria yang lebih penting mendapat bobot yang proporsional.</p>
                    </div>
                    <div class="alert alert-light border-start border-4 border-info">
                        <h5 class="alert-heading h6">2. Metode SMART (Simple Multi Attribute Rating Technique)</h5>
                        <p class="mb-0 small">Menghitung nilai akhir siswa dengan mengubah nilai setiap kriteria menjadi nilai utilitas (0-1), kemudian mengalikan dengan bobot kriteria untuk mendapatkan skor preferensi total.</p>
                    </div>
                </div>
            </div>
            
            <h3 class="h5 fw-bold mt-4 mb-3">Kriteria Penilaian</h3>
            <p class="text-muted">Berikut adalah enam kriteria yang digunakan dalam penilaian siswa teladan:</p>
            
            <div class="kriteria-grid">
                <div class="kriteria-card">
                    <span class="kriteria-code">C1</span>
                    <div class="kriteria-name">Nilai Raport Umum</div>
                    <div class="kriteria-desc">Mencakup seluruh mata pelajaran umum dengan rentang nilai 0-100. Kriteria ini memiliki prioritas tertinggi karena mencerminkan prestasi akademik siswa.</div>
                </div>
                
                <div class="kriteria-card">
                    <span class="kriteria-code">C2</span>
                    <div class="kriteria-name">Nilai Raport Diniyah</div>
                    <div class="kriteria-desc">Meliputi mata pelajaran keagamaan seperti Al-Qur'an, Hadits, Fiqih, dan Aqidah. Penting untuk membentuk karakter islami siswa.</div>
                </div>
                
                <div class="kriteria-card">
                    <span class="kriteria-code">C3</span>
                    <div class="kriteria-name">Akhlak</div>
                    <div class="kriteria-desc">Penilaian perilaku dan sikap siswa sehari-hari di sekolah, meliputi kedisiplinan, sopan santun, dan interaksi dengan guru serta teman.</div>
                </div>
                
                <div class="kriteria-card">
                    <span class="kriteria-code">C4</span>
                    <div class="kriteria-name">Hafalan Al-Qur'an</div>
                    <div class="kriteria-desc">Jumlah hafalan juz Al-Qur'an yang telah dikuasai siswa dengan tahsin yang baik.</div>
                </div>
                
                <div class="kriteria-card">
                    <span class="kriteria-code">C5</span>
                    <div class="kriteria-name">Kehadiran</div>
                    <div class="kriteria-desc">Persentase kehadiran siswa dalam kegiatan belajar mengajar selama satu semester.</div>
                </div>
                
                <div class="kriteria-card">
                    <span class="kriteria-code">C6</span>
                    <div class="kriteria-name">Ekstrakurikuler</div>
                    <div class="kriteria-desc">Partisipasi dan prestasi siswa dalam kegiatan ekstrakurikuler sekolah.</div>
                </div>
            </div>
            
            <div class="alert alert-info mt-4">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Catatan:</strong> Bobot setiap kriteria telah ditetapkan oleh tim akademik sekolah melalui rapat dewan guru dan diolah secara otomatis menggunakan metode ROC untuk memastikan objektifitas.
            </div>
        </div>
        
        <!-- Cara Kerja -->
        <div class="info-section">
            <div class="section-icon">
                <i class="bi bi-gear"></i>
            </div>
            <h2 class="section-title">Cara Kerja Sistem</h2>
            <p class="section-lead">Proses penilaian dilakukan melalui langkah-langkah berikut:</p>
            
            <div class="process-steps">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Penetapan Prioritas</h4>
                        <p>Tim akademik sekolah menetapkan urutan prioritas keenam kriteria berdasarkan visi-misi sekolah. Sistem kemudian menghitung bobot setiap kriteria menggunakan metode ROC secara otomatis.</p>
                    </div>
                </div>
                
                <div class="process-step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Normalisasi Nilai</h4>
                        <p>Setiap nilai kriteria dari seluruh siswa dinormalisasi ke skala 0-1 menggunakan metode SMART. Proses ini memastikan perbandingan yang adil antar kriteria yang memiliki skala berbeda.</p>
                    </div>
                </div>
                
                <div class="process-step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Perhitungan Skor Akhir</h4>
                        <p>Nilai normalisasi dikalikan dengan bobot kriteria, kemudian dijumlahkan untuk mendapatkan Skor Preferensi. Siswa dengan skor tertinggi menjadi kandidat siswa teladan.</p>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-success mt-4">
                <i class="bi bi-lightbulb me-2"></i>
                Bayangkan sistem ini seperti lomba dengan beberapa kategori - setiap kategori memiliki "nilai penting" yang berbeda, dan semua dinilai secara adil untuk menentukan pemenang keseluruhan.
            </div>
        </div>
        
        <!-- Form Pencarian -->
        <div class="info-section search-section">
            <div class="section-icon">
                <i class="bi bi-search"></i>
            </div>
            <h2 class="section-title">Cek Nilai Siswa</h2>
            <p class="section-lead">Wali siswa dapat melihat rincian nilai untuk setiap kriteria penilaian. Silakan masukkan data berikut untuk mengakses informasi nilai:</p>
            
            <form action="{{ route('cek.nilai') ?? '#' }}" method="GET" id="formCekNilai">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">NISN atau Nama Siswa <span class="required">*</span></label>
                            <input type="text" class="form-control" name="search" placeholder="Masukkan NISN atau nama lengkap" required>
                            <div class="form-help">Masukkan Nomor Induk Siswa Nasional atau nama lengkap siswa sesuai data sekolah</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Kelas</label>
                            <select class="form-control" name="kelas">
                                <option value="">Pilih Kelas</option>
                                <option value="6A">Kelas 6A</option>
                                <option value="6B">Kelas 6B</option>
                                <option value="6C">Kelas 6C</option>
                                <option value="6D">Kelas 6D</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Periode Penilaian</label>
                            <select class="form-control" name="periode">
                                <option value="">Periode Aktif</option>
                                @foreach($periodes ?? [] as $periode)
                                    <option value="{{ $periode->id }}">{{ $periode->nama_periode }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn-search">
                        <i class="bi bi-search"></i>
                        Tampilkan Nilai
                    </button>
                </div>
            </form>
            
            <div class="privacy-note">
                <i class="bi bi-shield-lock"></i>
                <strong>Privasi Data:</strong> Informasi nilai bersifat rahasia dan hanya dapat diakses oleh wali siswa yang bersangkutan. Data ini tidak untuk disebarluaskan atau dibandingkan dengan siswa lain di luar kepentingan evaluasi pribadi.
            </div>
        </div>
        
        <!-- Panduan Penggunaan -->
        <div class="info-section" id="panduan">
            <div class="section-icon">
                <i class="bi bi-question-circle"></i>
            </div>
            <h2 class="section-title">Panduan Penggunaan</h2>
            <p class="section-lead">Pertanyaan yang Sering Diajukan</p>
            
            <div class="faq-accordion">
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span>Bagaimana jika ada nama siswa yang mirip?</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Gunakan NISN untuk pencarian yang lebih akurat. Jika menggunakan nama, pastikan penulisan sesuai dengan data sekolah termasuk penggunaan huruf kapital.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span>Mengapa skor anak saya berbeda antar periode?</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Skor dapat berubah karena: (1) perkembangan nilai siswa, (2) perubahan nilai siswa lain yang mempengaruhi normalisasi, atau (3) penyesuaian bobot kriteria oleh sekolah.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span>Siapa yang menentukan bobot kriteria?</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Bobot kriteria ditetapkan melalui rapat dewan guru berdasarkan prioritas pendidikan sekolah. Perhitungan bobot menggunakan metode ROC untuk memastikan konsistensi.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span>Bagaimana jika data nilai tampak tidak lengkap?</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Segera hubungi wali kelas untuk verifikasi data. Kemungkinan ada nilai yang belum diinput atau sedang dalam proses validasi.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span>Ke mana saya harus menghubungi untuk koreksi data?</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Hubungi wali kelas terlebih dahulu. Untuk masalah teknis, hubungi bagian administrasi sekolah di jam kerja.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Kebijakan & Privasi -->
        <div class="info-section">
            <div class="section-icon">
                <i class="bi bi-shield-check"></i>
            </div>
            <h2 class="section-title">Kebijakan & Privasi</h2>
            <p class="section-lead">Komitmen Kami terhadap Data Anda</p>
            
            <div class="policy-grid">
                <div class="policy-card">
                    <div class="policy-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                    <h4 class="policy-title">Akuntabilitas Data</h4>
                    <p class="policy-desc">Seluruh data penilaian dikelola dengan sistem yang aman dan hanya dapat diakses oleh pihak yang berwenang.</p>
                </div>
                
                <div class="policy-card">
                    <div class="policy-icon">
                        <i class="bi bi-lock"></i>
                    </div>
                    <h4 class="policy-title">Keamanan Informasi</h4>
                    <p class="policy-desc">Data siswa dilindungi dengan enkripsi dan hanya dapat diakses menggunakan kredensial yang valid.</p>
                </div>
                
                <div class="policy-card">
                    <div class="policy-icon">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <h4 class="policy-title">Hak Koreksi</h4>
                    <p class="policy-desc">Wali siswa berhak mengajukan koreksi jika menemukan ketidaksesuaian data dengan bukti pendukung.</p>
                </div>
                
                <div class="policy-card">
                    <div class="policy-icon">
                        <i class="bi bi-arrow-clockwise"></i>
                    </div>
                    <h4 class="policy-title">Pembaruan Data</h4>
                    <p class="policy-desc">Data nilai diperbarui setiap akhir bulan untuk penilaian harian dan akhir semester untuk nilai raport.</p>
                </div>
            </div>
            
            <div class="alert alert-light border mt-4">
                <i class="bi bi-book me-2"></i>
                Untuk informasi lebih lanjut tentang kebijakan sekolah terkait penilaian siswa teladan, silakan merujuk ke <strong>Buku Panduan Akademik SDIT As Sunnah Cirebon</strong>.
            </div>
        </div>
        
        <!-- Footer Info -->
        <div class="footer-info">
            <div class="school-info">
                <h3>SDIT As Sunnah Cirebon</h3>
                <p>Jl. Pendidikan No. 123, Cirebon</p>
                
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="bi bi-clock"></i>
                        <span>Senin-Jumat, 07.30-15.00 WIB</span>
                    </div>
                    <div class="contact-item">
                        <i class="bi bi-calendar"></i>
                        <span>Sabtu, 07.30-12.00 WIB</span>
                    </div>
                </div>
                
                <div class="quick-links">
                    <a href="{{ url('/') }}">
                        <i class="bi bi-house me-1"></i> Beranda
                    </a>
                    <a href="{{ route('hasil.publik') }}">
                        <i class="bi bi-trophy me-1"></i> Hasil Peringkat
                    </a>
                    <a href="#contact">
                        <i class="bi bi-telephone me-1"></i> Kontak Sekolah
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle FAQ items
    function toggleFaq(element) {
        const faqItem = element.parentElement;
        const wasActive = faqItem.classList.contains('active');
        
        // Close all FAQ items
        document.querySelectorAll('.faq-item').forEach(item => {
            item.classList.remove('active');
        });
        
        // Open clicked item if it wasn't active
        if (!wasActive) {
            faqItem.classList.add('active');
        }
    }
    
    // Form validation
    document.getElementById('formCekNilai')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const search = this.search.value.trim();
        if (!search) {
            alert('Mohon masukkan NISN atau nama siswa');
            return;
        }
        
        // Here you would normally submit the form or make an AJAX request
        alert('Fitur pencarian nilai akan segera tersedia. Silakan hubungi wali kelas untuk informasi nilai.');
    });
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>
@endsection