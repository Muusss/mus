<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name','email','password','role','kelas'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin(): bool { return ($this->role ?? null) === 'admin'; }
    public function isWaliKelas(): bool { return ($this->role ?? null) === 'wali_kelas'; }
}
