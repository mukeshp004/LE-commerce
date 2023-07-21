<?php

namespace App\Listeners;

use App\Models\ProductAttributeValue;
use App\Repositories\AttributeOptionRepository;
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
        protected AttributeOptionRepository $attributeOptionRepository,
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

        // dd('ProductFlatListener $product', $product);

        if (!array_key_exists($product->attribute_family_id, $familyAttributes)) {
            $familyAttributes[$product->attribute_family_id] = $product->attribute_family->custom_attributes;
        }

        if (
            $parentProduct
            && !array_key_exists($parentProduct->id, $superAttributes)
        ) {
            $superAttributes[$parentProduct->id] = $parentProduct->super_attributes()->pluck('code')->toArray();
        }


        $attributeValues = $product->attribute_values()->get();

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
            if (
                ($parentProduct
                    && !in_array($attribute->code, array_merge($superAttributes[$parentProduct->id], $this->fillableAttributeCodes))
                )
                || in_array($attribute->code, ['tax_category_id'])
                || !in_array($attribute->code, $this->flatColumns)
            ) {
                continue;
            }

            $attributes = collect([]);

            foreach ($product->attribute_family->groups as $group) {
                if (isset($data[$group->code])) {

                    foreach ($data[$group->code] as $key => $attribute) {
                        $attributes->put($key, $attribute);
                    }
                }
            }


            // if ($attribute->value_per_channel) {
            //     if ($attribute->value_per_locale) {
            //         $productAttributeValue = $attributeValues
            //             ->where('channel', $channel->code)
            //             ->where('locale', $locale->code)
            //             ->where('attribute_id', $attribute->id)
            //             ->first();
            //     } else {
            //         $productAttributeValue = $attributeValues
            //             ->where('channel', $channel->code)
            //             ->where('attribute_id', $attribute->id)
            //             ->first();
            //     }
            // } else {
            if ($attribute->value_per_locale) {
                $productAttributeValue = $attributeValues
                    ->where('locale', 'en')
                    ->where('attribute_id', $attribute->id)
                    ->first();
            } else {
                $productAttributeValue = $attributeValues
                    ->where('attribute_id', $attribute->id)
                    ->first();
            }
            // }

            /**
             * assigns the product attribute value to flat product
             */
            $productFlat->{$attribute->code} = $productAttributeValue[ProductAttributeValue::$attributeTypeFields[$attribute->type]] ?? null;

            if ($attribute->type == 'select') {
                $attributeOption = $this->getAttributeOptions($productFlat->{$attribute->code});

                if ($attributeOption) {
                    if ($attributeOptionTranslation = $attributeOption->translate(/*$locale->code*/'en')) {
                        $productFlat->{$attribute->code . '_label'} = $attributeOptionTranslation->label;
                    } else {
                        $productFlat->{$attribute->code . '_label'} = $attributeOption->admin_name;
                    }
                }
            } elseif ($attribute->type == 'multiselect') {
                $attributeOptionIds = explode(',', $productFlat->{$attribute->code});

                if (count($attributeOptionIds)) {
                    $attributeOptions = $this->getAttributeOptions($productFlat->{$attribute->code});

                    $optionLabels = [];

                    foreach ($attributeOptions as $attributeOption) {
                        if ($attributeOptionTranslation = $attributeOption->translate(/*$locale->code*/'en')) {
                            $optionLabels[] = $attributeOptionTranslation->label;
                        } else {
                            $optionLabels[] = $attributeOption->admin_name;
                        }
                    }

                    $productFlat->{$attribute->code . '_label'} = implode(', ', $optionLabels);
                }
            }
        } //end  of familyAttribute loop


        // $productFlat->min_price = $product->getTypeInstance()->getMinimalPrice();

        // $productFlat->max_price = $product->getTypeInstance()->getMaximumPrice();

        if ($parentProduct) {
            $parentProductFlat = $this->productFlatRepository->findOneWhere([
                'product_id' => $parentProduct->id,
                'channel'    => /* $channel->code*/ null,
                'locale'     => /* $locale->code */ 'en',
            ]);

            if ($parentProductFlat) {
                $productFlat->parent_id = $parentProductFlat->id;
            }
        }

        $productFlat->save();
    }


    /**
     * @param  string  $value
     * @return mixed
     */
    public function getAttributeOptions($value)
    {
        if (!$value) {
            return;
        }

        static $attributeOptions = [];

        if (array_key_exists($value, $attributeOptions)) {
            return $attributeOptions[$value];
        }

        if (is_numeric($value)) {
            return $attributeOptions[$value] = $this->attributeOptionRepository->find($value);
        } else {
            $attributeOptionIds = explode(',', $value);

            return $attributeOptions[$value] = $this->attributeOptionRepository->findWhereIn('id', $attributeOptionIds);
        }
    }
}
