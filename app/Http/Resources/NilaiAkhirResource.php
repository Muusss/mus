<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NilaiAkhirResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'alternatif_id' => $this->alternatif_id,
            'periode_id' => $this->periode_id,
            'total' => $this->total,
            'peringkat' => $this->peringkat,
            'alternatif' => new AlternatifResource($this->whenLoaded('alternatif')),
        ];
    }
}
