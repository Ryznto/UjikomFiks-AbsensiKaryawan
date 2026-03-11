<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentCategory;
use App\Models\AssessmentStatement;
use Illuminate\Http\Request;

class AssessmentStatementController extends Controller
{
    public function index(AssessmentCategory $assessmentCategory)
    {
        $statements = $assessmentCategory->statements()->orderBy('order')->get();
        return response()->json($statements);
    }

    public function store(Request $request, AssessmentCategory $assessmentCategory)
    {
        $request->validate([
            'statement' => 'required|string|max:255',
        ]);

        $lastOrder = $assessmentCategory->statements()->max('order') ?? 0;

        $assessmentCategory->statements()->create([
            'statement' => $request->statement,
            'order'     => $lastOrder + 1,
            'is_active' => true,
        ]);

        return redirect()->route('admin.assessment-categories.index')
            ->with('success', 'Pernyataan berhasil ditambahkan!');
    }

    public function update(Request $request, AssessmentStatement $assessmentStatement)
    {
        $request->validate([
            'statement' => 'required|string|max:255',
        ]);

        $assessmentStatement->update([
            'statement' => $request->statement,
        ]);

        return redirect()->route('admin.assessment-categories.index')
            ->with('success', 'Pernyataan berhasil diperbarui!');
    }

    public function destroy(AssessmentStatement $assessmentStatement)
    {
        if ($assessmentStatement->assessmentDetails()->exists()) {
            return redirect()->route('admin.assessment-categories.index')
                ->with('error', 'Pernyataan tidak bisa dihapus karena sudah punya data penilaian!');
        }

        $assessmentStatement->delete();

        return redirect()->route('admin.assessment-categories.index')
            ->with('success', 'Pernyataan berhasil dihapus!');
    }
}