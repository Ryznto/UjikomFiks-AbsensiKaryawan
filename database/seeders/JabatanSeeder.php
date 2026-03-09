<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jabatan;
use App\Models\Divisi;

class JabatanSeeder extends Seeder
{
    public function run()
    {
        $jabatans = [
            'Engineering' => [
                'Backend Developer',
                'Frontend Developer',
                'Mobile Developer',
                'DevOps Engineer',
                'QA Engineer',
            ],
            'Human Resource' => [
                'HR Manager',
                'HR Staff',
                'Recruitment Staff',
            ],
            'Finance' => [
                'Finance Manager',
                'Akuntan',
                'Staff Keuangan',
            ],
            'Marketing' => [
                'Marketing Manager',
                'Content Creator',
                'Social Media Specialist',
            ],
            'Operasional' => [
                'Operasional Manager',
                'Staff Operasional',
                'Admin',
            ],
        ];

        foreach ($jabatans as $namaDivisi => $listJabatan) {
            $divisi = Divisi::where('nama_divisi', $namaDivisi)->first();

            if (!$divisi) continue;

            foreach ($listJabatan as $namaJabatan) {
                Jabatan::firstOrCreate(
                    [
                        'nama_jabatan' => $namaJabatan,
                        'divisi_id'    => $divisi->id,
                    ]
                );
            }
        }
    }
}