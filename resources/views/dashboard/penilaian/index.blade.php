@extends('dashboard.layouts.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">Penilaian Alternatif</h3>
    </div>
    
    <div class="d-flex gap-2">
        <!-- Dropdown Periode -->
        <select class="form-select" id="periodePicker" onchange="changePeriode()">
            @foreach($periodes as $periode)
                <option value="{{ $periode->id }}" 
                    {{ $periodeAktif && $periodeAktif->id == $periode->id ? 'selected' : '' }}>
                    {{ $periode->nama_periode }}
                    @if($periode->is_active) (Aktif) @endif
                </option>
            @endforeach
        </select>
        
        @if(!isset($periodeAktif))
        <a href="{{ route('periode') }}" class="btn btn-warning">
            <i class="bi bi-exclamation-triangle"></i> Kelola Periode
        </a>
        @endif
    </div>
</div>

<!-- Info Periode Aktif -->
@if(isset($periodeAktif))
<div class="alert alert-info mb-3">
    <i class="bi bi-info-circle me-2"></i>
    Menampilkan penilaian untuk: <strong>{{ $periodeAktif->nama_periode }}</strong>
    (Tahun Ajaran {{ $periodeAktif->tahun_ajaran }}/{{ $periodeAktif->tahun_ajaran + 1 }})
</div>

<!-- Progress Pengisian -->
<div class="card mb-3">
    <div class="card-body">
        @php
            $totalData = $alternatif->count() * $kriteria->count();
            $terisiData = 0;
            foreach($alternatif as $alt) {
                foreach($kriteria as $k) {
                    $row = collect(data_get($penilaian, "{$alt->id}.{$k->id}", []))->first();
                    if($row && $row->nilai_asli !== null) {
                        $terisiData++;
                    }
                }
            }
            $persentase = $totalData > 0 ? round(($terisiData / $totalData) * 100, 1) : 0;
        @endphp
        <h6>Progress Pengisian: {{ $terisiData }} dari {{ $totalData }} data ({{ $persentase }}%)</h6>
        <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" 
                 style="width: {{ $persentase }}%" 
                 aria-valuenow="{{ $persentase }}" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
                {{ $persentase }}%
            </div>
        </div>
    </div>
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="tblPenilaian" class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        @foreach($kriteria as $k)
                            <th class="text-center">{{ $k->kode }}</th>
                        @endforeach
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($alternatif as $alt)
                    <tr data-alternatif-id="{{ $alt->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $alt->nis }}</td>
                        <td>{{ $alt->nama_siswa }}</td>
                        <td><span class="badge bg-primary">{{ $alt->kelas }}</span></td>

                        @php
                            $jumlahTerisi = 0;
                        @endphp
                        @foreach($kriteria as $k)
                            @php
                                $row = collect(data_get($penilaian, "{$alt->id}.{$k->id}", []))->first();
                                if($row && $row->nilai_asli !== null) {
                                    $jumlahTerisi++;
                                }
                            @endphp
                            <td class="text-center nilai-cell" data-kriteria-id="{{ $k->id }}">
                                @if($row && $row->nilai_asli !== null)
                                    <span class="badge bg-primary">{{ number_format((float) $row->nilai_asli, 0) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        @endforeach

                        <td class="text-center status-cell">
                            @if($jumlahTerisi == $kriteria->count())
                                <span class="badge bg-success">Lengkap</span>
                            @elseif($jumlahTerisi > 0)
                                <span class="badge bg-warning">{{ $jumlahTerisi }}/{{ $kriteria->count() }}</span>
                            @else
                                <span class="badge bg-danger">Kosong</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <button
                                type="button"
                                class="btn btn-sm btn-warning btn-edit"
                                data-index="{{ $loop->index }}"
                                onclick="editPenilaian({{ $alt->id }}, '{{ $alt->nama_siswa }}', {{ $periodeAktif->id ?? 'null' }}, {{ $loop->index }})">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Penilaian -->
<div class="modal fade" id="modalPenilaian" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Edit Penilaian: <span id="nama_siswa">-</span>
                    <br>
                    <small class="text-muted">
                        Periode: <span id="periode_nama">{{ $periodeAktif->nama_periode ?? '-' }}</span>
                    </small>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-4">Memuat formulir...</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
// Global variables
let currentIndex = 0;
let alternatifList = [];
let kriteriaList = [];

// Initialize on document ready
$(document).ready(function () {
    // Store alternatif list for navigation
    @foreach($alternatif as $alt)
        alternatifList.push({
            id: {{ $alt->id }},
            nama: '{{ $alt->nama_siswa }}',
            nis: '{{ $alt->nis }}'
        });
    @endforeach
    
    // Store kriteria list
    @foreach($kriteria as $k)
        kriteriaList.push({
            id: {{ $k->id }},
            kode: '{{ $k->kode }}'
        });
    @endforeach
    
    // Initialize DataTable
    if (window.jQuery && $.fn.DataTable) {
        $('#tblPenilaian').DataTable({
            responsive: true,
            pagingType: 'full_numbers',
            pageLength: 25,
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
});

function changePeriode() {
    const periodeId = document.getElementById('periodePicker').value;
    window.location.href = '{{ route("penilaian") }}?periode_id=' + periodeId;
}

function editPenilaian(alternatifId, namaSiswa, periodeId, index) {
    currentIndex = index;
    const $modal = $('#modalPenilaian');
    $modal.find('#nama_siswa').text(namaSiswa);
    $modal.find('.modal-body').html('<div class="text-center py-4">Memuat formulir...</div>');
    $modal.modal('show');

    // Build URL correctly
    let url = '{{ url("penilaian") }}/' + alternatifId + '/ubah';
    
    // Add periode_id to URL if exists
    if (periodeId) {
        url += '?periode_id=' + periodeId;
    }

    $.get(url, function (html) {
        $modal.find('.modal-body').html(html);
    }).fail(function (xhr) {
        const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Silakan coba lagi.';
        $modal.find('.modal-body').html('<div class="alert alert-danger">Gagal memuat formulir. ' + msg + '</div>');
    });
}

// Function to update table row without reload
window.updatePenilaianRow = function(alternatifId, values) {
    const $row = $('tr[data-alternatif-id="' + alternatifId + '"]');
    
    if ($row.length) {
        let terisi = 0;
        
        // Update each kriteria cell
        kriteriaList.forEach(function(kriteria) {
            const $cell = $row.find('.nilai-cell[data-kriteria-id="' + kriteria.id + '"]');
            if (values[kriteria.id]) {
                $cell.html('<span class="badge bg-primary">' + values[kriteria.id] + '</span>');
                terisi++;
            }
        });
        
        // Update status
        const $statusCell = $row.find('.status-cell');
        if (terisi === kriteriaList.length) {
            $statusCell.html('<span class="badge bg-success">Lengkap</span>');
        } else if (terisi > 0) {
            $statusCell.html('<span class="badge bg-warning">' + terisi + '/' + kriteriaList.length + '</span>');
        } else {
            $statusCell.html('<span class="badge bg-danger">Kosong</span>');
        }
        
        // Update progress bar
        updateProgress();
    }
};

// Navigate to previous/next student
window.navigasiSiswa = function(direction) {
    if (direction === 'prev' && currentIndex > 0) {
        currentIndex--;
    } else if (direction === 'next' && currentIndex < alternatifList.length - 1) {
        currentIndex++;
    } else {
        // Show notification if at the beginning or end
        if (direction === 'prev') {
            alert('Ini adalah siswa pertama');
        } else {
            alert('Ini adalah siswa terakhir');
        }
        return;
    }
    
    const nextSiswa = alternatifList[currentIndex];
    const periodeId = {{ $periodeAktif->id ?? 'null' }};
    
    // Load new form
    $('#modalPenilaian').find('#nama_siswa').text(nextSiswa.nama);
    $('#modalPenilaian').find('.modal-body').html('<div class="text-center py-4">Memuat data siswa berikutnya...</div>');
    
    let url = '{{ url("penilaian") }}/' + nextSiswa.id + '/ubah';
    if (periodeId) {
        url += '?periode_id=' + periodeId;
    }
    
    $.get(url, function (html) {
        $('#modalPenilaian').find('.modal-body').html(html);
    });
};

// Update progress bar
function updateProgress() {
    let totalData = alternatifList.length * kriteriaList.length;
    let terisiData = 0;
    
    $('tr[data-alternatif-id]').each(function() {
        $(this).find('.nilai-cell').each(function() {
            if ($(this).find('.badge').length > 0) {
                terisiData++;
            }
        });
    });
    
    let persentase = totalData > 0 ? Math.round((terisiData / totalData) * 100 * 10) / 10 : 0;
    
    $('.progress-bar').css('width', persentase + '%').attr('aria-valuenow', persentase).text(persentase + '%');
    $('.card-body h6').text('Progress Pengisian: ' + terisiData + ' dari ' + totalData + ' data (' + persentase + '%)');
}

// Keyboard shortcuts
$(document).on('keydown', function(e) {
    if ($('#modalPenilaian').hasClass('show')) {
        // Ctrl+S to save
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            $('#formPenilaian').submit();
        }
        // Ctrl+Arrow for navigation
        if (e.ctrlKey && e.key === 'ArrowLeft') {
            e.preventDefault();
            navigasiSiswa('prev');
        }
        if (e.ctrlKey && e.key === 'ArrowRight') {
            e.preventDefault();
            navigasiSiswa('next');
        }
    }
});
</script>
@endsection