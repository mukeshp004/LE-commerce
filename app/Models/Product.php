<?php

namespace App\Models;

use App\Product\Contracts\Product as ProductContract;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

// use Rinvex\Attributes\Traits\Attributable;

class Product extends Model implements ProductContract
{
    public static $PRODUCT_TYPE = [
        "Simple" => 1,
        'Configurable' => 2,
        'Grouped' => 3,
        'Virtual' => 4,
        'Downloadable' => 5,
        'BOOKING' => 6,
        'BUNDLE' => 7,
        'GIFT_CARD' => 8,
    ];
    // use Attributable;

    // protected $with = ['eav'];

    /**
     * The attributes that are mass assignable.
     *
     * @var $fillable
     */
    protected $fillable = [
        'type',
        'attribute_family_id',
        'sku',
        'parent_id',
    ];

    /**
     * Get the product that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
    }


    /**
     * Get the product flat entries that are associated with product.
     * May be one for each locale and each channel.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function product_flats(): HasMany
    {
        return $this->hasMany(ProductFlat::class);
    }

    /**
     * Get the product variants that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }


    /**
     * The categories that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    /**
     * Get type instance.
     *
     * @return AbstractType
     *
     * @throws \Exception
     */
    public function getTypeInstance()
    {
        if ($this->typeInstance) {
            return $this->typeInstance;
        }

        $this->typeInstance = app(config('product_types.' . $this->type . '.class'));

        if (!$this->typeInstance) {
            throw new Exception("Please ensure the product type '{$this->type}' is configured in your application.");
        }

        $this->typeInstance->setProduct($this);

        return $this->typeInstance;
    }

    public function attribute_family(): BelongsTo
    {
        return $this->belongsTo(AttributeFamily::class);
    }
}
