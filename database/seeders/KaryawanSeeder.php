<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Shift;
use Illuminate\Support\Facades\Hash;

class KaryawanSeeder extends Seeder
{
    public function run()
    {
        $password = Hash::make('karyawan123');

       $data = [
    'Engineering' => [
        ['nip' => '10122491', 'nama' => 'Budi Santoso',     'jabatan' => 'Backend Developer',        'shift' => 'Shift Pagi'],
        ['nip' => '10122492', 'nama' => 'Rina Wulandari',   'jabatan' => 'Frontend Developer',       'shift' => 'Shift Pagi'],
        ['nip' => '10122493', 'nama' => 'Dimas Prasetyo',   'jabatan' => 'Mobile Developer',         'shift' => 'Shift Siang'],
        ['nip' => '10122494', 'nama' => 'Sari Dewi',        'jabatan' => 'DevOps Engineer',          'shift' => 'Shift Malam'],
        ['nip' => '10122495', 'nama' => 'Fajar Nugroho',    'jabatan' => 'QA Engineer',              'shift' => 'Shift Pagi'],
    ],
    'Human Resource' => [
        ['nip' => '10122496', 'nama' => 'Anita Rahayu',     'jabatan' => 'HR Manager',               'shift' => 'Shift Pagi'],
        ['nip' => '10122497', 'nama' => 'Teguh Widodo',     'jabatan' => 'HR Staff',                 'shift' => 'Shift Pagi'],
        ['nip' => '10122498', 'nama' => 'Mega Lestari',     'jabatan' => 'Recruitment Staff',        'shift' => 'Shift Siang'],
    ],
    'Finance' => [
        ['nip' => '10122499', 'nama' => 'Hendra Kusuma',    'jabatan' => 'Finance Manager',          'shift' => 'Shift Pagi'],
        ['nip' => '10122500', 'nama' => 'Dewi Anggraini',   'jabatan' => 'Akuntan',                  'shift' => 'Shift Pagi'],
        ['nip' => '10122501', 'nama' => 'Rizky Firmansyah', 'jabatan' => 'Staff Keuangan',           'shift' => 'Shift Siang'],
    ],
    'Marketing' => [
        ['nip' => '10122502', 'nama' => 'Putri Handayani',  'jabatan' => 'Marketing Manager',        'shift' => 'Shift Pagi'],
        ['nip' => '10122503', 'nama' => 'Agus Setiawan',    'jabatan' => 'Content Creator',          'shift' => 'Shift Pagi'],
        ['nip' => '10122504', 'nama' => 'Nurul Hidayah',    'jabatan' => 'Social Media Specialist',  'shift' => 'Shift Siang'],
        ['nip' => '10122505', 'nama' => 'Bayu Adi Saputra', 'jabatan' => 'Content Creator',          'shift' => 'Shift Malam'],
    ],
    'Operasional' => [
        ['nip' => '10122506', 'nama' => 'Wahyu Hidayat',    'jabatan' => 'Operasional Manager',      'shift' => 'Shift Pagi'],
        ['nip' => '10122507', 'nama' => 'Fitri Rahmawati',  'jabatan' => 'Staff Operasional',        'shift' => 'Shift Pagi'],
        ['nip' => '10122508', 'nama' => 'Eko Prasetyo',     'jabatan' => 'Admin',                    'shift' => 'Shift Siang'],
        ['nip' => '10122509', 'nama' => 'Lina Marlina',     'jabatan' => 'Staff Operasional',        'shift' => 'Shift Malam'],
    ],
];

        foreach ($data as $namaDivisi => $karyawans) {
            $divisi = Divisi::where('nama_divisi', $namaDivisi)->first();
            if (!$divisi) continue;

            foreach ($karyawans as $k) {
                $jabatan = Jabatan::where('nama_jabatan', $k['jabatan'])
                    ->where('divisi_id', $divisi->id)
                    ->first();
                $shift = Shift::where('nama_shift', $k['shift'])->first();

                if (!$jabatan || !$shift) continue;

                // Skip kalau NIP udah ada
                if (User::where('nip', $k['nip'])->exists()) continue;

                $user = User::create([
                    'nip'      => $k['nip'],
                    'password' => $password,
                    'role'     => 'karyawan',
                ]);

                Karyawan::create([
                    'user_id'      => $user->id,
                    'nama'         => $k['nama'],
                    'divisi_id'    => $divisi->id,
                    'jabatan_id'   => $jabatan->id,
                    'shift_id'     => $shift->id,
                    'status_aktif' => true,
                ]);
            }
        }

        $this->command->info('Karyawan seeder selesai! Default password: karyawan123');
    }
}