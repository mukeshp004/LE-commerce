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

    abstract public function create();

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
