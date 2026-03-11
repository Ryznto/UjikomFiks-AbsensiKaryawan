<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    public function myReport(Request $request)
    {
        $period   = $request->get('period', now()->format('F Y'));
        $karyawan = Karyawan::where('user_id', Auth::id())->firstOrFail();

        $history = Assessment::where('evaluatee_id', $karyawan->id)
            ->with('details.statement.category')
            ->orderByDesc('assessment_date')
            ->get();

        $currentAssessment = Assessment::where('evaluatee_id', $karyawan->id)
            ->where('period', $period)
            ->with('details.statement.category')
            ->first();

        // Data radar chart - digroup per kategori
        $radarLabels = [];
        $radarScores = [];
        if ($currentAssessment) {
            $grouped = $currentAssessment->details->groupBy('statement.category.name');
            foreach ($grouped as $categoryName => $details) {
                $radarLabels[] = $categoryName;
                $radarScores[] = round($details->avg('score'), 2);
            }
        }

        return view('karyawan.assessments.my_report', compact(
            'karyawan',
            'history',
            'currentAssessment',
            'period',
            'radarLabels',
            'radarScores'
        ));
    }

    public function show(Assessment $assessment)
    {
        $karyawan = Karyawan::where('user_id', Auth::id())->firstOrFail();

        if ($assessment->evaluatee_id !== $karyawan->id) {
            abort(403, 'Akses ditolak!');
        }

        $assessment->load(['evaluator', 'details.statement.category']);

        // Radar chart digroup per kategori
        $radarLabels = [];
        $radarScores = [];
        $grouped = $assessment->details->groupBy('statement.category.name');
        foreach ($grouped as $categoryName => $details) {
            $radarLabels[] = $categoryName;
            $radarScores[] = round($details->avg('score'), 2);
        }

        return view('karyawan.assessments.show', compact(
            'assessment',
            'radarLabels',
            'radarScores'
        ));
    }
}