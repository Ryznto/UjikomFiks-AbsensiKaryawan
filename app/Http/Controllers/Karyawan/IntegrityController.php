<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\FlexibilityItem;
use App\Models\PointLedger;
use App\Models\UserToken;
use App\Services\LedgerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @package App\Http\Controllers\Karyawan
 * @author AbsensiKu
 * @version 1.0.0
 *
 * Controller untuk halaman Dompet Integritas karyawan.
 * Menampilkan saldo poin, riwayat mutasi, marketplace, dan inventory token.
 */
class IntegrityController extends Controller
{
    /**
     * Tampilkan halaman utama dompet integritas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user     = Auth::user();
        $karyawan = $user->karyawan;

        // [QUERY] = riwayat mutasi poin terbaru
        $ledgers = PointLedger::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        // [QUERY] = katalog item marketplace yang aktif
        $items = FlexibilityItem::where('is_active', true)->get();

        // [QUERY] = inventory token milik user
        $tokens = UserToken::where('user_id', $user->id)
            ->with('item')
            ->latest()
            ->get();

        // [HITUNG] = level berdasarkan saldo poin
        $level = $this->getLevel($user->point_balance);

        return view('karyawan.integrity.index', compact(
            'user', 'karyawan', 'ledgers', 'items', 'tokens', 'level'
        ));
    }

    /**
     * Proses pembelian token dari marketplace.
     *
     * @param Request $request
     * @param FlexibilityItem $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function buyToken(Request $request, FlexibilityItem $item)
    {
        $user = Auth::user();

        // [GUARD] = cek saldo mencukupi
        if ($user->point_balance < $item->point_cost) {
            return back()->with('error', 'Saldo poin tidak mencukupi!');
        }

        // [GUARD] = cek stok limit per bulan
        if ($item->stock_limit) {
            $beliBulanIni = UserToken::where('user_id', $user->id)
                ->where('item_id', $item->id)
                ->whereMonth('created_at', now()->month)
                ->count();

            if ($beliBulanIni >= $item->stock_limit) {
                return back()->with('error', 'Batas pembelian item ini bulan ini sudah tercapai!');
            }
        }

        // [LEDGER] = catat pengeluaran poin
        $ledger = new LedgerService();
        $ledger->record(
            $user,
            'SPEND',
            $item->point_cost,
            'Beli token: ' . $item->item_name,
        );

        // [TOKEN] = buat token baru di inventory user
        UserToken::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'status'  => 'AVAILABLE',
        ]);

        return back()->with('success', 'Token berhasil dibeli! Cek inventory kamu.');
    }

    /**
     * Tentukan level karyawan berdasarkan saldo poin.
     *
     * @param int $poin
     * @return array
     */
    private function getLevel(int $poin): array
    {
        return match(true) {
            $poin >= 200 => ['nama' => 'Disiplin Elite', 'warna' => '#f59e0b', 'icon' => '👑'],
            $poin >= 100 => ['nama' => 'Sangat Disiplin', 'warna' => '#10b981', 'icon' => '⭐'],
            $poin >= 50  => ['nama' => 'Disiplin', 'warna' => '#4f7cff', 'icon' => '🔵'],
            $poin >= 0   => ['nama' => 'Pemula', 'warna' => '#6b7280', 'icon' => '🌱'],
            default      => ['nama' => 'Perlu Perbaikan', 'warna' => '#ef4444', 'icon' => '⚠️'],
        };
    }
}