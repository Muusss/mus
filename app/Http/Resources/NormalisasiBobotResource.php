<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NormalisasiBobotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kriteria_id' => $this->kriteria_id,
            'normalisasi' => $this->normalisasi,
            'kriteria' => new KriteriaResource($this->whenLoaded('kriteria')),
        ];
    }
}