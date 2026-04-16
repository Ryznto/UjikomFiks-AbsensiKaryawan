<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\PointRule;
use App\Services\LedgerService;
use Carbon\Carbon;

class MarkAbsenAlfa extends Command
{
    protected $signature   = 'absen:mark-alfa';
    protected $description = 'Auto mark karyawan yang tidak absen sebagai alfa dan kurangi poin';

    protected LedgerService $ledger;

    public function __construct(LedgerService $ledger)
    {
        parent::__construct();
        $this->ledger = $ledger;
    }

    public function handle()
    {
        $today    = Carbon::today('Asia/Jakarta')->toDateString();
        $sekarang = Carbon::now('Asia/Jakarta');

        $karyawans = Karyawan::with('shift', 'user')->where('status_aktif', true)->get();

        // Cari rule untuk alfa
        $alfaRule = PointRule::where('condition_type', 'alfa')->where('is_active', true)->first();

        if (!$alfaRule) {
            $this->error('❌ Rule Alfa tidak ditemukan! Buat dulu di Point Rules Engine.');
            return 1;
        }

        $marked = 0;

        foreach ($karyawans as $karyawan) {
            $shift = $karyawan->shift;

            if (!$shift) continue;

            // Jalanin 2 jam setelah jam masuk shift
            $batasAlfa = Carbon::today('Asia/Jakarta')
                ->setTimeFromTimeString($shift->jam_masuk)
                ->addHours(2);

            if ($sekarang->lt($batasAlfa)) continue;

            // Cek sudah ada record presensi belum
            $sudahAda = Presensi::where('karyawan_id', $karyawan->id)
                ->whereDate('tanggal', $today)
                ->exists();

            if ($sudahAda) continue;

            // Cek izin/cuti approved
            $izinAktif = \App\Models\IzinCuti::where('karyawan_id', $karyawan->id)
                ->where('status', 'approved')
                ->whereDate('tanggal_mulai', '<=', $today)
                ->whereDate('tanggal_selesai', '>=', $today)
                ->exists();

            if ($izinAktif) continue;

            // Buat record presensi alfa
            $presensi = Presensi::create([
                'karyawan_id'  => $karyawan->id,
                'shift_id'     => $karyawan->shift_id,
                'tanggal'      => $today,
                'status_absen' => 'alfa',
            ]);

            // KURANGI POIN KARYAWAN
            $user = $karyawan->user;
            if ($user && $alfaRule) {
                $this->ledger->record(
                    user: $user,
                    type: 'PENALTY',
                    amount: abs($alfaRule->point_modifier),
                    description: "Alfa (Tidak Hadir) - {$today}",
                    presensiId: $presensi->id
                );
                $this->info("✓ Denda alfa untuk: {$karyawan->nama} (-" . abs($alfaRule->point_modifier) . " poin)");
            }

            $marked++;
        }

        $this->info("✅ Selesai! {$marked} karyawan di-mark alfa dan dikurangi poin.");
        return 0;
    }
}