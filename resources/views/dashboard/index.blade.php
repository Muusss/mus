@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <!-- Mobile Alert for Period -->
    @if(!isset($periodeAktif))
    <div class="alert alert-warning alert-dismissible fade show d-block d-md-none mb-3" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div>
                <strong>Periode tidak aktif!</strong>
                <div class="small">Aktifkan periode semester</div>
            </div>
        </div>
        <a href="{{ route('periode') }}" class="btn btn-sm btn-warning mt-2 w-100">
            <i class="bi bi-calendar-week"></i> Kelola Periode
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Page Heading dengan Filter -->
    <div class="row mb-3 mb-md-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h1 class="h3 mb-1 text-gray-800">Dashboard</h1>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        @if(isset($periodeAktif))
                            <span class="badge bg-success">{{ $periodeAktif->nama_periode }}</span>
                        @endif
                        @if(auth()->user()->role === 'wali_kelas')
                            <span class="badge bg-info">Kelas {{ auth()->user()->kelas }}</span>
                        @endif
                    </div>
                </div>
                
                @if(auth()->user()->role !== 'wali_kelas')
                <!-- Filter Kelas untuk Admin -->
                <div class="w-100 w-md-auto">
                    <select class="form-select" id="filterKelas" onchange="filterByKelas()">
                        <option value="all" {{ $kelasFilter == 'all' ? 'selected' : '' }}>
                            Semua Kelas
                        </option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas }}" {{ $kelasFilter == $kelas ? 'selected' : '' }}>
                                Kelas {{ $kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Cards - Responsive Grid -->
    <div class="row g-3 mb-4">
        <!-- Total Siswa -->
        <div class="col-6 col-md-3">
            <div class="stat-card primary h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-xs text-uppercase mb-1 text-muted d-none d-md-block">
                            Total Siswa
                        </div>
                        <div class="text-xs text-uppercase mb-1 text-muted d-block d-md-none">
                            Siswa
                        </div>
                        <div class="h3 mb-0 font-weight-bold">{{ $jumlahSiswa ?? 0 }}</div>
                        <small class="text-muted d-none d-sm-block">
                            {{ $kelasFilter && $kelasFilter !== 'all' ? 'Kelas '.$kelasFilter : 'Semua' }}
                        </small>
                    </div>
                    <div class="icon">
                        <i class="bi bi-people d-none d-sm-block"></i>
                        <i class="bi bi-people fs-5 d-block d-sm-none"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kriteria -->
        <div class="col-6 col-md-3">
            <div class="stat-card success h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-xs text-uppercase mb-1 text-muted">
                            Kriteria
                        </div>
                        <div class="h3 mb-0 font-weight-bold">{{ $jumlahKriteria ?? 0 }}</div>
                        <small class="text-muted d-none d-sm-block">Aktif</small>
                    </div>
                    <div class="icon">
                        <i class="bi bi-list-check d-none d-sm-block"></i>
                        <i class="bi bi-list-check fs-5 d-block d-sm-none"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Penilaian -->
        <div class="col-6 col-md-3">
            <div class="stat-card warning h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-xs text-uppercase mb-1 text-muted d-none d-md-block">
                            Data Penilaian
                        </div>
                        <div class="text-xs text-uppercase mb-1 text-muted d-block d-md-none">
                            Penilaian
                        </div>
                        <div class="h3 mb-0 font-weight-bold">{{ $jumlahPenilaian ?? 0 }}</div>
                        <small class="text-muted d-none d-sm-block">
                            {{ isset($periodeAktif) ? 'Terisi' : '-' }}
                        </small>
                    </div>
                    <div class="icon">
                        <i class="bi bi-clipboard-data d-none d-sm-block"></i>
                        <i class="bi bi-clipboard-data fs-5 d-block d-sm-none"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Siswa Teladan -->
        <div class="col-6 col-md-3">
            <div class="stat-card info h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-xs text-uppercase mb-1 text-muted d-none d-md-block">
                            Siswa Teladan
                        </div>
                        <div class="text-xs text-uppercase mb-1 text-muted d-block d-md-none">
                            Teladan
                        </div>
                        @if(isset($nilaiAkhir) && $nilaiAkhir->count() > 0)
                            @php
                                $first = $nilaiAkhir->first();
                                $fullName = optional(optional($first)->alternatif)->nama_siswa;
                                $topName = $fullName ? explode(' ', $fullName)[0] : '-';
                            @endphp
                            <div class="h5 mb-0 font-weight-bold text-truncate">{{ $topName }}</div>
                            <small class="text-muted d-none d-sm-block">
                                Peringkat 1
                            </small>
                        @else
                            <div class="h5 mb-0 font-weight-bold">-</div>
                            <small class="text-muted d-none d-sm-block">Belum ada</small>
                        @endif
                    </div>
                    <div class="icon">
                        <i class="bi bi-trophy d-none d-sm-block"></i>
                        <i class="bi bi-trophy fs-5 d-block d-sm-none"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($periodeAktif) && isset($nilaiAkhir) && $nilaiAkhir->count() > 0)
    <!-- Charts & Tables -->
    <div class="row g-3">
        <!-- Chart - Full width on mobile -->
        <div class="col-12 col-lg-8">
            <div class="custom-table h-100">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Grafik Nilai Siswa 
                        <span class="d-none d-md-inline">
                            {{ $kelasFilter && $kelasFilter !== 'all' ? 'Kelas '.$kelasFilter : '' }}
                        </span>
                    </h6>
                    <button class="btn btn-sm btn-outline-primary d-md-none" onclick="toggleChartView()">
                        <i class="bi bi-arrows-angle-expand"></i> Perbesar
                    </button>
                </div>
                <div id="chart_peringkat" style="min-height: 300px;"></div>
            </div>
        </div>

        <!-- Top 5 - Full width on mobile -->
        <div class="col-12 col-lg-4">
            <div class="custom-table h-100">
                <h6 class="m-0 font-weight-bold text-primary mb-3">
                    Top 5 Siswa 
                    <span class="d-none d-md-inline">Teladan</span>
                </h6>
                
                <!-- Mobile Card View -->
                <div class="d-block d-md-none">
                    @forelse($top5 ?? [] as $index => $siswa)
                        <div class="card mb-2 border-start border-3 border-{{ $index == 0 ? 'warning' : 'primary' }}">
                            <div class="card-body p-2">
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <span class="badge bg-{{ $index == 0 ? 'warning text-dark' : 'secondary' }} rounded-circle p-2">
                                            {{ $index + 1 }}
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 small">{{ optional($siswa->alternatif)->nama_siswa ?? '-' }}</h6>
                                        <small class="text-muted">
                                            {{ optional($siswa->alternatif)->kelas ?? '-' }}
                                        </small>
                                    </div>
                                    <div>
                                        <span class="badge bg-primary">
                                            {{ number_format((float) ($siswa->total ?? 0), 3) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">Belum ada data</p>
                    @endforelse
                </div>

                <!-- Desktop View -->
                <div class="d-none d-md-block">
                    @forelse($top5 ?? [] as $index => $siswa)
                        <div class="d-flex align-items-center mb-3 p-3 bg-light rounded">
                            <div class="me-3">
                                @if($index == 0)
                                    <div class="badge bg-warning text-dark rounded-circle p-3">
                                        <i class="bi bi-trophy-fill"></i>
                                    </div>
                                @else
                                    <div class="badge bg-secondary rounded-circle p-3">{{ $index + 1 }}</div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ optional($siswa->alternatif)->nama_siswa ?? '-' }}</h6>
                                <small class="text-muted">
                                    {{ optional($siswa->alternatif)->kelas ?? '-' }} | 
                                    NIS: {{ optional($siswa->alternatif)->nis ?? '-' }}
                                </small>
                            </div>
                            <div>
                                <span class="badge bg-primary">
                                    {{ number_format((float) ($siswa->total ?? 0), 3) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">Belum ada data</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Full Table - Responsive -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="custom-table">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Hasil Perankingan
                        <span class="badge bg-info ms-2 d-none d-md-inline">{{ $nilaiAkhir->count() }} siswa</span>
                    </h6>
                    <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-outline-info" onclick="exportToExcel(this)">
                            <i class="bi bi-file-earmark-excel"></i>
                            <span class="d-none d-sm-inline">Excel</span>
                        </button>
                        <a href="{{ route('pdf.hasilAkhir') }}?kelas={{ $kelasFilter }}" 
                           target="_blank" 
                           class="btn btn-outline-danger">
                            <i class="bi bi-file-earmark-pdf"></i>
                            <span class="d-none d-sm-inline">PDF</span>
                        </a>
                    </div>
                </div>
                
                <!-- Mobile Card View -->
                <div class="d-block d-md-none">
                    @forelse($nilaiAkhir->take(10) as $row)
                        <div class="card mb-2 {{ $row->peringkat_kelas <= 3 ? 'border-success' : '' }}">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <span class="badge bg-{{ $row->peringkat_kelas == 1 ? 'warning text-dark' : ($row->peringkat_kelas <= 3 ? 'info' : 'secondary') }} me-2">
                                            #{{ $row->peringkat_kelas }}
                                        </span>
                                        <span class="badge bg-primary">{{ optional($row->alternatif)->kelas ?? '-' }}</span>
                                    </div>
                                    @if($row->peringkat_kelas == 1)
                                        <span class="badge bg-success">Teladan</span>
                                    @endif
                                </div>
                                <h6 class="mb-1">{{ optional($row->alternatif)->nama_siswa ?? '-' }}</h6>
                                <div class="small text-muted">NIS: {{ optional($row->alternatif)->nis ?? '-' }}</div>
                                <div class="mt-2">
                                    <strong>Nilai: </strong>
                                    <span class="badge bg-success">
                                        {{ number_format((float) ($row->total ?? 0), 4) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">Belum ada data</p>
                    @endforelse
                    
                    @if($nilaiAkhir->count() > 10)
                        <div class="text-center mt-3">
                            <a href="{{ route('hasil-akhir') }}" class="btn btn-primary">
                                Lihat Semua ({{ $nilaiAkhir->count() }} siswa)
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Desktop Table View -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-striped" id="rankingTable">
                        <thead>
                            <tr>
                                <th width="60">Rank</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th width="80">Kelas</th>
                                <th width="60">JK</th>
                                <th width="100">Nilai Total</th>
                                <th width="120">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($nilaiAkhir as $row)
                                <tr class="{{ $row->peringkat_kelas <= 3 ? 'table-success' : '' }}">
                                    <td>
                                        <span class="badge bg-{{ $row->peringkat_kelas == 1 ? 'warning text-dark' : ($row->peringkat_kelas <= 3 ? 'info' : 'secondary') }}">
                                            {{ $row->peringkat_kelas }}
                                        </span>
                                    </td>
                                    <td>{{ optional($row->alternatif)->nis ?? '-' }}</td>
                                    <td><strong>{{ optional($row->alternatif)->nama_siswa ?? '-' }}</strong></td>
                                    <td>
                                        <span class="badge bg-primary">{{ optional($row->alternatif)->kelas ?? '-' }}</span>
                                    </td>
                                    <td>{{ optional($row->alternatif)->jk ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ number_format((float) ($row->total ?? 0), 4) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($row->peringkat_kelas == 1)
                                            <span class="badge bg-success">Siswa Teladan</span>
                                        @elseif($row->peringkat_kelas <= 3)
                                            <span class="badge bg-info">Nominasi</span>
                                        @else
                                            <span class="badge bg-light text-dark">Partisipan</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Empty State - Responsive -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-4 py-md-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">Belum Ada Data</h4>
                    @if(!isset($periodeAktif))
                        <p class="text-muted">Silakan aktifkan periode semester terlebih dahulu.</p>
                        <a href="{{ route('periode') }}" class="btn btn-primary">
                            <i class="bi bi-calendar-week"></i> Kelola Periode
                        </a>
                    @else
                        <p class="text-muted">Belum ada data penilaian untuk periode {{ $periodeAktif->nama_periode }}.</p>
                        <a href="{{ route('penilaian') }}" class="btn btn-primary">
                            <i class="bi bi-pencil-square"></i> Input Penilaian
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Mobile Quick Actions -->
    <div class="position-fixed bottom-0 start-0 end-0 p-3 bg-white border-top d-md-none" style="z-index: 100;">
        <div class="d-flex gap-2">
            <a href="{{ route('penilaian') }}" class="btn btn-primary flex-fill">
                <i class="bi bi-pencil-square"></i> Penilaian
            </a>
            <a href="{{ route('hasil-akhir') }}" class="btn btn-success flex-fill">
                <i class="bi bi-trophy"></i> Hasil
            </a>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
/* Mobile optimizations */
@media (max-width: 576px) {
    .stat-card {
        padding: 1rem;
        margin-bottom: 0.5rem;
    }
    
    .stat-card .icon {
        font-size: 1.5rem;
        opacity: 0.3;
    }
    
    .h3 {
        font-size: 1.5rem;
    }
    
    .custom-table {
        padding: 1rem;
    }
    
    /* Add padding bottom for mobile quick actions */
    body {
        padding-bottom: 80px;
    }
}

/* Smooth transitions */
.stat-card, .card {
    transition: all 0.3s ease;
}

/* Touch-friendly buttons */
.btn {
    min-height: 38px;
    padding: 0.5rem 1rem;
}

@media (max-width: 768px) {
    .btn-group-sm .btn {
        padding: 0.4rem 0.6rem;
        font-size: 0.875rem;
    }
}

/* Better card shadows on mobile */
.card {
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

/* Responsive table scroll indicator */
.table-responsive {
    position: relative;
}

.table-responsive::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 30px;
    background: linear-gradient(to right, transparent, rgba(255,255,255,0.8));
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.3s;
}

.table-responsive:hover::after {
    opacity: 1;
}
</style>
@endsection

@section('js')
<script>
// Touch-friendly interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add touch feedback
    document.querySelectorAll('.btn, .card').forEach(element => {
        element.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.98)';
        });
        element.addEventListener('touchend', function() {
            this.style.transform = 'scale(1)';
        });
    });
});

// Filter by Kelas
function filterByKelas() {
    const kelas = document.getElementById('filterKelas').value;
    window.location.href = '{{ route("dashboard") }}?kelas=' + kelas;
}

// Export functions
function exportToExcel(btn) {
    // Show loading on mobile
    if (window.innerWidth < 768 && btn) {
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        btn.disabled = true;
    }
    
    // Simulate export
    setTimeout(() => {
        alert('Fitur export Excel akan segera tersedia');
        location.reload();
    }, 1000);
}

// Toggle chart view on mobile
function toggleChartView() {
    const chartDiv = document.getElementById('chart_peringkat');
    if (chartDiv.requestFullscreen) {
        chartDiv.requestFullscreen();
    } else if (chartDiv.webkitRequestFullscreen) {
        chartDiv.webkitRequestFullscreen();
    }
}

$(document).ready(function() {
    // Responsive DataTable
    @if(isset($nilaiAkhir) && $nilaiAkhir->count() > 0)
    
    // Only initialize DataTable on desktop
    if (window.innerWidth >= 768) {
        $('#rankingTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'asc']],
            dom: '<"d-flex justify-content-between"lf>rtip',
            language: {
                search: "Cari:",
                lengthMenu: "_MENU_",
                info: "_START_-_END_ dari _TOTAL_",
                paginate: {
                    first: '<i class="bi bi-chevron-double-left"></i>',
                    last: '<i class="bi bi-chevron-double-right"></i>',
                    next: '<i class="bi bi-chevron-right"></i>',
                    previous: '<i class="bi bi-chevron-left"></i>'
                }
            }
        });
    }
    
    // Responsive Chart
    const chartData = @json($chartSeries ?? []);
    const chartLabels = @json($chartLabels ?? []);

    if(Array.isArray(chartData) && chartData.length > 0) {
        // Responsive chart height
        const chartHeight = window.innerWidth < 576 ? 250 : 350;
        
        const options = {
            series: [{
                name: 'Nilai Total',
                data: chartData
            }],
            chart: { 
                type: 'bar', 
                height: chartHeight,
                toolbar: { 
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        reset: false
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            colors: ['#4e73df'],
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    horizontal: false,
                    columnWidth: window.innerWidth < 576 ? '80%' : '60%',
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: window.innerWidth >= 576,
                formatter: function(val) {
                    return val.toFixed(3);
                },
                offsetY: -20,
                style: {
                    fontSize: '10px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: chartLabels,
                labels: { 
                    rotate: window.innerWidth < 576 ? -90 : -45,
                    style: { 
                        fontSize: window.innerWidth < 576 ? '10px' : '11px'
                    },
                    trim: true,
                    hideOverlappingLabels: true
                }
            },
            yaxis: {
                title: { 
                    text: window.innerWidth >= 576 ? 'Nilai Total' : '',
                    style: {
                        fontSize: '12px'
                    }
                },
                labels: {
                    formatter: function(val) {
                        return val.toFixed(2);
                    }
                }
            },
            grid: {
                borderColor: '#e3e6f0',
                strokeDashArray: 4
            },
            responsive: [{
                breakpoint: 576,
                options: {
                    chart: {
                        height: 250
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '80%'
                        }
                    },
                    xaxis: {
                        labels: {
                            rotate: -90,
                            style: {
                                fontSize: '10px'
                            }
                        }
                    }
                }
            }]
        };

        const chart = new ApexCharts(document.querySelector("#chart_peringkat"), options);
        chart.render();
        
        // Redraw chart on window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                chart.updateOptions({
                    chart: {
                        height: window.innerWidth < 576 ? 250 : 350
                    }
                });
            }, 250);
        });
    }
    @endif
});
</script>
@endsection
