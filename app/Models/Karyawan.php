<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawan';

    protected $fillable = [
        'user_id',
        'nama',
        'divisi_id',
        'jabatan_id',
        'shift_id',
        'no_hp',
        'foto',
        'status_aktif',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }

    public function izinCutis()
    {
        return $this->hasMany(IzinCuti::class);
    }
}