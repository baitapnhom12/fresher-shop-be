<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                UserSeeder::class,
                DiscountSeeder::class,
                BrandSeeder::class,
                BannerSeeder::class,
                CategorySeeder::class,
                ConcentrationSeeder::class,
                SizeSeeder::class,
                FeatureSeeder::class,
                ProductSeeder::class,
                ProductFeatureSeeder::class,
                QuantitySeeder::class,
                ImageSeeder::class,
                PageSeeder::class,
                ArticleSeeder::class,
                PostSeeder::class,
                ArticlePostSeeder::class,
                QuestionSeeder::class,
                ContactSeeder::class
            ]
        );
    }
}
