<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use  HasFactory, Translatable;

    public $translatedAttributes = ['name', 'description'];

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'swatch_type',
        'validation',
        'is_required',
        'is_unique',
        'is_filterable',
        'is_configurable',
        'is_user_defined',
        'is_visible_on_front',
        'is_comparable',
        'use_in_flat',
        'position',
        'status',
        'locale_id'
    ];


    public static $type = [
        'text' => 1,
        'textarea' => 2,
        'number' => 3,
        'file' => 4,
        'radio' => 5,
        'checkbox' => 6,
        'select' => 7,
        'multiselect' => 8,
        'image' => 9,
        'price' => 10,
        'boolean' => 11,
        'datetime' => 12,
        'date' => 13,
    ];

    public static $swatchType = [
        'text' => 1,
        'color' => 2,
        'file' => 3,
    ];

    public static $validation = [
        'numeric' => 1,
        'email' => 2,
        'decimal' => 3,
        'url' => 4
    ];


    protected $with = ['translations', 'options'];

    /**
     * Get the options.
     */
    public function options()
    {
        return $this->hasMany(AttributeOption::class);
    }
}
