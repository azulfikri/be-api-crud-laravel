<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();

        $categoryIds = Category::pluck('id')->toArray();

        for ($i = 0; $i < 10; $i++) {
            Book::create([
                'judul' => $faker->sentence(3),
                'penulis' => $faker->name,
                'tahun_terbit' => $faker->year,
                'jumlah_halaman' => $faker->numberBetween(100, 500),
                'category_id' => $faker->randomElement($categoryIds),
                'image' => $faker->imageUrl(300, 400, 'books', true, 'Cover'), // contoh dummy image
            ]);
        }
    }
}
