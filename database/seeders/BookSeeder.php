<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lecture
        $jsonPath = database_path('seeders/books.json');
        $json = file_get_contents($jsonPath);
        
        // Conversion
        $books = json_decode($json, true);

        // Insertion
        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
