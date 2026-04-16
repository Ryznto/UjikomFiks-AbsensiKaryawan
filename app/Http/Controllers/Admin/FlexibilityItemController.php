<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlexibilityItem;
use Illuminate\Http\Request;

/**
 * @package App\Http\Controllers\Admin
 * @author AbsensiKu
 * @version 1.0.0
 *
 * Controller untuk mengelola katalog item kelonggaran (Marketplace).
 */
class FlexibilityItemController extends Controller
{
    /**
     * Tampilkan daftar semua item marketplace.
     */
    public function index()
    {
        $items = FlexibilityItem::latest()->paginate(10);
        return view('admin.flexibility-items.index', compact('items'));
    }

    /**
     * Simpan item baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_name'         => 'required|string|max:255',
            'description'       => 'nullable|string',
            'point_cost'        => 'required|integer|min:1',
            'stock_limit'       => 'nullable|integer|min:1',
            'token_type'        => 'required|in:late_tolerance,izin_tanpa_surat,wfh',
            'tolerance_minutes' => 'nullable|integer|min:1',
        ]);

        FlexibilityItem::create($request->only([
            'item_name',
            'description',
            'point_cost',
            'stock_limit',
            'token_type',
            'tolerance_minutes',
        ]));

        return redirect()->route('admin.flexibility-items.index')
            ->with('success', 'Item berhasil ditambahkan.');
    }

    /**
     * Update item yang sudah ada.
     */
    public function update(Request $request, FlexibilityItem $flexibilityItem)
    {
        $request->validate([
            'item_name'         => 'required|string|max:255',
            'description'       => 'nullable|string',
            'point_cost'        => 'required|integer|min:1',
            'stock_limit'       => 'nullable|integer|min:1',
            'token_type'        => 'required|in:late_tolerance,izin_tanpa_surat,wfh',
            'tolerance_minutes' => 'nullable|integer|min:1',
        ]);

        $flexibilityItem->update($request->only([
            'item_name',
            'description',
            'point_cost',
            'stock_limit',
            'token_type',
            'tolerance_minutes',
        ]));

        return redirect()->route('admin.flexibility-items.index')
            ->with('success', 'Item berhasil diperbarui.');
    }

    /**
     * Toggle aktif/nonaktif item.
     */
    public function toggle(FlexibilityItem $flexibilityItem)
    {
        $flexibilityItem->update(['is_active' => !$flexibilityItem->is_active]);

        return redirect()->route('admin.flexibility-items.index')
            ->with('success', 'Status item berhasil diubah.');
    }

    /**
     * Hapus item dari database.
     */
    public function destroy(FlexibilityItem $flexibilityItem)
    {
        $flexibilityItem->delete();

        return redirect()->route('admin.flexibility-items.index')
            ->with('success', 'Item berhasil dihapus.');
    }
}