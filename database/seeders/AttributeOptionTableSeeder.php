<?php

namespace Database\Seeders;

use App\Models\Attribute;
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
                'attribute_id' => Attribute::firstWhere('code', 'color')->id,
            ],
            [
                'id'           => '2',
                'name'   => 'Green',
                'sort_order'   => '2',
                'attribute_id' => Attribute::firstWhere('code', 'color')->id,
            ],
            [
                'id'           => '3',
                'name'   => 'Yellow',
                'sort_order'   => '3',
                'attribute_id' => Attribute::firstWhere('code', 'color')->id,
            ],
            [
                'id'           => '4',
                'name'   => 'Black',
                'sort_order'   => '4',
                'attribute_id' => Attribute::firstWhere('code', 'color')->id,
            ],
            [
                'id'           => '5',
                'name'   => 'White',
                'sort_order'   => '5',
                'attribute_id' => Attribute::firstWhere('code', 'color')->id,
            ],
            [
                'id'           => '6',
                'name'   => 'S',
                'sort_order'   => '1',
                'attribute_id' => Attribute::firstWhere('code', 'size')->id,
            ],
            [
                'id'           => '7',
                'name'   => 'M',
                'sort_order'   => '2',
                'attribute_id' => Attribute::firstWhere('code', 'size')->id,
            ],
            [
                'id'           => '8',
                'name'   => 'L',
                'sort_order'   => '3',
                'attribute_id' => Attribute::firstWhere('code', 'size')->id,
            ],
            [
                'id'           => '9',
                'name'   => 'XL',
                'sort_order'   => '4',
                'attribute_id' => Attribute::firstWhere('code', 'size')->id,
            ]
        ];

        foreach ($attributeOption as $key => $option) {
            AttributeOption::create($option);
        }
    }
}
