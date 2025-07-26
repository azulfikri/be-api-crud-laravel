<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = ['Novel', 'Komik', 'Biografi', 'Teknologi', 'Bisnis'];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
            ]);
        }
    }
}
