<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Models
 * @author AbsensiKu
 * @version 1.0.0
 *
 * Model inventory token kelonggaran milik user.
 */
class UserToken extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
        'status',
        'used_at_attendance_id',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Relasi ke user pemilik token.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke item katalog.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(FlexibilityItem::class, 'item_id');
    }

    /**
     * Relasi ke presensi saat token dipakai.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function presensi()
    {
        return $this->belongsTo(Presensi::class, 'used_at_attendance_id');
    }
}