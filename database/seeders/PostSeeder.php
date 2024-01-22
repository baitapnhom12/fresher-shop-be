<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Path to the JSON file
        $jsonPath = __DIR__ . '/data/posts.json'; // Update the path if needed

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
        foreach ($jsonData as $postData) {
            Post::create([
                'id' => $postData['id'],
                'title' => $postData['title'],
                'image' => $postData['image'],
                'author' => $postData['author'],
                'content' => $postData['content'],
                'slug' => $postData['slug'],
                'article_id' => $postData['article_id'],
                'active' => $postData['active'],
                'popular' => $postData['popular'],
            ]);
        }
    }
}
