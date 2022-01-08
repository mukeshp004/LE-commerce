<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Rinvex\Attributes\Traits\Attributable;

class Product extends Model
{
    use Attributable;

    protected $with = ['eav'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sku', 'type'];
}
