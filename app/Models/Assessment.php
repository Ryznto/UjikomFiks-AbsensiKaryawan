<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @package App\\Models
 * @author AbsensiKu
 * @version 1.0.0
 * 
 * Model Eloquent untuk penilaian (assessment) karyawan.
 */
class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluator_id',
        'evaluatee_id',
        'assessment_date',
        'period',
        'total_score',
        'average_score',
        'general_notes',
        'status',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'total_score'     => 'decimal:2',
        'average_score'   => 'decimal:2',
    ];

    // Admin yang menilai
    /**
     * Relasi ke admin yang melakukan penilaian.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    // Karyawan yang dinilai
    public function evaluatee()
    {
        return $this->belongsTo(Karyawan::class, 'evaluatee_id');
    }

    public function details()
    {
        return $this->hasMany(AssessmentDetail::class);
    }

    // Helper label nilai
    public function getScoreLabelAttribute(): string
    {
        $avg = $this->average_score;
        if ($avg >= 4.5) return 'Sangat Baik';
        if ($avg >= 3.5) return 'Baik';
        if ($avg >= 2.5) return 'Cukup';
        if ($avg >= 1.5) return 'Kurang';
        return 'Sangat Kurang';
    }

    // Helper warna badge
    public function getScoreBadgeAttribute(): string
    {
        $avg = $this->average_score;
        if ($avg >= 4.5) return 'success';
        if ($avg >= 3.5) return 'primary';
        if ($avg >= 2.5) return 'warning';
        if ($avg >= 1.5) return 'danger';
        return 'secondary';
    }

    // Hitung ulang rata-rata dari semua statement
    public function recalculateScore(): void
    {
        $details = $this->details()->get();
        if ($details->isEmpty()) return;

        $this->update([
            'total_score'   => $details->sum('score'),
            'average_score' => round($details->avg('score'), 2),
        ]);
    }
}