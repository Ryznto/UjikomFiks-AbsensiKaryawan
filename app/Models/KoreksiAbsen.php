<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function presensi()
    {
        return $this->belongsTo(Presensi::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}