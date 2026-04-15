<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// [INHERITANCE] = class Presensi mewarisi semua fitur dari class Model Laravel
/**
 * @package App\\Models
 * @author AbsensiKu
 * @version 1.0.0
 * 
 * Model Eloquent untuk data presensi karyawan harian.
 */
class Presensi extends Model
{
    // [VARIABLE/Property] = paksa Laravel pakai tabel 'presensi' bukan 'presensis'
    protected $table = 'presensi';

    // [ARRAY] = daftar kolom yang boleh diisi, sebagai keamanan mass assignment
    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'shift_id',
        'jam_masuk',
        'jam_pulang',
        'foto_masuk',
        'foto_pulang',
        'latitude',
        'longitude',
        'status_absen',
        'status_pulang',
        'keterangan',
    ];

    // [METHOD + RELASI belongsTo] = 1 presensi milik 1 karyawan
    // cara pakai: $presensi->karyawan->nama
    /**
     * Relasi ke karyawan.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    // [METHOD + RELASI belongsTo] = 1 presensi milik 1 shift
    // cara pakai: $presensi->shift->nama_shift
    /**
     * Relasi ke shift kerja.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
