<?php

namespace App\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Product;
use App\Models\ProductInventory;

/**
 * Class ProductInventoryRepository.
 *
 * @package namespace App\Repositories;
 */
class ProductInventoryRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProductInventory::class;
    }

    public function saveInventories(array $data, $product)
    {

        if (isset($data['inventories'])) {
            foreach ($data['inventories'] as $inventorySourceId => $quantity) {
                // dd($inventorySourceId, $quantity);
                if(str_contains($inventorySourceId, 'inventory-')) {
                    $inventorySourceId = intval(str_replace('inventory-','', $inventorySourceId));
                }
                
                $quantity = is_null($quantity) ? 0 : $quantity;

                $productInventory = $this->findOneWhere([
                    'product_id'          => $product->id,
                    'inventory_source_id' => $inventorySourceId,
                    'vendor_id'           => isset($data['vendor_id']) ? $data['vendor_id'] : 0,
                ]);

                if ($productInventory) {
                    $productInventory->quantity = $quantity;

                    $productInventory->save();
                } else {
                    $this->create([
                        'quantity'            => $quantity,
                        'product_id'          => $product->id,
                        'inventory_source_id' => $inventorySourceId,
                        'vendor_id'           => isset($data['vendor_id']) ? $data['vendor_id'] : 0,
                    ]);
                }
            }
        }
    }

    /**
     * Check if product inventories are already loaded. If already loaded then load from it.
     *
     * @return object
     */
    public function checkInLoadedProductInventories($product)
    {
        static $productInventories = [];

        if (array_key_exists($product->id, $productInventories)) {
            return $productInventories[$product->id];
        }

        return $productInventories[$product->id] = $product->inventories;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
