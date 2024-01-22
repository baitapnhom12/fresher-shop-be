<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to the JSON file
        $jsonPath = __DIR__ . '/data/images.json'; // Update the path if needed

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

        // Insert each image into the database
        foreach ($jsonData as $imageData) {
            Image::create([
                'user_id' => $imageData['user_id'] ?? null, // Assuming these fields can be nullable
                'product_id' => $imageData['product_id'] ?? null,
                'category_id' => $imageData['category_id'] ?? null,
                'brand_id' => $imageData['brand_id'] ?? null,
                'path' => $imageData['path'],
                'main' => $imageData['main'] ?? false, // Assuming 'main' is a boolean field
            ]);
        }

        $bannerImages = [
            'https://res.cloudinary.com/dz7fxpflg/image/upload/v1703748988/categories/1703748986banner5.png',
            'https://res.cloudinary.com/dz7fxpflg/image/upload/v1703748992/categories/1703748990banner4.png',
            'https://res.cloudinary.com/dz7fxpflg/image/upload/v1703748997/categories/1703748994banner3.png',
            'https://res.cloudinary.com/dz7fxpflg/image/upload/v1703749002/categories/1703748999banner2.png',
            'https://res.cloudinary.com/dz7fxpflg/image/upload/v1703749006/categories/1703749003banner1.png',
            'https://res.cloudinary.com/dz7fxpflg/image/upload/v1703749067/categories/1703749065banner10.png',
            'https://res.cloudinary.com/dz7fxpflg/image/upload/v1703749072/categories/1703749069banner9.png',
            'https://res.cloudinary.com/dz7fxpflg/image/upload/v1703749076/categories/1703749074banner8.png',
            'https://res.cloudinary.com/dz7fxpflg/image/upload/v1703749081/categories/1703749078banner7.png',
            'https://res.cloudinary.com/dz7fxpflg/image/upload/v1703749085/categories/1703749083banner6.png',
        ];

        $bannerImageId = 100;

        foreach ($bannerImages as $bannerImage) {
            Image::create([
                'banner_id' => $bannerImageId++,
                'path' => $bannerImage,
            ]);
        }
    }
}
