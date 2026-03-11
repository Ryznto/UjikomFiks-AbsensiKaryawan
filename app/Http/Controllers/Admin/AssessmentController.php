<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentCategory;
use App\Models\AssessmentDetail;
use App\Models\AssessmentStatement;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    // Dashboard penilai - list semua karyawan
    public function index(Request $request)
    {
        $period = $request->get('period', now()->format('F Y'));

        $karyawans = Karyawan::with(['user', 'jabatan', 'divisi'])
            ->where('status_aktif', true)
            ->get();

        $assessedIds = Assessment::where('evaluator_id', Auth::id())
            ->where('period', $period)
            ->pluck('evaluatee_id')
            ->toArray();

        $totalKaryawan   = $karyawans->count();
        $totalAssessed   = count($assessedIds);
        $progressPercent = $totalKaryawan > 0
            ? round(($totalAssessed / $totalKaryawan) * 100)
            : 0;

        return view('admin.assessments.index', compact(
            'karyawans', 'assessedIds', 'period',
            'totalKaryawan', 'totalAssessed', 'progressPercent'
        ));
    }

    // Form penilaian per karyawan
    public function create(Request $request, Karyawan $karyawan)
    {
        $period = $request->get('period', now()->format('F Y'));

        // Ambil kategori beserta pernyataan-pernyataannya
        $categories = AssessmentCategory::active()
            ->forEmployee()
            ->with(['statements' => function($q) {
                $q->where('is_active', true)->orderBy('order');
            }])
            ->get();

        // Cek apakah sudah pernah dinilai
        $existingAssessment = Assessment::where('evaluator_id', Auth::id())
            ->where('evaluatee_id', $karyawan->id)
            ->where('period', $period)
            ->with('details.statement')
            ->first();

        return view('admin.assessments.create', compact(
            'karyawan', 'categories', 'period', 'existingAssessment'
        ));
    }

    // Simpan penilaian
    public function store(Request $request)
    {
        $request->validate([
            'evaluatee_id'  => 'required|exists:karyawan,id',
            'period'        => 'required|string',
            'general_notes' => 'nullable|string|max:2000',
            'scores'        => 'required|array|min:1',
            'scores.*'      => 'required|numeric|min:1|max:5',
        ]);

        DB::beginTransaction();
        try {
            $assessment = Assessment::updateOrCreate(
                [
                    'evaluator_id' => Auth::id(),
                    'evaluatee_id' => $request->evaluatee_id,
                    'period'       => $request->period,
                ],
                [
                    'assessment_date' => now()->toDateString(),
                    'general_notes'   => $request->general_notes,
                    'status'          => 'submitted',
                ]
            );

            // Simpan nilai per pernyataan
            foreach ($request->scores as $statementId => $score) {
                AssessmentDetail::updateOrCreate(
                    [
                        'assessment_id' => $assessment->id,
                        'statement_id'  => $statementId,
                    ],
                    ['score' => $score]
                );
            }

            $assessment->recalculateScore();

            DB::commit();

            return redirect()
                ->route('admin.assessments.index', ['period' => $request->period])
                ->with('success', 'Penilaian berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Laporan semua karyawan
    public function report(Request $request)
    {
        $period = $request->get('period', now()->format('F Y'));

        $assessments = Assessment::with(['evaluatee.user', 'evaluatee.jabatan', 'details.statement.category'])
            ->where('period', $period)
            ->orderByDesc('average_score')
            ->paginate(20);

        $stats = Assessment::where('period', $period)
            ->selectRaw('AVG(average_score) as overall_avg, MAX(average_score) as highest, MIN(average_score) as lowest, COUNT(*) as total')
            ->first();

        return view('admin.assessments.report', compact('assessments', 'period', 'stats'));
    }

    // Detail satu assessment
    public function show(Assessment $assessment)
    {
        $assessment->load(['evaluator', 'evaluatee.user', 'details.statement.category']);

        // Group detail per kategori untuk radar chart
        $radarLabels = [];
        $radarScores = [];

        $grouped = $assessment->details->groupBy('statement.category.name');
        foreach ($grouped as $categoryName => $details) {
            $radarLabels[] = $categoryName;
            $radarScores[] = round($details->avg('score'), 2);
        }

        return view('admin.assessments.show', compact('assessment', 'radarLabels', 'radarScores'));
    }
}