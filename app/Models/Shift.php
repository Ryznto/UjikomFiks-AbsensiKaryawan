<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\\Models
 * @author AbsensiKu
 * @version 1.0.0
 * 
 * Model Eloquent untuk mengelola jadwal shift kerja.
 */
class Shift extends Model
{
    protected $table = 'shift';

    protected $fillable = [
        'nama_shift',
        'jam_masuk',
        'jam_pulang',
        'toleransi_terlambat',
    ];

    /**
     * Relasi ke karyawan dengan shift ini.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }
}
