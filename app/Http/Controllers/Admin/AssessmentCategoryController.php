<?php

namespace App\Http\Controllers\Admin;

use App\Models\AssessmentCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssessmentCategoryController extends Controller
{
        public function index()
    {
        $categories = AssessmentCategory::with('statements')->latest()->paginate(10);
        return view('admin.assessment_categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:assessment_categories,name',
            'description' => 'nullable|string',
            'type'        => 'required|in:Employee',
            'max_score'   => 'required|integer|min:1|max:10',
        ]);

        AssessmentCategory::create($request->all());

        return redirect()->route('admin.assessment-categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, AssessmentCategory $assessmentCategory)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:assessment_categories,name,' . $assessmentCategory->id,
            'description' => 'nullable|string',
            'max_score'   => 'required|integer|min:1|max:10',
        ]);

        $assessmentCategory->update($request->all());

        return redirect()->route('admin.assessment-categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function toggleStatus(AssessmentCategory $assessmentCategory)
    {
        $assessmentCategory->update(['is_active' => !$assessmentCategory->is_active]);

        $status = $assessmentCategory->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.assessment-categories.index')
            ->with('success', "Kategori berhasil {$status}!");
    }

    public function destroy(AssessmentCategory $assessmentCategory)
{
    $hasData = $assessmentCategory->statements()
        ->whereHas('assessmentDetails')
        ->exists();

    if ($hasData) {
        return redirect()->route('admin.assessment-categories.index')
            ->with('error', 'Kategori tidak bisa dihapus karena sudah punya data penilaian!');
    }

    $assessmentCategory->delete();

    return redirect()->route('admin.assessment-categories.index')
        ->with('success', 'Kategori berhasil dihapus!');
}
}