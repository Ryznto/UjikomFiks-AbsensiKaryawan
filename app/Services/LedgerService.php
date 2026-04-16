<?php

namespace App\Services;

use App\Models\PointLedger;
use App\Models\User;

/**
 * @package App\Services
 * @author AbsensiKu
 * @version 1.0.0
 *
 * Service untuk mencatat mutasi poin ke buku besar (Ledger)
 * dan mengupdate saldo poin user secara atomic.
 */
class LedgerService
{
    /**
     * Catat transaksi poin ke ledger dan update saldo user.
     *
     * @param User   $user
     * @param string $type        EARN | SPEND | PENALTY
     * @param int    $amount      jumlah poin (selalu positif)
     * @param string $description keterangan transaksi
     * @param int|null $presensiId FK ke presensi jika ada
     * @return PointLedger
     */
    public function record(
        User $user,
        string $type,
        int $amount,
        string $description,
        ?int $presensiId = null
    ): PointLedger {
        // [HITUNG] = tentukan apakah poin ditambah atau dikurangi
        $modifier = in_array($type, ['SPEND', 'PENALTY']) ? -$amount : $amount;

        // [UPDATE] = update saldo user secara atomic (hindari race condition)
        $user->increment('point_balance', $modifier);
        $user->refresh();

        // [CATAT] = simpan riwayat mutasi ke ledger
        return PointLedger::create([
            'user_id'          => $user->id,
            'transaction_type' => $type,
            'amount'           => $amount,
            'current_balance'  => $user->point_balance,
            'description'      => $description,
            'presensi_id'      => $presensiId,
        ]);
    }
}