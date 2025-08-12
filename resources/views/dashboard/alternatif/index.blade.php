@extends('dashboard.layouts.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="mb-0">Data Siswa</h3>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalForm" onclick="create_button()">Tambah Siswa</button>
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
            <td>{{ $s->jk }}</td>
            <td>{{ $s->kelas }}</td>
            <td class="text-nowrap">
                <button class="btn btn-sm btn-warning"
                        data-bs-toggle="modal" data-bs-target="#modalForm"
                        onclick="show_button({{ $s->id }})">
                Edit
                </button>
                <form action="{{ route('alternatif.delete') }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus siswa ini?')">
                @csrf
                <input type="hidden" name="id" value="{{ $s->id }}">
                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
            </tr>
        @empty
            <tr>
            <td colspan="6" class="text-center text-muted">Belum ada data siswa.</td>
            </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- Modal Create / Edit --}}
<div class="modal fade" id="modalForm" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <form id="formSiswa" method="POST" action="{{ route('alternatif.store') }}">
        @csrf
        <input type="hidden" name="id"> {{-- diisi saat edit --}}
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Tambah Siswa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">NIS</label>
              <input type="text" class="form-control" name="nis" required maxlength="30">
            </div>
            <div class="col-md-8">
              <label class="form-label">Nama Siswa</label>
              <input type="text" class="form-control" name="nama_siswa" required maxlength="100">
            </div>
            <div class="col-md-4">
              <label class="form-label">Jenis Kelamin</label>
              <select class="form-select" name="jk" required>
                <option value="" disabled selected>Pilih</option>
                <option value="Lk">Lk</option>
                <option value="Pr">Pr</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Kelas</label>
              <select class="form-select" name="kelas" required>
                <option value="" disabled selected>Pilih</option>
                <option value="6A">6A</option>
                <option value="6B">6B</option>
                <option value="6C">6C</option>
                <option value="6D">6D</option>
              </select>
            </div>
          </div>

          {{-- pesan validasi --}}
          @if ($errors->any())
            <div class="alert alert-danger mt-3">
              <ul class="mb-0">
                @foreach ($errors->all() as $err)
                  <li>{{ $err }}</li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
$(function () {
  $('#myTable').DataTable({
    responsive: true,
    pagingType: 'full_numbers',
    order: [[1, 'asc']] // urut NIS
  });
});

function create_button() {
  // Judul & action -> STORE
  $('#modalTitle').text('Tambah Siswa');
  $('#formSiswa').attr('action', '{{ route('alternatif.store') }}');
  $('#formSiswa input[name=_method]').remove();

  // Kosongkan field
  $('#formSiswa input[name=id]').val('');
  $('#formSiswa input[name=nis]').val('');
  $('#formSiswa input[name=nama_siswa]').val('');
  $('#formSiswa select[name=jk]').val('');
  $('#formSiswa select[name=kelas]').val('');
}

function show_button(alternatif_id) {
  // Judul & action -> UPDATE
  $('#modalTitle').text('Edit Siswa');
  $('#formSiswa').attr('action', '{{ route('alternatif.update') }}');

  // Spoofing method bila diperlukan oleh route (pakai POST di controller-mu)
  if (!$('#formSiswa input[name=_method]').length) {
    $('#formSiswa').append('<input type="hidden" name="_method" value="POST">');
  }

  $('#btnSubmit').prop('disabled', true).text('Memuat...');

  $.ajax({
    type: 'GET',
    url: '{{ route('alternatif.edit') }}',
    data: {
      _token: '{{ csrf_token() }}',
      alternatif_id: alternatif_id
    },
    success: function (d) {
      $('#formSiswa input[name=id]').val(d.id);
      $('#formSiswa input[name=nis]').val(d.nis);
      $('#formSiswa input[name=nama_siswa]').val(d.nama_siswa);
      $('#formSiswa select[name=jk]').val(d.jk);
      $('#formSiswa select[name=kelas]').val(d.kelas);
    },
    error: function () {
      alert('Gagal memuat data siswa.');
    },
    complete: function () {
      $('#btnSubmit').prop('disabled', false).text('Simpan');
    }
  });
}
</script>
@endsection
