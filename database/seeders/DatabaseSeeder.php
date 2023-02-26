<?php

namespace Database\Seeders;

use Database\Seeders\AttributeFamilyTableSeeder;
use Database\Seeders\AttributeGroupTableSeeder;
use Database\Seeders\AttributeOptionTableSeeder;
use Database\Seeders\AttributeTableSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        $this->call(UserSeeder::class);

        // Attribute Seeder
        $this->call(AttributeTableSeeder::class);
        $this->call(AttributeOptionTableSeeder::class);
        $this->call(AttributeFamilyTableSeeder::class);
        $this->call(AttributeGroupTableSeeder::class);
        $this->call(LocalesTableSeeder::class);
        $this->call(ChannelTableSeeder::class);
    }
}
