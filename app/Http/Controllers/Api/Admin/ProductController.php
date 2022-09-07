<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
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
        // $this->validate(request(), [
        // 'type'        => 'required',
        // 'attribute_family_id' => 'required',
        // 'sku'                 => ['required', 'unique:products,sku', new Slug],
        // ]);


        $data = $request->all();

        // dd($data);


        $product = $this->productRepository->create($data);


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
        // dd($product);
        $product = $this->productRepository->with(['variants'])->findOrFail($id);

        $response = $product->toArray();

        return $response;


        // foreach ($product->attribute_family->groups as $group) {
        //     // array_push($product[$group->code] =
        // }
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

        dd($data);

        $multiselectAttributeCodes = [];

        $product = $this->productRepository->findOrFail($id);


        // foreach ($product->attribute_family->groups  as $attributeGroup) {
        //     // echo $attributeGroup->name . '<br/>';
        //     $customAttributes = $product->getEditableAttributes($attributeGroup);

        //     // dd($customAttributes);

        //     if (count($customAttributes)) {
        //         foreach ($customAttributes as $attribute) {
        //             // echo $attribute->type . '<br/>';
        //             if ($attribute->type == 'multiselect' || $attribute->type == 'checkbox') {
        //                 array_push($multiselectAttributeCodes, $attribute->code);
        //             }
        //         }
        //     }

        //     // dd('$multiselectAttributeCodes', $multiselectAttributeCodes);

        //     if (count($multiselectAttributeCodes)) {
        //         foreach ($multiselectAttributeCodes as $multiselectAttributeCode) {
        //             if (!isset($data[$multiselectAttributeCode])) {
        //                 $data[$multiselectAttributeCode] = [];
        //             }
        //         }
        //     }
        // }

        $product = $this->productRepository->update($data, $id);
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
