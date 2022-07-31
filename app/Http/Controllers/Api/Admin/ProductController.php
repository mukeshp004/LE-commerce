<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

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
        return $this->productRepository->with(['attribute_family'])->all();
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
        $this->validate(request(), [
            'type'        => 'required',
            'attribute_family_id' => 'required',
            // 'sku'                 => ['required', 'unique:products,sku', new Slug],
        ]);


        $data = $request->all();


        $product = $this->productRepository->create($data);

        return $product;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
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
        $data = $request->all();
        // dd($data);


        $product = $this->productRepository->findOrFail($id);

        return $product->attribute_family->groups;

        foreach ($product->attribute_family->groups  as $attributeGroup) {
        }


        $this->productRepository->update($data, $id);
        // return $product->update($request->only($product->getFillable()));
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
