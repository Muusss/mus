<!-- resources/views/public/partials/hasil-nilai.blade.php -->
<div class="result-content">
    <!-- Student Info Card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-person-circle me-2"></i>
                Informasi Siswa
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama:</strong> {{ $hasilPencarian['siswa']->nama_siswa }}</p>
                    <p><strong>NISN:</strong> {{ $hasilPencarian['siswa']->nis }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Kelas:</strong> {{ $hasilPencarian['siswa']->kelas }}</p>
                    <p><strong>Periode:</strong> {{ $hasilPencarian['periode']->nama_periode }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Score Summary -->
    @if($hasilPencarian['nilaiAkhir'])
    <div class="card mb-4 border-success">
        <div class="card-body text-center">
            <h3 class="text-success mb-3">Skor Preferensi</h3>
            <div class="display-3 text-primary">
                {{ number_format($hasilPencarian['nilaiAkhir']->total * 100, 2) }}%
            </div>
            @if($hasilPencarian['rankingKelas'])
            <div class="mt-3">
                <span class="badge bg-warning text-dark fs-6">
                    Peringkat {{ $hasilPencarian['rankingKelas'] }} dari {{ $hasilPencarian['totalSiswaKelas'] }} siswa
                </span>
            </div>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Detail Scores Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-list-check me-2"></i>
                Detail Nilai per Kriteria
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th class="text-center">Nilai</th>
                            <th class="text-center">Bobot</th>
                            <th class="text-center">Kontribusi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hasilPencarian['kriteria'] as $krit)
                            @php
                                $nilai = $hasilPencarian['penilaian'][$krit->id] ?? null;
                                $nilaiAsli = $nilai ? $nilai->nilai_asli : 0;
                                $nilaiNormal = $nilai ? $nilai->nilai_normal : 0;
                                $kontribusi = $nilaiNormal * $krit->bobot_roc;
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $krit->kode }}</strong> - {{ $krit->kriteria }}
                                </td>
                                <td class="text-center">
                                    @if($nilai)
                                        <span class="badge {{ $nilaiAsli >= 3 ? 'bg-success' : ($nilaiAsli >= 2 ? 'bg-warning' : 'bg-info') }}">
                                            {{ $nilaiAsli }}/4
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ number_format($krit->bobot_roc * 100, 1) }}%
                                </td>
                                <td class="text-center">
                                    {{ number_format($kontribusi * 100, 2) }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Comparison with Average -->
    @if($hasilPencarian['rataRataKelas'])
    <div class="card mt-4">
        <div class="card-body">
            <h5>Perbandingan dengan Rata-rata Kelas</h5>
            <div class="progress" style="height: 30px;">
                <div class="progress-bar bg-primary" role="progressbar" 
                     style="width: {{ $hasilPencarian['nilaiAkhir'] ? $hasilPencarian['nilaiAkhir']->total * 100 : 0 }}%">
                    Siswa: {{ $hasilPencarian['nilaiAkhir'] ? number_format($hasilPencarian['nilaiAkhir']->total * 100, 1) : 0 }}%
                </div>
            </div>
            <div class="progress mt-2" style="height: 30px;">
                <div class="progress-bar bg-secondary" role="progressbar" 
                     style="width: {{ $hasilPencarian['rataRataKelas'] }}%">
                    Rata-rata: {{ $hasilPencarian['rataRataKelas'] }}%
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Status Badge -->
    <div class="text-center mt-4">
        @if($hasilPencarian['rankingKelas'] == 1)
            <div class="badge bg-success p-3 fs-5">
                <i class="bi bi-trophy-fill"></i> SISWA TELADAN KELAS {{ $hasilPencarian['siswa']->kelas }}
            </div>
        @elseif($hasilPencarian['rankingKelas'] <= 3)
            <div class="badge bg-warning text-dark p-3 fs-5">
                <i class="bi bi-award-fill"></i> NOMINASI SISWA TELADAN
            </div>
        @elseif($hasilPencarian['rankingKelas'] <= 10)
            <div class="badge bg-info p-3 fs-5">
                <i class="bi bi-star-fill"></i> 10 BESAR KELAS
            </div>
        @else
            <div class="badge bg-secondary p-3 fs-5">
                PARTISIPAN
            </div>
        @endif
    </div>
</div>