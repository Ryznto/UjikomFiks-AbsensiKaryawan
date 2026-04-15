<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\\Models
 * @author AbsensiKu
 * @version 1.0.0
 * 
 * Model Eloquent untuk pengajuan izin dan cuti karyawan.
 */
class IzinCuti extends Model
{
    protected $table = 'izin_cuti';

    protected $fillable = [
        'karyawan_id',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'status',
        'approved_by',
        'approved_at',
    ];

    /**
     * Relasi ke karyawan yang mengajukan izin/cuti.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    /**
     * Relasi ke admin yang menyetujui izin/cuti.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
