@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid px-3 px-md-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-3">
        <div class="d-flex align-items-center flex-wrap gap-2">
            <h3 class="mb-0">Data Siswa</h3>
            @if(auth()->user()->role !== 'wali_kelas')
            <!-- Filter Kelas untuk Admin -->
            <select class="form-select form-select-sm w-auto" id="filterKelas" onchange="filterByKelas()">
                <option value="all" {{ $kelasFilter == 'all' ? 'selected' : '' }}>Semua Kelas</option>
                @foreach($kelasList as $kelas)
                    <option value="{{ $kelas }}" {{ $kelasFilter == $kelas ? 'selected' : '' }}>
                        Kelas {{ $kelas }}
                    </option>
                @endforeach
            </select>
            @else
            <span class="badge bg-info">Kelas {{ auth()->user()->kelas }}</span>
            @endif
        </div>
        
        <!-- Action Buttons -->
        <div class="btn-group" role="group">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" onclick="create_button()">
                <i class="bi bi-plus-circle"></i>
                <span class="d-none d-sm-inline">Tambah Siswa</span>
                <span class="d-inline d-sm-none">Tambah</span>
            </button>
        </div>
    </div>

    <!-- Statistik per Kelas - Responsive Cards -->
    <div class="row g-2 mb-3">
        @if($kelasFilter && $kelasFilter !== 'all')
            <!-- Single class stat -->
            @if(isset($stats[$kelasFilter]))
            <div class="col-12">
                <div class="alert alert-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Kelas {{ $kelasFilter }}</strong>
                            <div class="small">
                                Total: {{ $stats[$kelasFilter]['total'] }} siswa 
                                (L: {{ $stats[$kelasFilter]['lk'] }}, P: {{ $stats[$kelasFilter]['pr'] }})
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-primary">{{ $stats[$kelasFilter]['lk'] }} <i class="bi bi-gender-male"></i></span>
                            <span class="badge bg-danger">{{ $stats[$kelasFilter]['pr'] }} <i class="bi bi-gender-female"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @else
            <!-- All classes stats -->
            @foreach(['6A', '6B', '6C', '6D'] as $kelas)
            <div class="col-6 col-sm-6 col-md-3">
                <div class="card h-100">
                    <div class="card-body p-2 p-sm-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-title mb-1">Kelas {{ $kelas }}</h6>
                                <p class="mb-0">
                                    <span class="badge bg-secondary">{{ $stats[$kelas]['total'] ?? 0 }}</span>
                                    <small class="d-none d-sm-inline">siswa</small>
                                </p>
                            </div>
                            <div class="d-flex flex-column gap-1">
                                <span class="badge bg-primary">{{ $stats[$kelas]['lk'] ?? 0 }}</span>
                                <span class="badge bg-danger">{{ $stats[$kelas]['pr'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>

    <!-- Main Table Card -->
    <div class="card">
        <div class="card-body">
            <!-- Mobile View -->
            <div class="d-block d-md-none">
                @forelse ($alternatif as $s)
                <div class="card mb-2">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $s->nama_siswa }}</h6>
                                <div class="small text-muted">NIS: {{ $s->nis }}</div>
                            </div>
                            <div class="d-flex gap-1">
                                <span class="badge bg-success">{{ $s->kelas }}</span>
                                <span class="badge bg-{{ $s->jk == 'Lk' ? 'primary' : 'danger' }}">
                                    {{ $s->jk }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-warning flex-fill"
                                    data-bs-toggle="modal" data-bs-target="#modalForm"
                                    onclick="show_button({{ $s->id }})">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <form action="{{ route('alternatif.delete') }}" method="POST" class="flex-fill" 
                                  onsubmit="return confirm('Hapus siswa ini?')">
                                @csrf
                                <input type="hidden" name="id" value="{{ $s->id }}">
                                <button type="submit" class="btn btn-sm btn-danger w-100">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">
                        Belum ada data siswa
                        {{ $kelasFilter && $kelasFilter !== 'all' ? 'untuk Kelas '.$kelasFilter : '' }}.
                    </p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" onclick="create_button()">
                        <i class="bi bi-plus-circle"></i> Tambah Siswa Pertama
                    </button>
                </div>
                @endforelse
            </div>

            <!-- Desktop Table View -->
            <div class="table-responsive d-none d-md-block">
                <table id="myTable" class="table table-striped table-bordered w-100">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th width="100">NIS</th>
                            <th>Nama Siswa</th>
                            <th width="60">JK</th>
                            <th width="80">Kelas</th>
                            <th width="140">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($alternatif as $s)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $s->nis }}</td>
                            <td>{{ $s->nama_siswa }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $s->jk == 'Lk' ? 'primary' : 'danger' }}">
                                    {{ $s->jk }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">{{ $s->kelas }}</span>
                            </td>
                            <td class="text-nowrap">
                                <button class="btn btn-sm btn-warning"
                                        data-bs-toggle="modal" data-bs-target="#modalForm"
                                        onclick="show_button({{ $s->id }})">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <form action="{{ route('alternatif.delete') }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Hapus siswa ini?')">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $s->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada data siswa
                                {{ $kelasFilter && $kelasFilter !== 'all' ? 'untuk Kelas '.$kelasFilter : '' }}.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Form - Responsive -->
    <div class="modal fade" id="modalForm" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form id="formSiswa" method="POST" action="{{ route('alternatif.store') }}">
                    @csrf
                    <input type="hidden" name="id">
                    
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Tambah Siswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">NIS <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nis" required maxlength="30"
                                       placeholder="Contoh: 2024001">
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_siswa" required maxlength="100"
                                       placeholder="Contoh: Ahmad Fauzi">
                            </div>
                            
                            <div class="col-6">
                                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="jk" id="jk_lk" value="Lk" required>
                                    <label class="btn btn-outline-primary" for="jk_lk">
                                        <i class="bi bi-gender-male"></i> Laki-laki
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="jk" id="jk_pr" value="Pr" required>
                                    <label class="btn btn-outline-danger" for="jk_pr">
                                        <i class="bi bi-gender-female"></i> Perempuan
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-6">
                                <label class="form-label">Kelas <span class="text-danger">*</span></label>
                                <select class="form-select" name="kelas" required>
                                    <option value="" disabled selected>Pilih</option>
                                    <option value="6A">6A</option>
                                    <option value="6B">6B</option>
                                    <option value="6C">6C</option>
                                    <option value="6D">6D</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
/* Mobile optimizations */
@media (max-width: 576px) {
    .card-body {
        padding: 0.75rem;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
}

/* Touch-friendly buttons */
.btn {
    min-height: 38px;
}

.btn-group .btn {
    padding: 0.5rem;
}

/* Better card hover on mobile */
@media (hover: hover) {
    .card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
}

/* Radio button group styling */
.btn-check:checked + .btn-outline-primary {
    background-color: var(--bs-primary);
    color: white;
}

.btn-check:checked + .btn-outline-danger {
    background-color: var(--bs-danger);
    color: white;
}
</style>
@endsection

@section('js')
<script>
// Filter function
function filterByKelas() {
    const kelas = document.getElementById('filterKelas').value;
    window.location.href = '{{ route("alternatif") }}?kelas=' + kelas;
}

// DataTable initialization (desktop only)
$(function () {
    if (window.innerWidth >= 768) {
        $('#myTable').DataTable({
            responsive: true,
            pagingType: 'full_numbers',
            order: [[4, 'asc'], [1, 'asc']],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                },
                emptyTable: "Tidak ada data yang tersedia",
                zeroRecords: "Tidak ada data yang cocok"
            }
        });
    }
});

