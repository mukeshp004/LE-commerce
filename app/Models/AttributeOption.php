<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeOption extends Model
{
    use  HasFactory, Translatable;

    public $translatedAttributes = ['name'];

    protected $fillable = [
        'name',
        'swatch_value',
        'sort_order',
        'attribute_id',
    ];

    /**
     * Get the attribute that owns the attribute option.
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Get image url for the swatch value url.
     */
    public function swatch_value_url()
    {
        if ($this->swatch_value && $this->attribute->swatch_type == 'image') {
            return url('cache/small/' . $this->swatch_value);
        }

        return;
    }

    /**
     * Get image url for the product image.
     */
    public function getSwatchValueUrlAttribute()
    {
        return $this->swatch_value_url();
    }
}
