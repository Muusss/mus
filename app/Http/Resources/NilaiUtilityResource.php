<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NilaiUtilityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'alternatif_id' => $this->alternatif_id,
            'kriteria_id' => $this->kriteria_id,
            'nilai' => $this->nilai,
        ];
    }
}

