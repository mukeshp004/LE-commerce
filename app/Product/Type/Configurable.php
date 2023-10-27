<?php

namespace App\Product\Type;

use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductSuperAttributeValue;

class Configurable extends AbstractType
{
    /**
     * Skip attribute for configurable product type.
     *
     * @var array
     */
    protected $skipAttributes = ['price', 'cost', 'special_price', 'special_price_from', 'special_price_to', 'length', 'width', 'height', 'weight'];

    /**
     * These are the types which can be fillable when generating variant.
     *
     * @var array
     */
    protected $fillableTypes = ['sku', 'name', 'url_key', 'short_description', 'description', 'price', 'weight', 'status'];


    /**
     * Set default variant id.
     *
     * @param  int  $defaultVariantId
     * @return void
     */
    public function setDefaultVariantId($defaultVariantId)
    {
        $this->product->additional = array_merge($this->product->additional ?? [], [
            'default_variant_id' => $defaultVariantId,
        ]);
    }

    /**
     * Update default variant id if present in request.
     *
     * @return void
     */
    public function updateDefaultVariantId()
    {
        $defaultVariantId = request()->get('default_variant_id');

        if ($defaultVariantId) {
            $this->setDefaultVariantId($defaultVariantId);

            $this->product->save();
        }
    }

    /**
     * Create configurable product.
     *
     * @param  array  $data
     * @return \Webkul\Product\Contracts\Product
     */
    public function create(array $data)
    {
        $fillableArray =  collect($data)->only($this->productRepository->getModel()->getFillable());
        // $fillableArray->put("sku", $data['sku']);
        

        $product = $this->productRepository->getModel()->create($fillableArray->toArray());
        

        // needs to implement to save super attributes
        if(request()->has('super_attributes')) {
            $superAttributes = collect(request()->get('super_attributes', []));
            $product->super_attributes()->attach($superAttributes->keys());

            $superAttributes->each(function($options, $attributeId) use($product) {
                foreach ($options as $optionId) {
                    ProductSuperAttributeValue::create([
                        "product_id" => $product->id,
                        "attribute_id" => $attributeId,
                        "option_id" => $optionId,
                    ]);
                }
            });
        }

        // if (isset($data['super_attributes'])) {
        // // //     $super_attributes = [];

        //     foreach ($data['super_attributes'] as $attributeId) {
        // // //         $attribute = $this->getAttributeByCode($attributeCode);
        // // //         $super_attributes[$attribute->id] = $attributeOptions;
        //         $product->super_attributes()->attach($attributeId);
        //     }
        // }

        if (request()->has('variants')) {

            // will be added from update method
            // foreach (request()->get('variants') as $permutation) {
            //     $this->createVariant($product, $permutation);
            // }
        }

        return $product;
    }


    /**
     * Create variant.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  array                              $variation
     * @param  array                              $data
     * @return \Webkul\Product\Contracts\Product
     */
    public function createVariant($product, $variation, $data = [])
    {
        $data = [
            'sku'   => $variation['sku'],
            'name' => $variation['name'],
            'inventories' => [],
            'price' => $variation['price'],
            'weight' => 0,
            'status' => $variation['status']

        ];

        $data = $this->fillRequiredFields($data);

        $productType = Product::$PRODUCT_TYPE;
        $typeOfVariants = $productType['Simple'];
        $productType = array_flip(Product::$PRODUCT_TYPE);

        $productInstance = app(config('product_types.' . strtolower($productType[$product->type]) . '.class'));

        if (isset($productInstance->variantsType) && !in_array($productInstance->variantsType, [$productType['Bundle'], $productType['Configurable'], $productType['Grouped']])) {
            $typeOfVariants = $productInstance->variantsType;
        }

        // creates variable product
        $variant = $this->productRepository->getModel()->create([
            'parent_id'           => $product->id,
            'type'                => $typeOfVariants,
            'attribute_family_id' => $product->attribute_family_id,
            'sku'                 => $data['sku'],
        ]);

        // to save product attributes
        foreach ($this->fillableTypes as $attributeCode) {

            if (!isset($data[$attributeCode])) {
                continue;
            }

            $attribute = $this->attributeRepository->findOneByField('code', $attributeCode);
            
            // $attributeTypeFields = $this->getAttributeTypeValues($attribute->type, $data[$attributeCode]);

            $columnName = ProductAttributeValue::$attributeTypeFields[$attribute->type];

            $this->attributeValueRepository->create([
                'product_id'   => $variant->id,
                'attribute_id' => $attribute->id,
                $columnName => $data[$attributeCode],
                // 'value' => $data[$attributeCode],
                
            ]);
        }
        
        /**
         * To add configurable data in db
         */
        $attributeValues = [];
        $product->super_attributes->each(function ($attribute, int $key) use ($variant, $variation, &$attributeValues) {
            
            $columnName = ProductAttributeValue::$attributeTypeFields[$attribute->type];
            
            $attributeValues[] =  [
                        'product_id'   => $variant->id,
                        'attribute_id' => $attribute->id,
                        $columnName        => $variation[$attribute->code],
                        // 'value'        => $variation[$attribute->code],
                        'channel'      => null,
                        'locale'       => null,
            ];
        });

        $this->attributeValueRepository->insert($attributeValues);

        /* Data tos store attribute and attribut options  start */

        // $this->productInventoryRepository->saveInventories($data, $variant);

        // $this->productImageRepository->upload($data, $variant, 'images');

        return $variant;
    }

    

