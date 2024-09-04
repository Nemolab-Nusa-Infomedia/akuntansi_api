<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        User::create([
            'role_id' => 1,
            'name' => 'Admin Ganteng',
            'email' => 'admin@gmail.com',
            'phone' => '082141765353',
            'password' => Hash::make('1234567890'),
            'status_account' => 'active'
        ]);
    }
}
