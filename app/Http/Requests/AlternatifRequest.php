<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlternatifRequest extends FormRequest
{
    public function rules(): array {
        $id = $this->route('alternatif')?->id; // untuk unique ignore saat update
        return [
            'nis'        => 'required|string|max:30|unique:alternatif,nis'.($id?','.$id:''),
            'nama_siswa' => 'required|string|max:100',
            'jk'         => 'required|in:Lk,Pr',
            'kelas'      => 'required|in:6A,6B,6C,6D',
        ];
    }
}