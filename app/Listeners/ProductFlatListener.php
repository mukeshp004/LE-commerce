<?php

namespace App\Listeners;

use App\Repositories\AttributeRepository;
use App\Repositories\ProductAttributeValueRepository;
use App\Repositories\ProductFlatRepository;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ProductFlatListener
{
    /**
     * Attribute codes that can be fill during flat creation.
     *
     * @var string[]
     */
    protected $fillableAttributeCodes = [
        'sku',
        'name',
        'price',
        'weight',
        'status',
    ];

    /**
     * @var array
     */
    public $attributeTypeFields = [
        'text'        => 'text',
        'textarea'    => 'text',
        'price'       => 'float',
        'boolean'     => 'boolean',
        'select'      => 'integer',
        'multiselect' => 'text',
        'datetime'    => 'datetime',
        'date'        => 'date',
        'file'        => 'text',
        'image'       => 'text',
        'checkbox'    => 'text',
    ];

    /**
     * @var array
     */
    protected $flatColumns = [];

    /**
     * Create a new listener instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Webkul\Attribute\Repositories\AttributeOptionRepository  $attributeOptionRepository
     * @param  \Webkul\Product\Repositories\ProductFlatRepository  $productFlatRepository
     * @param  \Webkul\Product\Repositories\ProductAttributeValueRepository  $productAttributeValueRepository
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        protected ProductFlatRepository $productFlatRepository,
        protected ProductAttributeValueRepository $productAttributeValueRepository
    ) {

        $this->flatColumns = Schema::getColumnListing('product_flats');
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function afterProductCreatedUpdated($product)
    {
        //
        $this->createFlat($product);
    }

    /**
     * Creates product flat
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  \Webkul\Product\Contracts\Product  $parentProduct
     * @return void
     */
    public function createFlat($product, $parentProduct = null)
    {
        static $familyAttributes = [];

        static $superAttributes = [];

        // dd('$product', $product);
        // dd('ProductFlatListener $product', $product);

        if (!array_key_exists($product->attribute_family_id, $familyAttributes)) {
            $familyAttributes[$product->attribute_family_id] = $product->attribute_family->custom_attributes;
        }

        // dd('$familyAttributes', $familyAttributes);

        if (
            $parentProduct
            && !array_key_exists($parentProduct->id, $superAttributes)
        ) {
            $superAttributes[$parentProduct->id] = $parentProduct->super_attributes()->pluck('code')->toArray();
        }

        $attributeValues = $product->attribute_values()->get();

        // dd('$product', $product);
        DB::enableQueryLog();
        $productFlat = $this->productFlatRepository->updateOrCreate([
            'product_id' => $product->id,
            'channel'    => 1,
            'locale'     => 'en',
            'sku'        => $product->sku ? $product->sku : ''      // added extra
        ]);

        $query = DB::getQueryLog();
        // dd($query);
        // dd($productFlat);


        foreach ($familyAttributes[$product->attribute_family_id] as $attribute) {
            dump($attribute->code);
            // dump(array_merge($superAttributes[$parentProduct->id], $this->fillableAttributeCodes));

            if (
                ($parentProduct
                    && !in_array($attribute->code, array_merge($superAttributes[$parentProduct->id], $this->fillableAttributeCodes))
                )
                || in_array($attribute->code, ['tax_category_id'])
                || !in_array($attribute->code, $this->flatColumns)
            ) {
                continue;
            }
        }
    }
}
