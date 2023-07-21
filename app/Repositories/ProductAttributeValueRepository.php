<?php

namespace App\Repositories;

use App\Models\Attribute;
use App\Models\ProductAttributeValue;
use Illuminate\Container\Container as App;
use Webkul\Attribute\Repositories\AttributeRepository;

class ProductAttributeValueRepository extends Repository
{
    /**
     * AttributeRepository object
     *
     * @var \Webkul\Attribute\Repositories\AttributeRepository
     */
    protected $attributeRepository;

    /**
     * Create a new reposotory instance.
     *
     * @param  \Webkul\Attribute\Repositories\AttributeRepository  $attributeRepository
     * @param  \Illuminate\Container\Container  $app
     * @return void
     */
    public function __construct(
        App $app
    ) {
        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return ProductAttributeValue::class;
    }

    /**
     * @param  array  $data
     * @return \Webkul\Product\Contracts\ProductAttributeValue
     */
    // public function create(array $data)
    // {
    //     if (isset($data['attribute_id'])) {
    //         $attribute = Attribute::find($data['attribute_id']);
    //     } else {
    //         $attribute = Attribute::findOneByField('code', $data['attribute_code']);
    //     }

    //     if (!$attribute) {
    //         return;
    //     }

    //     $data[ProductAttributeValue::$attributeTypeFields[$attribute->type]] = $data['value'];

    //     return $this->model->create($data);
    // }

    /**
     * @param  string  $column
     * @param  int  $attributeId
     * @param  int  $productId
     * @param  string  $value
     * @return boolean
     */
    public function isValueUnique($productId, $attributeId, $column, $value)
    {
        $result = $this->resetScope()->model->where($column, $value)->where('attribute_id', '=', $attributeId)->where('product_id', '!=', $productId)->get();

        return ! $result->count();
    }
}
