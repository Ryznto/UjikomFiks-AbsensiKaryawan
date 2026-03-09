<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shift;

class ShiftSeeder extends Seeder
{
    public function run()
    {
        $shifts = [
            [
                'nama_shift'          => 'Shift Pagi',
                'jam_masuk'           => '07:00:00',
                'jam_pulang'          => '15:00:00',
                'toleransi_terlambat' => 15,
            ],
            [
                'nama_shift'          => 'Shift Siang',
                'jam_masuk'           => '12:00:00',
                'jam_pulang'          => '20:00:00',
                'toleransi_terlambat' => 15,
            ],
            [
                'nama_shift'          => 'Shift Malam',
                'jam_masuk'           => '20:00:00',
                'jam_pulang'          => '07:00:00',
                'toleransi_terlambat' => 15,
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::firstOrCreate(
                ['nama_shift' => $shift['nama_shift']],
                $shift
            );
        }
    }
}