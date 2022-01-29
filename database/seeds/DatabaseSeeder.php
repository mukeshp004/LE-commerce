<?php

use Database\Seeders\AttributeFamilyTableSeeder;
use Database\Seeders\AttributeGroupTableSeeder;
use Database\Seeders\AttributeTableSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);

        // Attribute Seeder
        $this->call(AttributeTableSeeder::class);
        // $this->call(AttributeOptionTableSeeder::class);
        $this->call(AttributeFamilyTableSeeder::class);
        $this->call(AttributeGroupTableSeeder::class);
    }
}
