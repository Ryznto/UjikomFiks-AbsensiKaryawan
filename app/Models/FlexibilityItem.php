<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Models
 * @author AbsensiKu
 * @version 1.0.0
 *
 * Model katalog item kelonggaran yang bisa ditukar dengan poin.
 */
class FlexibilityItem extends Model
{
    protected $fillable = [
        'item_name',
        'description',
        'point_cost',
        'stock_limit',
        'token_type',
        'tolerance_minutes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'point_cost' => 'integer',
        'stock_limit' => 'integer',
        'tolerance_minutes' => 'integer',
    ];

    /**
     * Relasi ke token milik user yang berasal dari item ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userTokens()
    {
        return $this->hasMany(UserToken::class, 'item_id');
    }
}