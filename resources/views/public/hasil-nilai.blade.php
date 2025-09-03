@extends('layouts.public')

@section('title', 'Hasil Pencarian Nilai')

@section('styles')
<style>
    /* Result container */
    .result-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    /* Result card */
    .result-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 30px;
    }
    
    .result-header {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 30px;
    }
    
    .result-header h2 {
        margin: 0;
        font-weight: 700;
        font-size: 1.8rem;
    }
    
    .result-header .timestamp {
        opacity: 0.9;
        font-size: 0.95rem;
        margin-top: 10px;
    }
    
    /* Student info */
    .student-info {
        padding: 30px;
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .info-item {
        display: flex;
        flex-direction: column;
    }
    
    .info-label {
        font-size: 0.9rem;
        color: #666;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .info-value {
        font-size: 1.1rem;
        color: var(--dark-color);
        font-weight: 700;
    }
    
    /* Score table */
    .score-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .score-table thead {
        background: linear-gradient(135deg, #f6f8fb, #fff);
    }
    
    .score-table th {
        padding: 15px;
        text-align: left;
        font-weight: 700;
        color: var(--dark-color);
        border-bottom: 2px solid #e9ecef;
        font-size: 0.95rem;
    }
    
    .score-table td {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .score-table tbody tr:hover {
        background: #f8f9fa;
    }
    
    .kriteria-code {
        display: inline-block;
        background: var(--primary-color);
        color: white;
        padding: 3px 8px;
        border-radius: 5px;
        font-weight: 700;
        font-size: 0.85rem;
        margin-right: 8px;
    }
    
    .nilai-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .nilai-tinggi {
        background: rgba(34, 197, 94, 0.1);
        color: #16a34a;
    }
    
    .nilai-sedang {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }
    
    .nilai-rendah {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }
    
    /* Final score section */
    .final-score {
        padding: 30px;
        background: linear-gradient(135deg, #f6f8fb, #fff);
        text-align: center;
    }
    
    .score-display {
        display: flex;
        justify-content: center;
        gap: 40px;
        margin: 30px 0;
        flex-wrap: wrap;
    }
    
    .score-item {
        text-align: center;
    }
    
    .score-item-label {
        font-size: 0.95rem;
        color: #666;
        margin-bottom: 10px;
        font-weight: 600;
    }
    
    .score-item-value {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .rank-badge {
        display: inline-block;
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: white;
        padding: 10px 25px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
    }
    
    /* Action buttons */
    .action-buttons {
        padding: 30px;
        background: white;
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .btn-action {
        padding: 12px 30px;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
    }
    
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    
    .btn-success {
        background: #28a745;
        color: white;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    /* Empty state */
    .empty-state {
        padding: 60px 30px;
        text-align: center;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #ddd;
        margin-bottom: 20px;
    }
    
    .empty-state h3 {
        color: var(--dark-color);
        margin-bottom: 15px;
    }
    
    .empty-state p {
        color: #666;
        margin-bottom: 30px;
    }
    
    /* Notes section */
    .notes-section {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 20px;
        margin: 20px 30px;
        border-radius: 10px;
    }
    
    .notes-section h4 {
        color: #856404;
        margin-bottom: 10px;
        font-weight: 700;
    }
    
    .notes-section p {
        color: #856404;
        margin-bottom: 5px;
        line-height: 1.6;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .score-display {
            flex-direction: column;
            gap: 20px;
        }
        
        .score-table {
            font-size: 0.9rem;
        }
        
        .score-table th,
        .score-table td {
            padding: 10px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .btn-action {
            width: 100%;
            justify-content: center;
        }
    }
    
    @media print {
        .action-buttons,
        .navbar-custom,
        .page-header .hero-buttons {
            display: none !important;
        }
        
        .result-card {
            box-shadow: none;
            border: 1px solid #ddd;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4" style="padding-top: 100px;">
    @if(isset($hasilPencarian) && $hasilPencarian['siswa'])
        <!-- Result Card -->
        <div class="result-container">
            <div class="result-card">
                <!-- Header -->
                <div class="result-header">
                    <h2><i class="bi bi-clipboard-data me-2"></i> Hasil Pencarian Nilai Siswa</h2>
                    <div class="timestamp">
                        <i class="bi bi-clock"></i> Waktu akses: {{ $hasilPencarian['waktuAkses'] }}
                    </div>
                </div>
                
                <!-- Student Info -->
                <div class="student-info">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nama Siswa</span>
                            <span class="info-value">{{ $hasilPencarian['siswa']->nama_siswa }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">NISN</span>
                            <span class="info-value">{{ $hasilPencarian['siswa']->nis }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kelas</span>
                            <span class="info-value">{{ $hasilPencarian['siswa']->kelas }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Jenis Kelamin</span>
                            <span class="info-value">{{ $hasilPencarian['siswa']->jk == 'Lk' ? 'Laki-laki' : 'Perempuan' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Periode</span>
                            <span class="info-value">{{ $hasilPencarian['periode']->nama_periode }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Score Table -->
                <div class="p-4">
                    <h4 class="mb-3 fw-bold">Rincian Nilai per Kriteria</h4>
                    <div class="table-responsive">
                        <table class="score-table">
                            <thead>
                                <tr>
                                    <th width="35%">Kriteria</th>
                                    <th width="15%" class="text-center">Nilai Asli</th>
                                    <th width="15%" class="text-center">Nilai Utilitas</th>
                                    <th width="15%" class="text-center">Bobot</th>
                                    <th width="20%" class="text-center">Kontribusi Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hasilPencarian['kriteria'] as $krit)
                                    @php
                                        $nilai = $hasilPencarian['penilaian'][$krit->id] ?? null;
                                        $nilaiAsli = $nilai ? $nilai->nilai_asli : 0;
                                        $nilaiNormal = $nilai ? $nilai->nilai_normal : 0;
                                        $kontribusi = $nilaiNormal * $krit->bobot_roc;
                                        
                                        // Determine badge class
                                        $badgeClass = 'nilai-rendah';
                                        if ($nilaiAsli >= 3) $badgeClass = 'nilai-tinggi';
                                        elseif ($nilaiAsli >= 2) $badgeClass = 'nilai-sedang';
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="kriteria-code">{{ $krit->kode }}</span>
                                            {{ $krit->kriteria }}
                                        </td>
                                        <td class="text-center">
                                            <span class="nilai-badge {{ $badgeClass }}">
                                                {{ $nilaiAsli ?: '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            {{ number_format($nilaiNormal, 4) }}
                                        </td>
                                        <td class="text-center">
                                            {{ number_format($krit->bobot_roc, 4) }}
                                        </td>
                                        <td class="text-center">
                                            <strong>{{ number_format($kontribusi, 4) }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Final Score -->
                @if($hasilPencarian['nilaiAkhir'])
                    <div class="final-score">
                        <h3 class="mb-4 fw-bold">Skor Preferensi & Peringkat</h3>
                        
                        <div class="score-display">
                            <div class="score-item">
                                <div class="score-item-label">Total Skor Preferensi</div>
                                <div class="score-item-value">{{ number_format($hasilPencarian['nilaiAkhir']->total, 4) }}</div>
                            </div>
                            
                            @if($hasilPencarian['rankingKelas'])
                                <div class="score-item">
                                    <div class="score-item-label">Peringkat di Kelas {{ $hasilPencarian['siswa']->kelas }}</div>
                                    <div class="score-item-value">{{ $hasilPencarian['rankingKelas'] }}</div>
                                    <small class="text-muted">dari {{ $hasilPencarian['totalSiswaKelas'] }} siswa</small>
                                </div>
                            @endif
                        </div>
                        
                        @if($hasilPencarian['rankingKelas'] == 1)
                            <div class="rank-badge">
                                <i class="bi bi-trophy-fill"></i> Siswa Teladan Kelas {{ $hasilPencarian['siswa']->kelas }}
                            </div>
                        @elseif($hasilPencarian['rankingKelas'] <= 3)
                            <div class="rank-badge">
                                <i class="bi bi-award-fill"></i> Nominasi Siswa Teladan
                            </div>
                        @elseif($hasilPencarian['rankingKelas'] <= 10)
                            <div class="rank-badge">
                                <i class="bi bi-star-fill"></i> 10 Besar Kelas
                            </div>
                        @endif
                    </div>
                @else
                    <div class="empty-state">
                        <i class="bi bi-exclamation-circle"></i>
                        <h3>Nilai Belum Tersedia</h3>
                        <p>Nilai untuk periode ini belum diproses. Silakan hubungi wali kelas untuk informasi lebih lanjut.</p>
                    </div>
                @endif
                
                <!-- Notes -->
                <div class="notes-section">
                    <h4><i class="bi bi-info-circle me-2"></i>Cara Membaca Hasil</h4>
                    <p><strong>Nilai Asli:</strong> Nilai mentah sesuai skala masing-masing kriteria (1-4)</p>
                    <p><strong>Nilai Utilitas:</strong> Nilai yang sudah dinormalisasi ke skala 0-1</p>
                    <p><strong>Kontribusi Skor:</strong> Hasil perkalian nilai utilitas dengan bobot kriteria</p>
                    <p><strong>Skor Preferensi:</strong> Penjumlahan seluruh kontribusi skor dari semua kriteria</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    @if($hasilPencarian['nilaiAkhir'])
                        <form action="{{ route('nilai.pdf') ?? '#' }}" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="siswa_id" value="{{ $hasilPencarian['siswa']->id }}">
                            <input type="hidden" name="periode_id" value="{{ $hasilPencarian['periode']->id }}">
                            <button type="submit" class="btn-action btn-success">
                                <i class="bi bi-file-earmark-pdf"></i> Unduh PDF
                            </button>
                        </form>
                    @endif
                    
                    <button onclick="window.print()" class="btn-action btn-secondary">
                        <i class="bi bi-printer"></i> Cetak Halaman
                    </button>
                    
                    <a href="{{ route('informasi') }}" class="btn-action btn-primary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Form
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="result-container">
            <div class="empty-state">
                <i class="bi bi-search"></i>
                <h3>Tidak Ada Hasil Pencarian</h3>
                <p>Silakan kembali ke halaman informasi untuk melakukan pencarian nilai.</p>
                <a href="{{ route('informasi') }}" class="btn-action btn-primary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Halaman Informasi
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Auto hide success message after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
@endsection