<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'nip',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    public function adminProfile()
    {
        return $this->hasOne(AdminProfile::class);
    }

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class);
    }
}