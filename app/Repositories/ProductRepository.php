<?php

namespace App\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
// use App\Entities\Product;
use App\Enums\ProductType;
use App\Listeners\ProductFlatListener;
use App\Models\Product;
// use App\Product\Contracts\Product;
use App\Validators\ProductValidator;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductRepository.
 *
 * @package namespace App\Repositories;
 */
class ProductRepository extends Repository
{

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        // protected CustomerRepository $customerRepository,
        protected AttributeRepository $attributeRepository,
        protected ProductAttributeValueRepository $productAttributeValueRepository,
        // protected ElasticSearchRepository $elasticSearchRepository,
        // protected SearchSynonymRepository $searchSynonymRepository,
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
        return Product::class;
    }

    public function create(array $data)
    {

        $productType = array_flip(Product::$PRODUCT_TYPE);


        $typeInstance = app(config('product_types.' . strtolower($productType[$data['type']]) . '.class'));

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
        $product = $this->findOrFail($id);

        $product = $product->getTypeInstance()->update($data, $id, $attribute);


        if (isset($data['channels'])) {
            $product['channels'] = $data['channels'];
        }

        return $product;
    }

    /**
     * Return product by filtering through attribute values.
     *
     * @param  string  $code
     * @param  mixed  $value
     * @return \Webkul\Product\Contracts\Product
     */
    public function findByAttributeCode($code, $value)
    {
        $attribute = $this->attributeRepository->findOneByField('code', $code);

        $attributeValues = $this->productAttributeValueRepository->findWhere([
            'attribute_id'          => $attribute->id,
            $attribute->column_name => $value,
        ]);

        // if ($attribute->value_per_channel) {
        //     if ($attribute->value_per_locale) {
        //         $attributeValues = $attributeValues
        //             ->where('channel', core()->getRequestedChannelCode())
        //             ->where('locale', core()->getRequestedLocaleCode());
        //     } else {
        //         $attributeValues = $attributeValues
        //             ->where('channel', core()->getRequestedChannelCode());
        //     }
        // } else {
        //     if ($attribute->value_per_locale) {
        //         $attributeValues = $attributeValues
        //             ->where('locale', core()->getRequestedLocaleCode());
        //     }
        // }

        return $attributeValues->first()?->product;
    }

    /**
     * Retrieve product from slug without throwing an exception.
     */
    public function findBySlug(string $slug): ?Product
    {
        return $this->findByAttributeCode('url_key', $slug);
    }

    /**
     * Retrieve product from slug.
     */
    public function findBySlugOrFail(string $slug): ?Product
    {
        $product = $this->findBySlug($slug);

        if (!$product) {
            throw (new ModelNotFoundException())->setModel(
                get_class($this->model),
                $slug
            );
        }

        return $product;
    }

    /**
     * Get all products.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll(array $params = [])
    {

        return $this->searchFromDatabase($params);
    }

    /**
     * Search product from database.
     *
     * @return \Illuminate\Support\Collection
     */
    public function searchFromDatabase(array $params = [])
    {
        $params = array_merge([
            'status'               => 1,
            'visible_individually' => 1,
            'url_key'              => null,
        ], $params);

        if (!empty($params['query'])) {
            $params['name'] = $params['query'];
        }

        $query = $this->with([
            'attribute_family',
            'images',
            'videos',
            'attribute_values',
            'price_indices',
            'inventory_indices',
            'reviews',
        ])->scopeQuery(function ($query) use ($params) {
            $prefix = DB::getTablePrefix();

            $qb = $query->distinct()
                ->select('products.*')
                ->leftJoin('products as variants', DB::raw('COALESCE(' . $prefix . 'variants.parent_id, ' . $prefix . 'variants.id)'), '=', 'products.id');
            // ->leftJoin('product_price_indices', function ($join) {
            //     $customerGroup = $this->customerRepository->getCurrentGroup();

            //     $join->on('products.id', '=', 'product_price_indices.product_id')
            //         ->where('product_price_indices.customer_group_id', $customerGroup->id);
            // });

            if (!empty($params['category_id'])) {
                $qb->leftJoin('product_categories', 'product_categories.product_id', '=', 'products.id')
                    ->whereIn('product_categories.category_id', explode(',', $params['category_id']));
            }

            if (!empty($params['type'])) {
                $qb->where('products.type', $params['type']);
            }

            /**
             * Filter query by price.
             */
            // if (!empty($params['price'])) {
            //     $priceRange = explode(',', $params['price']);

            //     $qb->whereBetween('product_price_indices.min_price', [
            //         core()->convertToBasePrice(current($priceRange)),
            //         core()->convertToBasePrice(end($priceRange)),
            //     ]);
            // }

            /**
             * Retrieve all the filterable attributes.
             */
            $filterableAttributes = $this->attributeRepository->getProductDefaultAttributes(array_keys($params));

            /**
             * Filter the required attributes.
             */
            $attributes = $filterableAttributes->whereIn('code', [
                'name',
                'status',
                'visible_individually',
                'url_key',
            ]);

            /**
             * Filter collection by required attributes.
             */
            // foreach ($attributes as $attribute) {
            //     $alias = $attribute->code.'_product_attribute_values';

            //     $qb->leftJoin('product_attribute_values as '.$alias, 'products.id', '=', $alias.'.product_id')
            //         ->where($alias.'.attribute_id', $attribute->id);

            //     if ($attribute->code == 'name') {
            //         $synonyms = $this->searchSynonymRepository->getSynonymsByQuery(urldecode($params['name']));

            //         $qb->where(function ($subQuery) use ($alias, $synonyms) {
            //             foreach ($synonyms as $synonym) {
            //                 $subQuery->orWhere($alias.'.text_value', 'like', '%'.$synonym.'%');
            //             }
            //         });
            //     } elseif ($attribute->code == 'url_key') {
            //         if (empty($params['url_key'])) {
            //             $qb->whereNotNull($alias.'.text_value');
            //         } else {
            //             $qb->where($alias.'.text_value', 'like', '%'.urldecode($params['url_key']).'%');
            //         }
            //     } else {
            //         if (is_null($params[$attribute->code])) {
            //             continue;
            //         }

            //         $qb->where($alias.'.'.$attribute->column_name, 1);
            //     }
            // }

            /**
             * Filter the filterable attributes.
             */
            $attributes = $filterableAttributes->whereNotIn('code', [
                'price',
                'name',
                'status',
                'visible_individually',
                'url_key',
            ]);

            /**
             * Filter query by attributes.
             */
            if ($attributes->isNotEmpty()) {
                $qb->leftJoin('product_attribute_values', 'products.id', '=', 'product_attribute_values.product_id');

                $qb->where(function ($filterQuery) use ($params, $attributes) {
                    foreach ($attributes as $attribute) {
                        $filterQuery->orWhere(function ($attributeQuery) use ($params, $attribute) {
                            $attributeQuery = $attributeQuery->where('product_attribute_values.attribute_id', $attribute->id);

                            $values = explode(',', $params[$attribute->code]);

                            if ($attribute->type == 'price') {
                                // $attributeQuery->whereBetween('product_attribute_values.'.$attribute->column_name, [
                                //     core()->convertToBasePrice(current($values)),
                                //     core()->convertToBasePrice(end($values)),
                                // ]);
                            } else {
                                $attributeQuery->whereIn('product_attribute_values.' . $attribute->column_name, $values);
                            }
                        });
                    }
                });

                $qb->groupBy('products.id');
            }

            /**
             * Sort collection.
             */
            $sortOptions = $this->getSortOptions($params);

            if ($sortOptions['order'] != 'rand') {
                $attribute = $this->attributeRepository->findOneByField('code', $sortOptions['sort']);

                if ($attribute) {
                    if ($attribute->code === 'price') {
                        $qb->orderBy('product_price_indices.min_price', $sortOptions['order']);
                    } else {
                        $alias = 'sort_product_attribute_values';

                        // $qb->leftJoin('product_attribute_values as ' . $alias, function ($join) use ($alias, $attribute) {
                        //     $join->on('products.id', '=', $alias . '.product_id')
                        //         ->where($alias . '.attribute_id', $attribute->id)
                        //         ->where($alias . '.channel', core()->getRequestedChannelCode())
                        //         ->where($alias . '.locale', core()->getRequestedLocaleCode());
                        // })
                        //     ->orderBy($alias . '.' . $attribute->column_name, $sortOptions['order']);
                    }
                } else {
                    /* `created_at` is not an attribute so it will be in else case */
                    $qb->orderBy('products.created_at', $sortOptions['order']);
                }
            } else {
                return $qb->inRandomOrder();
            }

            return $qb->groupBy('products.id');
        });

        // $limit = $this->getPerPageLimit($params);

        return $query->paginate(10);
        // return $query->get();
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
