<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    protected $table = 'sub_kriterias';

    // skor 1–4 + opsional rentang
    protected $fillable = ['kriteria_id','label','skor','min_val','max_val'];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }
}
