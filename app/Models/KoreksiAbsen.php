<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\\Models
 * @author AbsensiKu
 * @version 1.0.0
 * 
 * Model Eloquent untuk permintaan koreksi absensi karyawan.
 */
class KoreksiAbsen extends Model
{
    protected $table = 'koreksi_absen';

    protected $fillable = [
        'karyawan_id',
        'presensi_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'alasan',
        'status',
        'approved_by',
        'approved_at',
        'catatan_admin',
    ];

    /**
     * Relasi ke karyawan yang mengajukan koreksi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    /**
     * Relasi ke presensi yang dikoreksi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function presensi()
    {
        return $this->belongsTo(Presensi::class);
    }

    /**
     * Relasi ke admin yang menyetujui koreksi.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
