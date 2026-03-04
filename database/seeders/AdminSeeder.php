<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AdminProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'nip'      => '10122490',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        AdminProfile::create([
            'user_id'    => $user->id,
            'nama_admin' => 'Administrator',
            'email'      => 'admin@absensiku.com',
            'no_hp'      => '08123456789',
        ]);
    }
}