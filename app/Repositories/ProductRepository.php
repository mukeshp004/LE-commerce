<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
// use App\Entities\Product;
use App\Enums\ProductType;
use App\Models\Product;
// use App\Product\Contracts\Product;
use App\Validators\ProductValidator;
use Illuminate\Support\Facades\Event;

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

        $typeInstance = app(config('product_types.' . strtolower($productType[$data['type']]) . '.class'));
        $product = $typeInstance->create($data);

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
        Event::dispatch('catalog.product.update.before', $id);

        $product = $this->find($id);


        $product = $product->getTypeInstance()->update($data, $id, $attribute);

        dd($product);

        if (isset($data['channels'])) {
            $product['channels'] = $data['channels'];
        }

        Event::dispatch('catalog.product.update.after', $product);

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
