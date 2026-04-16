<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'nip',
        'password',
        'role',
        'point_balance', // ← tambah ini!
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

    public function pointLedgers()
    {
        return $this->hasMany(PointLedger::class);
    }

    public function userTokens()
    {
        return $this->hasMany(UserToken::class);
    }

    public function availableTokens()
    {
        return $this->hasMany(UserToken::class)
                    ->where('status', 'AVAILABLE');
    }
}