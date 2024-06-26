<?php

namespace App\Models;

use App\Product\Contracts\Product as ProductContract;
use App\Repositories\AttributeRepository;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

class Product extends Model implements ProductContract
{
    use HasFactory;

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
     * The attributes that should be cast.
     *
     * @var $casts
     */
    protected $casts = [
        'additional' => 'array',
    ];

    /**
     * The type of product.
     *
     * @var \Webkul\Product\Type\AbstractType
     */
    protected $typeInstance;

    protected $with = [];

    /**
     * Loaded attribute values.
     *
     * @var array
     */
    public static $loadedAttributeValues = [];

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
     * Get the product Meta Data
     */
    public function meta_data(): HasOne
    {
        return $this->hasOne(ProductMetaData::class);
    }

    /**
     * Get the product variants that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id')->with(['inventories']);
    }

    /**
     * The inventory sources that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function inventory_sources(): BelongsToMany
    {
        return $this->belongsToMany(InventorySource::class, 'product_inventories')
            ->withPivot('id', 'quantity ');
    }

    /**
     * Get inventory source quantity.
     *
     * @param  $inventorySourceId
     * @return bool
     */
    public function inventory_source_qty($inventorySourceId)
    {
        return $this->inventories()
            ->where('inventory_source_id', $inventorySourceId)
            ->sum('qty');
    }

    /**
     * The inventories that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventories(): HasMany
    {
        return $this->hasMany(ProductInventory::class, 'product_id');
    }

    /**
     * The ordered inventories that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function ordered_inventories(): HasMany
    // {
    //     // return $this->hasMany(ProductOrderedInventoryProxy::modelClass(), 'product_id');
    // }



    /**
     * The categories that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function getTypeText()
    {

        $typeText = collect(Product::$PRODUCT_TYPE)->flip()->get($this->type);

        // dd($typeText);
        // dd(strtolower($typeText));

        // $typeText = "simple";
        if ($this->type == 2) {
            $typeText = 'configurable';
        }
        return strtolower($typeText);
    }


    public function setTypeText()
    {
        // dd($this->type);
        $typeText = collect($this->attributes['type_text'])->flip()->get($this->type);

        // dd($typeText);

        // $typeText = "simple";
        // if ($this->type == 2) {
        // }
        // return $typeText;

        $this->attributes['type_text'] = $typeText;
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


        // $this->typeInstance = app(config('product_types.' . $this->type . '.class'));
        // echo 'product_types.' . $this->getTypeText() . '.class';
        $this->typeInstance = app(config('product_types.' . $this->getTypeText() . '.class'));


        if (!$this->typeInstance) {
            throw new Exception("Please ensure the product type '{$this->type}' is configured in your application.");
        }

        // dd('$this->typeInstance', $this->typeInstance);
        $this->typeInstance->setProduct($this);

        return $this->typeInstance;
    }

    public function attribute_family(): BelongsTo
    {
        return $this->belongsTo(AttributeFamily::class);
    }

    /**
     * Retrieve product attributes.
     *
     * @param  Group  $group
     * @param  bool  $skipSuperAttribute
     * @return \Illuminate\Support\Collection
     *
     * @throws \Exception
     */
    public function getEditableAttributes($group = null, $skipSuperAttribute = true): Collection
    {
        return $this->getTypeInstance()
            ->getEditableAttributes($group, $skipSuperAttribute);
    }

    /**
     * The super attributes that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function super_attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'product_super_attributes');
    }

    public function super_attribute_values(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'product_super_attribute_values')->withPivot('option_id');
    }


    /**
     * Get the product attribute values that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attribute_values(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    /**
     * Return the product attribute.
     *
     * @return self
     */
    public function getProductAttribute()
    {
        return $this;
    }


