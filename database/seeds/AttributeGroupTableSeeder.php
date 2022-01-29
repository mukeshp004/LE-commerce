<?php

namespace Database\Seeders;

use App\Models\AttributeFamily;
use App\Models\AttributeGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('attribute_groups')->truncate();

        DB::table('attribute_group_mappings')->truncate();


        $attributeFamily = AttributeFamily::firstWhere('code', 'default');
        AttributeGroup::insert([
            [
                'name'                => 'General',
                'position'            => '1',
                'is_user_defined'     => '0',
                'attribute_family_id' => $attributeFamily->id,
            ],
            [
                'name'                => 'Description',
                'position'            => '2',
                'is_user_defined'     => '0',
                'attribute_family_id' => $attributeFamily->id,
            ],
            [
                'name'                => 'Meta Description',
                'position'            => '3',
                'is_user_defined'     => '0',
                'attribute_family_id' => $attributeFamily->id,
            ],
            [
                'name'                => 'Price',
                'position'            => '4',
                'is_user_defined'     => '0',
                'attribute_family_id' => $attributeFamily->id,
            ],
            [
                'name'                => 'Shipping',
                'position'            => '5',
                'is_user_defined'     => '0',
                'attribute_family_id' => $attributeFamily->id
            ],
        ]);

        // DB::table('attribute_group_mappings')->insert([
        //     [
        //         'attribute_id'        => '1',
        //         'attribute_group_id'  => '1',
        //         'position'            => '1',
        //     ],
        // ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    }
}
