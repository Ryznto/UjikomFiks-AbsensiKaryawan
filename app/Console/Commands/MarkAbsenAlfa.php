<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Karyawan;
use App\Models\Presensi;
use Carbon\Carbon;

class MarkAbsenAlfa extends Command
{
    protected $signature   = 'absen:mark-alfa';
    protected $description = 'Auto mark karyawan yang tidak absen sebagai alfa';

    public function handle()
    {
        $today    = Carbon::today('Asia/Jakarta')->toDateString();
        $sekarang = Carbon::now('Asia/Jakarta');

        $karyawans = Karyawan::with('shift')->where('status_aktif', true)->get();

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

            Presensi::create([
                'karyawan_id'  => $karyawan->id,
                'shift_id'     => $karyawan->shift_id,
                'tanggal'      => $today,
                'status_absen' => 'alfa',
            ]);

            $marked++;
        }

        $this->info("Done! {$marked} karyawan di-mark alfa.");
    }
}