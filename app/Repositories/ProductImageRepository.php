<?php

namespace App\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Product;

/**
 * Class ProductRepository.
 *
 * @package namespace App\Repositories;
 */
class ProductImageRepository extends ProductMediaRepository
{

    public function __construct(
        protected ProductRepository $productRepository,
    ) {
        
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Product::class;
    }

    /**
     * Upload images.
     *
     * @param  array  $data
     * @param  \Webkul\Product\Models\Product  $product
     * @return void
     */
    public function uploadImages($data, $product): void
    {
        $this->upload($data, $product, 'images');

        if (isset($data['variants'])) {
            $this->uploadVariantImages($data['variants']);
        }
    }

    /**
     * Upload variant images.
     *
     * @param  array $variants
     * @return void
     */
    public function uploadVariantImages($variants): void
    {
        foreach ($variants as $variantsId => $variantData) {
            $product = $this->productRepository->find($variantsId);

            if (! $product) {
                break;
            }

            $this->upload($variantData, $product, 'images');
        }
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
