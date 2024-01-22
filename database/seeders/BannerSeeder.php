<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($id = 100; $id <= 109; $id++) {
            Banner::create([
                'id' => $id,
                'name' => 'banner ' . ($id - 99),
            ]);
        }
    }
}
