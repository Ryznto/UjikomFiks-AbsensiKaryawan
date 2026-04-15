<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\\Models
 * @author AbsensiKu
 * @version 1.0.0
 * 
 * Model Eloquent untuk mengelola divisi organisasi.
 */
class Divisi extends Model
{
    protected $table = 'divisi';

    protected $fillable = ['nama_divisi'];

    /**
     * Relasi ke jabatan dalam divisi ini.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jabatans()
    {
        return $this->hasMany(Jabatan::class);
    }

    /**
     * Relasi ke karyawan di divisi ini.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }
}
