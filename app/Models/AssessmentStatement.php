<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @package App\\Models
 * @author AbsensiKu
 * @version 1.0.0
 * 
 * Model Eloquent untuk statement pertanyaan penilaian assessment.
 */
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

    /**
     * Relasi ke kategori assessment.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(AssessmentCategory::class, 'category_id');
    }

    /**
     * Relasi ke detail penilaian dari statement ini.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assessmentDetails()
    {
        return $this->hasMany(AssessmentDetail::class, 'statement_id');
    }

    /**
     * Scope untuk mengambil statement yang aktif.
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
