<?php

namespace Database\Seeders;

use App\Models\InventorySource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventorySourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('inventory_sources')->truncate();

        InventorySource::create([
            "name" => "Default",
            "code" => "default",
            "status" => 1,
        ]);
    }
}
