<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AssessmentStatement;

class AssessmentCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'max_score',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_score' => 'integer',
    ];

    public function statements()
    {
        return $this->hasMany(AssessmentStatement::class, 'category_id')->orderBy('order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForEmployee($query)
    {
        return $query->where('type', 'Employee');
    }
}