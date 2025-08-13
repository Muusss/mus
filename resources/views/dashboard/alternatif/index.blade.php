@extends('dashboard.layouts.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center">
        <h3 class="mb-0">Data Siswa</h3>
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
        <span class="badge bg-info ms-3">Kelas {{ auth()->user()->kelas }}</span>
        @endif
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" onclick="create_button()">
        Tambah Siswa
    </button>
</div>

<!-- Statistik per Kelas -->
<div class="row mb-3">
    @php
        $stats = $alternatif->groupBy('kelas')->map(function($group, $kelas) {
            return [
                'total' => $group->count(),
                'lk' => $group->where('jk', 'Lk')->count(),
                'pr' => $group->where('jk', 'Pr')->count()
            ];
        });
    @endphp
    
    @if($kelasFilter && $kelasFilter !== 'all')
        <!-- Tampilkan stat untuk kelas terfilter -->
        @if(isset($stats[$kelasFilter]))
        <div class="col-md-12">
            <div class="alert alert-info">
                <strong>Kelas {{ $kelasFilter }}:</strong> 
                Total {{ $stats[$kelasFilter]['total'] }} siswa 
                ({{ $stats[$kelasFilter]['lk'] }} Laki-laki, {{ $stats[$kelasFilter]['pr'] }} Perempuan)
            </div>
        </div>
        @endif
    @else
        <!-- Tampilkan semua stat kelas -->
        @foreach(['6A', '6B', '6C', '6D'] as $kelas)
            <div class="col-md-3">
                <div class="card mb-2">
                    <div class="card-body p-3">
                        <h6 class="card-title mb-0">Kelas {{ $kelas }}</h6>
                        <p class="mb-0">
                            <small>
                                Total: {{ $stats[$kelas]['total'] ?? 0 }} siswa<br>
                                L: {{ $stats[$kelas]['lk'] ?? 0 }}, P: {{ $stats[$kelas]['pr'] ?? 0 }}
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table id="myTable" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>#</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>JK</th>
            <th>Kelas</th>
            <th style="width:130px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
        @forelse ($alternatif as $s)
            <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $s->nis }}</td>
            <td>{{ $s->nama_siswa }}</td>
            <td>
                <span class="badge bg-{{ $s->jk == 'Lk' ? 'primary' : 'danger' }}">
                    {{ $s->jk }}
                </span>
            </td>
            <td>
                <span class="badge bg-success">{{ $s->kelas }}</span>
            </td>
            <td class="text-nowrap">
                <button class="btn btn-sm btn-warning"
                        data-bs-toggle="modal" data-bs-target="#modalForm"
                        onclick="show_button({{ $s->id }})">
                Edit
                </button>
                <form action="{{ route('alternatif.delete') }}" method="POST" class="d-inline" 
                      onsubmit="return confirm('Hapus siswa ini?')">
                @csrf
                <input type="hidden" name="id" value="{{ $s->id }}">
                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
            </tr>
        @empty
            <tr>
            <td colspan="6" class="text-center text-muted">
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

<!-- Modal tetap sama -->

@endsection

@section('js')
<script>
// Filter function
function filterByKelas() {
    const kelas = document.getElementById('filterKelas').value;
    window.location.href = '{{ route("alternatif") }}?kelas=' + kelas;
}

$(function () {
  $('#myTable').DataTable({
    responsive: true,
    pagingType: 'full_numbers',
    order: [[4, 'asc'], [1, 'asc']], // urut Kelas, lalu NIS
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
});

// Fungsi create_button dan show_button tetap sama
</script>
@endsection