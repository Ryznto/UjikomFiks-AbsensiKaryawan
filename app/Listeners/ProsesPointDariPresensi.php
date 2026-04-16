<?php

namespace App\Listeners;

use App\Events\PresensiDisimpan;
use App\Services\PointRuleEngine;
use App\Services\LedgerService;
use App\Services\TokenInterceptorService;

/**
 * @package App\Listeners
 * @author AbsensiKu
 * @version 1.0.0
 *
 * Listener yang berjalan otomatis setiap kali event PresensiDisimpan dipicu.
 * Menjalankan Token Interceptor lalu Rule Engine untuk menghitung poin.
 */
class ProsesPointDariPresensi
{
    protected PointRuleEngine $ruleEngine;
    protected TokenInterceptorService $interceptor;

    public function __construct(
        LedgerService $ledger,
        TokenInterceptorService $interceptor
    ) {
        // [INJECT] = PointRuleEngine butuh LedgerService, jadi kita pass langsung
        $this->ruleEngine  = new PointRuleEngine($ledger);
        $this->interceptor = $interceptor;
    }

    /**
     * Handle event PresensiDisimpan.
     *
     * @param PresensiDisimpan $event
     * @return void
     */
    public function handle(PresensiDisimpan $event): void
    {
        // [LOAD] = pastikan relasi shift & karyawan sudah ter-load
        $presensi = $event->presensi->load(['karyawan.user', 'shift']);
        $user     = $presensi->karyawan?->user;

        // [GUARD] = jika user tidak ditemukan, stop
        if (!$user) return;

        // [1] INTERCEPTOR = cek token kelonggaran dulu sebelum hitung poin
        $tokenDigunakan = $this->interceptor->intercept($user, $presensi);

        // [2] REFRESH = reload presensi karena status mungkin berubah oleh interceptor
        $presensi->refresh()->load(['karyawan.user', 'shift']);

        // [3] RULE ENGINE = evaluasi & catat poin otomatis (ledger sudah di dalam engine)
        if (!$tokenDigunakan) {
            $this->ruleEngine->evaluate($presensi);
        }
    }
}