<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentStatement extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'statement',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(AssessmentCategory::class, 'category_id');
    }

    public function assessmentDetails()
    {
        return $this->hasMany(AssessmentDetail::class, 'statement_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}