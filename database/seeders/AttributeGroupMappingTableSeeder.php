<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Attribute;
use App\Models\AttributeGroup;
use Illuminate\Support\Facades\DB;

class AttributeGroupMappingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('attribute_group_mappings')->truncate();

        // general mapping
        DB::table('attribute_group_mappings')->insert([
            [
                'attribute_id'        => Attribute::firstWhere('code', 'sku')->id, // 1
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'general')->id,
                'position'            => '1',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'name')->id, // 2
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'general')->id,
                'position'            => '3',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'url_key')->id, //'3',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'general')->id,
                'position'            => '4',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'tax_category_id')->id, //'4',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'general')->id,
                'position'            => '5',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'new')->id, //'5',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'general')->id,
                'position'            => '6',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'featured')->id, //'6',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'general')->id,
                'position'            => '7',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'visible_individually')->id, //'7',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'general')->id,
                'position'            => '8',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'status')->id, //'8',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'general')->id,
                'position'            => '10',
            ], 
            [
                'attribute_id'        => Attribute::firstWhere('code', 'color')->id, //'23',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'general')->id,
                'position'            => '11',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'size')->id, //'24',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'general')->id,
                'position'            => '12',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'brand')->id, //'25',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'general')->id,
                'position'            => '13',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'guest_checkout')->id, //'26',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'general')->id,
                'position'            => '9',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'product_number')->id, //'27',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'general')->id,
                'position'            => '2',
            ]
        ]);

        // description
        DB::table('attribute_group_mappings')->insert([
            [
                'attribute_id'        => Attribute::firstWhere('code', 'short_description')->id, //'9',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'description')->id,
                'position'            => '1',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'description')->id, //'10',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'description')->id,
                'position'            => '2',
            ]
        ]);

        // price
        DB::table('attribute_group_mappings')->insert([
            [
                'attribute_id'        => Attribute::firstWhere('code', 'price')->id, //'11',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'price')->id,
                'position'            => '1',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'cost')->id, //'12',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'price')->id,
                'position'            => '2',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'special_price')->id, //'13',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'price')->id,
                'position'            => '3',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'special_price_from')->id, //'14',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'price')->id,
                'position'            => '4',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'special_price_to')->id, //'15',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'price')->id,
                'position'            => '5',
            ]
        ]);

        
        // shipping
        DB::table('attribute_group_mappings')->insert([
            [
                'attribute_id'        => Attribute::firstWhere('code', 'length')->id, //'19',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'shipping')->id,
                'position'            => '1',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'width')->id, //'20',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'shipping')->id,
                'position'            => '2',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'height')->id, //'21',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'shipping')->id,
                'position'            => '3',
            ], [
                'attribute_id'        => Attribute::firstWhere('code', 'weight')->id, //'22',
                'attribute_group_id'  => AttributeGroup::firstWhere('code', 'shipping')->id,
                'position'            => '4',
            ]
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    }
}
