<?php

namespace Database\Seeders;

use App\Models\Product; // Replace with the correct namespace if different
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to the JSON file
        $jsonPath = __DIR__ . '/data/products.json'; // Update the path if needed

        // Check if the file exists
        if (!File::exists($jsonPath)) {
            echo 'JSON file not found.';

            return;
        }

        // Read the file and decode the JSON
        $jsonData = json_decode(File::get($jsonPath), true);

        // Check if the JSON data is valid
        if (is_null($jsonData)) {
            echo 'Invalid JSON.';

            return;
        }

        // Insert each product into the database
        foreach ($jsonData as $productData) {
            $product = Product::create([
                'id' => $productData['id'],
                'name' => $productData['name'],
                'slug' => $productData['slug'],
                'description' => $productData['description'],
                'brand_id' => $productData['brand_id'],
                'concentration_id' => $productData['concentration_id'],
                'categories' => json_encode($productData['categories']),
            ]);
        }
    }
}
