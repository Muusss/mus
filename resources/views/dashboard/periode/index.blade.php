@extends('dashboard.layouts.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Data Periode Semester</h3>
    @if(auth()->user()->role === 'admin')
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPeriode">
        Tambah Periode
    </button>
    @endif
</div>

@if(!$periodes->where('is_active', true)->first())
<div class="alert alert-warning">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <strong>Perhatian!</strong> Belum ada periode yang aktif. Silakan aktifkan salah satu periode.
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tahun Ajaran</th>
                        <th>Semester</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periodes as $periode)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $periode->tahun_ajaran }}/{{ $periode->tahun_ajaran + 1 }}</td>
                        <td>
                            <span class="badge bg-{{ $periode->semester == 1 ? 'primary' : 'info' }}">
                                {{ $periode->semester == 1 ? 'Ganjil' : 'Genap' }}
                            </span>
                        </td>
                        <td>
                            @if($periode->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            @if(auth()->user()->role === 'admin')
                                @if(!$periode->is_active)
                                <form action="{{ route('periode.setActive', $periode->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Aktifkan</button>
                                </form>
                                @endif
                                
                                <form action="{{ route('periode.delete') }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Hapus periode ini?')">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $periode->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data periode</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Periode -->
<div class="modal fade" id="modalPeriode" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('periode.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Periode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <input type="number" name="tahun_ajaran" class="form-control" 
                               min="2020" max="2100" value="{{ date('Y') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-select" required>
                            <option value="">Pilih Semester</option>
                            <option value="1">Ganjil</option>
                            <option value="2">Genap</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection