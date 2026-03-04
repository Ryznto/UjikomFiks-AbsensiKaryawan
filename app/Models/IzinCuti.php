<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}