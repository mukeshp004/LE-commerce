<?php

namespace App\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
// use App\Entities\Product;
use App\Enums\ProductType;
use App\Listeners\ProductFlatListener;
use App\Models\Product;
// use App\Product\Contracts\Product;
use App\Validators\ProductValidator;

/**
 * Class ProductRepository.
 *
 * @package namespace App\Repositories;
 */
class ProductRepository extends Repository
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


        $typeInstance = app(config('product_types.' . strtolower($productType[$data['type']]) . '.class'));

        // dd($typeInstance);

        $product = $typeInstance->create($data);

        $product = $this->update($data, $product->id);

        return $product;
    }

    /**
     * Update product.
     *
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \Webkul\Product\Contracts\Product
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        event('catalog.product.update.before', $id);

        $product = $this->find($id);


        // return $product;
        // dd('$product before update', $product);

        $product = $product->getTypeInstance()->update($data, $id, $attribute);

        // dd('$product After update', $product);


        if (isset($data['channels'])) {
            $product['channels'] = $data['channels'];
        }

        // dd('ProductRepository $product', $product);
        // event('catalog.product.update.after', [$product]);

        // dd(app(ProductFlatListener::class));
        (app(ProductFlatListener::class))->afterProductCreatedUpdated($product);


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
