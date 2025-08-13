@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Hasil Akhir Perankingan Siswa Teladan</h3>
                    @if(isset($kelasFilter) && $kelasFilter !== 'all')
                        <span class="badge bg-info">Kelas {{ $kelasFilter }}</span>
                    @endif
                    @if(isset($periodeAktif))
                        <span class="badge bg-success">{{ $periodeAktif->nama_periode }}</span>
                    @endif
                </div>
                
                <div class="d-flex gap-2">
                    <!-- Filter Kelas -->
                    @if(auth()->user()->role !== 'wali_kelas')
                    <select class="form-select" id="filterKelas" onchange="filterByKelas()">
                        <option value="all" {{ $kelasFilter == 'all' ? 'selected' : '' }}>Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas }}" {{ $kelasFilter == $kelas ? 'selected' : '' }}>
                                Kelas {{ $kelas }}
                            </option>
                        @endforeach
                    </select>
                    @endif
                    
                    <a href="{{ route('perhitungan') }}" class="btn btn-secondary">
                        <i class="bi bi-calculator"></i> Lihat Perhitungan
                    </a>
                    <a href="{{ route('pdf.hasilAkhir') }}?kelas={{ $kelasFilter }}" target="_blank" class="btn btn-danger">
                        <i class="bi bi-file-pdf"></i> Cetak PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(!isset($periodeAktif))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Perhatian!</strong> Belum ada periode semester yang aktif.
        <a href="{{ route('periode') }}" class="btn btn-sm btn-primary ms-2">
            <i class="bi bi-calendar-week"></i> Kelola Periode
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(isset($periodeAktif) && $nilaiAkhir && $nilaiAkhir->count() > 0)
        <!-- Statistik Per Kelas (jika filter all) -->
        @if($kelasFilter == 'all')
        <div class="row mb-4">
            @foreach($kelasList as $kelas)
                @php
                    $juaraKelas = $nilaiAkhir->filter(function($item) use ($kelas) {
                        return $item->alternatif && $item->alternatif->kelas == $kelas;
                    })->first();
                @endphp
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h6 class="text-muted">Juara Kelas {{ $kelas }}</h6>
                            @if($juaraKelas && $juaraKelas->alternatif)
                                <h5 class="mb-0">{{ $juaraKelas->alternatif->nama_siswa }}</h5>
                                <span class="badge bg-primary">{{ number_format($juaraKelas->total, 4) }}</span>
                            @else
                                <p class="text-muted mb-0">Belum ada data</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

        <!-- Ringkasan Top 3 -->
        <div class="row mb-4">
            @php
                $top3 = $nilaiAkhir->take(3);
            @endphp
            @foreach($top3 as $index => $siswa)
            <div class="col-md-4">
                <div class="card {{ $index == 0 ? 'border-warning' : ($index == 1 ? 'border-secondary' : 'border-danger') }} border-2">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if($index == 0)
                                <i class="bi bi-trophy-fill text-warning" style="font-size: 3rem;"></i>
                                <h5 class="mt-2 text-warning">JUARA 1</h5>
                            @elseif($index == 1)
                                <i class="bi bi-award-fill text-secondary" style="font-size: 2.5rem;"></i>
                                <h5 class="mt-2 text-secondary">JUARA 2</h5>
                            @else
                                <i class="bi bi-award-fill text-danger" style="font-size: 2.5rem;"></i>
                                <h5 class="mt-2 text-danger">JUARA 3</h5>
                            @endif
                        </div>
                        <h4 class="card-title">{{ $siswa->alternatif->nama_siswa ?? '-' }}</h4>
                        <p class="text-muted mb-1">NIS: {{ $siswa->alternatif->nis ?? '-' }}</p>
                        <p class="text-muted mb-3">Kelas: {{ $siswa->alternatif->kelas ?? '-' }}</p>
                        <h3 class="text-primary">{{ number_format($siswa->total ?? 0, 4) }}</h3>
                        @if($kelasFilter && $kelasFilter !== 'all')
                            <span class="badge bg-success">Siswa Teladan Kelas {{ $kelasFilter }}</span>
                        @else
                            <span class="badge bg-success">Siswa Teladan</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Tabel Lengkap Perankingan -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            Tabel Perankingan Siswa Teladan 
                            @if($kelasFilter && $kelasFilter !== 'all')
                                Kelas {{ $kelasFilter }}
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tblHasil" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="80">Peringkat</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Kelas</th>
                                        <th class="text-center">Total Nilai</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($nilaiAkhir as $row)
                                    <tr class="{{ $row->peringkat_kelas <= 3 ? 'table-success' : '' }}">
                                        <td class="text-center">
                                            @if($row->peringkat_kelas == 1)
                                                <span class="badge bg-warning text-dark fs-6">
                                                    <i class="bi bi-trophy-fill"></i> {{ $row->peringkat_kelas }}
                                                </span>
                                            @elseif($row->peringkat_kelas == 2)
                                                <span class="badge bg-secondary fs-6">
                                                    <i class="bi bi-award-fill"></i> {{ $row->peringkat_kelas }}
                                                </span>
                                            @elseif($row->peringkat_kelas == 3)
                                                <span class="badge bg-danger fs-6">
                                                    <i class="bi bi-award-fill"></i> {{ $row->peringkat_kelas }}
                                                </span>
                                            @else
                                                <span class="badge bg-info">{{ $row->peringkat_kelas }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $row->alternatif->nis ?? '-' }}</td>
                                        <td>
                                            <strong>{{ $row->alternatif->nama_siswa ?? '-' }}</strong>
                                            @if($row->peringkat_kelas == 1)
                                                <i class="bi bi-star-fill text-warning ms-2"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if($row->alternatif->jk == 'Lk')
                                                <span class="badge bg-primary">Laki-laki</span>
                                            @else
                                                <span class="badge bg-danger">Perempuan</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $row->alternatif->kelas ?? '-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary fs-6">
                                                {{ number_format($row->total ?? 0, 4) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($row->peringkat_kelas == 1)
                                                <span class="badge bg-success">Siswa Teladan</span>
                                            @elseif($row->peringkat_kelas <= 3)
                                                <span class="badge bg-info">Nominasi</span>
                                            @elseif($row->peringkat_kelas <= 10)
                                                <span class="badge bg-secondary">10 Besar</span>
                                            @else
                                                <span class="badge bg-light text-dark">Partisipan</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-primary">{{ $nilaiAkhir->count() }}</h3>
                        <p class="text-muted mb-0">Total Siswa</p>
                        @if($kelasFilter && $kelasFilter !== 'all')
                            <small>Kelas {{ $kelasFilter }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-success">{{ number_format($nilaiAkhir->max('total') ?? 0, 4) }}</h3>
                        <p class="text-muted mb-0">Nilai Tertinggi</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-warning">{{ number_format($nilaiAkhir->avg('total') ?? 0, 4) }}</h3>
                        <p class="text-muted mb-0">Nilai Rata-rata</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <h3 class="text-danger">{{ number_format($nilaiAkhir->min('total') ?? 0, 4) }}</h3>
                        <p class="text-muted mb-0">Nilai Terendah</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Distribusi Nilai -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Grafik Distribusi Nilai</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartNilai" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Tampilan jika belum ada data -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Belum Ada Data Hasil Perhitungan</h4>
                        @if(!isset($periodeAktif))
                            <p class="text-muted">Silakan aktifkan periode semester terlebih dahulu.</p>
                            <a href="{{ route('periode') }}" class="btn btn-warning">
                                <i class="bi bi-calendar-week"></i> Kelola Periode
                            </a>
                        @else
                            <p class="text-muted">Silakan lakukan perhitungan ROC + SMART terlebih dahulu.</p>
                            <a href="{{ route('perhitungan') }}" class="btn btn-primary">
                                <i class="bi bi-calculator"></i> Ke Halaman Perhitungan
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function filterByKelas() {
    const kelas = document.getElementById('filterKelas').value;
    window.location.href = '{{ route("hasil-akhir") }}?kelas=' + kelas;
}

$(document).ready(function() {
    @if(isset($nilaiAkhir) && $nilaiAkhir->count() > 0)
    $('#tblHasil').DataTable({
        responsive: true,
        pageLength: 25,
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
    
    // Chart Distribusi Nilai
    const ctx = document.getElementById('chartNilai').getContext('2d');
    const labels = [];
    const data = [];
    const backgroundColors = [];
    
    @foreach($nilaiAkhir->take(20) as $index => $row)
        labels.push('{{ $row->alternatif->nama_siswa ?? "-" }}');
        data.push({{ number_format($row->total ?? 0, 4) }});
        @if($index < 3)
            backgroundColors.push('rgba(255, 193, 7, 0.8)'); // Gold for top 3
        @else
            backgroundColors.push('rgba(54, 162, 235, 0.8)'); // Blue for others
        @endif
    @endforeach
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Nilai Total',
                data: data,
                backgroundColor: backgroundColors,
                borderColor: backgroundColors.map(c => c.replace('0.8', '1')),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 1
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Nilai: ' + context.parsed.y.toFixed(4);
                        }
                    }
                }
            }
        }
    });
    @endif
});
</script>
@endsection