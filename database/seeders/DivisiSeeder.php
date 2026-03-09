<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Divisi;

class DivisiSeeder extends Seeder
{
    public function run()
    {
        $divisis = [
            'Engineering',
            'Human Resource',
            'Finance',
            'Marketing',
            'Operasional',
        ];

        foreach ($divisis as $nama) {
            Divisi::firstOrCreate(['nama_divisi' => $nama]);
        }
    }
}