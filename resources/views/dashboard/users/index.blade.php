@extends('dashboard.layouts.main')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Data User</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUser" onclick="createUser()">
        <i class="bi bi-person-plus"></i> Tambah User
    </button>
</div>

<!-- Statistik -->
<div class="row mb-3">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Total User</h6>
                <h3 class="mb-0">{{ $users->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Admin</h6>
                <h3 class="mb-0">{{ $users->where('role', 'admin')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Wali Kelas</h6>
                <h3 class="mb-0">{{ $users->where('role', 'wali_kelas')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Kelas Terisi</h6>
                <h3 class="mb-0">
                    {{ $users->where('role', 'wali_kelas')->pluck('kelas')->unique()->filter()->count() }}/4
                </h3>
            </div>
        </div>
    </div>
</div>

<!-- Tabel User -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="tblUser" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Kelas</th>
                        <th>Terdaftar</th>
                        <th>Status</th>
                        <th style="width:180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $user->name }}</strong>
                            @if($user->id === auth()->id())
                                <span class="badge bg-info ms-2">Anda</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-danger">Admin</span>
                            @else
                                <span class="badge bg-primary">Wali Kelas</span>
                            @endif
                        </td>
                        <td>
                            @if($user->kelas)
                                <span class="badge bg-success">{{ $user->kelas }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Terverifikasi</span>
                            @else
                                <span class="badge bg-warning">Belum Verifikasi</span>
                            @endif
                        </td>
                        <td class="text-nowrap">
                            <button class="btn btn-sm btn-warning" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalUser"
                                    onclick="editUser({{ $user->id }})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            
                            <button class="btn btn-sm btn-info"
                                    onclick="resetPassword({{ $user->id }}, '{{ $user->name }}')">
                                <i class="bi bi-key"></i>
                            </button>
                            
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.delete') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Hapus user {{ $user->name }}?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Create/Edit User -->
<div class="modal fade" id="modalUser" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formUser" method="POST" action="{{ route('users.store') }}">
                @csrf
                <input type="hidden" name="id">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            Password 
                            <span class="text-danger">*</span>
                            <small class="text-muted" id="passwordHint">(Min. 6 karakter)</small>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" id="password">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        <small class="text-muted" id="editPasswordHint" style="display:none;">
                            Kosongkan jika tidak ingin mengubah password
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select" name="role" id="role" required onchange="toggleKelas()">
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="wali_kelas">Wali Kelas</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="kelasDiv" style="display:none;">
                        <label class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select class="form-select" name="kelas" id="kelas">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelasList as $kls)
                                <option value="{{ $kls }}">{{ $kls }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Wajib diisi untuk role Wali Kelas</small>
                    </div>
                    
                    <!-- Info Box -->
                    <div class="alert alert-info">
                        <small>
                            <strong>Catatan:</strong>
                            <ul class="mb-0">
                                <li>Admin dapat mengakses semua fitur</li>
                                <li>Wali Kelas hanya dapat mengakses data kelasnya</li>
                                <li>Password default setelah reset: <code>password123</code></li>
                            </ul>
                        </small>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reset Password Confirmation -->
<form id="formReset" method="POST" action="{{ route('users.resetPassword') }}" style="display:none;">
    @csrf
    <input type="hidden" name="id" id="resetUserId">
</form>

@endsection

@section('js')
<script>
$(document).ready(function() {
    $('#tblUser').DataTable({
        responsive: true,
        order: [[3, 'asc'], [4, 'asc']],
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

function createUser() {
    $('#modalTitle').text('Tambah User');
    $('#formUser').attr('action', '{{ route("users.store") }}');
    $('#formUser')[0].reset();
    $('#formUser input[name=id]').val('');
    $('#password').prop('required', true);
    $('#passwordHint').show();
    $('#editPasswordHint').hide();
    $('#kelasDiv').hide();
}

function editUser(userId) {
    $('#modalTitle').text('Edit User');
    $('#formUser').attr('action', '{{ route("users.update") }}');
    $('#password').prop('required', false);
    $('#passwordHint').hide();
    $('#editPasswordHint').show();
    
    $.ajax({
        url: '{{ route("users.edit") }}',
        type: 'GET',
        data: {
            _token: '{{ csrf_token() }}',
            user_id: userId
        },
        success: function(data) {
            $('#formUser input[name=id]').val(data.id);
            $('#formUser input[name=name]').val(data.name);
            $('#formUser input[name=email]').val(data.email);
            $('#formUser select[name=role]').val(data.role);
            
            if (data.role === 'wali_kelas') {
                $('#kelasDiv').show();
                $('#formUser select[name=kelas]').val(data.kelas);
            } else {
                $('#kelasDiv').hide();
            }
        }
    });
}

function toggleKelas() {
    const role = $('#role').val();
    if (role === 'wali_kelas') {
        $('#kelasDiv').show();
        $('#kelas').prop('required', true);
    } else {
        $('#kelasDiv').hide();
        $('#kelas').prop('required', false);
        $('#kelas').val('');
    }
}

function togglePassword() {
    const passwordInput = $('#password');
    const toggleIcon = $('#toggleIcon');
    
    if (passwordInput.attr('type') === 'password') {
        passwordInput.attr('type', 'text');
        toggleIcon.removeClass('bi-eye').addClass('bi-eye-slash');
    } else {
        passwordInput.attr('type', 'password');
        toggleIcon.removeClass('bi-eye-slash').addClass('bi-eye');
    }
}

function resetPassword(userId, userName) {
    if (confirm(`Reset password untuk ${userName}?\n\nPassword akan direset menjadi: password123`)) {
        $('#resetUserId').val(userId);
        $('#formReset').submit();
    }
}
</script>
@endsection