<?php

namespace Database\Seeders;

use App\Models\Size; // Replace with the correct namespace if different
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to the JSON file
        $jsonPath = __DIR__ . '/data/sizes.json'; // Update the path if needed

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

        // Insert each size into the database
        foreach ($jsonData as $sizeData) {
            Size::create([
                'id' => $sizeData['id'],
                'name' => $sizeData['name'],
            ]);
        }
    }
}
