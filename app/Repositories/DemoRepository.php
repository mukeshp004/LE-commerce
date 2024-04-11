<?php

use App\Models\Product;
use App\Repositories\Repository;

class DemoRepository extends Repository {

     /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Product::class;
    }
}