<?php

namespace Database\Seeders;

use App\Models\AttributeOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeOptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('attribute_options')->delete();

        DB::table('attribute_option_translations')->delete();

        $attributeOption = [
            [
                'id'           => '1',
                'name'   => 'Red',
                'sort_order'   => '1',
                'attribute_id' => '23',
            ],
            [
                'id'           => '2',
                'name'   => 'Green',
                'sort_order'   => '2',
                'attribute_id' => '23',
            ],
            [
                'id'           => '3',
                'name'   => 'Yellow',
                'sort_order'   => '3',
                'attribute_id' => '23',
            ],
            [
                'id'           => '4',
                'name'   => 'Black',
                'sort_order'   => '4',
                'attribute_id' => '23',
            ],
            [
                'id'           => '5',
                'name'   => 'White',
                'sort_order'   => '5',
                'attribute_id' => '23',
            ],
            [
                'id'           => '6',
                'name'   => 'S',
                'sort_order'   => '1',
                'attribute_id' => '24',
            ],
            [
                'id'           => '7',
                'name'   => 'M',
                'sort_order'   => '2',
                'attribute_id' => '24',
            ],
            [
                'id'           => '8',
                'name'   => 'L',
                'sort_order'   => '3',
                'attribute_id' => '24',
            ],
            [
                'id'           => '9',
                'name'   => 'XL',
                'sort_order'   => '4',
                'attribute_id' => '24',
            ]
        ];

        foreach ($attributeOption as $key => $option) {
            AttributeOption::create($option);
        }
    }
}
