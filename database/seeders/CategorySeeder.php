<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create(['name' => 'Cinta']);
        Category::create(['name' => 'Alam']);
        Category::create(['name' => 'Agama']);
        Category::create(['name' => 'Politik']);
    }
}
