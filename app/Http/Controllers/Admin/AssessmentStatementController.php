<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentCategory;
use App\Models\AssessmentStatement;
use Illuminate\Http\Request;

/**
 * Controller untuk mengelola statement assessment.
 * 
 * @package App\\Http\\Controllers\\Admin
 * @author AbsensiKu
 * @version 1.0.0
 */
class AssessmentStatementController extends Controller
{
    /**
     * Menampilkan daftar statement assessment kategori tertentu.
     * 
     * @param \App\\Models\\AssessmentCategory $assessmentCategory Kategori assessment
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(AssessmentCategory $assessmentCategory)
    {
        $statements = $assessmentCategory->statements()->orderBy('order')->get();
        return response()->json($statements);
    }

    /**
     * Menyimpan statement assessment baru.
     * 
     * @param \Illuminate\Http\Request $request Data statement
     * @param \App\\Models\\AssessmentCategory $assessmentCategory Kategori assessment
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
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

    /**
     * Memperbarui statement assessment.
     * 
     * @param \Illuminate\Http\Request $request Data statement baru
     * @param \App\\Models\\AssessmentStatement $assessmentStatement Statement assessment
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
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

    /**
     * Menghapus statement assessment.
     * 
     * @param \App\\Models\\AssessmentStatement $assessmentStatement Statement assessment
     * @return \Illuminate\Http\RedirectResponse
     */
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