// Form functions
function create_button() {
    $('#modalTitle').text('Tambah Siswa');
    $('#formSiswa').attr('action', '{{ route("alternatif.store") }}');
    $('#formSiswa')[0].reset();
    $('#formSiswa input[name=id]').val('');
    
    // Reset radio buttons
    document.querySelectorAll('input[name="jk"]').forEach(radio => {
        radio.checked = false;
    });
    
    // Focus on first input when modal opens
    $('#modalForm').on('shown.bs.modal', function () {
        $('input[name="nis"]').focus();
    });
}

function show_button(id) {
    $('#modalTitle').text('Edit Siswa');
    $('#formSiswa').attr('action', '{{ route("alternatif.update") }}');
    $('#btnSubmit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Memuat...');

    $.ajax({
        type: 'GET',
        url: '{{ route("alternatif.edit") }}',
        data: {
            _token: '{{ csrf_token() }}',
            alternatif_id: id
        },
        success: function (data) {
            $('#formSiswa input[name=id]').val(data.id);
            $('#formSiswa input[name=nis]').val(data.nis);
            $('#formSiswa input[name=nama_siswa]').val(data.nama_siswa);
            
            // Set radio button
            if (data.jk === 'Lk') {
                document.getElementById('jk_lk').checked = true;
            } else {
                document.getElementById('jk_pr').checked = true;
            }
            
            $('#formSiswa select[name=kelas]').val(data.kelas);
        },
        error: function () {
            alert('Gagal memuat data siswa.');
            $('#modalForm').modal('hide');
        },
        complete: function () {
            $('#btnSubmit').prop('disabled', false).html('<i class="bi bi-save"></i> Simpan');
        }
    });
}

// Add swipe to delete on mobile (optional)
if (window.innerWidth < 768) {
    let touchStartX = 0;
    let touchEndX = 0;
    
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        card.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe(card);
        });
    });
    
    function handleSwipe(card) {
        if (touchStartX - touchEndX > 100) {
            // Swipe left - show delete button
            card.querySelector('.btn-danger')?.classList.add('pulse');
            setTimeout(() => {
                card.querySelector('.btn-danger')?.classList.remove('pulse');
            }, 1000);
        }
    }
}
</script>

<style>
.pulse {
    animation: pulse 0.5s;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
</style>
@endsection