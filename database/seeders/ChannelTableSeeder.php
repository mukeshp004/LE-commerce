<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChannelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('channels')->delete();

        DB::table('channels')->insert([
            'id'                => 1,
            'code'              => 'default',
            'theme'             => 'velocity',
            'hostname'          => config('app.url'),
            'root_category_id'  => 1,
            'default_locale_id' => 1,
            'base_currency_id'  => 1,
        ]);

        DB::table('channel_currencies')->insert([
            'channel_id'  => 1,
            'currency_id' => 1,
        ]);

        DB::table('channel_locales')->insert([
            'channel_id' => 1,
            'locale_id'  => 1,
        ]);
    }
}
