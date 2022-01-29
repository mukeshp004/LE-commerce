<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('attributes')->truncate();
        DB::table('attribute_translations')->truncate();

        $now = Carbon::now();

        $attributes = [
            [
                'code'                => 'sku',
                'name'                => 'SKU',
                'type'                => 'text',
                'validation'          => NULL,
                'position'            => '1',
                'is_required'         => '1',
                'is_unique'           => '1',
                'value_per_locale'    => '0',
                'value_per_channel'   => '0',
                'is_filterable'       => '0',
                'is_configurable'     => '0',
                'is_user_defined'     => '0',
                'is_visible_on_front' => '0',
                'use_in_flat'         => '1',
                'created_at'          => $now,
                'updated_at'          => $now,
                'is_comparable'       => '0',
            ],
            [
                'code'                => 'name',
                'name'                => 'Name',
                'type'                => 'text',
                'validation'          => NULL,
                'position'            => '3',
                'is_required'         => '1',
                'is_unique'           => '0',
                'value_per_locale'    => '1',
                'value_per_channel'   => '1',
                'is_filterable'       => '0',
                'is_configurable'     => '0',
                'is_user_defined'     => '0',
                'is_visible_on_front' => '0',
                'use_in_flat'         => '1',
                'created_at'          => $now,
                'updated_at'          => $now,
                'is_comparable'       => '1',
            ],
        ];

        foreach ($attributes as $key => $attribute) {
            Attribute::create($attribute);
        }


        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
