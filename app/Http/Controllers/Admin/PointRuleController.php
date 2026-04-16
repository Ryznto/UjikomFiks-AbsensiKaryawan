<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointRule;
use Illuminate\Http\Request;

/**
 * @package App\Http\Controllers\Admin
 * @author AbsensiKu
 * @version 1.0.0
 *
 * Controller untuk mengelola aturan poin dinamis (Rule Engine).
 */
class PointRuleController extends Controller
{
    /**
     * Tampilkan daftar semua rule poin.
     */
    public function index()
    {
        $rules = PointRule::latest()->paginate(10);
        return view('admin.point-rules.index', compact('rules'));
    }

    /**
     * Simpan rule baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rule_name'          => 'required|string|max:255',
            'condition_type'     => 'required|in:check_in,late_minutes,alfa',
            'condition_operator' => 'required|in:<,>,BETWEEN',
            'condition_value'    => 'required|string',
            'condition_value_max'=> 'nullable|string',
            'point_modifier'     => 'required|integer',
        ]);

        PointRule::create($request->only([
            'rule_name',
            'condition_type',
            'condition_operator',
            'condition_value',
            'condition_value_max',
            'point_modifier',
        ]));

        return redirect()->route('admin.point-rules.index')
            ->with('success', 'Rule berhasil ditambahkan.');
    }

    /**
     * Update rule yang sudah ada.
     */
    public function update(Request $request, PointRule $pointRule)
    {
        $request->validate([
            'rule_name'          => 'required|string|max:255',
            'condition_type'     => 'required|in:check_in,late_minutes,alfa',
            'condition_operator' => 'required|in:<,>,BETWEEN',
            'condition_value'    => 'required|string',
            'condition_value_max'=> 'nullable|string',
            'point_modifier'     => 'required|integer',
        ]);

        $pointRule->update($request->only([
            'rule_name',
            'condition_type',
            'condition_operator',
            'condition_value',
            'condition_value_max',
            'point_modifier',
        ]));

        return redirect()->route('admin.point-rules.index')
            ->with('success', 'Rule berhasil diperbarui.');
    }

    /**
     * Toggle aktif/nonaktif rule.
     */
    public function toggle(PointRule $pointRule)
    {
        $pointRule->update(['is_active' => !$pointRule->is_active]);

        return redirect()->route('admin.point-rules.index')
            ->with('success', 'Status rule berhasil diubah.');
    }

    /**
     * Hapus rule dari database.
     */
    public function destroy(PointRule $pointRule)
    {
        $pointRule->delete();

        return redirect()->route('admin.point-rules.index')
            ->with('success', 'Rule berhasil dihapus.');
    }
}