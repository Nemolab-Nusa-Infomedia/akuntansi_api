<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CompanyCategory;

use function PHPSTORM_META\map;

class CompanyCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $categories = [
                "technology",
                "fnb",
                "ritel",
                "otomotif",
                "etc"
            ];

            foreach ($categories as $category) {
                CompanyCategory::create([
                    "name" => $category
                ]);
            }
        } catch (\Throwable $th) {
            error_log($th);
        }
    }
}
