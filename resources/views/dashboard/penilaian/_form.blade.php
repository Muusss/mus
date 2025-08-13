<form id="formPenilaian" data-alternatif-id="{{ $alternatif->id }}">
    @csrf
    
    <!-- Hidden input untuk periode -->
    <input type="hidden" name="periode_id" value="{{ $periodeAktif->id }}">
    <input type="hidden" name="alternatif_id" value="{{ $alternatif->id }}">

    <div class="mb-2">
        <strong>{{ $alternatif->nis }} - {{ $alternatif->nama_siswa }}</strong>
        <br>
        <span class="badge bg-info">{{ $periodeAktif->nama_periode }}</span>
    </div>

    <!-- Alert untuk notifikasi -->
    <div id="alertNotif" class="alert alert-success d-none" role="alert">
        <i class="bi bi-check-circle"></i> <span id="alertMessage">Data berhasil disimpan!</span>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width:60px">Kode</th>
                <th>Kriteria</th>
                <th style="width:100px">Nilai (1-4)</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
        @foreach($kriteria as $k)
            @php
                $row = collect(data_get($rows, $k->id, []))->first();
                // Get subkriteria untuk kriteria ini
                $subKriterias = \App\Models\SubKriteria::where('kriteria_id', $k->id)
                    ->orderBy('skor', 'asc')
                    ->get();
            @endphp
            <tr>
                <td class="text-center">{{ $k->kode }}</td>
                <td>{{ $k->kriteria }}</td>
                <td>
                    <input
                        type="number" 
                        step="1" 
                        min="1" 
                        max="4"
                        class="form-control nilai-input text-center"
                        name="nilai_asli[{{ $k->id }}]"
                        data-kriteria-id="{{ $k->id }}"
                        value="{{ old('nilai_asli.'.$k->id, $row->nilai_asli ?? '') }}"
                        placeholder="1-4"
                        onchange="updateKeterangan(this, {{ $k->id }})"
                    >
                    <div class="invalid-feedback"></div>
                </td>
                <td>
                    <div id="keterangan-{{ $k->id }}" class="small">
                        @if($subKriterias->isNotEmpty())
                            <div class="subkriteria-info">
                                @foreach($subKriterias as $sub)
                                    <div class="d-flex align-items-center mb-1 subkriteria-item" data-skor="{{ $sub->skor }}">
                                        <span class="badge bg-secondary me-2" style="width: 25px;">{{ $sub->skor }}</span>
                                        <span class="text-muted">
                                            {{ $sub->label }}
                                            @if($sub->min_val !== null && $sub->max_val !== null)
                                                ({{ $sub->min_val }} - {{ $sub->max_val }})
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <span class="text-muted">Tidak ada subkriteria</span>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="alert alert-info">
        <small>
            <i class="bi bi-lightbulb"></i> <strong>Tips:</strong>
            <ul class="mb-0 mt-1">
                <li>Gunakan <kbd>Tab</kbd> untuk pindah ke kolom berikutnya</li>
                <li>Gunakan <kbd>Shift+Tab</kbd> untuk kembali ke kolom sebelumnya</li>
                <li>Tekan <kbd>Enter</kbd> atau <kbd>Ctrl+S</kbd> untuk menyimpan</li>
                <li>Nilai akan otomatis tersimpan saat pindah field</li>
            </ul>
        </small>
    </div>

    <div class="d-flex justify-content-between">
        <div>
            <button type="button" class="btn btn-secondary" id="btnPrevSiswa">
                <i class="bi bi-chevron-left"></i> Siswa Sebelumnya
            </button>
            <button type="button" class="btn btn-secondary" id="btnNextSiswa">
                Siswa Berikutnya <i class="bi bi-chevron-right"></i>
            </button>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" id="btnSimpan">
                <i class="bi bi-save"></i> Simpan
            </button>
            <button type="button" class="btn btn-success" id="btnSimpanLanjut">
                <i class="bi bi-save"></i> Simpan & Lanjut
            </button>
        </div>
    </div>
</form>

<style>
.subkriteria-item {
    transition: all 0.3s ease;
    padding: 2px 5px;
    border-radius: 3px;
}

.subkriteria-item.active {
    background-color: #e7f3ff;
    font-weight: bold;
}

.subkriteria-item.active .badge {
    background-color: #0d6efd !important;
}

.subkriteria-item.active .text-muted {
    color: #0d6efd !important;
}

.nilai-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

kbd {
    padding: 2px 4px;
    font-size: 90%;
    color: #fff;
    background-color: #333;
    border-radius: 3px;
}
</style>

