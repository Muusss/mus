<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlternatifResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nis' => $this->nis,
            'nama_siswa' => $this->nama_siswa,
            'alternatif' => $this->nama_siswa, // alias for compatibility
            'jk' => $this->jk,
            'kelas' => $this->kelas,
        ];
    }
}