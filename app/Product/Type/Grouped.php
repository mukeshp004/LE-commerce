<?php

namespace App\Product\Type;

use App\Repositories\ProductRepository;

class Grouped extends AbstractType
{
    /**
     * Product grouped product repository instance.
     *
     * @var \Webkul\Product\Repositories\ProductGroupedProductRepository
     */
    protected $productGroupedProductRepository;

    /**
     * Skip attribute for downloadable product type.
     *
     * @var array
     */
    protected $skipAttributes = ['price', 'cost', 'special_price', 'special_price_from', 'special_price_to', 'length', 'width', 'height', 'weight'];

    public function __construct(
        ProductRepository $productRepository
    ) {
        parent::__construct(
            $productRepository
        );
    }

    public function create()
    {

        return 'Grouped';
    }
}
