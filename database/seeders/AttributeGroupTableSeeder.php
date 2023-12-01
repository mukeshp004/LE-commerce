<?php

namespace Database\Seeders;

use App\Models\Attribute;
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



        $attributeFamily = AttributeFamily::firstWhere('code', 'default');
        AttributeGroup::insert([
            [
                // 'id'                  => '1',
                'code'                => 'general',
                'name'                => 'General',
                'position'            => '1',
                'is_user_defined'     => '0',
                'attribute_family_id' => $attributeFamily->id,
            ],
            [
                // 'id'                  => '2',
                'code'                => 'description',
                'name'                => 'Description',
                'position'            => '2',
                'is_user_defined'     => '0',
                'attribute_family_id' => $attributeFamily->id,
            ],
            [
                // 'id'                  => '4',
                'code'                => 'price',
                'name'                => 'Price',
                'position'            => '4',
                'is_user_defined'     => '0',
                'attribute_family_id' => $attributeFamily->id,
            ],
            [
                // 'id'                  => '5',
                'code'                => 'shipping',
                'name'                => 'Shipping',
                'position'            => '5',
                'is_user_defined'     => '0',
                'attribute_family_id' => $attributeFamily->id
            ],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    }
}
