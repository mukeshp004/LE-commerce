<?php

namespace App\Product\Type;

use App\Models\Product;

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
        $fillableArray->put("sku", $data['general']['sku']);

        // dd('Configurable Product', $fillableArray->toArray());
        $product = $this->productRepository->getModel()->create($fillableArray->toArray());
        // $product = $this->productRepository->getModel()->find(1);

        // dump($product);


        if (request()->has('variants')) {
            // dump(request()->get('variants'));

            foreach (request()->get('variants') as $permutation) {
                $this->createVariant($product, $permutation);
            }
        }

        return $product;
    }


    /**
     * Create variant.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @param  array                              $permutation
     * @param  array                              $data
     * @return \Webkul\Product\Contracts\Product
     */
    public function createVariant($product, $permutation, $data = [])
    {
        // dd($product);
        if (!count($data)) {
            $data = [
                'sku'   => $permutation['sku'],
                'name' => $permutation['name'],
                'inventories' => [],
                'price' => $permutation['price'],
                'weight' => 0,
                'status' => $permutation['status']

            ];
        }

        $data = $this->fillRequiredFields($data);
        $productType = Product::$PRODUCT_TYPE;

        $typeOfVariants = $productType['Simple'];

        $productType = array_flip(Product::$PRODUCT_TYPE);
        $productInstance = app(config('product_types.' . strtolower($productType[$product->type]) . '.class'));


        // dd($productInstance);

        if (isset($productInstance->variantsType) && !in_array($productInstance->variantsType, [$productType['Bundle'], $productType['Configurable'], $productType['Grouped']])) {
            $typeOfVariants = $productInstance->variantsType;
        }

        // dd($this->fillableTypes);
        // dd($typeOfVariants);

        $variant = $this->productRepository->getModel()->create([
            'parent_id'           => $product->id,
            'type'                => $typeOfVariants,
            'attribute_family_id' => $product->attribute_family_id,
            'sku'                 => $data['sku'],
        ]);

        foreach ($this->fillableTypes as $attributeCode) {
            if (!isset($data[$attributeCode])) {
                continue;
            }

            $attribute = $this->attributeRepository->findOneByField('code', $attributeCode);

            $this->attributeValueRepository->create([
                'product_id'   => $variant->id,
                'attribute_id' => $attribute->id,
                'value'        => $data[$attributeCode],
            ]);
        }
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
            'short_description' => $data['sku'],
            'description'       => $data['sku'],
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
        $product = parent::update($data, $id, $attribute);

        $this->updateDefaultVariantId();
    }
}
