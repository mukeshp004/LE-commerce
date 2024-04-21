<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('categories')->truncate();

        Category::create([
            'uuid' => Str::uuid(),
            'name' => 'Grocery',
            'slug' => 'grocery',
            'description' => 'Grocery',
            'show_in_menu' => true,
            'display_mode' => true,
            'status' => 1
        ]);

        
        Category::create([
            'uuid' => Str::uuid(),
            'name' => 'Mobiles',
            'slug' => 'mobiles',
            'description' => 'Mobiles',
            'show_in_menu' => true,
            'display_mode' => true,
            'status' => 1
        ]);

        Category::create([
            'uuid' => Str::uuid(),
            'name' => 'Fashion',
            'slug' => 'fashion',
            'description' => 'Fashion',
            'show_in_menu' => true,
            'display_mode' => true,
            'status' => 1
        ]);

        Category::create([
            'uuid' => Str::uuid(),
            'name' => 'Electronics',
            'slug' => 'electronics',
            'description' => 'Electronics',
            'show_in_menu' => true,
            'display_mode' => true,
            'status' => 1
        ]);
    }
}
