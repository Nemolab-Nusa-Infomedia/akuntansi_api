<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subscription;
use Illuminate\Support\Str;

class SubscriptionSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $subsciptions = [
                [
                    "name" => "Starter",
                    "price" => 1000000,
                    "desc" => "Lorem",
                    "duration" => 1,
                    "duration_text" => "1 Tahun"
                ],
                [
                    "name" => "Pro",
                    "price" => 5000000,
                    "desc" => "Lorem",
                    "duration" => 1,
                    "duration_text" => "1 Tahun"
                ]
            ];

            foreach ($subsciptions as $subsciption) {
                Subscription::create([
                    "id" => Str::uuid(),
                    "name" => $subsciption["name"],
                    "price" => $subsciption["price"],
                    "description" => $subsciption["desc"],
                    "duration" => $subsciption["duration"],
                    "duration_text" => $subsciption["duration_text"]
                ]);
            }
        } catch (\Throwable $th) {
            error_log($th);
        }
    }
}
