<?php

namespace App\Services;

use App\Models\PointRule;
use App\Models\Presensi;
use App\Models\User;

class PointRuleEngine
{
    protected LedgerService $ledger;

    public function __construct(LedgerService $ledger)
    {
        $this->ledger = $ledger;
    }

    public function evaluate(Presensi $presensi): void
    {
        $karyawan = $presensi->karyawan;
        $user = $karyawan?->user;

        if (!$user || !$presensi->jam_masuk) return;

        $rules = PointRule::where('is_active', true)->get();

        foreach ($rules as $rule) {
            if ($this->evaluateRule($rule, $presensi)) {
                $type = $rule->point_modifier > 0 ? 'EARN' : 'PENALTY';

                $this->ledger->record(
                    user: $user,
                    type: $type,
                    amount: abs($rule->point_modifier),
                    description: $rule->rule_name . ' — ' . $presensi->tanggal,
                    presensiId: $presensi->id,
                );
            }
        }
    }

    private function evaluateRule(PointRule $rule, Presensi $presensi): bool
    {
        return match ($rule->condition_type) {
            'check_in'     => $this->evaluateCheckIn($rule, $presensi),
            'late_minutes' => $this->evaluateLateMinutes($rule, $presensi),
            'alfa'         => $this->evaluateAlfa($rule, $presensi),
            default        => false,
        };
    }

    private function evaluateCheckIn(PointRule $rule, Presensi $presensi): bool
    {
        if (!$presensi->jam_masuk) return false;

        $jamMasuk = $this->timeToMinutes($presensi->jam_masuk);
        $condVal  = $this->timeToMinutes($rule->condition_value);

        return match ($rule->condition_operator) {
            '<'       => $jamMasuk < $condVal,
            '>'       => $jamMasuk > $condVal,
            'BETWEEN' => $jamMasuk >= $condVal &&
                         $jamMasuk <= $this->timeToMinutes($rule->condition_value_max),
            default   => false,
        };
    }

    private function evaluateLateMinutes(PointRule $rule, Presensi $presensi): bool
    {
        if ($presensi->status_absen !== 'terlambat') return false;

        $shift = $presensi->shift;
        if (!$shift) return false;

        $menitTelat = max(0,
            $this->timeToMinutes($presensi->jam_masuk) -
            $this->timeToMinutes($shift->jam_masuk)
        );

        $condVal = (int) $rule->condition_value;

        return match ($rule->condition_operator) {
            '<'       => $menitTelat < $condVal,
            '>'       => $menitTelat > $condVal,
            'BETWEEN' => $menitTelat >= $condVal &&
                         $menitTelat <= (int) $rule->condition_value_max,
            default   => false,
        };
    }

    private function evaluateAlfa(PointRule $rule, Presensi $presensi): bool
    {
        return $presensi->status_absen === 'alfa';
    }

    private function timeToMinutes(string $time): int
    {
        $parts = explode(':', $time);
        return ((int) $parts[0] * 60) + (int) ($parts[1] ?? 0);
    }
}