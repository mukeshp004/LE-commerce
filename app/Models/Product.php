<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
// use Rinvex\Attributes\Traits\Attributable;

class Product extends Model
{
    public static $PRODUCT_TYPE = [
        "Simple" => 1,
        'Configurable' => 2,
        'Grouped' => 3,
        'Virtual' => 4,
        'Downloadable' => 5,
        'BOOKING' => 6,
        'BUNDLE' => 7,
        'GIFT_CARD' => 8,
    ];
    // use Attributable;

    protected $with = ['eav'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sku', 'type', 'parent_id', 'attribute_family_id'];


    /**
     * Get type instance.
     *
     * @return AbstractType
     *
     * @throws \Exception
     */
    // public function getTypeInstance()
    // {
    //     if ($this->typeInstance) {
    //         return $this->typeInstance;
    //     }

    //     $this->typeInstance = app(config('product_types.' . $this->type . '.class'));

    //     if (!$this->typeInstance) {
    //         throw new Exception("Please ensure the product type '{$this->type}' is configured in your application.");
    //     }

    //     $this->typeInstance->setProduct($this);

    //     return $this->typeInstance;
    // }
}