<script>
// Function to highlight selected subkriteria
function updateKeterangan(input, kriteriaId) {
    const nilai = parseInt(input.value);
    const keteranganDiv = document.getElementById('keterangan-' + kriteriaId);
    
    if (keteranganDiv) {
        // Remove all active classes
        keteranganDiv.querySelectorAll('.subkriteria-item').forEach(item => {
            item.classList.remove('active');
        });
        
        // Add active class to matching score
        if (nilai >= 1 && nilai <= 4) {
            const activeItem = keteranganDiv.querySelector('.subkriteria-item[data-skor="' + nilai + '"]');
            if (activeItem) {
                activeItem.classList.add('active');
            }
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Highlight existing values
    document.querySelectorAll('.nilai-input').forEach(input => {
        if (input.value) {
            updateKeterangan(input, input.getAttribute('data-kriteria-id'));
        }
    });
});

$(document).ready(function() {
    // Auto save saat pindah field
    let autoSaveTimer;
    $('.nilai-input').on('change', function() {
        clearTimeout(autoSaveTimer);
        const $input = $(this);
        
        // Validate input
        const value = parseInt($input.val());
        if (value < 1 || value > 4 || isNaN(value)) {
            if ($input.val() !== '') {
                $input.addClass('is-invalid');
                return;
            }
        }
        $input.removeClass('is-invalid');
        
        // Auto save after 1 second
        autoSaveTimer = setTimeout(function() {
            simpanDataAjax(false);
        }, 1000);
    });

    // Handle form submission
    $('#formPenilaian').on('submit', function(e) {
        e.preventDefault();
        simpanDataAjax(false);
    });

    // Simpan & Lanjut
    $('#btnSimpanLanjut').on('click', function() {
        simpanDataAjax(true);
    });

    // Previous/Next buttons
    $('#btnPrevSiswa').on('click', function() {
        navigasiSiswa('prev');
    });

    $('#btnNextSiswa').on('click', function() {
        navigasiSiswa('next');
    });

    // Function to save data via AJAX
    function simpanDataAjax(lanjutNext) {
        const formData = $('#formPenilaian').serialize();
        const alternatifId = $('#formPenilaian').data('alternatif-id');
        
        // Show loading
        $('#btnSimpan, #btnSimpanLanjut').prop('disabled', true);
        $('#btnSimpan').html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...');
        
        $.ajax({
            url: '{{ url("penilaian") }}/' + alternatifId + '/ubah',
            type: 'POST',
            data: formData,
            success: function(response) {
                // Show success notification
                $('#alertNotif').removeClass('d-none alert-danger').addClass('alert-success');
                $('#alertMessage').text('Data berhasil disimpan!');
                
                // Update table in background
                updateTableRow(alternatifId);
                
                // Hide notification after 2 seconds
                setTimeout(function() {
                    $('#alertNotif').addClass('d-none');
                }, 2000);
                
                // If simpan & lanjut, go to next student
                if (lanjutNext) {
                    setTimeout(function() {
                        navigasiSiswa('next');
                    }, 500);
                }
            },
            error: function(xhr) {
                // Show error notification
                $('#alertNotif').removeClass('d-none alert-success').addClass('alert-danger');
                $('#alertMessage').text('Gagal menyimpan data. Silakan coba lagi.');
            },
            complete: function() {
                // Reset button
                $('#btnSimpan, #btnSimpanLanjut').prop('disabled', false);
                $('#btnSimpan').html('<i class="bi bi-save"></i> Simpan');
            }
        });
    }

    // Update table row in parent window
    function updateTableRow(alternatifId) {
        // Get values from form
        const values = {};
        $('.nilai-input').each(function() {
            const kriteriaId = $(this).data('kriteria-id');
            const value = $(this).val();
            if (value) {
                values[kriteriaId] = value;
            }
        });
        
        // Update badges in main table
        if (window.parent && window.parent.updatePenilaianRow) {
            window.parent.updatePenilaianRow(alternatifId, values);
        }
    }

    // Navigate to prev/next student
    function navigasiSiswa(direction) {
        if (window.parent && window.parent.navigasiSiswa) {
            window.parent.navigasiSiswa(direction);
        }
    }

    // Focus on first empty input
    const firstEmpty = $('.nilai-input').filter(function() { 
        return $(this).val() === ''; 
    }).first();
    
    if (firstEmpty.length) {
        firstEmpty.focus();
    } else {
        $('.nilai-input').first().focus();
    }

    // Enable Tab navigation between inputs
    $('.nilai-input').on('keydown', function(e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            const inputs = $('.nilai-input');
            const currentIndex = inputs.index(this);
            const nextIndex = e.shiftKey ? currentIndex - 1 : currentIndex + 1;
            
            if (nextIndex >= 0 && nextIndex < inputs.length) {
                inputs.eq(nextIndex).focus().select();
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            simpanDataAjax(false);
        } else if (e.key >= '1' && e.key <= '4') {
            // Allow direct number input
            e.preventDefault();
            $(this).val(e.key).trigger('change');
            
            // Auto move to next field
            const inputs = $('.nilai-input');
            const currentIndex = inputs.index(this);
            if (currentIndex < inputs.length - 1) {
                setTimeout(() => {
                    inputs.eq(currentIndex + 1).focus().select();
                }, 100);
            }
        }
    });
});
</script>