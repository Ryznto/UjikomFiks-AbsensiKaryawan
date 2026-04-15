<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @package App\\Models
 * @author AbsensiKu
 * @version 1.0.0
 * 
 * Model User dengan autentikasi Laravel (Admin & Karyawan).
 */
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

    /**
     * Relasi ke profil admin (jika role admin).
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function adminProfile()
    {
        return $this->hasOne(AdminProfile::class);
    }

    /**
     * Relasi ke data karyawan (jika role karyawan).
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function karyawan()
    {
        return $this->hasOne(Karyawan::class);
    }
}
