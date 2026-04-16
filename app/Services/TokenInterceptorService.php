<?php

namespace App\Services;

use App\Models\Presensi;
use App\Models\UserToken;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TokenInterceptorService
{
    public function intercept(User $user, Presensi $presensi): bool
    {
        // Hanya proses jika terlambat
        if ($presensi->status_absen !== 'terlambat') {
            return false;
        }

        // Cari token AVAILABLE (apapun tokennya, asalkan masih aktif)
        $token = UserToken::where('user_id', $user->id)
            ->where('status', 'AVAILABLE')
            ->whereHas('item', function ($q) {
                // Token untuk keterlambatan (late_tolerance)
                $q->where('token_type', 'late_tolerance');
            })
            ->first();

        // Jika tidak ada token, return false
        if (!$token) {
            return false;
        }

        // HITUNG menit keterlambatan (buat catatan aja)
        $jamMasuk = $this->timeToMinutes($presensi->jam_masuk);
        $jamShift = $this->timeToMinutes($presensi->shift->jam_masuk);
        $menitTelat = max(0, $jamMasuk - $jamShift);

        // PAKAI TOKEN (ubah status jadi USED)
        $token->update([
            'status' => 'USED',
            'used_at_attendance_id' => $presensi->id,
        ]);

        // UBAH status absen jadi tepat waktu
        $presensi->update([
            'status_absen' => 'tepat_waktu',
            'keterangan' => "✅ Token {$token->item->item_name} digunakan (terlambat {$menitTelat} menit)",
        ]);

        return true;
    }

    private function timeToMinutes(string $time): int
    {
        $parts = explode(':', $time);
        return ((int) $parts[0] * 60) + (int) ($parts[1] ?? 0);
    }
}