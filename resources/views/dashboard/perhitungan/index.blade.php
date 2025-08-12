@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h3>Perhitungan Metode ROC + SMART</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Perhitungan</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Step Progress -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="steps-progress">
                        <div class="step-item completed">
                            <div class="step-number">1</div>
                            <div class="step-title">Data Siswa</div>
                        </div>
                        <div class="step-item completed">
                            <div class="step-number">2</div>
                            <div class="step-title">Kriteria & Bobot ROC</div>
                        </div>
                        <div class="step-item completed">
                            <div class="step-number">3</div>
                            <div class="step-title">Penilaian</div>
                        </div>
                        <div class="step-item active">
                            <div class="step-number">4</div>
                            <div class="step-title">Perhitungan SMART</div>
                        </div>
                        <div class="step-item">
                            <div class="step-number">5</div>
                            <div class="step-title">Hasil Akhir</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bobot ROC -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">1. Bobot Kriteria (Metode ROC)</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Kriteria</th>
                                <th>Prioritas</th>
                                <th>Bobot ROC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kriteria as $k)
                            <tr>
                                <td>{{ $k->kode }}</td>
                                <td>{{ $k->kriteria }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $k->urutan_prioritas }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        {{ number_format($k->bobot_roc, 4) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <th colspan="3">Total Bobot</th>
                                <th>{{ number_format($sumBobotKriteria, 4) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Formula ROC -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Formula ROC</h5>
                </div>
                <div class="card-body">
                    <div class="formula-box">
                        <h6>Rumus Rank Order Centroid:</h6>
                        <div class="text-center my-3">
                            <img src="https://latex.codecogs.com/svg.latex?W_m%20=%20\frac{1}{m}\sum_{i=m}^{n}\frac{1}{i}" 
                                 alt="ROC Formula" class="img-fluid">
                        </div>
                        <p class="small">Dimana:</p>
                        <ul class="small">
                            <li>W<sub>m</sub> = Bobot kriteria ke-m</li>
                            <li>m = Jumlah total kriteria ({{ $kriteria->count() }})</li>
                            <li>i = Urutan prioritas kriteria</li>
                        </ul>
                        
                        <div class="alert alert-success mt-3">
                            <strong>Contoh Perhitungan C1:</strong><br>
                            W<sub>1</sub> = (1/1 + 1/2 + 1/3 + 1/4 + 1/5 + 1/6) / 6<br>
                            W<sub>1</sub> = 2.45 / 6 = 0.4083
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Normalisasi Matriks -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">2. Normalisasi Matriks (Metode SMART)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Nama Siswa</th>
                                    <th colspan="{{ $kriteria->count() }}" class="text-center">
                                        Nilai Normalisasi (0-1)
                                    </th>
                                </tr>
                                <tr>
                                    @foreach($kriteria as $k)
                                        <th class="text-center">{{ $k->kode }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alternatif->take(10) as $alt)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $alt->nama_siswa }}</td>
                                    @foreach($kriteria as $k)
                                        @php
                                            $nilai = $penilaian->where('alternatif_id', $alt->id)
                                                              ->where('kriteria_id', $k->id)
                                                              ->first();
                                        @endphp
                                        <td class="text-center">
                                            @if($nilai && $nilai->nilai_normal !== null)
                                                <span class="badge bg-primary">
                                                    {{ number_format($nilai->nilai_normal, 3) }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="alert alert-info mt-3">
                        <strong>Formula Normalisasi SMART:</strong><br>
                        Benefit: U<sub>i</sub> = (X<sub>i</sub> - X<sub>min</sub>) / (X<sub>max</sub> - X<sub>min</sub>)<br>
                        Cost: U<sub>i</sub> = (X<sub>max</sub> - X<sub>i</sub>) / (X<sub>max</sub> - X<sub>min</sub>)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nilai Utility -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">3. Nilai Utility (Normalisasi)</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama Siswa</th>
                                @foreach ($kriteria as $krit)
                                    <th class="text-center">{{ $krit->kode }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <x-nilai-table
                            :alternatif="$alternatif"
                            :kriteria="$kriteria"
                            :data="$nilaiUtility"
                            :show-total="false"
                        />
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Nilai Akhir -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">4. Nilai Akhir (ROC × Utility)</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama Siswa</th>
                                @foreach ($kriteria as $krit)
                                    <th class="text-center">{{ $krit->kode }}</th>
                                @endforeach
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <x-nilai-table
                            :alternatif="$alternatif"
                            :kriteria="$kriteria"
                            :data="$nilaiAkhir"
                            :show-total="true"
                        />
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Hasil Perhitungan -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">5. Hasil Akhir (ROC × SMART)</h5>
                        <button class="btn btn-success btn-sm" onclick="hitungUlang()">
                            <i class="bi bi-calculator"></i> Hitung Ulang
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tblHasil" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Peringkat</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    @foreach($kriteria as $k)
                                        <th class="text-center">{{ $k->kode }}</th>
                                    @endforeach
                                    <th class="text-center">Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hasil as $h)
                                <tr class="{{ $loop->iteration <= 3 ? 'table-success' : '' }}">
                                    <td>
                                        <span class="badge bg-{{ $loop->iteration == 1 ? 'warning text-dark' : 'info' }}">
                                            {{ $h->peringkat }}
                                        </span>
                                    </td>
                                    <td>{{ $h->alternatif->nis }}</td>
                                    <td><strong>{{ $h->alternatif->nama_siswa }}</strong></td>
                                    <td>{{ $h->alternatif->kelas }}</td>
                                    @foreach($kriteria as $k)
                                        @php
                                            $nilai = $penilaian->where('alternatif_id', $h->alternatif_id)
                                                              ->where('kriteria_id', $k->id)
                                                              ->first();
                                            $nilaiAkhir = $nilai ? ($nilai->nilai_normal * $k->bobot_roc) : 0;
                                        @endphp
                                        <td class="text-center small">
                                            {{ number_format($nilaiAkhir, 3) }}
                                        </td>
                                    @endforeach
                                    <td class="text-center">
                                        <span class="badge bg-primary">
                                            {{ number_format($h->total, 4) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($loop->iteration == 1)
                                            <span class="badge bg-success">Siswa Teladan</span>
                                        @elseif($loop->iteration <= 3)
                                            <span class="badge bg-info">Nominasi</span>
                                        @else
                                            -
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
</div>
@endsection

@section('js')
<script>
function hitungUlang() {
    Swal.fire({
        title: 'Hitung Ulang?',
        text: "Proses ini akan menghitung ulang semua nilai!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Ya, Hitung!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ route('perhitungan.smart') }}";
        }
    });
}

$(document).ready(function() {
    $('#tblHasil').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc']]
    });
});
</script>

<style>
.steps-progress {
    display: flex;
    justify-content: space-between;
    position: relative;
}

.step-item {
    text-align: center;
    flex: 1;
    position: relative;
}

.step-item::before {
    content: '';
    position: absolute;
    top: 20px;
    left: -50%;
    right: 50%;
    height: 2px;
    background: #dee2e6;
    z-index: -1;
}

.step-item:first-child::before {
    display: none;
}

.step-item.completed::before,
.step-item.active::before {
    background: #28a745;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #dee2e6;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-weight: bold;
}

.step-item.completed .step-number {
    background: #28a745;
}

.step-item.active .step-number {
    background: #ffc107;
    color: #000;
}

.formula-box {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
}
</style>
@endsection
