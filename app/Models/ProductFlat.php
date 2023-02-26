<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFlat extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * Ignorable attributes.
     *
     * @var array
     */
    protected $ignorableAttributes = [
        'pivot',
        'parent_id',
        'attribute_family_id',
    ];

    /**
     * Retrieve type instance.
     *
     * @return \Webkul\Product\Type\AbstractType
     */
    public function getTypeInstance()
    {
        return $this->product->getTypeInstance();
    }

    /**
     * Get the product that owns the attribute value.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the product variants that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * Get product type value from base product.
     *
     * @return string
     */
    public function getTypeAttribute()
    {
        return $this->product->type;
    }

    /**
     * Get the product attribute family that owns the product.
     *
     * @return \Webkul\Attribute\Models\AttributeFamily
     */
    public function getAttributeFamilyAttribute()
    {
        return $this->product->attribute_family;
    }
}
