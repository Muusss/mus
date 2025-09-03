<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KriteriaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kode' => $this->kode,
            'kriteria' => $this->kriteria,
            'atribut' => $this->atribut,
            'urutan_prioritas' => $this->urutan_prioritas,
            'bobot_roc' => $this->bobot_roc,
        ];
    }
}