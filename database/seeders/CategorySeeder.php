<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $category = new Category();
            $category->category_name = 'Category '.Str::random(5);
            $category->disabled = 0;
            $category->save();
        }
    }
}
