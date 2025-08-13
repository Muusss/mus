@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Input Penilaian Kelas {{ $kelas }}</h3>
            <span class="badge bg-info">{{ $periodeAktif->nama_periode }}</span>
        </div>
        <a href="{{ route('penilaian') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('penilaian.updateKelas') }}">
        @csrf
        <input type="hidden" name="kelas" value="{{ $kelas }}">
        <input type="hidden" name="periode_id" value="{{ $periodeAktif->id }}">

        <div class="card">
            <div class="card-body">
                <!-- Instructions -->
                <div class="alert alert-info mb-3">
                    <h6><i class="bi bi-info-circle"></i> Petunjuk Pengisian:</h6>
                    <ul class="mb-0">
                        <li>Isi nilai 1-4 untuk setiap kriteria</li>
                        <li>Gunakan <kbd>Tab</kbd> untuk pindah ke kolom berikutnya</li>
                        <li>Tekan <kbd>Ctrl+S</kbd> untuk menyimpan semua data</li>
                    </ul>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th rowspan="2" style="width: 50px;">No</th>
                                <th rowspan="2" style="width: 100px;">NIS</th>
                                <th rowspan="2" style="width: 200px;">Nama Siswa</th>
                                <th colspan="{{ $kriteria->count() }}" class="text-center">Kriteria Penilaian</th>
                                <th rowspan="2" style="width: 100px;">Status</th>
                            </tr>
                            <tr>
                                @foreach($kriteria as $k)
                                    <th class="text-center" style="width: 80px;">
                                        {{ $k->kode }}
                                        <br>
                                        <small class="text-warning">{{ $k->kriteria }}</small>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alternatifs as $alt)
                            <tr data-alternatif-id="{{ $alt->id }}">
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $alt->nis }}</td>
                                <td>{{ $alt->nama_siswa }}</td>
                                
                                @php $terisi = 0; @endphp
                                @foreach($kriteria as $k)
                                    @php
                                        $nilai = $penilaians->get($alt->id)?->get($k->id)?->first();
                                        if ($nilai && $nilai->nilai_asli !== null) {
                                            $terisi++;
                                        }
                                    @endphp
                                    <td class="p-1">
                                        <input type="number" 
                                               name="nilai[{{ $alt->id }}][{{ $k->id }}]"
                                               class="form-control text-center nilai-input"
                                               value="{{ $nilai->nilai_asli ?? '' }}"
                                               min="1" 
                                               max="4"
                                               step="1"
                                               data-alternatif="{{ $alt->id }}"
                                               data-kriteria="{{ $k->id }}"
                                               placeholder="-">
                                    </td>
                                @endforeach
                                
                                <td class="text-center status-cell">
                                    @if($terisi == $kriteria->count())
                                        <span class="badge bg-success">Lengkap</span>
                                    @elseif($terisi > 0)
                                        <span class="badge bg-warning">{{ $terisi }}/{{ $kriteria->count() }}</span>
                                    @else
                                        <span class="badge bg-danger">Kosong</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-between mt-3">
                    <div>
                        <button type="button" class="btn btn-info" onclick="fillRandom()">
                            <i class="bi bi-dice-3"></i> Isi Acak (Demo)
                        </button>
                        <button type="button" class="btn btn-warning" onclick="clearAll()">
                            <i class="bi bi-eraser"></i> Kosongkan Semua
                        </button>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-save"></i> Simpan Semua Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Legend Kriteria -->
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="mb-0">Keterangan Kriteria & Nilai</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($kriteria as $k)
                <div class="col-md-4 mb-3">
                    <h6><span class="badge bg-primary">{{ $k->kode }}</span> {{ $k->kriteria }}</h6>
                    @php
                        $subKriterias = \App\Models\SubKriteria::where('kriteria_id', $k->id)
                            ->orderBy('skor', 'asc')
                            ->get();
                    @endphp
                    @if($subKriterias->isNotEmpty())
                        <ul class="list-unstyled ms-3">
                            @foreach($subKriterias as $sub)
                                <li>
                                    <span class="badge bg-secondary">{{ $sub->skor }}</span>
                                    {{ $sub->label }}
                                    @if($sub->min_val !== null && $sub->max_val !== null)
                                        ({{ $sub->min_val }}-{{ $sub->max_val }})
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
.nilai-input {
    width: 60px;
    padding: 4px;
    font-weight: bold;
}

.nilai-input:focus {
    background-color: #fff3cd;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.nilai-input.is-invalid {
    background-color: #f8d7da;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

kbd {
    padding: 2px 4px;
    font-size: 90%;
    color: #fff;
    background-color: #333;
    border-radius: 3px;
}

/* Sticky header */
.sticky-top {
    position: sticky;
    top: 0;
    z-index: 10;
}
</style>
@endsection

@section('js')
<script>
// Auto-update status when value changes
$(document).on('input', '.nilai-input', function() {
    const $row = $(this).closest('tr');
    const filled = $row.find('.nilai-input').filter(function() {
        return $(this).val() !== '';
    }).length;
    const total = {{ $kriteria->count() }};
    
    const $statusCell = $row.find('.status-cell');
    if (filled === total) {
        $statusCell.html('<span class="badge bg-success">Lengkap</span>');
    } else if (filled > 0) {
        $statusCell.html('<span class="badge bg-warning">' + filled + '/' + total + '</span>');
    } else {
        $statusCell.html('<span class="badge bg-danger">Kosong</span>');
    }
});

// Tab navigation
$(document).on('keydown', '.nilai-input', function(e) {
    if (e.key === 'Tab') {
        // Default tab behavior
        return true;
    } else if (e.key === 'Enter') {
        e.preventDefault();
        // Move to next input
        const inputs = $('.nilai-input');
        const currentIndex = inputs.index(this);
        if (currentIndex < inputs.length - 1) {
            inputs.eq(currentIndex + 1).focus().select();
        }
    } else if (e.key >= '1' && e.key <= '4') {
        // Direct number input
        e.preventDefault();
        $(this).val(e.key).trigger('input');
        
        // Auto move to next
        const inputs = $('.nilai-input');
        const currentIndex = inputs.index(this);
        if (currentIndex < inputs.length - 1) {
            setTimeout(() => {
                inputs.eq(currentIndex + 1).focus().select();
            }, 100);
        }
    }
});

// Fill random values (for demo)
function fillRandom() {
    if (confirm('Isi semua nilai dengan angka acak 1-4?')) {
        $('.nilai-input').each(function() {
            $(this).val(Math.floor(Math.random() * 4) + 1).trigger('input');
        });
    }
}

// Clear all values
function clearAll() {
    if (confirm('Hapus semua nilai yang sudah diisi?')) {
        $('.nilai-input').val('').trigger('input');
    }
}

// Ctrl+S to save
$(document).keydown(function(e) {
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        $('form').submit();
    }
});

// Validation before submit
$('form').on('submit', function(e) {
    let hasValue = false;
    $('.nilai-input').each(function() {
        if ($(this).val()) {
            hasValue = true;
            return false; // break
        }
    });
    
    if (!hasValue) {
        e.preventDefault();
        alert('Silakan isi minimal satu nilai sebelum menyimpan!');
    }
});
</script>
@endsection