<?php

namespace App\Product\Type;

use App\Repositories\ProductRepository;

abstract class AbstractType
{
    /**
     * Product repository instance.
     *
     * @var \Webkul\Product\Repositories\ProductRepository
     */
    protected $productRepository;

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

    public function __construct(
        ProductRepository $productRepository
    ) {

        $this->productRepository = $productRepository;
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
        // $product->update($data);


        return $product->attribute_family->custom_attributes;

        foreach ($product->attribute_family->custom_attributes as $attribute) {
        }
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
}
