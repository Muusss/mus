<form method="POST" action="{{ route('penilaian.update', ['id' => $alternatif->id]) }}">
    @csrf
    
    <!-- Hidden input untuk periode -->
    <input type="hidden" name="periode_id" value="{{ $periodeAktif->id }}">

    <div class="mb-2">
        <strong>{{ $alternatif->nis }} - {{ $alternatif->nama_siswa }}</strong>
        <br>
        <span class="badge bg-info">{{ $periodeAktif->nama_periode }}</span>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Kriteria</th>
                <th style="width:180px">Nilai (1-4)</th>
            </tr>
        </thead>
        <tbody>
        @foreach($kriteria as $k)
            @php
                $row = collect(data_get($rows, $k->id, []))->first();
            @endphp
            <tr>
                <td>{{ $k->kode }}</td>
                <td>{{ $k->kriteria }}</td>
                <td>
                    <input
                        type="number" 
                        step="1" 
                        min="1" 
                        max="4"
                        class="form-control @error('nilai_asli.'.$k->id) is-invalid @enderror"
                        name="nilai_asli[{{ $k->id }}]"
                        value="{{ old('nilai_asli.'.$k->id, $row->nilai_asli ?? '') }}"
                        placeholder="1-4"
                    >
                    @error('nilai_asli.'.$k->id)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="alert alert-info">
        <small>
            <i class="bi bi-info-circle"></i> 
            Nilai: 1 = Kurang, 2 = Cukup, 3 = Baik, 4 = Sangat Baik
        </small>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-primary">Simpan</button>
    </div>
</form>