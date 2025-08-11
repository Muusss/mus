<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Alternatif;

class AlternatifPolicy
{
    public function view(User $u, Alternatif $s){ return $u->role==='admin' || ($u->role==='wali_kelas' && $s->kelas===$u->kelas); }
    public function update(User $u, Alternatif $s){ return $this->view($u,$s); }
    public function delete(User $u, Alternatif $s){ return $this->view($u,$s); }
    public function viewAny(User $u){ return in_array($u->role,['admin','wali_kelas']); }
    public function create(User $u){ return in_array($u->role,['admin','wali_kelas']); }

}

