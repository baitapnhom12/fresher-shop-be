<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 100; $i++) {
            Discount::create([
                'name' => 'HTVShop_' . Str::random(8),
                'percent' => rand(0, 99) / 10,
                'active' => 1,
            ]);
        }
    }
}
