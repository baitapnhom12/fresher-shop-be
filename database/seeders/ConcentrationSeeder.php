<?php

namespace Database\Seeders;

use App\Models\Concentration; // Replace with the correct namespace if different
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ConcentrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to the JSON file
        $jsonPath = __DIR__ . '/data/concentrations.json'; // Update the path if needed

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

        // Insert each concentration into the database
        foreach ($jsonData as $concentrationData) {
            Concentration::create([
                'id' => $concentrationData['id'],
                'name' => $concentrationData['name'],
                'slug' => $concentrationData['slug'],
            ]);
        }
    }
}
