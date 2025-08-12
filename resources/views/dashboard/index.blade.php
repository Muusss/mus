<!-- resources/views/dashboard/index.blade.php -->
@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <!-- Header dengan info sekolah -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-0">Sistem Pendukung Keputusan Pemilihan Siswa Teladan</h2>
                            <p class="mb-0 opacity-8">SDIT As Sunnah Cirebon - Metode ROC & SMART</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="badge bg-white text-primary">
                                Tahun Ajaran {{ date('Y') }}/{{ date('Y')+1 }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards Statistik -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Siswa</p>
                            <h3 class="mb-0 text-primary">{{ $jumlahSiswa ?? 0 }}</h3>
                            @if(auth()->user()?->role === 'wali_kelas')
                                <small class="text-muted">Kelas {{ auth()->user()->kelas }}</small>
                            @else
                                <small class="text-muted">Semua Kelas</small>
                            @endif
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-people-fill text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Kriteria Penilaian</p>
                            <h3 class="mb-0 text-success">{{ $jumlahKriteria ?? 0 }}</h3>
                            <small class="text-muted">Aktif</small>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-list-check text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Data Penilaian</p>
                            <h3 class="mb-0 text-warning">{{ $jumlahPenilaian ?? 0 }}</h3>
                            <small class="text-muted">Terisi</small>
                        </div>
                        <div class="icon-box bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-clipboard-data text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            // Satukan cara akses nilai akhir jadi koleksi aman
            $naCol = $nilaiAkhir instanceof \Illuminate\Support\Collection ? $nilaiAkhir : collect($nilaiAkhir ?? []);
            $first = $naCol->first();
            $topName = is_object($first)
                ? (data_get($first, 'alternatif.nama_siswa') ?? data_get($first, 'nama_siswa') ?? '-')
                : '-';
        @endphp

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Siswa Teladan</p>
                            @if($naCol->isNotEmpty())
                                <h3 class="mb-0 text-info">{{ $topName }}</h3>
                                <small class="text-muted">Peringkat 1</small>
                            @else
                                <h3 class="mb-0 text-info">-</h3>
                                <small class="text-muted">Belum Ada</small>
                            @endif
                        </div>
                        <div class="icon-box bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-trophy-fill text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Grafik Peringkat -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Grafik Nilai Siswa (ROC + SMART)</h5>
                        <button class="btn btn-sm btn-outline-primary" onclick="location.reload()">
                            <i class="bi bi-arrow-clockwise"></i> Refresh
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="chart_peringkat" style="min-height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- Top 5 Siswa -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Top 5 Siswa Teladan</h5>
                </div>
                <div class="card-body">
                    @forelse($top5 ?? [] as $index => $siswa)
                        <div class="d-flex align-items-center mb-3">
                            <div class="rank-badge me-3">
                                @if($index == 0)
                                    <span class="badge bg-warning text-dark rounded-circle p-2">
                                        <i class="bi bi-trophy-fill"></i>
                                    </span>
                                @elseif($index == 1)
                                    <span class="badge bg-secondary rounded-circle p-2">{{ $index + 1 }}</span>
                                @elseif($index == 2)
                                    <span class="badge bg-danger rounded-circle p-2">{{ $index + 1 }}</span>
                                @else
                                    <span class="badge bg-info rounded-circle p-2">{{ $index + 1 }}</span>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ data_get($siswa, 'alternatif.nama_siswa', '-') }}</h6>
                                <small class="text-muted">
                                    {{ data_get($siswa, 'alternatif.kelas', '-') }} | NIS: {{ data_get($siswa, 'alternatif.nis', '-') }}
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary">{{ number_format((float) data_get($siswa, 'total', 0), 3) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">Belum ada data</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Lengkap -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Hasil Perankingan Siswa Teladan</h5>
                        <div>
                            @if(auth()->user()?->role === 'admin')
                                <a href="{{ route('spk.proses') }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-calculator"></i> Hitung Ulang
                                </a>
                            @endif
                            <button class="btn btn-primary btn-sm" onclick="window.print()">
                                <i class="bi bi-printer"></i> Cetak
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblRanking" class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="60">Rank</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>JK</th>
                                    <th>Nilai Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($naCol as $i => $row)
                                    @php
                                        $rank = data_get($row, 'peringkat') ?? ($i + 1);
                                        $badge =
                                            $rank == 1 ? 'warning text-dark' :
                                            ($rank == 2 ? 'secondary' :
                                            ($rank == 3 ? 'danger' : 'info'));
                                    @endphp
                                    <tr class="{{ $rank <= 3 ? 'table-success' : '' }}">
                                        <td>
                                            <span class="badge bg-{{ $badge }}">{{ $rank }}</span>
                                        </td>
                                        <td>{{ data_get($row, 'alternatif.nis', '-') }}</td>
                                        <td><strong>{{ data_get($row, 'alternatif.nama_siswa', '-') }}</strong></td>
                                        <td>{{ data_get($row, 'alternatif.kelas', '-') }}</td>
                                        <td>{{ data_get($row, 'alternatif.jk', '-') }}</td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ number_format((float) data_get($row, 'total', 0), 4) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($rank == 1)
                                                <span class="badge bg-success">Siswa Teladan</span>
                                            @elseif($rank <= 3)
                                                <span class="badge bg-info">Nominasi</span>
                                            @else
                                                <span class="badge bg-light text-dark">Partisipan</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-1"></i>
                                            <p>Belum ada hasil perhitungan</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
{{-- pastikan jQuery & DataTables sudah dimuat di layout --}}
<script>
$(document).ready(function() {
    // DataTable
    if ($.fn.DataTable) {
        $('#tblRanking').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'asc']],
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
    }

    // Chart Configuration
    const chartData = @json($chartSeries ?? []);
    const chartLabels = @json($chartLabels ?? []);

    if(Array.isArray(chartData) && chartData.length > 0) {
        const options = {
            series: [{
                name: 'Nilai Total',
                data: chartData.slice(0, 10)
            }],
            chart: { type: 'bar', height: 350, toolbar: { show: false } },
            plotOptions: {
                bar: {
                    borderRadius: 8,
                    horizontal: false,
                    columnWidth: '60%',
                    dataLabels: { position: 'top' }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: (val) => Number(val ?? 0).toFixed(3),
                offsetY: -20,
                style: { fontSize: '10px', colors: ["#304758"] }
            },
            xaxis: {
                categories: (Array.isArray(chartLabels) ? chartLabels : []).slice(0, 10),
                labels: { rotate: -45, rotateAlways: true, style: { fontSize: '11px' } }
            },
            yaxis: {
                title: { text: 'Nilai Total (ROC + SMART)' },
                labels: { formatter: (val) => Number(val ?? 0).toFixed(2) }
            },
            colors: ['#6366f1'],
            tooltip: { y: { formatter: (val) => Number(val ?? 0).toFixed(4) } },
            grid: { borderColor: '#f1f1f1', strokeDashArray: 3 }
        };

        const chart = new ApexCharts(document.querySelector("#chart_peringkat"), options);
        chart.render();
    }
});
</script>

<style>
.icon-box {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.rank-badge .badge {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
}
@media print {
    .btn, .card-header .d-flex > div:last-child {
        display: none !important;
    }
}
</style>
@endsection
