<?php

namespace App\Http\Controllers\Admin; 

use App\Models\AssessmentCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssessmentCategoryController extends Controller
{
    // Tampilkan semua kategori
    public function index()
    {
        $categories = AssessmentCategory::latest()->paginate(10);
       return view('admin.assessment_categories.index', compact('categories'));
    }

    // Simpan kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:assessment_categories,name',
            'description' => 'nullable|string',
            'type'        => 'required|in:Employee',
            'max_score'   => 'required|integer|min:1|max:10',
        ]);

        AssessmentCategory::create($request->all());

        return redirect()->route('assessment-categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    // Update kategori
    public function update(Request $request, AssessmentCategory $assessmentCategory)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:assessment_categories,name,' . $assessmentCategory->id,
            'description' => 'nullable|string',
            'max_score'   => 'required|integer|min:1|max:10',
        ]);

        $assessmentCategory->update($request->all());

        return redirect()->route('assessment-categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    // Aktifkan / Nonaktifkan kategori
    public function toggleStatus(AssessmentCategory $assessmentCategory)
    {
        $assessmentCategory->update(['is_active' => !$assessmentCategory->is_active]);

        $status = $assessmentCategory->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('assessment-categories.index')
            ->with('success', "Kategori berhasil {$status}!");
    }

    // Hapus kategori
    public function destroy(AssessmentCategory $assessmentCategory)
    {
        if ($assessmentCategory->assessmentDetails()->exists()) {
            return redirect()->route('assessment-categories.index')
                ->with('error', 'Kategori tidak bisa dihapus karena sudah punya data penilaian!');
        }

        $assessmentCategory->delete();

        return redirect()->route('assessment-categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}