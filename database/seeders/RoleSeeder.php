<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'admin',
            'akuntan',
            'staff'
        ];
        foreach ($data as $value) {
            Role::create([
                'name' => $value
            ]);
        }
    }
}
