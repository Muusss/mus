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
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="tblPenilaian" class="table table-striped">
                <thead>
                    <tr>
                        <th>Alternatif</th>
                        @foreach($kriteria as $k)
                            <th class="text-center">{{ $k->kriteria }}</th>
                        @endforeach
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($alternatif as $alt)
                    <tr>
                        <td>{{ $alt->nis }} - {{ $alt->nama_siswa }}</td>

                        @foreach($kriteria as $k)
                            @php
                                $row = collect(data_get($penilaian, "{$alt->id}.{$k->id}", []))->first();
                            @endphp
                            <td class="text-center">
                                @if($row && $row->nilai_asli !== null)
                                    <span class="badge bg-primary">{{ number_format((float) $row->nilai_asli, 0) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        @endforeach

                        <td class="text-center">
                            <button
                                type="button"
                                class="btn btn-sm btn-warning"
                                onclick="editPenilaian({{ $alt->id }}, @js($alt->nama_siswa), {{ $periodeAktif->id ?? 'null' }})">
                                Edit
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
<div class="modal fade" id="modalPenilaian" tabindex="-1" aria-hidden="true">
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
function changePeriode() {
    const periodeId = document.getElementById('periodePicker').value;
    window.location.href = '{{ route("penilaian") }}?periode_id=' + periodeId;
}

function editPenilaian(alternatifId, namaSiswa, periodeId) {
    const $modal = $('#modalPenilaian');
    $modal.find('#nama_siswa').text(namaSiswa);
    $modal.find('.modal-body').html('<div class="text-center py-4">Memuat formulir...</div>');
    $modal.modal('show');

    let url = @json(route('penilaian.edit', ['id' => '__ID__']));
    url = url.replace('__ID__', encodeURIComponent(alternatifId));
    
    // Add periode_id to URL
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

$(document).ready(function () {
    if (window.jQuery && $.fn.DataTable) {
        $('#tblPenilaian').DataTable({
            responsive: true,
            pagingType: 'full_numbers',
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
</script>
@endsection