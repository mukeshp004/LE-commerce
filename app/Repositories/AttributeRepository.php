<?php

namespace App\Repositories;

use App\Models\Attribute;

/**
 * Class AttributeRepository.
 *
 * @package namespace App\Repositories;
 */
class AttributeRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Attribute::class;
    }

    /**
     * Get attribute by code.
     *
     * @param  string  $code
     * @return \Webkul\Attribute\Contracts\Attribute
     */
    public function getAttributeByCode($code)
    {
        static $attributes = [];

        if (array_key_exists($code, $attributes)) {
            return $attributes[$code];
        }

        return $attributes[$code] = $this->findOneByField('code', $code);
    }

    /**
     * Get family attributes.
     *
     * @param  \Webkul\Attribute\Contracts\AttributeFamily  $attributeFamily
     * @return \Webkul\Attribute\Contracts\Attribute
     */
    public function getFamilyAttributes($attributeFamily)
    {
        static $attributes = [];

        if (array_key_exists($attributeFamily->id, $attributes)) {
            return $attributes[$attributeFamily->id];
        }

        return $attributes[$attributeFamily->id] = $attributeFamily->custom_attributes;
    }
}