    /**
     * Fill required fields.
     *
     * @param  array  $data
     * @param  int  $id
     * @return \Webkul\Product\Contracts\Product
     */
    public function fillRequiredFields(array $data): array
    {
        /**
         * Name field is not present when variant is created so adding sku.
         */
        return array_merge($data, [
            'url_key'           => $data['sku'],
            'short_description' => $data['name'],
            'description'       => $data['name'],
        ]);
    }

    /**
     * Update configurable product.
     *
     * @param  array   $data
     * @param  int     $id
     * @param  string  $attribute
     * @return \Webkul\Product\Contracts\Product
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        /**
         * This will update the current product 
         */
        $product = parent::update($data, $id, $attribute);

        $this->updateDefaultVariantId();

        $previousVariantIds = $product->variants->pluck('id');

        /* 
         * If has variant then create variant product
        */
        if (isset($data['variants'])) {
            foreach ($data['variants'] as $variantKey => $variantData) {

                // create new variants
                if (!isset($variantData['id'])) {
                    $this->createVariant($product, $variantData, []);
                } else {
                    // update new variants
                    $variantId = $variantData['id'];

                    // remove from previousVariantIds, so that variants doesnt gets deleted
                    if (is_numeric($index = $previousVariantIds->search($variantId))) {
                        $previousVariantIds->forget($index);
                    }

                    $this->updateVariant($variantId, $variantData);
                }
            }
        }

        // this delete removed variant product 
        foreach ($previousVariantIds as $variantId) {
            // $this->productRepository->delete($variantId);
        }


        return $product;
    }

    /**
     * Update variant.
     *
     * @param  array  $data
     * @param  int    $id
     * @return \Webkul\Product\Contracts\Product
     */
    public function updateVariant($id, array $data)
    {
        $variant = $this->productRepository->find($id);


        $variant->update(['sku' => $data['sku']]);

        foreach ($this->fillableTypes as $attributeCode) {
            if (!isset($data[$attributeCode])) {
                continue;
            }

            // $attribute = $this->getAttributeByCode($attributeCode);
            $attribute = $this->attributeRepository->findOneByField('code', $attributeCode);

            $channel = isset($data['channel']) ? $data['channel'] : null;
            $locale = isset($data['locale'])  ? $data['locale'] : null;

            if ($attribute->value_per_channel) {
                if ($attribute->value_per_locale) {
                    $productAttributeValue = $variant->attribute_values
                        ->where('channel', $attribute->value_per_channel ? $channel : null)
                        ->where('locale', $attribute->value_per_locale ? $locale : null)
                        ->where('attribute_id', $attribute->id)
                        ->first();
                } else {
                    $productAttributeValue = $variant->attribute_values
                        ->where('channel', $attribute->value_per_channel ? $channel : null)
                        ->where('attribute_id', $attribute->id)
                        ->first();
                }
            } else {
                if ($attribute->value_per_locale) {
                    $productAttributeValue = $variant->attribute_values
                        ->where('locale', $attribute->value_per_locale ? $locale : null)
                        ->where('attribute_id', $attribute->id)
                        ->first();
                } else {
                    $productAttributeValue = $variant->attribute_values
                        ->where('attribute_id', $attribute->id)
                        ->first();
                }
            }

            $columnName = ProductAttributeValue::$attributeTypeFields[$attribute->type];

            if (!$productAttributeValue) {
                $this->attributeValueRepository->create([
                    'product_id'   => $variant->id,
                    'attribute_id' => $attribute->id,
                    // $columnName    => $data[$attribute->code],
                    'value'    => $data[$attribute->code],
                    'channel'      => $attribute->value_per_channel ? $channel : null,
                    'locale'       => $attribute->value_per_locale ? $locale : null,
                ]);
            } else {
                $productAttributeValue->update([$columnName => $data[$attribute->code]]);
            }
        }

        $this->productInventoryRepository->saveInventories($data, $variant);

        return $variant;
    }
}
