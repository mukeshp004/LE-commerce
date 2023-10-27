<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductInventory extends Model
{
    use HasFactory;


    protected $fillable = [
        'quantity',
        'product_id',
        'inventory_source_id',
        'vendor_id',
    ];

    /**
     * Get the product attribute family that owns the product.
     */
    public function inventory_source(): BelongsTo
    {
        return $this->belongsTo(InventorySource::class);
    }

    /**
     * Get the product that owns the product inventory.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
