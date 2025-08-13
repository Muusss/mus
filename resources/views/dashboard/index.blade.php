@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <!-- Page Heading dengan Filter -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            @if(auth()->user()->role !== 'wali_kelas')
            <!-- Filter Kelas untuk Admin -->
            <div class="ms-3">
                <select class="form-select form-select-sm" id="filterKelas" onchange="filterByKelas()">
                    <option value="all" {{ $kelasFilter == 'all' ? 'selected' : '' }}>Semua Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas }}" {{ $kelasFilter == $kelas ? 'selected' : '' }}>
                            Kelas {{ $kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            @else
            <!-- Badge untuk Wali Kelas -->
            <span class="badge bg-info ms-3">Kelas {{ auth()->user()->kelas }}</span>
            @endif
        </div>
        <a href="{{ route('spk.proses') }}" class="btn btn-custom btn-custom-primary">
            <i class="bi bi-calculator me-2"></i>Proses Perhitungan
        </a>
    </div>

    <!-- Info Box Filter -->
    @if($kelasFilter && $kelasFilter !== 'all')
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="bi bi-funnel me-2"></i>
        Menampilkan data untuk <strong>Kelas {{ $kelasFilter }}</strong>
        @if(auth()->user()->role !== 'wali_kelas')
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light ms-2">
            <i class="bi bi-x-circle"></i> Reset Filter
        </a>
        @endif
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted">
                            Total Siswa
                        </div>
                        <div class="h4 mb-0 font-weight-bold">{{ $jumlahSiswa ?? 0 }}</div>
                        <small class="text-muted">
                            {{ $kelasFilter && $kelasFilter !== 'all' ? 'Kelas '.$kelasFilter : 'Semua Kelas' }}
                        </small>
                    </div>
                    <div class="icon">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted">
                            Kriteria Penilaian
                        </div>
                        <div class="h4 mb-0 font-weight-bold">{{ $jumlahKriteria ?? 0 }}</div>
                        <small class="text-muted">Aktif</small>
                    </div>
                    <div class="icon">
                        <i class="bi bi-list-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted">
                            Data Penilaian
                        </div>
                        <div class="h4 mb-0 font-weight-bold">{{ $jumlahPenilaian ?? 0 }}</div>
                        <small class="text-muted">
                            {{ $kelasFilter && $kelasFilter !== 'all' ? 'Kelas '.$kelasFilter : 'Terisi' }}
                        </small>
                    </div>
                    <div class="icon">
                        <i class="bi bi-clipboard-data"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-muted">
                            Siswa Teladan
                        </div>
                        @php
                            $first = $nilaiAkhir->first();
                            $topName = $first ? $first->alternatif->nama_siswa : '-';
                        @endphp
                        <div class="h5 mb-0 font-weight-bold">{{ $topName }}</div>
                        @if($first)
                            <small class="text-muted">
                                Peringkat 1 {{ $kelasFilter && $kelasFilter !== 'all' ? 'Kelas '.$kelasFilter : '' }}
                            </small>
                        @endif
                    </div>
                    <div class="icon">
                        <i class="bi bi-trophy"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Tables -->
    <div class="row">
        <!-- Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="custom-table">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Grafik Nilai Siswa 
                        {{ $kelasFilter && $kelasFilter !== 'all' ? 'Kelas '.$kelasFilter : '' }}
                    </h6>
                </div>
                <div id="chart_peringkat" style="min-height: 350px;"></div>
            </div>
        </div>

        <!-- Top 5 -->
        <div class="col-xl-4 col-lg-5">
            <div class="custom-table">
                <h6 class="m-0 font-weight-bold text-primary mb-3">
                    Top 5 Siswa Teladan
                    {{ $kelasFilter && $kelasFilter !== 'all' ? 'Kelas '.$kelasFilter : '' }}
                </h6>
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
                            <h6 class="mb-0">{{ $siswa->alternatif->nama_siswa ?? '-' }}</h6>
                            <small class="text-muted">
                                {{ $siswa->alternatif->kelas ?? '-' }} | 
                                NIS: {{ $siswa->alternatif->nis ?? '-' }}
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

    <!-- Full Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="custom-table">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Hasil Perankingan Siswa 
                        {{ $kelasFilter && $kelasFilter !== 'all' ? 'Kelas '.$kelasFilter : '' }}
                    </h6>
                    <div>
                        <button class="btn btn-sm btn-info" onclick="exportToExcel()">
                            <i class="bi bi-file-earmark-excel"></i> Export Excel
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="exportToPDF()">
                            <i class="bi bi-file-earmark-pdf"></i> Export PDF
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table datatable" id="rankingTable">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>NIS</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>JK</th>
                                <th>Nilai Total</th>
                                <th>Status</th>
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
                                    <td>{{ $row->alternatif->nis ?? '-' }}</td>
                                    <td><strong>{{ $row->alternatif->nama_siswa ?? '-' }}</strong></td>
                                    <td>
                                        <span class="badge bg-primary">{{ $row->alternatif->kelas ?? '-' }}</span>
                                    </td>
                                    <td>{{ $row->alternatif->jk ?? '-' }}</td>
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
</div>
@endsection

@section('js')
<script>
// Filter by Kelas
function filterByKelas() {
    const kelas = document.getElementById('filterKelas').value;
    window.location.href = '{{ route("dashboard") }}?kelas=' + kelas;
}

// Export functions
function exportToExcel() {
    // Implementation for Excel export
    alert('Fitur export Excel akan segera tersedia');
}

function exportToPDF() {
    const kelas = '{{ $kelasFilter }}';
    const url = kelas && kelas !== 'all' 
        ? '{{ route("pdf.hasilAkhir") }}?kelas=' + kelas
        : '{{ route("pdf.hasilAkhir") }}';
    window.open(url, '_blank');
}

$(document).ready(function() {
    // Chart
    const chartData = @json($chartSeries ?? []);
    const chartLabels = @json($chartLabels ?? []);

    if(Array.isArray(chartData) && chartData.length > 0) {
        const options = {
            series: [{
                name: 'Nilai Total',
                data: chartData
            }],
            chart: { 
                type: 'bar', 
                height: 350, 
                toolbar: { show: true } 
            },
            colors: ['#4e73df'],
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    horizontal: false,
                    columnWidth: '60%',
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: true,
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
                    rotate: -45,
                    style: { fontSize: '11px' } 
                }
            },
            yaxis: {
                title: { text: 'Nilai Total' }
            },
            grid: {
                borderColor: '#e3e6f0'
            }
        };

        const chart = new ApexCharts(document.querySelector("#chart_peringkat"), options);
        chart.render();
    }
});
</script>
@endsection