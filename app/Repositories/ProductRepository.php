<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
// use App\Entities\Product;
use App\Enums\ProductType;
use App\Models\Product;
use App\Validators\ProductValidator;

/**
 * Class ProductRepository.
 *
 * @package namespace App\Repositories;
 */
class ProductRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Product::class;
    }

    public function create(array $data)
    {
        $productType = array_flip(Product::$PRODUCT_TYPE);

        $typeInstance = app(config('product_types.' . strtolower($productType[$data['product_type']]) . '.class'));
        $product = $typeInstance->create($data);

        return $product;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
