<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Listeners\ProductFlatListener;
use App\Models\Product;
use App\Models\ProductSuperAttributeValue;
use App\Repositories\ProductRepository;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    private ProductRepository $productRepository;

    function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->productRepository->all();
        // return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate(request(), [
        // 'type'        => 'required',
        // 'attribute_family_id' => 'required',
        // 'sku'                 => ['required', 'unique:products,sku', new Slug],
        // ]);


        $data = $request->all();

        $product = $this->productRepository->create($data);

        // app(ProductFlatListener::class)->afterProductCreatedUpdated($product);
        
        return $product;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function show(Product $product)
    public function show(int $id)
    {
        $product = $this->productRepository->with(['variants', /*'super_attributes',*/ 'super_attribute_values'])->findOrFail($id);

        $pav = ProductSuperAttributeValue::select(['attribute_id', 'option_id'])->where('product_id', $product->id)->get();
        if($pav) {
            // $pav = $pav->groupBy('attribute_id');
            $pav = $pav->mapToGroups(function ( $item, int $key) {
                return [$item['attribute_id'] => $item['option_id']];
            });

            $product['super_attributes'] = $pav;
        }


        $productArray = $product->toArray();

        

        $product->name = $productArray['general']['name'];
        $response = $product->toArray();

        return $response;


        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Product $product)
    public function update(Request $request, $id)
    {

        event('catalog.product.update.before', $id);

        // dd(Product::findOrFail($id)->toArray());
        // dd(request()->all());

        $product = $this->productRepository->findOrFail($id);

        $product = $this->productRepository->update($request->all(), $id);

        // event('catalog.product.update.after', $product);
        app(ProductFlatListener::class)->afterProductCreatedUpdated($product);

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
