<?php

namespace Database\Seeders;

use App\Models\Books;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;


class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $response = Http::get('https://fakerapi.it/api/v1/books?_quantity=100');
        Books::truncate();
        $jsonData = $response->json();
        if (!empty($jsonData['data'])) {
            foreach($jsonData['data'] as $book) {
                $imageContent = file_get_contents($book['image']);
                $image_name = date('mdYHis') . uniqid().'.png';
                file_put_contents(public_path('image'.DIRECTORY_SEPARATOR.$image_name), $imageContent);
                $book['image'] = $image_name;
                Books::create($book);
            }
        }
    }
}
