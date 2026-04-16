<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Models
 * @author AbsensiKu
 * @version 1.0.0
 *
 * Model buku besar mutasi poin karyawan (seperti rekening bank).
 */
class PointLedger extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_type',
        'amount',
        'current_balance',
        'description',
        'presensi_id',
    ];

    protected $casts = [
        'amount' => 'integer',
        'current_balance' => 'integer',
    ];

    /**
     * Relasi ke user pemilik poin.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke presensi yang memicu transaksi poin.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function presensi()
    {
        return $this->belongsTo(Presensi::class);
    }
}