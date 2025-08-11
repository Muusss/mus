<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PenilaianStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'alternatif_id'   => ['required','integer','exists:alternatifs,id'],
            'kriteria_id'     => ['required','integer','exists:kriterias,id'],

            // Pilih salah satu:
            'nilai_angka'     => ['nullable','numeric','min:0','max:100','required_without_all:sub_kriteria_id,label'],
            'sub_kriteria_id' => ['nullable','integer','exists:sub_kriterias,id','required_without_all:nilai_angka,label'],
            'label'           => ['nullable','string','max:100','required_without_all:nilai_angka,sub_kriteria_id'],
        ];
    }

    public function messages(): array
    {
        return [
            'alternatif_id.*'   => 'Siswa wajib diisi.',
            'kriteria_id.*'     => 'Kriteria wajib dipilih.',
            'nilai_angka.required_without_all' => 'Isi nilai angka ATAU pilih sub kriteria/label.',
            'sub_kriteria_id.required_without_all' => 'Pilih sub kriteria ATAU isi nilai angka/label.',
            'label.required_without_all' => 'Isi label ATAU nilai angka/sub kriteria.',
        ];
    }
}
