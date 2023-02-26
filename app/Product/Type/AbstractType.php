<?php

namespace App\Product\Type;

use App\Models\ProductAttributeValue;
use App\Repositories\AttributeRepository;
use App\Repositories\ProductAttributeValueRepository;
use App\Repositories\ProductRepository;

abstract class AbstractType
{
    /**
     * Attribute repository instance.
     *
     * @var \Webkul\Attribute\Repositories\AttributeRepository
     */
    protected $attributeRepository;

    /**
     * Product repository instance.
     *
     * @var \Webkul\Product\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * Product attribute value repository instance.
     *
     * @var \Webkul\Product\Repositories\ProductAttributeValueRepository
     */
    protected $attributeValueRepository;


    /**
     * Product instance.
     *
     * @var \Webkul\Product\Models\Product
     */
    protected $product;


    /**
     * Has child products aka variants.
     *
     * @var bool
     */
    protected $hasVariants = false;


    /**
     * Skip attribute for simple product type.
     *
     * @var array
     */
    protected $skipAttributes = [];



    public function __construct(
        AttributeRepository $attributeRepository,
        ProductRepository $productRepository,
        ProductAttributeValueRepository $attributeValueRepository,

    ) {
        $this->attributeRepository = $attributeRepository;
        $this->productRepository = $productRepository;
        $this->attributeValueRepository = $attributeValueRepository;
    }

    /**
     * Create product.
     *
     * @param  array  $data
     * @return \Webkul\Product\Contracts\Product
     */
    public function create(array $data)
    {
        // dd($this->productRepository->getModel());
        // return $this->productRepository->getModel()->getFillable();

        // return $data;
        $fillableArray =  collect($data)->only($this->productRepository->getModel()->getFillable());
        $fillableArray->put("sku", $data['general']['sku']);

        return $this->productRepository->getModel()->create($fillableArray->toArray());
    }

    public function update(array $data, $id, $attribute = 'id')
    {
        $product = $this->productRepository->find($id);
        $product->update($data);

        // dd('AbstractType Data', $data);

        $attributes = collect([]);

        foreach ($product->attribute_family->groups as $group) {
            if (isset($data[$group->code])) {

                foreach ($data[$group->code] as $key => $attribute) {
                    $attributes->put($key, $attribute);
                }
            }
        }

        // dd('===$attributes', $attributes);

        foreach ($product->attribute_family->custom_attributes as $key => $attribute) {

            // echo $attribute->type . '<br/>';

            // if ($attribute->type === 'boolean') {
            //     echo $attribute->type . ' ' . $attribute->code . '<br/>';

            //     $data[$attribute->code] = isset($data[$attribute->code]) && $data[$attribute->code] ? 1 : 0;
            // }

            if (!$attributes->has($attribute->code)) {
                continue;
            }

            // if ($attribute->type === 'price' && isset($data[$attribute->code]) && $data[$attribute->code] === '') {
            //     $data[$attribute->code] = null;
            // }

            // // if ($attribute->type === 'date' && $data[$attribute->code] === '' && $route !== 'admin.catalog.products.massupdate') {
            // if ($attribute->type === 'date' && $data[$attribute->code] === '') {
            //     $data[$attribute->code] = null;
            // }

            if ($attribute->type === 'multiselect' || $attribute->type === 'checkbox') {
                $data[$attribute->code] = implode(',', $data[$attribute->code]);
            }

            if ($attribute->type === 'image' || $attribute->type === 'file') {
                $data[$attribute->code] = gettype($data[$attribute->code]) === 'object'
                    ? request()->file($attribute->code)->store('product/' . $product->id)
                    : null;
            }

            $attributeValue = $this->attributeValueRepository->findOneWhere([
                'product_id'   => $product->id,
                'attribute_id' => $attribute->id,
                'channel'      => $attribute->value_per_channel ? (isset($data['channel']) ? $data['channel'] : null) : null,
                'locale'       => $attribute->value_per_locale ? (isset($data['locale'])  ? $data['locale'] : null) : null,
            ]);

            // dd($attributeValue);

            if (!$attributeValue) {
                $attrValueData = [
                    'product_id'   => $product->id,
                    'attribute_id' => $attribute->id,
                    'value'        => $attributes->get($attribute->code),
                    'channel'      => $attribute->value_per_channel ? (isset($data['channel']) ? $data['channel'] : null) : null,
                    'locale'       => $attribute->value_per_locale ? (isset($data['locale'])  ? $data['locale'] : null) : null,
                ];

                $this->attributeValueRepository->create($attrValueData);
            } else {

                if ($attribute->code == 'featured') {
                    // dd($attribute);
                }
                $this->attributeValueRepository->update([
                    ProductAttributeValue::$attributeTypeFields[$attribute->type] => $attributes->get($attribute->code),
                ], $attributeValue->id);

                if ($attribute->type == 'image' || $attribute->type == 'file') {
                    // Storage::delete($attributeValue->text_value);
                }
            }
        }

        // dd('AbtractType::class', $data);

        return $product;
    }

    /**
     * Specify type instance product.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return \Webkul\Product\Type\AbstractType
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }


    /**
     * Return true if this product can have variants.
     *
     * @return bool
     */
    public function hasVariants()
    {
        return $this->hasVariants;
    }

    /**
     * Retrieve product attributes.
     *
     * @param  \Webkul\Attribute\Contracts\Group  $group
     * @param  bool  $skipSuperAttribute
     * @return \Illuminate\Support\Collection
     */
    public function getEditableAttributes($group = null, $skipSuperAttribute = true)
    {
        if ($skipSuperAttribute) {
            $this->skipAttributes = array_merge(
                $this->product->super_attributes->pluck('code')->toArray(),
                $this->skipAttributes
            );
        }

        if (!$group) {
            return $this->product->attribute_family->attributes()->whereNotIn(
                'attributes.code',
                $this->skipAttributes
            )->get();
        }
        return $group->attributes()->whereNotIn('code', $this->skipAttributes)->get();
    }
}
