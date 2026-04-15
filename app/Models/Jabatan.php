<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\\Models
 * @author AbsensiKu
 * @version 1.0.0
 * 
 * Model Eloquent untuk mengelola jabatan karyawan.
 */
class Jabatan extends Model
{
    protected $table = 'jabatan';

    protected $fillable = [
        'nama_jabatan',
        'divisi_id',
    ];

    /**
     * Relasi ke divisi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    /**
     * Relasi ke karyawan dengan jabatan ini.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function karyawans()
    {
        return $this->hasMany(Karyawan::class);
    }
}
