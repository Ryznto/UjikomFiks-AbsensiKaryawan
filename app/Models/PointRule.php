<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Models
 * @author AbsensiKu
 * @version 1.0.0
 *
 * Model untuk aturan dinamis pemberian/pengurangan poin.
 */
class PointRule extends Model
{
    protected $fillable = [
        'rule_name',
        'condition_type',
        'condition_operator',
        'condition_value',
        'condition_value_max',
        'point_modifier',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'point_modifier' => 'integer',
    ];
}