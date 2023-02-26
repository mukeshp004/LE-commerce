<?php

namespace App\Repositories;

use App\Models\ProductFlat;
use Illuminate\Container\Container;

class ProductFlatRepository extends Repository
{

    public function __construct(
        protected AttributeRepository $attributeRepository,
        Container $container
    ) {

        parent::__construct($container);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProductFlat::class;
    }
}