    /**
     * Get an attribute from the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (
            !method_exists(static::class, $key)
            && !in_array($key, [
                'pivot',
                'parent_id',
                'attribute_family_id',
            ])
            && !isset($this->attributes[$key])
        ) {
            if (isset($this->id)) {
                $this->attributes[$key] = '';

                $attribute = app(AttributeRepository::class)
                    ->getAttributeByCode($key);

                $this->attributes[$key] = $this->getCustomAttributeValue($attribute);

                return $this->getAttributeValue($key);
            }
        }

        return parent::getAttribute($key);
    }

    /**
     * Get an product attribute value.
     *
     * @return mixed
     */
    public function getCustomAttributeValue($attribute)
    {
        if (!$attribute) {
            return;
        }

        // $locale = core()->checkRequestedLocaleCodeInRequestedChannel();
        // $channel = core()->getRequestedChannelCode();

        if (
            array_key_exists($this->id, self::$loadedAttributeValues)
            && array_key_exists($attribute->id, self::$loadedAttributeValues[$this->id])
        ) {
            return self::$loadedAttributeValues[$this->id][$attribute->id];
        }

        // if ($attribute->value_per_channel) {
        //     if ($attribute->value_per_locale) {
        //         $attributeValue = $this->attribute_values()
        //             ->where('channel', $channel)
        //             ->where('locale', $locale)
        //             ->where('attribute_id', $attribute->id)
        //             ->first();
        //     } else {
        //         $attributeValue = $this->attribute_values()
        //             ->where('channel', $channel)
        //             ->where('attribute_id', $attribute->id)
        //             ->first();
        //     }
        // } else {
        // if ($attribute->value_per_locale) {
        //     $attributeValue = $this->attribute_values()
        //         ->where('locale', $locale)
        //         ->where('attribute_id', $attribute->id)
        //         ->first();
        // } else {
        $attributeValue = $this->attribute_values()
            ->where('attribute_id', $attribute->id)
            ->first();
        // }
        // }

        return self::$loadedAttributeValues[$this->id][$attribute->id] = $attributeValue[ProductAttributeValue::$attributeTypeFields[$attribute->type]] ?? null;
    }

    /**
     * Attributes to array.
     *
     * @return array
     */
    public function attributesToArray(): array
    {
        $attributes = parent::attributesToArray();


        // dd('attribute', $attributes);

        $hiddenAttributes = $this->getHidden();

        if (isset($this->id)) {
            $groups = $this->attribute_family->groups;

            foreach ($groups as $group) {
                $familyAttributes = $group->attributes()->get();
                // dd($familyAttributes);

                $tmpAttributes = [];
                foreach ($familyAttributes as $attribute) {
                    if (in_array($attribute->code, $hiddenAttributes)) {
                        continue;
                    }


                    // $tmpAttributes[$attribute->code] = $this->getCustomAttributeValue($attribute);
                    
                    /**
                     * This will set directly on payment pojo without groups
                     */
                    $attributes[$attribute->code] = $this->getCustomAttributeValue($attribute);
                }

                /**
                 * this will stamp attribute and attribute value with group
                 */
                // $attributes[$group->code] = $tmpAttributes;
            }
        }

        // dd('attribute', $attributes);


        // if (isset($this->id)) {
        //     $familyAttributes = $this->checkInLoadedFamilyAttributes();

        //     // dd($familyAttributes->toArray());

        //     foreach ($familyAttributes as $attribute) {
        //         if (in_array($attribute->code, $hiddenAttributes)) {
        //             continue;
        //         }

        //         // dump();

        //         $attributes[$attribute->code] = $this->getCustomAttributeValue($attribute);
        //     }
        // }



        return $attributes;
    }

    /**
     * Check in loaded family attributes.
     *
     * @return object
     */
    public function checkInLoadedFamilyAttributes(): object
    {
        static $loadedFamilyAttributes = [];

        if (array_key_exists($this->attribute_family_id, $loadedFamilyAttributes)) {
            return $loadedFamilyAttributes[$this->attribute_family_id];
        }

        return $loadedFamilyAttributes[$this->attribute_family_id] = app(AttributeRepository::class)
            ->getFamilyAttributes($this->attribute_family);
    }

    /**
     * The related products that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function related_products(): BelongsToMany
    {
        return $this->belongsToMany(static::class, 'product_relations', 'product_id', 'related_id')
            ->limit(4);
    }

    /**
     * The up sells that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function up_sells(): BelongsToMany
    {
        return $this->belongsToMany(static::class, 'product_up_sells', 'product_id', 'related_id')
            ->limit(4);
    }

    /**
     * The cross sells that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cross_sells(): BelongsToMany
    {
        return $this->belongsToMany(static::class, 'product_cross_sells', 'product_id', 'related_id')
            ->limit(4);
    }
}
