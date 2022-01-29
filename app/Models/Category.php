<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use  HasFactory, Translatable;

    public $translatedAttributes = ['name', 'slug', 'description'];

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'description',
        'show_in_menu',
        'display_mode',
        'status',
        'locale_id'
    ];


    protected $with = ['translations'];
}
